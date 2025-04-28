<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
require __DIR__ . '/auth.php';

// Admin Login (Public)
Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');

// Admin Protected Routes
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminIndex'])
            ->name('dashboard');

        // Admin-specific Order routes
        Route::resource('orders', OrderController::class);
    });

// User Protected Routes
Route::middleware(['auth', 'verified'])
    ->group(function () {
        // User Dashboard
        Route::get('/dashboard', [DashboardController::class, 'userIndex'])
            ->name('dashboard');

        // User-specific Order routes
        Route::resource('orders', OrderController::class);

        // Profile Routes
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');
    });
