<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class AdminCartController extends Controller
{
    /**
     * 🛒 सबै कार्ट गतिविधि प्रदर्शन
     */
    public function index(Request $request)
    {
        // 🔍 खोजी र फिल्टरिङ्ग
        $query = Cart::with(['items.menu', 'user'])
                    ->withCount('items');

        // 🔎 प्रयोगकर्ता द्वारा फिल्टर
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // 📆 मिति द्वारा फिल्टर
        if ($request->filled('date')) {
            $query->whereDate('updated_at', $request->input('date'));
        }

        // 📊 कार्ट अवस्था द्वारा फिल्टर (खाली/गैर-खाली)
        if ($request->filled('status')) {
            $query->has('items', $request->input('status') === 'empty' ? '=' : '>', 0);
        }

        // 📅 नवीनतम कार्ट पहिले
        $query->latest();

        // 📄 पृष्ठीकरण
        $perPage = $request->input('per_page', 10);
        $carts = $query->paginate($perPage)->appends($request->query());

        // 📋 कार्ट सारांश सांख्यिकीय
        $stats = [
            'total_carts' => Cart::count(),
            'active_carts' => Cart::has('items', '>', 0)->count(),
            'empty_carts' => Cart::has('items', '=', 0)->count(),
            'total_items' => CartItem::sum('quantity'),
            'avg_items_per_cart' => round(CartItem::count() / max(1, Cart::count()), 2)
        ];

        return view('admin.carts.index', compact('carts', 'stats'));
    }

    /**
     * 📄 कार्टको विवरण प्रदर्शन
     */
    public function show($id)
    {
        $cart = Cart::with([
                'items.menu',
                'user',
                'items' => function($q) {
                    $q->withSum('menu as total_price', 'price * quantity');
                }
            ])
            ->findOrFail($id);

        // 📊 कार्ट विश्लेषण
        $analysis = [
            'item_count' => $cart->items->sum('quantity'),
            'total_value' => $cart->items->sum(fn($i) => $i->price * $i->quantity),
            'tax_amount' => $cart->items->sum(fn($i) => $i->price * $i->quantity) * 0.13,
            'total_with_tax' => $cart->items->sum(fn($i) => $i->price * $i->quantity) * 1.13
        ];

        return view('admin.carts.show', compact('cart', 'analysis'));
    }

    /**
     * 📤 CSV रूपमा कार्ट डेटा निर्यात गर्नुहोस्
     */
    public function export(Request $request)
    {
        $query = Cart::with(['items.menu', 'user']);

        // 📆 मिति फिल्टर
        if ($request->filled('date')) {
            $query->whereDate('updated_at', $request->input('date'));
        }

        // 👤 प्रयोगकर्ता फिल्टर
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        $carts = $query->get();

        // 📋 CSV सिर्शीर्ष तैयार गर्नुहोस्
        $headers = [
            'कार्ट ID',
            'प्रयोगकर्ता',
            'आइटमहरू',
            'कुल मूल्य',
            'कुल कर सहित',
            'अन्तिम अपडेट',
        ];

        // 📄 CSV डाटा तैयार गर्नुहोस्
        $csvData = $carts->map(function ($cart) {
            return [
                $cart->id,
                $cart->user?->name ?? 'अतिथि',
                $cart->items->sum('quantity'),
                number_format($cart->items->sum(fn($i) => $i->price * $i->quantity), 2),
                number_format($cart->items->sum(fn($i) => $i->price * $i->quantity) * 1.13, 2),
                $cart->updated_at->format('M d, Y H:i'),
            ];
        });

        // 📥 CSV फाइल डाउनलोड गर्नुहोस्
        return response()->streamDownload(function () use ($headers, $csvData) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF"); // 🔤 UTF-8 BOM
            fputcsv($handle, $headers);
            foreach ($csvData as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, 'कार्ट_गतिविधि_' . now()->format('Y-m-d') . '.csv');
    }

    /**
     * 🧹 कार्ट मेमोरी सफा गर्नुहोस्
     */
    public function clearOldCarts()
    {
        // 🕒 7 दिन भन्दा पुराना कार्टहरू सफा गर्नुहोस्
        $deleted = Cart::where('updated_at', '<', now()->subDays(7))->delete();

        return back()->with('success', "$deleted पुराना कार्टहरू सफा गरियो");
    }

    /**
     * 📊 कार्ट विश्लेषण डेस्कटप डेटा
     */
    public function dashboard()
    {
        $stats = [
            'total_carts' => Cart::count(),
            'active_carts' => Cart::has('items', '>', 0)->count(),
            'empty_carts' => Cart::has('items', '=', 0)->count(),
            'total_items' => CartItem::sum('quantity'),
            'avg_items_per_cart' => round(CartItem::count() / max(1, Cart::count()), 2),
            'total_value' => number_format(CartItem::sum(fn($i) => $i->price * $i->quantity), 2),
            'daily_average' => number_format(
                CartItem::where('created_at', '>=', now()->startOfDay())
                    ->sum(fn($i) => $i->price * $i->quantity), 2
            )
        ];

        $recentCarts = Cart::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($cart) {
                return [
                    'id' => $cart->id,
                    'user' => $cart->user?->name ?? 'अतिथि',
                    'item_count' => $cart->items->sum('quantity'),
                    'total' => number_format($cart->items->sum(fn($i) => $i->price * $i->quantity) * 1.13, 2),
                    'updated' => $cart->updated_at->diffForHumans()
                ];
            });

        return view('admin.carts.dashboard', compact('stats', 'recentCarts'));
    }
}
