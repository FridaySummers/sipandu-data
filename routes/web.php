<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataManagementController;

// Public routes
Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes - require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/data-management', [DataManagementController::class, 'index'])->name('datamanagement');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    Route::get('/calendar', [DashboardController::class, 'calendar'])->name('calendar');
    Route::get('/forum', [DashboardController::class, 'forum'])->name('forum');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
    Route::get('/dinas-status', [DashboardController::class, 'dinasStatus'])->name('dinas-status');
});
