<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    ProfileController,
    AdminController,
    DashboardController,
    OrderController,
    MenuController,
    GalleryController,
    ContactController,
    ReservationController,
    TranslateController,
    DishController
};

// =======================
// Public Routes
// =======================

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public Order Routes (Guest Order Placement & Tracking)
Route::prefix('order')->name('order.')->group(function () {
    Route::get('/', [OrderController::class, 'create'])->name('create');
    Route::post('/', [OrderController::class, 'store'])->name('store');
    Route::get('/track/{order}', [OrderController::class, 'track'])->name('track');
    Route::get('/list', [OrderController::class, 'index'])->name('index');
});

// Static Pages (Public)
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/services', [HomeController::class, 'services'])->name('services');

// Public Menu Routes
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{dish}', [MenuController::class, 'show'])->name('menu.show');

// =======================
// Authentication Routes
// =======================
require __DIR__.'/auth.php';

// =======================
// Admin Routes (Spatie Role Protection)
// =======================

// Public Admin Login
Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');

// Protected Admin Area with Role Middleware
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [DashboardController::class, 'adminIndex'])
            ->name('dashboard');

        // Admin Order Management
        Route::resource('orders', OrderController::class)
            ->except(['create', 'store'])
            ->names([
                'index' => 'admin.orders.index',
                'show' => 'admin.orders.show',
                'edit' => 'admin.orders.edit',
                'update' => 'admin.orders.update',
                'destroy' => 'admin.orders.destroy'
            ]);

        Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])
            ->name('orders.updateStatus');

        Route::get('orders/{order}/invoice', [OrderController::class, 'generateInvoice'])
            ->name('orders.invoice');

        // Admin Menu Management
        Route::resource('menus', MenuController::class)
            ->except(['show'])
            ->names([
                'index' => 'admin.menus.index',
                'create' => 'admin.menus.create',
                'store' => 'admin.menus.store',
                'edit' => 'admin.menus.edit',
                'update' => 'admin.menus.update',
                'destroy' => 'admin.menus.destroy'
            ]);

        // Admin Dish Management
        Route::resource('dishes', DishController::class)
            ->names([
                'index' => 'admin.dishes.index',
                'create' => 'admin.dishes.create',
                'store' => 'admin.dishes.store',
                'show' => 'admin.dishes.show',
                'edit' => 'admin.dishes.edit',
                'update' => 'admin.dishes.update',
                'destroy' => 'admin.dishes.destroy'
            ]);

        // Admin Settings
        Route::get('/settings', [AdminController::class, 'settings'])
            ->name('settings');
    });

// =======================
// Authenticated User Routes (Role-Aware)
// =======================
Route::middleware(['auth', 'verified'])->group(function () {
    // User Dashboard
    Route::get('/dashboard', [DashboardController::class, 'userIndex'])
        ->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Order Management
    Route::resource('orders', OrderController::class)
        ->names([
            'index' => 'user.orders.index',
            'show' => 'user.orders.show',
            'edit' => 'user.orders.edit',
            'update' => 'user.orders.update'
        ])
        ->except(['create', 'store', 'destroy']);

    // ✅ सीधा `orders.store` रूट थपियो
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])
        ->name('user.orders.cancel');

    Route::get('/orders/{order}/receipt', [OrderController::class, 'downloadReceipt'])
        ->name('user.orders.receipt');

    // Reservation Routes
    Route::resource('reservations', ReservationController::class)
        ->names([
            'index' => 'reservations.index',
            'create' => 'reservations.create',
            'store' => 'reservations.store',
            'show' => 'reservations.show',
            'edit' => 'reservations.edit',
            'update' => 'reservations.update',
            'destroy' => 'reservations.destroy'
        ]);
});

// =======================
// Translation Routes
// =======================
Route::get('/translate', [TranslateController::class, 'show'])->name('translate');
Route::post('/translate-text', [TranslateController::class, 'translate'])->name('translate.text');