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
        Route::resource('orders', OrderController::class);
    });

// User Protected Routes
Route::middleware(['auth', 'verified'])
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'userIndex'])
            ->name('dashboard');

        // Orders
        Route::resource('orders', OrderController::class);

        // Reservations
        Route::resource('reservations', ReservationController::class)
             ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');

        // Menu, Gallery, Contact
        Route::get('/menu', [MenuController::class, 'index'])->name('menu');
        Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
        Route::get('/contact', [ContactController::class, 'index'])->name('contact');
        Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

    });
