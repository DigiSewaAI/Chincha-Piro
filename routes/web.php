<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    ProfileController,
    AdminController,
    DashboardController,
    OrderController,
    GalleryController,
    ContactController,
    ReservationController,
    TranslateController,
    MenuController, // Public MenuController
};
use App\Http\Controllers\Admin\MenuController as AdminMenuController; // Admin MenuController

// ------------------
// 🔓 Public Routes
// ------------------

Route::get('/', [HomeController::class, 'index'])->name('home');

// ✅ Public Menu Routes
Route::get('/menu', [MenuController::class, 'publicMenu'])->name('menu.index');
Route::get('/menu/{id}', [MenuController::class, 'show'])->name('menu.show');

// Public Gallery & Static Pages
Route::get('/gallery', [GalleryController::class, 'publicIndex'])->name('gallery.public');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/services', [HomeController::class, 'services'])->name('services');

// Contact Page
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Translation Page
Route::get('/translate', [TranslateController::class, 'show'])->name('translate');
Route::post('/translate-text', [TranslateController::class, 'translate'])->name('translate.text');

// Reservation List Page (public)
Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');

// ✅ Public Order Routes
Route::prefix('orders')->name('orders.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/', [OrderController::class, 'create'])->name('create');
        Route::post('/new', [OrderController::class, 'store'])->name('store');
    });

    Route::get('/list', [OrderController::class, 'publicIndex'])->name('public.index');
    Route::get('/track/{order}', [OrderController::class, 'track'])->name('track');
    Route::get('/success', [OrderController::class, 'success'])->name('success');
});

// ----------------------
// 🔐 Auth Routes
// ----------------------

require __DIR__.'/auth.php';

// ----------------------
// 👤 Authenticated User Routes
// ----------------------

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'userIndex'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/history', [OrderController::class, 'index'])->name('index');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::get('/{order}/receipt', [OrderController::class, 'downloadReceipt'])->name('receipt');
    });

    // User Reservations
    Route::resource('reservations', ReservationController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
});

// ----------------------
// 🛠️ Admin Routes
// ----------------------

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('dashboard');

        // 🧾 Order Management
        Route::resource('orders', OrderController::class)->except(['create', 'store']);
        Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('orders/{order}/invoice', [OrderController::class, 'generateInvoice'])->name('orders.invoice');

        // 🍽️ Menu Management (using Admin MenuController)
        Route::resource('menu', AdminMenuController::class)->except(['show']);

        // 🛎️ Reservation Management
        Route::resource('reservations', ReservationController::class)->except(['index', 'store']);
        Route::get('reservations/history', [ReservationController::class, 'history'])->name('reservations.history');

        // 🖼️ Gallery Management
        Route::resource('gallery', GalleryController::class)->except(['show']);
        Route::patch('gallery/{gallery}/toggle-status', [GalleryController::class, 'toggleStatus'])->name('gallery.toggleStatus');
        Route::post('gallery/{gallery}/mark-featured', [GalleryController::class, 'markFeatured'])->name('gallery.markFeatured');

        // ⚙️ Site Settings
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    });
