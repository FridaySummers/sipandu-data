<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DinasController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/dinas-status', [DinasController::class, 'getStatus']);
    Route::get('/dinas/{dinas}', [DinasController::class, 'show']);
    Route::post('/dinas', [DinasController::class, 'store']);
});
