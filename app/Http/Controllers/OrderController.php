<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Dish;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\StatusHistory;
use App\Events\NewOrderEvent;
use App\Notifications\OrderPlacedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display the order form to the user.
     */
    public function index()
    {
        $categories = Category::all();
        $dishes = Dish::where('is_available', true)
                      ->with('category')
                      ->orderBy('spice_level', 'desc')
                      ->get();

        $orders = Auth::user()->orders()
                       ->with(['items.dish', 'statusHistories'])
                       ->latest()
                       ->paginate(10);

        return view('orders.index', compact('dishes', 'orders', 'categories'));
    }

    /**
     * Admin: List all orders with filters and enhanced search.
     */
    public function listOrders()
    {
        $this->authorize('viewAny', Order::class);
        $filters = request(['status', 'search', 'date_range']);

        $orders = Order::with(['user', 'items.dish'])
                    ->when($filters['status'], fn($q) => $q->where('status', $filters['status']))
                    ->when($filters['search'], function ($q) use ($filters) {
                        return $q->whereHas('user', fn($query) =>
                            $query->where('name', 'like', "%{$filters['search']}%")
                                  ->orWhere('email', 'like', "%{$filters['search']}%")
                        )->orWhere('id', $filters['search']);
                    })
                    ->latest()
                    ->paginate(10)
                    ->appends($filters);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Store a new order with validation and event dispatching.
     */
    public function store(Request $request)
    {
        $validated = $this->validateOrderRequest($request);

        try {
            $order = DB::transaction(function () use ($validated) {
                $dishIds = collect($validated['items'])->pluck('dish_id')->toArray();

                $availableDishes = Dish::whereIn('id', $dishIds)
                                       ->where('is_available', true)
                                       ->get()
                                       ->keyBy('id');

                if (count($availableDishes) !== count($validated['items'])) {
                    throw new \Exception('केही व्यंजनहरू अहिले उपलब्ध छैनन्');
                }

                $total = $this->calculateTotal($validated['items'], $availableDishes);

                $order = Auth::user()->orders()->create([
                    'total_price' => $total,
                    'customer_name' => $validated['customer_name'] ?? Auth::user()->name,
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'special_instructions' => $validated['special_instructions'] ?? null,
                    'preferred_delivery_time' => $validated['preferred_delivery_time'] ?? null,
                    'payment_method' => $validated['payment_method'] ?? 'cash',
                    'status' => 'pending'
                ]);

                foreach ($validated['items'] as $item) {
                    $dish = $availableDishes[$item['dish_id']];
                    OrderItem::create([
                        'order_id' => $order->id,
                        'dish_id' => $dish->id,
                        'quantity' => $item['quantity'],
                        'price' => $dish->price,
                        'total' => $dish->price * $item['quantity'],
                        'note' => $item['note'] ?? null
                    ]);
                }

                $this->logStatusChange($order, 'pending', 'Order placed by customer');

                event(new NewOrderEvent($order));
                Auth::user()->notify(new OrderPlacedNotification($order));

                return $order;
            });

            return redirect()->route('orders.track', $order)
                ->with('success', 'अर्डर सफल भयो! ट्र्याकिङ्ग आईडी: ' . $order->id);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'अर्डर गर्न असफल: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the tracking page for an order.
     */
    public function track(Order $order)
    {
        if (Auth::id() !== $order->user_id) {
            abort(403, 'तपाईंलाई यो अर्डर हेर्न अनुमति छैन');
        }

        $statusHistory = $order->statusHistories()
            ->with('changer')
            ->orderBy('created_at', 'desc')
            ->get();

        $items = $order->items()
            ->with('dish')
            ->get();

        return view('orders.track', compact('order', 'statusHistory', 'items'));
    }

    /**
     * Update the status of an order.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(['pending', 'confirmed', 'preparing', 'on_delivery', 'completed', 'cancelled']),
                function ($attribute, $value, $fail) use ($order) {
                    if (!$this->isValidStatusTransition($order->status, $value)) {
                        $fail("अवस्था परिवर्तन '{$order->status}' बाट '{$value}' मा गर्न मिल्दैन");
                    }
                }
            ],
            'notes' => 'nullable|string|max:500'
        ]);

        DB::transaction(function () use ($order, $validated) {
            $order->update(['status' => $validated['status']]);
            $this->logStatusChange($order, $validated['status'], $validated['notes'] ?? null);
        });

        return back()->with('success', 'अवस्था अपडेट भयो: ' . $order->status);
    }

    /**
     * Delete an order.
     */
    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        if ($order->status === 'completed') {
            return back()->withErrors(['error' => 'पूरा भएको अर्डर हटाउन सकिँदैन']);
        }

        DB::transaction(function () use ($order) {
            $order->items()->delete();
            $order->delete();
        });

        return redirect()->route('admin.orders.index')
                         ->with('info', 'अर्डर सफलतापूर्वक हटाइयो');
    }

    /**
     * Show form to create an order.
     */
    public function create()
    {
        $dishes = Dish::where('is_available', true)->get();
        return view('orders.create', compact('dishes'));
    }

    /**********************
     * Private Helper Methods
     **********************/
    private function validateOrderRequest(Request $request): array
    {
        $rules = [
            'items' => 'required|array|min:1',
            'items.*.dish_id' => 'required|exists:dishes,id',
            'items.*.quantity' => 'required|integer|between:1,10',
            'items.*.note' => 'nullable|string|max:255',
            'customer_name' => [
                Rule::requiredIf(!Auth::check()),
                'string',
                'max:100'
            ],
            'phone' => ['required', 'regex:/^(98|97|96)[0-9]{8}$/', 'max:10'],
            'address' => 'required|string|max:500',
            'special_instructions' => 'nullable|string|max:1000',
            'preferred_delivery_time' => 'nullable|date|after:now',
            'payment_method' => ['nullable', Rule::in(['cash', 'esewa', 'khalti', 'card'])]
        ];

        $messages = [
            'phone.regex' => 'फोन नम्बर ९८, ९७ वा ९६ बाट सुरु गरेर १० अंकको हुनुपर्छ।',
            'items.*.quantity.between' => 'प्रत्येक वस्तुको मात्रा 1 देखि 10 को बीचमा हुनुपर्छ।'
        ];

        return Validator::make($request->all(), $rules, $messages)->validate();
    }

    private function calculateTotal(array $items, $availableDishes): float
    {
        return collect($items)->sum(fn($item) =>
            $availableDishes[$item['dish_id']]->price * $item['quantity']
        );
    }

    private function isValidStatusTransition(string $current, string $new): bool
    {
        $validTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['preparing', 'cancelled'],
            'preparing' => ['on_delivery'],
            'on_delivery' => ['completed'],
            'completed' => [],
            'cancelled' => []
        ];
        return in_array($new, $validTransitions[$current] ?? []);
    }

    private function logStatusChange(Order $order, string $status, ?string $notes)
    {
        StatusHistory::create([
            'order_id' => $order->id,
            'status' => $status,
            'changed_by' => Auth::id(),
            'notes' => $notes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}
