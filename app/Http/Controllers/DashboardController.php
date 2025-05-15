<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Reservation;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard प्रदर्शन गर्नुहोस्
     */
    public function adminIndex()
    {
        // Active Users (5 ओटा मात्र)
        $activeUsers = User::where('is_active', true)->latest()->take(5)->get();

        // Recent Orders with User (केवल Userको id र name)
        $recentOrders = Order::with(['user' => function ($query) {
            $query->select('id', 'name', 'email'); // 'user_id' छैन, किनकि User.id = Order.user_id
        }])->latest()->limit(5)->get();

        // Chart Data
        $chartLabels = ['बैशाख', 'जेठ', 'असार', 'साउन', 'भदौ', 'असोज'];
        $chartData = [65000, 59000, 80000, 81000, 56000, 75000];

        return view('admin.dashboard', compact(
            'activeUsers',
            'recentOrders',
            'chartLabels',
            'chartData'
        ));
    }

    /**
     * User Dashboard प्रदर्शन गर्नुहोस्
     */
    public function userIndex(Request $request)
    {
        $user = $request->user();

        // Today's Statistics
        $todayReservations = Reservation::whereDate('created_at', today())->count();
        $todayOrders = Order::whereDate('created_at', today())->where('user_id', $user->id)->count();

        // Dashboard Statistics
        $menuCount = Menu::count();

        // Recent Orders for Authenticated User
        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Order Statistics (Pending, Completed)
        $orderStats = [
            'pending' => Order::where('user_id', $user->id)
                ->whereStatus('pending')
                ->count(),
            'completed' => Order::where('user_id', $user->id)
                ->whereStatus('completed')
                ->count(),
        ];

        return view('dashboard', compact(
            'user',
            'todayReservations',
            'todayOrders',
            'menuCount',
            'recentOrders',
            'orderStats'
        ));
    }
}