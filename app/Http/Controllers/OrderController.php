<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Dish;
use Illuminate\Http\Request;
use App\Events\NewOrderEvent; // Import Event Class

class OrderController extends Controller
{
    /**
     * Show a list of all orders.
     */
    public function index()
    {
        $orders = Order::latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    /**
     * Store a new order in the database.
     */
    public function store(Request $request)
    {
        // 1. Validate the request
        $validated = $request->validate([
            'dish_id' => 'required|exists:dishes,id',
            'quantity' => 'required|integer|min:1|max:10',
            'customer_name' => 'required|string|max:100',
            'phone' => 'required|regex:/^98\d{8}$/',
            'address' => 'required|string|max:500',
            'special_instructions' => 'nullable|string'
        ]);

        // 2. Calculate total price
        $dish = Dish::findOrFail($validated['dish_id']);
        $total = $dish->price * $validated['quantity'];

        // 3. Create order
        $order = Order::create([
            'user_id' => auth()->id(), // ✅ Authenticated user ID
            'dish_id' => $validated['dish_id'],
            'quantity' => $validated['quantity'],
            'total_price' => $total,
            'customer_name' => $validated['customer_name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'special_instructions' => $validated['special_instructions'] ?? null,
            'status' => 'पुष्टि हुन बाँकी'
        ]);

        // 4. Broadcast real-time notification
        broadcast(new NewOrderEvent($order))->toOthers();

        // 5. Redirect back with success message
        return back()->with('success', 'अर्डर सफल भयो! हामी तपाईंलाई ३० मिनेट भित्र फोन गर्नेछौं');
    }

    /**
     * Show a specific order.
     */
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    /**
     * Delete an order.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->back()->with('success', 'Order हटाइयो।');
    }

    /**
     * Track an order by ID.
     */
    public function track(Order $order)
    {
        // Order अवस्थित छ कि जाँच गर्नुहोस्
        if (!$order->exists) {
            return redirect()->route('home')->with('error', 'अर्डर भेटिएन');
        }

        return view('orders.track', compact('order'));
    }

    /**
     * Update order status via admin panel.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|string']);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'अर्डर स्थिति अपडेट भयो!');
    }
}
