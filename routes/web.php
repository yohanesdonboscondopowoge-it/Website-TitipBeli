<?php
// routes/web.php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth routes dari Breeze
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // === TRIPS (Auth required untuk CRUD) ===
    Route::get('/my-trips', [TripController::class, 'myTrips'])->name('trips.my');
    Route::get('/trips/create', [TripController::class, 'create'])->name('trips.create');
    Route::post('/trips', [TripController::class, 'store'])->name('trips.store');
    Route::get('/trips/{trip}/edit', [TripController::class, 'edit'])->name('trips.edit');
    Route::put('/trips/{trip}', [TripController::class, 'update'])->name('trips.update');
    Route::delete('/trips/{trip}', [TripController::class, 'destroy'])->name('trips.destroy');

    // === REQUESTS (Auth required untuk CRUD) ===
    Route::get('/my-requests', [RequestController::class, 'myRequests'])->name('requests.my');
    Route::get('/requests/create', [RequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');
    Route::get('/requests/{titipRequest}/edit', [RequestController::class, 'edit'])->name('requests.edit');
    Route::put('/requests/{titipRequest}', [RequestController::class, 'update'])->name('requests.update');
    Route::delete('/requests/{titipRequest}', [RequestController::class, 'destroy'])->name('requests.destroy');

    // === ORDERS ===
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/trips/{trip}/order', [OrderController::class, 'createFromTrip'])->name('orders.createFromTrip');
    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{order}/payment', [OrderController::class, 'uploadPayment'])->name('orders.uploadPayment');
    Route::post('/orders/{order}/delivery', [OrderController::class, 'updateDelivery'])->name('orders.updateDelivery');
    Route::post('/orders/{order}/confirm', [OrderController::class, 'confirmReceived'])->name('orders.confirmReceived');
    Route::post('/orders/{order}/dispute', [OrderController::class, 'dispute'])->name('orders.dispute');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // === RATING ===
    Route::post('/orders/{order}/rate', [RatingController::class, 'store'])->name('ratings.store');
});

// === PUBLIC ROUTES (di bawah, setelah auth routes) ===
Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
Route::get('/trips/{trip}', [TripController::class, 'show'])->name('trips.show');

Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
Route::get('/requests/{titipRequest}', [RequestController::class, 'show'])->name('requests.show');

// === ADMIN ROUTES ===
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
    // User management
Route::get('/users/{user}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('users.edit');
Route::put('/users/{user}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
Route::post('/users/{user}/reset-password', [App\Http\Controllers\AdminController::class, 'resetPassword'])->name('users.reset-password');
Route::post('/users/{user}/toggle-ban', [App\Http\Controllers\AdminController::class, 'toggleBan'])->name('users.toggle-ban');
Route::delete('/users/{user}', [App\Http\Controllers\AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/pending-payments', [App\Http\Controllers\AdminController::class, 'pendingPayments'])->name('pending-payments');
    Route::get('/disputes', [App\Http\Controllers\AdminController::class, 'disputes'])->name('disputes');
    Route::post('/orders/{order}/verify-payment', [App\Http\Controllers\AdminController::class, 'verifyPayment'])->name('verify-payment');
    Route::post('/orders/{order}/reject-payment', [App\Http\Controllers\AdminController::class, 'rejectPayment'])->name('reject-payment');
    Route::post('/orders/{order}/resolve-dispute', [App\Http\Controllers\AdminController::class, 'resolveDispute'])->name('resolve-dispute');       
});



require __DIR__.'/auth.php';