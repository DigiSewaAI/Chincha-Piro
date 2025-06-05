<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Cart;
use App\Models\CartItem;
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
    /**
     * Display the order creation form.
     */
    public function create()
    {
        // Get the user's cart
        $cart = $this->getCart();

        // Check if cart is empty
        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'तपाईंको कार्ट खाली छ। कृपया कार्टमा आइटम थप्नुहोस्।');
        }

        // Load cart items with menu data
        $cartItems = $cart->items()->with('menu')->get();

        // Load available categories with menus
        $categories = Category::withAvailableMenus()->get();

        return view('orders.create', compact('cartItems', 'categories'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateOrderRequest($request);

        try {
            $order = DB::transaction(function () use ($validated) {
                return $this->createOrder($validated);
            });

            session()->flash('order_id', $order->id);

            return redirect()->route('orders.success')
                ->with('success', 'अर्डर सफल भयो! ट्र्याकिङ्ग ID: ' . $order->id);

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'अर्डर असफल: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the order success page.
     */
    public function success()
    {
        return view('orders.success');
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return view('orders.show', compact('order'));
    }

    /**
     * Display order tracking information.
     */
    public function track(Order $order)
    {
        $this->authorize('view', $order);

        $statusHistory = $order->statusHistories()
            ->with('changer')
            ->orderedChronologically()
            ->get();

        return view('orders.track', [
            'order' => $order->load('items.menu'),
            'statusHistory' => $statusHistory
        ]);
    }

    /**
     * Update the specified order's status in storage.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $this->validateStatusUpdate($request, $order);

        DB::transaction(function () use ($order, $validated) {
            $order->updateStatus($validated['status'], $validated['notes'] ?? null);
        });

        return back()->with('success', 'स्थिति अद्यावधिक भयो: ' . $order->status);
    }

    /**
     * Remove the specified order from storage.
     */
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
     * Private Helper Methods
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
            'items.*.menu_id' => 'required|exists:menus,id',
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
        $menus = Menu::whereIn('id', collect($validated['items'])->pluck('menu_id'))
            ->available()
            ->get()
            ->keyBy('id');

        if ($menus->count() !== count($validated['items'])) {
            throw new \Exception('केही वस्तुहरू उपलब्ध छैनन्');
        }

        $order = $user->orders()->create([
            'total_price' => $this->calculateTotal($validated['items'], $menus),
            'customer_name' => $validated['customer_name'] ?? $user->name,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'special_instructions' => $validated['special_instructions'],
            'preferred_delivery_time' => $validated['preferred_delivery_time'],
            'payment_method' => $validated['payment_method'],
            'status' => 'pending'
        ]);

        $this->createOrderItems($order, $validated['items'], $menus);
        $this->notifyOrderCreation($order);

        return $order;
    }

    private function calculateTotal(array $items, $menus): float
    {
        return collect($items)->sum(fn($item) =>
            $menus[$item['menu_id']]->price * $item['quantity']
        );
    }

    private function createOrderItems(Order $order, array $items, $menus): void
    {
        foreach ($items as $item) {
            $menu = $menus[$item['menu_id']];
            $order->items()->create([
                'menu_id' => $menu->id,
                'quantity' => $item['quantity'],
                'price' => $menu->price,
                'total' => $menu->price * $item['quantity'],
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

    /**
     * Get the current user's cart
     */
    protected function getCart()
    {
        if (Auth::check()) {
            return Cart::with('items.menu')->firstOrCreate(['user_id' => Auth::id()]);
        } else {
            $sessionId = session()->getId();
            return Cart::with('items.menu')->firstOrCreate(['session_id' => $sessionId]);
        }
    }
}
