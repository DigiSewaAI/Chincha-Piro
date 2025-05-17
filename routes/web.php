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
// Public Routes (Guest Access)
// =======================

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public Order Routes
Route::prefix('orders')->name('orders.')->group(function () {
    // Guest-only order form
    Route::middleware('guest')->group(function () {
        Route::get('/', [OrderController::class, 'create'])->name('create');
        Route::post('/new', [OrderController::class, 'store'])->name('store');
    });

    // Public order listing
    Route::get('/list', [OrderController::class, 'publicIndex'])->name('public.index');

    // Order tracking (accessible to everyone)
    Route::get('/track/{order}', [OrderController::class, 'track'])->name('track');

    // ✅ Order success page
    Route::get('/success', [OrderController::class, 'success'])->name('success');
});

// Static Pages
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/services', [HomeController::class, 'services'])->name('services');

// Public Menu Routes
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{dish}', [MenuController::class, 'show'])->name('menu.show');

// Public Reservations
Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');

// =======================
// Authentication Routes
// =======================
require __DIR__.'/auth.php';

// =======================
// Authenticated User Routes (Customer Access)
// =======================
Route::middleware(['auth', 'verified'])->group(function () {
    // User Dashboard
    Route::get('/dashboard', [DashboardController::class, 'userIndex'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Order History
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/history', [OrderController::class, 'index'])->name('index');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::get('/{order}/receipt', [OrderController::class, 'downloadReceipt'])->name('receipt');
    });
});

// =======================
// Admin Routes (Protected by Role: 'admin')
// =======================
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('dashboard');

        // Admin Order Management
        Route::resource('orders', OrderController::class)
            ->except(['create', 'store']) // Already defined in public routes
            ->names([
                'index' => 'orders.index',
                'show' => 'orders.show',
                'edit' => 'orders.edit',
                'update' => 'orders.update',
                'destroy' => 'orders.destroy'
            ]);

        // Admin-Specific Order Actions
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
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    });

// =======================
// Translation Routes
// =======================
Route::get('/translate', [TranslateController::class, 'show'])->name('translate');
Route::post('/translate-text', [TranslateController::class, 'translate'])->name('translate.text');
