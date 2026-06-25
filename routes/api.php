<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Default API Routes
|--------------------------------------------------------------------------
*/

// Route bawaan dari instalasi API (untuk auth Sanctum)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Custom API Routes Aplikasi Jual Benih
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    
    // Fitur Retur
    Route::prefix('returns')->group(function () {
        // [POST] Endpoint untuk Toko: Mengirim pengajuan retur baru
        Route::post('/', [ReturnController::class, 'store']);
        
        // [PUT] Endpoint untuk Manajer: Memberikan keputusan (Approve/Reject)
        Route::put('/{id}/approve', [ReturnController::class, 'approve']);
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('/trends', [DashboardController::class, 'getReturnTrends']);
        Route::get('/top-products', [DashboardController::class, 'getTopReturnedProducts']);
    });

});