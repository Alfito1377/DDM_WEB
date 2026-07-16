<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\ReturnController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Services\SageApiService;
use App\Http\Controllers\Api\ForecastingController;

// 1. LOGIN & AUTH
Route::get('/', function () { return view('auth.login'); })->name('login');
Route::get('/login/qr/{token}', [AuthController::class, 'qrLogin'])->name('login.qr');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 2. MIDDLEWARE AUTH (Semua yang login bisa akses ini)
Route::middleware(['auth'])->group(function () {
    Route::get('/unggah-data', [KnowledgeBaseController::class, 'index']);
    Route::post('/unggah-data', [KnowledgeBaseController::class, 'store']);
    Route::get('/chatbot', function () { return view('shared.chatbot'); });
});

// 3. AKSES KHUSUS SUPERADMIN
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->group(function () {
    Route::get('/register-toko', function () { return redirect('/superadmin/daftar-toko'); });
    Route::get('/daftar-customer', [AdminController::class, 'daftarCustomer']);
    Route::post('/register-customer', [AdminController::class, 'storeCustomer']);
    Route::get('/edit-customer/{id}', [AdminController::class, 'editCustomer']);
    Route::put('/update-customer/{id}', [AdminController::class, 'updateCustomer']);
    Route::delete('/delete-customer/{id}', [AdminController::class, 'destroyCustomer']);
    Route::get('/pengiriman', [AdminController::class, 'daftarPengiriman']);
    Route::post('/pengiriman', [AdminController::class, 'storePengiriman']);
    Route::get('/retur', [ReturnController::class, 'indexManager']);
    Route::post('/retur/{id}/approve', [ReturnController::class, 'approve']);
});

// 4. AKSES KHUSUS ADMIN / MANAJER
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () { return view('manajer.dashboard'); });
    Route::get('/dashboard', [DashboardController::class, 'indexManager']);
});

// 5. AKSES KHUSUS TOKO
Route::middleware(['auth', 'role:toko'])->prefix('toko')->group(function () {
    Route::get('/', [ReturnController::class, 'indexToko']);
    Route::get('/riwayat', [ReturnController::class, 'indexToko']);
    Route::post('/retur', [ReturnController::class, 'store']);
    Route::get('/retur/{id}/cetak', [ReturnController::class, 'printSuratJalan']);
    Route::delete('/retur/{id}/batal', [ReturnController::class, 'cancel']);
    Route::get('/panduan', [KnowledgeBaseController::class, 'panduanToko']);
    Route::get('/profil', [ReturnController::class, 'profilToko']);
    Route::post('/profil/ganti-password', [ReturnController::class, 'updatePasswordToko']);
});

Route::get('/api/historical-turnover', [ForecastingController::class, 'getHistoricalTurnover']);



/// sementara untuk cek toko
Route::get('/dev-login-toko', function () {
    $user = \App\Models\User::whereHas('role', function($query) {
        $query->where('role_name', 'toko');
    })->first();

    if ($user) {
        Auth::login($user);
        
        return redirect('/toko')->with('success', 'Bypass login berhasil!');
    }

    return 'Gagal bypass: User dengan role "toko" tidak ditemukan di database Anda.';
});

Route::get('/test-login-sage', function (SageApiService $sageApi) {
    $token = $sageApi->getToken();

    if ($token) {
        return response()->json([
            'status' => 'SUKSES',
            'pesan' => 'Koneksi ke API Sage berhasil!',
            'token' => $token
        ]);
    }

    return response()->json([
        'status' => 'GAGAL',
        'pesan' => 'Gagal mendapatkan token. Silakan cek kredensial di file .env atau lihat log error.'
    ], 500);
});

Route::get('/test-sage-api', function (SageApiService $sageApi) {
    $products = $sageApi->getProducts(1, 5);
    return response()->json($products);
});