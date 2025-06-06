<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;

class AdminCartController extends Controller
{
    /**
     * 🛒 सबै कार्ट गतिविधि प्रदर्शन
     */
    public function index(Request $request)
    {
        $query = Cart::with(['items.menu', 'user'])->withCount('items');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('date')) {
            $query->whereDate('updated_at', $request->input('date'));
        }

        if ($request->filled('status')) {
            $query->has('items', $request->input('status') === 'empty' ? '=' : '>', 0);
        }

        $query->latest();

        $perPage = $request->input('per_page', 10);
        $carts = $query->paginate($perPage)->appends($request->query());

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
        $cart = Cart::with(['items.menu', 'user'])->findOrFail($id);

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

        if ($request->filled('date')) {
            $query->whereDate('updated_at', $request->input('date'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        $carts = $query->get();

        $headers = [
            'कार्ट ID',
            'प्रयोगकर्ता',
            'आइटमहरू',
            'कुल मूल्य',
            'कुल कर सहित',
            'अन्तिम अपडेट',
        ];

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

        return response()->streamDownload(function () use ($headers, $csvData) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF"); // UTF-8 BOM
            fputcsv($handle, $headers);
            foreach ($csvData as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, 'cart_export_' . now()->format('Y-m-d') . '.csv');
    }

    /**
     * 🧹 पुराना कार्टहरू सफा गर्नुहोस्
     */
    public function clearOldCarts()
    {
        $deleted = Cart::where('updated_at', '<', now()->subDays(7))->delete();

        return back()->with('success', "$deleted पुराना कार्टहरू सफा गरियो");
    }

    /**
     * 📊 ड्यासबोर्ड तथ्यांक
     */
    public function dashboard()
    {
        $stats = [
            'total_carts' => Cart::count(),
            'active_carts' => Cart::has('items', '>', 0)->count(),
            'empty_carts' => Cart::has('items', '=', 0)->count(),
            'total_items' => CartItem::sum('quantity'),
            'avg_items_per_cart' => round(CartItem::count() / max(1, Cart::count()), 2),
            'total_value' => number_format(
                CartItem::all()->sum(fn($i) => $i->price * $i->quantity), 2
            ),
            'daily_average' => number_format(
                CartItem::where('created_at', '>=', now()->startOfDay())
                    ->get()->sum(fn($i) => $i->price * $i->quantity), 2
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
