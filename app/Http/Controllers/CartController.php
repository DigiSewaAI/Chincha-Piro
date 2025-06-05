<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use App\Notifications\CartItemAdded;

class CartController extends Controller
{
    /**
     * कार्ट पृष्ठ प्रदर्शन गर्ने
     */
    public function viewCart()
    {
        $cart = $this->getCart();
        $cartItems = $cart->items()->with('menu')->get();

        return view('cart.index', compact('cart', 'cartItems'));
    }

    /**
     * मेनु आइटमलाई कार्टमा थप्ने (उन्नत सुविधाहरू सहित)
     */
    public function addToCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'expected_price' => 'required|numeric'
        ]);

        // 📦 डेटाबेस लक गर्नुहोस् (स्टकको लागि)
        DB::beginTransaction();

        try {
            $menu = Menu::lockForUpdate()->findOrFail($id);

            // 📉 स्टक जाँच
            if ($menu->stock < $request->quantity) {
                return response()->json([
                    'error' => "अनुरोध गरिएको मात्रा उपलब्ध स्टकभन्दा बढी छ।"
                ], 400);
            }

            // 💰 मूल्य सत्यापन
            if ((float) $menu->price !== (float) $request->expected_price) {
                return response()->json([
                    'error' => "मूल्यमा परिवर्तन भएको छ। कृपया पृष्ठ रिफ्रेस गर्नुहोस्।"
                ], 400);
            }

            // 🛒 कार्ट प्राप्त गर्नुहोस्
            $cart = $this->getCart();

            // 📈 स्टक कम गर्नुहोस्
            $menu->decrement('stock', $request->quantity);

            // 🧾 कार्टमा आइटम थप्नुहोस्
            $cartItem = $cart->items()->updateOrCreate(
                ['menu_id' => $menu->id],
                [
                    'price' => $menu->price,
                    'quantity' => $request->quantity
                ]
            );

            // 📣 एडमिनलाई सूचना पठाउनुहोस्
            Notification::route('mail', 'admin@example.com')
                ->notify(new CartItemAdded($cartItem));

            // 📦 ताजा कार्टको संख्या प्राप्त गर्नुहोस्
            $cart->refresh();

            DB::commit();

            return response()->json([
                'success' => "मेनु आइटम कार्टमा थपियो!",
                'cart_count' => $cart->items->sum('quantity')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'कार्ट थप्न असफल'], 500);
        }
    }

    /**
     * कार्ट अपडेट गर्ने
     */
    public function updateCart(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $cartItem = CartItem::where('id', $id)
                ->whereHas('cart', function ($query) {
                    $query->where('user_id', Auth::id());
                })->firstOrFail();

            $menu = $cartItem->menu()->lockForUpdate()->firstOrFail();

            $oldQuantity = $cartItem->quantity;
            $newQuantity = $request->quantity;

            // 📉 स्टक अपडेट
            $difference = $newQuantity - $oldQuantity;
            if ($difference > 0 && $menu->stock < $difference) {
                return response()->json([
                    'error' => "अपर्याप्त स्टक! केवल $menu->stock उपलब्ध छ।"
                ], 400);
            }

            $menu->increment('stock', -$difference); // स्टक अपडेट
            $cartItem->update(['quantity' => $newQuantity]);

            DB::commit();

            return back()->with('success', 'कार्ट अपडेट भयो!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'कार्ट अपडेट गर्न असफल');
        }
    }

    /**
     * कार्टबाट आइटम हटाउने
     */
    public function removeFromCart($id)
    {
        DB::beginTransaction();

        try {
            $cartItem = CartItem::where('id', $id)
                ->whereHas('cart', function ($query) {
                    $query->where('user_id', Auth::id());
                })->firstOrFail();

            $menu = $cartItem->menu;
            $menu->increment('stock', $cartItem->quantity); // 📈 स्टक बढाउनुहोस्
            $cartItem->delete();

            DB::commit();

            return back()->with('success', 'आइटम कार्टबाट हटाइयो!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'आइटम हटाउन असफल');
        }
    }

    /**
     * सबै कार्ट आइटम हटाउने
     */
    public function clearCart()
    {
        DB::beginTransaction();

        try {
            $cart = $this->getCart();
            foreach ($cart->items as $item) {
                $item->menu->increment('stock', $item->quantity); // 📈 सबै आइटमको स्टक बढाउनुहोस्
            }
            $cart->items()->delete();

            DB::commit();

            return back()->with('success', 'कार्ट सफा भयो!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'कार्ट सफा गर्न असफल');
        }
    }

    /**
     * कार्टको आइटम संख्या प्राप्त गर्ने
     */
    public function getCartCount()
    {
        $cart = $this->getCart();
        $count = $cart->items->sum('quantity');
        return response()->json(['count' => $count]);
    }

    /**
     * वर्तमान कार्ट प्राप्त गर्ने
     */
    protected function getCart()
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        } else {
            $sessionId = Session::getId();
            return Cart::firstOrCreate(['session_id' => $sessionId]);
        }
    }
}
