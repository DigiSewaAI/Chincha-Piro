<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\CartController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // You can bind custom services here if needed
    }

    /**
     * Bootstrap any application services.
     *
     * - Sets Carbon locale to Nepali ('ne')
     * - Sets system locale for date/time formatting to 'ne_NP.UTF-8'
     * - Shares cart count across all views for both authenticated and guest users
     */
    public function boot(): void
    {
        // Set Carbon locale for Nepali formatting
        Carbon::setLocale('ne');

        // Set system locale for Nepali time/date functions (strftime, strptime, etc.)
        setlocale(LC_TIME, 'ne_NP.UTF-8');

        // Share cart count across all views
        View::composer('*', function ($view) {
            // Skip for JSON requests
            if (request()->wantsJson()) return;

            try {
                // Initialize cart count
                $cartCount = 0;

                // For authenticated users
                if (Auth::check()) {
                    $cart = app(CartController::class)->getCart();
                    $cartCount = $cart->items->sum('quantity');
                }
                // For guest users with session cart
                elseif (Session::has('cart_session_id')) {
                    $cart = CartController::getGuestCart();
                    $cartCount = $cart->items->sum('quantity');
                }

                // Share with all views
                $view->with('cartCount', $cartCount);
            } catch (\Exception $e) {
                // Log error but continue
                \Log::error("Cart count error: " . $e->getMessage());
                $view->with('cartCount', 0);
            }
        });
    }
}
