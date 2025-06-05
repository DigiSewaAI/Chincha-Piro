<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $cart = $this->getCart();
        $cartItems = $cart->items()->with('menu')->get();

        if ($cartItems->isEmpty()) {
            session()->flash('info', 'Your cart is empty.');
        }

        return view('cart.index', compact('cartItems'));
    }

    /**
     * Add a menu item to the cart.
     */
    public function add(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // ✅ Corrected: Query Builder मार्फत lockForUpdate() प्रयोग
            $menu = Menu::where('id', $id)->lockForUpdate()->firstOrFail();

            // Stock validation
            if ($menu->stock < $validated['quantity']) {
                return $this->handleResponse($request, [
                    'error' => "Requested quantity exceeds available stock. Available: {$menu->stock}"
                ], 400);
            }

            $cart = $this->getCart();

            // Add/update cart item
            $cartItem = $cart->items()->updateOrCreate(
                ['menu_id' => $menu->id],
                [
                    'price' => $menu->price,
                    'quantity' => DB::raw("quantity + {$validated['quantity']}")
                ]
            );

            // Update stock
            $menu->decrement('stock', $validated['quantity']);

            DB::commit();

            $responseData = [
                'success' => "Item added to cart!",
                'cart_count' => $cart->items()->sum('quantity')
            ];

            return $this->handleResponse($request, $responseData);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e); // Log the exception
            return $this->handleResponse($request, [
                'error' => 'Failed to add item to cart'
            ], 500);
        }
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate(['quantity' => 'required|integer|min:1']);

        DB::beginTransaction();

        try {
            $cartItem = CartItem::with('menu')
                ->where('id', $id)
                ->whereHas('cart', function ($query) {
                    Auth::check()
                        ? $query->where('user_id', Auth::id())
                        : $query->where('session_id', Session::getId());
                })
                ->firstOrFail();

            $menu = $cartItem->menu;
            $oldQuantity = $cartItem->quantity;
            $newQuantity = $validated['quantity'];

            // Stock validation
            $difference = $newQuantity - $oldQuantity;
            if ($difference > 0 && $menu->stock < $difference) {
                return $this->handleResponse($request, [
                    'error' => "Insufficient stock! Only {$menu->stock} available."
                ], 400);
            }

            // Update stock
            $menu->decrement('stock', $difference);
            $cartItem->update(['quantity' => $newQuantity]);

            DB::commit();

            return $this->handleResponse($request, [
                'success' => 'Cart updated!',
                'item_total' => $cartItem->price * $newQuantity,
                'cart_total' => $this->calculateCartTotal($cartItem->cart)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return $this->handleResponse($request, [
                'error' => 'Failed to update cart'
            ], 500);
        }
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $cartItem = CartItem::with('menu')
                ->where('id', $id)
                ->whereHas('cart', function ($query) {
                    Auth::check()
                        ? $query->where('user_id', Auth::id())
                        : $query->where('session_id', Session::getId());
                })
                ->firstOrFail();

            // Restore stock
            $cartItem->menu->increment('stock', $cartItem->quantity);
            $cart = $cartItem->cart;
            $cartItem->delete();

            DB::commit();

            return $this->handleResponse($request, [
                'success' => 'Item removed from cart!',
                'cart_count' => $cart->items()->sum('quantity'),
                'cart_total' => $this->calculateCartTotal($cart)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return $this->handleResponse($request, [
                'error' => 'Failed to remove item'
            ], 500);
        }
    }

    /**
     * Clear all items from the cart.
     */
    public function clear(Request $request)
    {
        DB::beginTransaction();

        try {
            $cart = $this->getCart();

            // Restore all stock
            $cart->items->each(function ($item) {
                $item->menu->increment('stock', $item->quantity);
            });

            $cart->items()->delete();

            DB::commit();

            return $this->handleResponse($request, [
                'success' => 'Cart cleared!',
                'cart_count' => 0,
                'cart_total' => 0
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return $this->handleResponse($request, [
                'error' => 'Failed to clear cart'
            ], 500);
        }
    }

    /**
     * Get cart item count for AJAX display.
     */
    public function getCount()
    {
        $cart = $this->getCart();
        return response()->json(['count' => $cart->items()->sum('quantity')]);
    }

    /**
     * Get the current cart (session or auth user).
     */
    protected function getCart(): Cart
    {
        if (Auth::check()) {
            return Cart::with('items')->firstOrCreate(['user_id' => Auth::id()]);
        }

        $sessionId = Session::getId();
        return Cart::with('items')->firstOrCreate(['session_id' => $sessionId]);
    }

    /**
     * Calculate the total price of items in the cart.
     */
    protected function calculateCartTotal(Cart $cart): float
    {
        return $cart->items->sum(fn($item) => $item->price * $item->quantity);
    }

    /**
     * Handle response based on request type (AJAX or redirect).
     */
    private function handleResponse(Request $request, array $data, int $status = 200)
    {
        if ($request->expectsJson()) {
            return response()->json($data, $status);
        }

        return $data['error']
            ? back()->with('error', $data['error'])
            : back()->with('success', $data['success']);
    }
}
