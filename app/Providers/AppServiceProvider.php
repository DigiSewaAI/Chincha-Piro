<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Date; // Optional, but good for clarity
use Carbon\Carbon; // For Nepali locale setup
use Illuminate\Support\Facades\App;

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
     */
    public function boot(): void
    {
        // Set Carbon locale for Nepali formatting
        Carbon::setLocale('ne');

        // Set system locale for Nepali time/date functions (strftime, strptime, etc.)
        setlocale(LC_TIME, 'ne_NP.UTF-8');
    }
}
