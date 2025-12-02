<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataManagementController;
use App\Http\Controllers\DinasController; // TAMBAHKAN INI
use Illuminate\Support\Facades\Route;

// Public landing page (Index). Akses aplikasi lewat tombol "Masuk".
Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes - require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Data Management CRUD Routes
    Route::prefix('data-management')->group(function () {
        Route::get('/', [DataManagementController::class, 'index'])->name('datamanagement');
        Route::get('/create', [DataManagementController::class, 'create'])->name('datamanagement.create');
        Route::post('/', [DataManagementController::class, 'store'])->name('datamanagement.store');
        Route::get('/{dataSubmission}/edit', [DataManagementController::class, 'edit'])->name('datamanagement.edit');
        Route::put('/{dataSubmission}', [DataManagementController::class, 'update'])->name('datamanagement.update');
        Route::delete('/{dataSubmission}', [DataManagementController::class, 'destroy'])->name('datamanagement.destroy');
        
        // Approval routes
        Route::post('/{dataSubmission}/approve', [DataManagementController::class, 'approve'])->name('datamanagement.approve');
        Route::post('/{dataSubmission}/reject', [DataManagementController::class, 'reject'])->name('datamanagement.reject');
    });

    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    Route::get('/calendar', [DashboardController::class, 'calendar'])->name('calendar');
    Route::get('/forum', [DashboardController::class, 'forum'])->name('forum');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
    Route::get('/dinas-status', [DashboardController::class, 'dinasStatus'])->name('dinas-status');
    
    // DINAS DYNAMIC ROUTES - UPDATE INI
    Route::prefix('dinas')->group(function () {
        // Dynamic route - HARUS DIATAS STATIC ROUTES
        Route::get('/{id}', [DinasController::class, 'show'])->name('dinas.show');
        
        // Keep static routes for backward compatibility
        Route::view('/dpmptsp', 'dinas.dpmptsp')->name('dinas.dpmptsp.old');
        Route::view('/perdagangan', 'dinas.perdagangan')->name('dinas.perdagangan.old');
        Route::view('/perindustrian', 'dinas.perindustrian')->name('dinas.perindustrian.old');
        Route::view('/koperasi', 'dinas.koperasi')->name('dinas.koperasi.old');
        Route::view('/tanaman-pangan', 'dinas.tanaman-pangan')->name('dinas.tanaman-pangan.old');
        Route::view('/perkebunan', 'dinas.perkebunan')->name('dinas.perkebunan.old');
        Route::view('/ketapang', 'dinas.ketapang')->name('dinas.ketapang.old');
        Route::view('/pariwisata', 'dinas.pariwisata')->name('dinas.pariwisata.old');
        Route::view('/dlh', 'dinas.dlh')->name('dinas.dlh.old');
        Route::view('/perikanan', 'dinas.perikanan')->name('dinas.perikanan.old');
    });
});