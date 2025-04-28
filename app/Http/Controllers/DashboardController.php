<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function adminIndex()
    {
        $activeUsers = User::where('is_active', true)->latest()->take(5)->get();
        $recentOrders = Order::with('user')->latest()->limit(5)->get();
        $chartLabels = ['बैशाख', 'जेठ', 'असार', 'साउन', 'भदौ', 'असोज'];
        $chartData = [65000, 59000, 80000, 81000, 56000, 75000];

        return view('admin.dashboard', compact(
            'activeUsers',
            'recentOrders',
            'chartLabels',
            'chartData'
        ));
    }

    public function userIndex(Request $request)
    {
        $user = $request->user();
        $activeUsers = User::where('is_active', true)->latest()->take(5)->get();
        $totalActiveUsers = User::where('is_active', true)->count();
        $recentOrders = Order::with('user')->latest()->limit(5)->get();

        $chartLabels = ['बैशाख', 'जेठ', 'असार', 'साउन', 'भदौ', 'असोज'];
        $chartData = [65000, 59000, 80000, 81000, 56000, 75000];

        return view('dashboard', compact(
            'user',
            'activeUsers',
            'totalActiveUsers',
            'recentOrders',
            'chartLabels',
            'chartData'
        ));
    }
}
