<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;



Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login_post']);
});

// Authenticated routes (accessible only if logged in)
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
    });

    Route::middleware('role:user')->group(function () {
        Route::get('/user/dashboard', [DashboardController::class, 'dashboard'])->name('user.dashboard');
    });


});
