<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Events\NewOrderEvent;

class OrderController extends Controller
{
    /**
     * Display the order form to the user.
     */
    public function index()
    {
        // Fetch available dishes with their categories
        $dishes = Dish::where('is_available', true)
                      ->with('category')
                      ->orderBy('spice_level', 'desc')
                      ->get();

        // Fetch orders for the currently authenticated user
        $orders = Order::where('user_id', Auth::id())
                       ->with('dish')  // Optional: Eager load dish relationship
                       ->latest()
                       ->get();

        // Pass both dishes and orders to the view
        return view('orders.index', compact('dishes', 'orders'));
    }

    /**
     * Admin: List all orders with filters.
     */
    public function listOrders()
    {
        $this->authorize('viewAny', Order::class);

        $orders = Order::with(['user', 'dish'])
                    ->latest()
                    ->filter(request(['status', 'search']))
                    ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Store a new order.
     */
    public function store(Request $request)
    {
        $validated = $this->validateOrderRequest($request);
        $orderData = $this->prepareOrderData($validated);

        $order = Order::create($orderData);
        $this->dispatchOrderEvents($order);

        return redirect()->route('orders.track', $order)
               ->with('success', 'अर्डर सफल भयो! ट्र्याकिङ्ग आईडी: '.$order->id);
    }

    /**
     * Display the tracking page for an order.
     */
    public function track(Order $order)
    {
        $statusHistory = $order->statusHistories()->latest()->get();
        return view('orders.track', compact('order', 'statusHistory'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,on_delivery,completed,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        $order->update($validated);
        $this->logStatusChange($order, $validated['status'], $validated['notes'] ?? null);

        return back()->with('success', 'अवस्था अपडेट भयो: '.$order->status);
    }

    /**
     * Delete an order.
     */
    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);
        $order->delete();

        return redirect()->route('admin.orders.index')
               ->with('info', 'अर्डर सफलतापूर्वक हटाइयो');
    }

    /**********************
     * Private Helper Methods
     **********************/

    private function validateOrderRequest(Request $request)
    {
        return $request->validate([
            'dish_id' => 'required|exists:dishes,id',
            'quantity' => 'required|integer|between:1,10',
            'customer_name' => [
                Rule::requiredIf(!Auth::check()),
                'string',
                'max:100'
            ],
            'phone' => 'required|regex:/^(98|97|96)\d{8}$/|max:10',
            'address' => 'required|string|max:500',
            'special_instructions' => 'nullable|string|max:1000',
            'preferred_delivery_time' => 'nullable|date|after:now'
        ]);
    }

    private function prepareOrderData(array $validated)
    {
        $dish = Dish::findOrFail($validated['dish_id']);

        return [
            'user_id' => Auth::id(),
            'dish_id' => $validated['dish_id'],
            'quantity' => $validated['quantity'],
            'total_price' => $dish->price * $validated['quantity'],
            'customer_name' => Auth::check() ? Auth::user()->name : $validated['customer_name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'special_instructions' => $validated['special_instructions'],
            'preferred_delivery_time' => $validated['preferred_delivery_time'],
            'status' => 'pending'
        ];
    }

    private function dispatchOrderEvents(Order $order)
    {
        event(new NewOrderEvent($order));

        if(config('services.sms.enabled')) {
            // SMS sending logic
        }
    }

    private function logStatusChange(Order $order, string $status, ?string $notes)
    {
        $order->statusHistories()->create([
            'status' => $status,
            'changed_by' => Auth::id(),
            'notes' => $notes
        ]);
    }
}
