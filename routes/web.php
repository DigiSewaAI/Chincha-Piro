<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TranslateController;
use App\Http\Controllers\DishController; // 📝 DishController थपिएको

// =======================
// Public Routes
// =======================

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public Order Routes
Route::prefix('order')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('order.index');
    Route::post('/', [OrderController::class, 'store'])->name('order.submit');
    Route::get('/track/{order}', [OrderController::class, 'track'])->name('order.track');
});

// Static Pages (Public)
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/about', fn() => view('public.about'))->name('about');
Route::get('/services', fn() => view('public.services'))->name('services');

// Public Menu Routes
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{id}', [MenuController::class, 'show'])->name('menu.show');

// Booking History (Optional User Page)
Route::middleware(['auth', 'verified'])
    ->get('/booking/history', fn() => view('user.booking-history'))
    ->name('booking.history');

// =======================
// Authentication Routes
// =======================
require __DIR__.'/auth.php';

// =======================
// Admin Routes
// =======================

// Public Admin Login
Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');

// Protected Admin Area
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('dashboard');

        // Admin Order Management
        Route::resource('orders', OrderController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
        Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

        // Admin Menu Management
        Route::resource('menus', MenuController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy', 'show']);

        // Admin Dish Management (नयाँ थपिएको)
        Route::resource('dishes', DishController::class); // 📝 DishController थपिएको

        // Admin Settings
        Route::get('/settings', fn() => view('admin.settings'))->name('settings');
    });

// =======================
// Authenticated User Routes
// =======================
Route::middleware(['auth', 'verified'])
    ->group(function () {
        // User Dashboard
        Route::get('/dashboard', [DashboardController::class, 'userIndex'])->name('dashboard');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // User Orders List, Details & Delete
        Route::resource('orders', OrderController::class)->only(['index', 'show', 'destroy']);

        // Reservations
        Route::resource('reservations', ReservationController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // Standalone route for storing orders
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    });

// =======================
// Translation Routes
// =======================
Route::get('/translate', [TranslateController::class, 'show'])->name('translate');
Route::post('/translate-text', [TranslateController::class, 'translate'])->name('translate.text');
