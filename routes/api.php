<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\StoresModel;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

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

// Endpoint Login untuk Mobile App (Tidak butuh auth:sanctum)
Route::post('/v1/login', [AuthController::class, 'apiLogin']);

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    // Fitur Toko / Mitra (Digunakan pekerja lapang untuk memilih toko di App Mobile)
    Route::get('/stores', function () {
        return response()->json([
            'success' => true,
            'data' => StoresModel::select('id', 'store_name', 'owner_name', 'address')->orderBy('store_name', 'asc')->get()
        ]);
    });
    // Fitur Retur
    Route::prefix('returns')->group(function () {
        // [GET] Endpoint untuk Pekerja Lapang: Melihat riwayat retur yang diajukan
        Route::get('/history-lapangan', [ReturnController::class, 'historyLapangan']);

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