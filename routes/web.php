<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataManagementController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('fe.index');
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

Route::get('/dashboard-fe', function () {
    return view('dashboard');
});
// FE static routes rendered via Blade (no auth)
Route::prefix('fe')->group(function () {
    Route::get('/', function () {
        return view('fe.index');
    });
    Route::get('/login', function () {
        return view('fe.login');
    });
    Route::get('/dashboard', function () {
        return view('fe.dashboard');
    });
    Route::get('/datamanagement', function () {
        return view('fe.datamanagement');
    });
    Route::get('/reports', function () {
        return view('fe.reports');
    });
    Route::get('/calendar', function () {
        return view('fe.calendar');
    });
    Route::get('/forum', function () {
        return view('fe.forum');
    });
    Route::get('/settings', function () {
        return view('fe.settings');
    });
    Route::get('/dinas-status', function () {
        return view('fe.dinas-status');
    });
    Route::get('/dpmptsp', function () { return view('fe.dpmptsp'); });
    Route::get('/perdagangan', function () { return view('fe.perdagangan'); });
    Route::get('/perindustrian', function () { return view('fe.perindustrian'); });
    Route::get('/koperasi', function () { return view('fe.koperasi'); });
    Route::get('/tanaman-pangan', function () { return view('fe.tanaman-pangan'); });
    Route::get('/perkebunan', function () { return view('fe.perkebunan'); });
    Route::get('/ketapang', function () { return view('fe.ketapang'); });
    Route::get('/pariwisata', function () { return view('fe.pariwisata'); });
    Route::get('/dlh', function () { return view('fe.dlh'); });
    Route::get('/perikanan', function () { return view('fe.perikanan'); });
  });
