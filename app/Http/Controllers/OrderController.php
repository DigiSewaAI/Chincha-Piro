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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Rules\ValidOrderItems;

class OrderController extends Controller
{
    public function index()
    {
        $categories = Category::with(['dishes' => function ($query) {
            $query->where('is_available', true)->orderBy('name_nepali');
        }])->get();

        $dishes = Dish::where('is_available', true)->get();

        $orders = Auth::user()->orders()
            ->with(['items.dish', 'statusHistories'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('categories', 'orders', 'dishes'));
    }

    public function publicIndex()
    {
        $orders = Order::publicOrders()
            ->with(['items.dish'])
            ->latest()
            ->paginate(10);

        return view('orders.public-index', compact('orders'));
    }

    public function create()
    {
        $categories = Category::withAvailableDishes();
        return view('orders.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateOrderRequest($request);

        try {
            $order = DB::transaction(function () use ($validated) {
                return $this->createOrder($validated);
            });

            // ✅ Add this line to flash the order ID to session
            session()->flash('order_id', $order->id);

            return redirect()->route('orders.success')
                ->with('success', 'अर्डर सफल भयो! ट्र्याकिङ्ग ID: ' . $order->id);

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'अर्डर असफल: ' . $e->getMessage()]);
        }
    }

    public function success()
    {
        return view('orders.success');
    }

    public function track(Order $order)
    {
        $this->authorize('view', $order);

        $statusHistory = $order->statusHistories()
            ->with('changer')
            ->orderedChronologically()
            ->get();

        return view('orders.track', [
            'order' => $order->load('items.dish'),
            'statusHistory' => $statusHistory
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $this->validateStatusUpdate($request, $order);

        DB::transaction(function () use ($order, $validated) {
            $order->updateStatus($validated['status'], $validated['notes'] ?? null);
        });

        return back()->with('success', 'स्थिति अद्यावधिक भयो: ' . $order->status);
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        $this->validateDeletion($order);

        DB::transaction(function () use ($order) {
            $order->safeDelete();
        });

        return redirect()->route('admin.orders.index')
                         ->with('info', 'अर्डर सफलतापूर्वक मेटियो');
    }

    /**********************************
     * निजी सहयोगी विधिहरू
     **********************************/

    private function processFilters(): array
    {
        return request()->validate([
            'status' => 'nullable|string',
            'search' => 'nullable|string|max:255',
            'date_range' => 'nullable|string'
        ]);
    }

    private function validateOrderRequest(Request $request): array
    {
        return Validator::make($request->all(), [
            'items' => ['required', 'array', 'min:1', new ValidOrderItems],
            'items.*.dish_id' => 'required|exists:dishes,id',
            'items.*.quantity' => 'required|integer|between:1,10',
            'items.*.note' => 'nullable|string|max:255',
            'customer_name' => [
                Rule::requiredIf(!Auth::check()),
                'string',
                'max:100'
            ],
            'phone' => ['required', 'regex:/^(98|97|96)\d{8}$/'],
            'address' => 'required|string|max:500',
            'special_instructions' => 'nullable|string|max:1000',
            'preferred_delivery_time' => 'nullable|date|after:+1 hour',
            'payment_method' => ['required', Rule::in(['cash', 'esewa', 'khalti'])]
        ], [
            'phone.regex' => 'वैध नेपाली मोबाइल नम्बर प्रविष्ट गर्नुहोस्',
            'items.*.quantity.between' => 'प्रत्येक वस्तुको मात्रा १-१० को बीचमा हुनुपर्छ',
            'items.required' => 'कम्तिमा एक वस्तु चयन गर्नुहोस्'
        ])->validate();
    }

    private function createOrder(array $validated): Order
    {
        $user = Auth::user();
        $dishes = Dish::whereIn('id', collect($validated['items'])->pluck('dish_id'))
            ->available()
            ->get()
            ->keyBy('id');

        if ($dishes->count() !== count($validated['items'])) {
            throw new \Exception('केही वस्तुहरू उपलब्ध छैनन्');
        }

        $order = $user->orders()->create([
            'total_price' => $this->calculateTotal($validated['items'], $dishes),
            'customer_name' => $validated['customer_name'] ?? $user->name,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'special_instructions' => $validated['special_instructions'],
            'preferred_delivery_time' => $validated['preferred_delivery_time'],
            'payment_method' => $validated['payment_method'],
            'status' => 'pending'
        ]);

        $this->createOrderItems($order, $validated['items'], $dishes);
        $this->notifyOrderCreation($order);

        return $order;
    }

    private function calculateTotal(array $items, $dishes): float
    {
        return collect($items)->sum(fn($item) =>
            $dishes[$item['dish_id']]->price * $item['quantity']
        );
    }

    private function createOrderItems(Order $order, array $items, $dishes): void
    {
        foreach ($items as $item) {
            $dish = $dishes[$item['dish_id']];
            $order->items()->create([
                'dish_id' => $dish->id,
                'quantity' => $item['quantity'],
                'price' => $dish->price,
                'total' => $dish->price * $item['quantity'],
                'note' => $item['note']
            ]);
        }
    }

    private function notifyOrderCreation(Order $order): void
    {
        event(new NewOrderEvent($order));
        $order->user->notify(new OrderPlacedNotification($order));
        StatusHistory::createInitialHistory($order);
    }

    private function validateStatusUpdate(Request $request, Order $order): array
    {
        return $request->validate([
            'status' => [
                'required',
                Rule::in(Order::STATUSES),
                function ($attr, $value, $fail) use ($order) {
                    if (!Order::isValidTransition($order->status, $value)) {
                        $fail("अमान्य स्थिति परिवर्तन: {$order->status} → {$value}");
                    }
                }
            ],
            'notes' => 'nullable|string|max:500'
        ]);
    }

    private function validateDeletion(Order $order): void
    {
        if ($order->isCompleted()) {
            throw new \Exception('पूरा भएको अर्डर मेटाउन असमर्थ');
        }
    }
}
