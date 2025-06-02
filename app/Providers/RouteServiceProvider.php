<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/dashboard';

    public function boot(): void
    {
        // Rate Limiter
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Model Binding
        Route::bind('menu', function ($value) {
            return $this->app->make(\App\Models\Menu::class)::findOrFail($value);
        });

        // Routes (web.php पहिले लोड गर्नुहोस्)
        $this->routes(function () {
            // ✅ Web Routes (public र admin routes)
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // ✅ API Routes
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });
    }
}
