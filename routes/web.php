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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

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

    // Public order listing & tracking
    Route::get('/list', [OrderController::class, 'publicIndex'])->name('public.index');
    Route::get('/track/{order}', [OrderController::class, 'track'])->name('track');
    Route::get('/success', [OrderController::class, 'success'])->name('success'); // ✅ Success page
});

// Static Pages
Route::get('/gallery', [GalleryController::class, 'publicIndex'])->name('gallery.public');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/services', [HomeController::class, 'services'])->name('services');

// Public Menu Routes
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{dish}', [MenuController::class, 'show'])->name('menu.show');

// Public Reservation Viewing (List Only)
Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');

// Translation
Route::get('/translate', [TranslateController::class, 'show'])->name('translate');
Route::post('/translate-text', [TranslateController::class, 'translate'])->name('translate.text');

// =======================
// Authentication Routes
// =======================
require __DIR__.'/auth.php';

// =======================
// Authenticated User Routes
// =======================
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'userIndex'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Order History & Receipt
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/history', [OrderController::class, 'index'])->name('index');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::get('/{order}/receipt', [OrderController::class, 'downloadReceipt'])->name('receipt');
    });

    // Authenticated User Reservations (Create/Edit)
    Route::resource('reservations', ReservationController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy']);
});

// =======================
// Admin Routes (Role Protected)
// =======================
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('dashboard');

        // Orders
        Route::resource('orders', OrderController::class)
            ->except(['create', 'store']);

        Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('orders/{order}/invoice', [OrderController::class, 'generateInvoice'])->name('orders.invoice');

        // Menus (MenuController)
        Route::resource('menus', MenuController::class)
            ->except(['show']);

        // Dishes (DishController)
        Route::resource('dishes', DishController::class);

        // Reservations
        Route::resource('reservations', ReservationController::class)
            ->except(['index', 'store']);

        // Gallery
        Route::resource('gallery', GalleryController::class)
            ->except(['show']);

        // Admin Settings
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    });
