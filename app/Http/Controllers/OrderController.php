<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Events\NewOrderNotification;

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
        $validated = $request->validate([
            'dish_id' => 'required|exists:dishes,id',
            'quantity' => 'required|integer|min:1|max:10',
            'customer_name' => 'required|string|max:100',
            'phone' => 'required|regex:/^98\d{8}$/',
            'address' => 'required|string|max:500',
            'special_instructions' => 'nullable|string'
        ]);

        // Calculate amount dynamically (if Dish price is needed)
        $dish = \App\Models\Dish::findOrFail($validated['dish_id']);
        $validated['amount'] = $dish->price * $validated['quantity'];
        $validated['status'] = 'पुष्टि हुन बाँकी'; // default status

        $order = Order::create($validated);

        // Send real-time notification
        broadcast(new NewOrderNotification($order))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'अर्डर सफल भयो! हामी तपाईंलाई ३० मिनेट भित्र फोन गर्नेछौं'
        ]);
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
}
