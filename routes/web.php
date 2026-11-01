<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientAreaController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\WorkerProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public Client-Facing Online Booking Portal (Step 4)
Route::get('/book', [PublicBookingController::class, 'index'])->name('booking.index');
Route::get('/book/availability', [PublicBookingController::class, 'checkAvailability'])->name('booking.availability');
Route::get('/book/success', [PublicBookingController::class, 'success'])->name('booking.success');
Route::post('/book', [PublicBookingController::class, 'store'])->name('booking.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    // Owner-Only Admin Workspace Routes
    Route::middleware('role:owner')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
        Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
        Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show')->whereNumber('client');
        Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update')->whereNumber('client');
        Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy')->whereNumber('client');

        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
        Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

        Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
        Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
        Route::get('/staff/{id}', [StaffController::class, 'show'])->name('staff.show');
        Route::put('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');
        Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
    });

    // Shared Owner & Worker Appointment Scheduling + Client Phone Lookup Routes
    Route::middleware('role:owner,worker')->group(function () {
        Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
        Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.update-status');

        Route::get('/clients/search-phone', [ClientController::class, 'searchByPhone'])->name('clients.search-phone');
    });

    // Worker-Only Personal Profile & Availability Routes (Phase 4)
    Route::middleware('role:worker')->group(function () {
        Route::get('/my-profile', [WorkerProfileController::class, 'index'])->name('worker.profile');
        Route::put('/my-profile', [WorkerProfileController::class, 'updateProfile'])->name('worker.profile.update');
        Route::patch('/my-profile/availability', [WorkerProfileController::class, 'updateAvailability'])->name('worker.profile.availability');
    });

    // Client-Only Personal Booking Portal
    Route::middleware('role:client')->group(function () {
        Route::get('/my-dashboard', [ClientAreaController::class, 'index'])->name('client.dashboard');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
