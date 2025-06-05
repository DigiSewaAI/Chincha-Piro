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

        // 🛒 कार्ट खाली छ कि भने सूचना
        if ($cartItems->isEmpty()) {
            session()->flash('info', 'तपाईंको कार्ट खाली छ।');
        }

        return view('cart.index', compact('cartItems'));
    }

    /**
     * मेनु आइटमलाई कार्टमा थप्ने
     */
    public function addToCart(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'expected_price' => 'required|numeric'
        ]);

        DB::beginTransaction();

        try {
            $menu = Menu::lockForUpdate()->findOrFail($id);

            // 📉 स्टक जाँच
            if ($menu->stock < $validated['quantity']) {
                return $this->handleResponse($request, [
                    'error' => "अनुरोध गरिएको मात्रा उपलब्ध स्टकभन्दा बढी छ। उपलब्ध: {$menu->stock}"
                ], 400);
            }

            // 💰 मूल्य सत्यापन
            if ((float) $menu->price !== (float) $validated['expected_price']) {
                return $this->handleResponse($request, [
                    'error' => "मूल्यमा परिवर्तन भएको छ। कृपया पृष्ठ रिफ्रेस गर्नुहोस्।"
                ], 400);
            }

            $cart = $this->getCart();

            // 🧾 कार्टमा आइटम थप्नुहोस्
            $cartItem = $cart->items()->updateOrCreate(
                ['menu_id' => $menu->id],
                [
                    'price' => $menu->price,
                    'quantity' => $validated['quantity']
                ]
            );

            // 📈 स्टक कम गर्नुहोस्
            $menu->decrement('stock', $validated['quantity']);

            // 📣 एडमिनलाई सूचना पठाउनुहोस्
            Notification::route('mail', 'admin@example.com')
                ->notify(new CartItemAdded($cartItem));

            DB::commit();

            $responseData = [
                'success' => "मेनु आइटम कार्टमा थपियो!",
                'cart_count' => $cart->items->sum('quantity')
            ];

            return $this->handleResponse($request, $responseData);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleResponse($request, [
                'error' => 'कार्ट थप्न असफल'
            ], 500);
        }
    }

    /**
     * कार्ट अपडेट गर्ने
     */
    public function updateCart(Request $request, $id)
    {
        $validated = $request->validate(['quantity' => 'required|integer|min:1']);

        DB::beginTransaction();

        try {
            $cartItem = CartItem::where('id', $id)
                ->whereHas('cart', function ($query) {
                    $query->where('user_id', Auth::id());
                })->firstOrFail();

            $menu = $cartItem->menu()->lockForUpdate()->firstOrFail();

            $oldQuantity = $cartItem->quantity;
            $newQuantity = $validated['quantity'];

            // 📉 स्टक अपडेट
            $difference = $newQuantity - $oldQuantity;
            if ($difference > 0 && $menu->stock < $difference) {
                return $this->handleResponse($request, [
                    'error' => "अपर्याप्त स्टक! केवल {$menu->stock} उपलब्ध छ।"
                ], 400);
            }

            $menu->increment('stock', -$difference); // 📈 स्टक अपडेट
            $cartItem->update(['quantity' => $newQuantity]);

            DB::commit();

            return $this->handleResponse($request, [
                'success' => 'कार्ट अपडेट भयो!',
                'total' => $cartItem->price * $cartItem->quantity
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleResponse($request, [
                'error' => 'कार्ट अपडेट गर्न असफल'
            ], 500);
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

            return $this->handleResponse($request, [
                'success' => 'आइटम कार्टबाट हटाइयो!',
                'cart_count' => $this->getCart()->items->sum('quantity')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleResponse($request, [
                'error' => 'आइटम हटाउन असफल'
            ], 500);
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

            // 📈 सबै आइटमको स्टक बढाउनुहोस्
            $cart->items->each(function ($item) {
                $item->menu->increment('stock', $item->quantity);
            });

            $cart->items()->delete(); // 🧹 कार्ट सफा गर्नुहोस्

            DB::commit();

            return $this->handleResponse($request, [
                'success' => 'कार्ट सफा भयो!',
                'cart_count' => 0
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleResponse($request, [
                'error' => 'कार्ट सफा गर्न असफल'
            ], 500);
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

    /**
     * प्रतिक्रिया प्रकारको आधारमा JSON वा रिडाइरेक्ट ह्यान्डल गर्नुहोस्
     */
    private function handleResponse(Request $request, array $data, int $status = 200)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($data, $status);
        }

        if (isset($data['error'])) {
            return back()->withInput()->withErrors([$data['error']]);
        }

        if (isset($data['success'])) {
            return back()->with('success', $data['success']);
        }

        return back();
    }
}
