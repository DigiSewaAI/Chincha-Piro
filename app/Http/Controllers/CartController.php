<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Food;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        $cartItems = $cart->items()->with('food')->get();

        if ($cartItems->isEmpty()) {
            session()->flash('info', 'तपाईँको कार्ट खाली छ।');
        }

        return view('cart.index', compact('cartItems'));
    }

    public function add(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'item_id' => 'required|exists:foods,id',
                'quantity' => 'required|numeric|min:1',
                'expected_price' => 'required|numeric'
            ]);

            $food = Food::findOrFail($validated['item_id']);

            // स्टक जाँच गर्ने
            if ($food->stock < $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'स्टक उपलब्ध छैन! केवल ' . $food->stock . ' उपलब्ध छ।'
                ], 400);
            }

            // मूल्य परिवर्तन जाँच गर्ने
            if ($food->price != $validated['expected_price']) {
                return response()->json([
                    'success' => false,
                    'message' => 'मूल्य परिवर्तन भएको छ! नयाँ मूल्य: रु ' . $food->price
                ], 400);
            }

            $cart = $this->getCart();
            $cartItem = $cart->items()->where('food_id', $food->id)->first();

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $validated['quantity'];

                if ($food->stock < $newQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'स्टक पर्याप्त छैन! केवल ' . $food->stock . ' उपलब्ध छ।'
                    ], 400);
                }

                $cartItem->update(['quantity' => $newQuantity]);
                $food->decrement('stock', $validated['quantity']);
            } else {
                $cart->items()->create([
                    'food_id' => $food->id,
                    'quantity' => $validated['quantity'],
                    'price' => $food->price
                ]);

                $food->decrement('stock', $validated['quantity']);
            }

            DB::commit();

            $cart = $cart->fresh(['items.food']);
            $cartCount = $cart->items->sum('quantity');
            $cartTotal = $this->calculateCartTotal($cart);

            return response()->json([
                'success' => true,
                'message' => $food->name . ' कार्टमा थपियो!',
                'cart_count' => $cartCount,
                'cart_total' => $cartTotal,
                'cart_items' => $cart->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->food->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total' => $item->price * $item->quantity
                    ];
                })
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'अमान्य डेटा प्रविष्ट भयो'
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'कार्टमा आइटम थप्न असफल भयो: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate(['quantity' => 'required|integer|min:1']);

            DB::beginTransaction();

            $cartItem = CartItem::with('food')
                ->where('id', $id)
                ->whereHas('cart', function ($query) {
                    Auth::check()
                        ? $query->where('user_id', Auth::id())
                        : $query->where('session_id', Session::getId());
                })
                ->firstOrFail();

            $food = $cartItem->food;
            $oldQuantity = $cartItem->quantity;
            $newQuantity = $validated['quantity'];
            $difference = $newQuantity - $oldQuantity;

            if ($difference > 0) {
                if ($food->stock < $difference) {
                    return response()->json([
                        'success' => false,
                        'message' => "भण्डार पर्याप्त छैन! केवल {$food->stock} उपलब्ध छ।"
                    ], 400);
                }
                $food->decrement('stock', $difference);
            } elseif ($difference < 0) {
                $food->increment('stock', abs($difference));
            }

            $cartItem->update(['quantity' => $newQuantity]);

            DB::commit();

            $cart = $cartItem->cart->fresh('items');

            return response()->json([
                'success' => true,
                'message' => 'कार्ट अपडेट गरियो!',
                'cart_count' => $cart->items->sum('quantity'),
                'cart_total' => $this->calculateCartTotal($cart)
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'अमान्य मात्रा प्रविष्ट भएको छ'], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return response()->json(['success' => false, 'message' => 'कार्ट अपडेट गर्न असफल भयो'], 500);
        }
    }

    public function remove(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $cartItem = CartItem::with('food')
                ->where('id', $id)
                ->whereHas('cart', function ($query) {
                    Auth::check()
                        ? $query->where('user_id', Auth::id())
                        : $query->where('session_id', Session::getId());
                })
                ->firstOrFail();

            $cartItem->food->increment('stock', $cartItem->quantity);
            $cart = $cartItem->cart;
            $cartItem->delete();

            DB::commit();

            $updatedCart = $cart->fresh('items');

            return response()->json([
                'success' => true,
                'message' => 'आइटम कार्टबाट हटाइयो!',
                'cart_count' => $updatedCart->items->sum('quantity'),
                'cart_total' => $this->calculateCartTotal($updatedCart)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return response()->json(['success' => false, 'message' => 'आइटम हटाउन असफल भयो'], 500);
        }
    }

    public function clear(Request $request)
    {
        try {
            DB::beginTransaction();

            $cart = $this->getCart();

            $cart->items->each(function ($item) {
                $item->food->increment('stock', $item->quantity);
            });

            $cart->items()->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'कार्ट खाली गरियो!',
                'cart_count' => 0,
                'cart_total' => 0
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return response()->json(['success' => false, 'message' => 'कार्ट खाली गर्न असफल भयो'], 500);
        }
    }

    public function count()
    {
        $cart = $this->getCart();
        return response()->json(['count' => $cart->items->sum('quantity')]);
    }

    protected function getCart(): Cart
    {
        if (Auth::check()) {
            return Cart::with('items.food')->firstOrCreate(
                ['user_id' => Auth::id()],
                ['session_id' => Session::getId()]
            );
        }

        return Cart::with('items.food')->firstOrCreate(['session_id' => Session::getId()]);
    }

    protected function calculateCartTotal(Cart $cart): float
    {
        return round($cart->items->sum(fn($item) => $item->price * $item->quantity), 2);
    }
}
