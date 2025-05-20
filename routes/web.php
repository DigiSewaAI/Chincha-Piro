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
// Public Routes
// =======================

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('orders')->name('orders.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/', [OrderController::class, 'create'])->name('create');
        Route::post('/new', [OrderController::class, 'store'])->name('store');
    });

    Route::get('/list', [OrderController::class, 'publicIndex'])->name('public.index');
    Route::get('/track/{order}', [OrderController::class, 'track'])->name('track');
    Route::get('/success', [OrderController::class, 'success'])->name('success');
});

Route::get('/gallery', [GalleryController::class, 'publicIndex'])->name('gallery.public');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/services', [HomeController::class, 'services'])->name('services');

Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{dish}', [MenuController::class, 'show'])->name('menu.show');

Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');

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
    Route::get('/dashboard', [DashboardController::class, 'userIndex'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/history', [OrderController::class, 'index'])->name('index');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::get('/{order}/receipt', [OrderController::class, 'downloadReceipt'])->name('receipt');
    });

    Route::resource('reservations', ReservationController::class)->only([
        'create', 'store', 'edit', 'update', 'destroy'
    ]);
});

// =======================
// Admin Routes
// =======================
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('dashboard');

        Route::resource('orders', OrderController::class)->except(['create', 'store']);
        Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('orders/{order}/invoice', [OrderController::class, 'generateInvoice'])->name('orders.invoice');

        Route::resource('menus', MenuController::class)->except(['show']);
        Route::resource('dishes', DishController::class);
        Route::resource('reservations', ReservationController::class)->except(['index', 'store']);

        // ✅ Gallery Routes
        Route::resource('gallery', GalleryController::class)->except(['show']);
        // This includes:
        // GET     /admin/gallery           → index
        // GET     /admin/gallery/create    → create
        // POST    /admin/gallery           → store
        // GET     /admin/gallery/{id}/edit → edit
        // PUT     /admin/gallery/{id}      → update
        // DELETE  /admin/gallery/{id}      → destroy ✔️ This is admin.gallery.destroy

<<<<<<< HEAD
        // Gallery
        Route::resource('gallery', GalleryController::class)
            ->except(['show']); // 'show' is handled by public route

        // Custom Gallery Actions
        Route::patch('gallery/{gallery}/toggle-status', [GalleryController::class, 'toggleStatus'])
            ->name('gallery.toggleStatus');
        Route::post('gallery/{gallery}/mark-featured', [GalleryController::class, 'markFeatured'])
            ->name('gallery.markFeatured');

        // Admin Settings
=======
>>>>>>> bf0c141ad1cd9b5312caa672c6a6e68455c88f46
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    });
