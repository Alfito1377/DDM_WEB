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
use App\Http\Controllers\ChatController;

// 1. LOGIN & AUTH
Route::get('/', function () {
    if (Auth::check()) {
        $roleName = strtolower(Auth::user()->role->role_name ?? '');
        if ($roleName === 'superadmin') return redirect('/superadmin/dashboard-logistik');
        if ($roleName === 'admin') return redirect('/admin/dashboard');
        if ($roleName === 'toko') return redirect('/toko/penerimaan');
        if ($roleName === 'pekerja_lapang') return redirect('/lapangan/retur');
    }
    return view('auth.login');
})->name('login');
Route::get('/login/qr/', [AuthController::class, 'qrLogin'])->name('login.qr');
Route::get('/login/qr/checkpoint', [AuthController::class, 'qrLoginCheckpoint'])->name('login.qr.checkpoint');
Route::post('/login/qr/checkpoint', [AuthController::class, 'storeCheckpoint'])->name('login.qr.checkpoint.post');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 2. MIDDLEWARE AUTH (Semua yang login bisa akses ini)
Route::middleware(['auth'])->group(function () {});

// 2.5 1-Click Email Approval (Signed URL, no auth required)
Route::get('/retur/email-approve/{id}', [ReturnController::class, 'emailApproveView'])
    ->name('retur.email.approve')
    ->middleware('signed');
Route::post('/retur/email-approve/{id}', [ReturnController::class, 'emailApproveProcess'])
    ->name('retur.email.approve.process')
    ->middleware('signed');
Route::post('/checkpoint/verify', [AdminController::class, 'prosesScanCheckpoint'])->name('checkpoint.verify');
// ...

// 3. AKSES KHUSUS SUPERADMIN & ADMIN
Route::middleware(['auth', 'role:superadmin,admin'])->group(function () {
    Route::get('/superadmin', function () {
        return redirect('/superadmin/dashboard-logistik');
    });
    Route::get('/admin', function () {
        return redirect('/admin/dashboard-logistik');
    });
    Route::get('/superadmin/register-toko', function () {
        return redirect('/superadmin/daftar-customer');
    });

    // Rute proxy backend ke Python Analytics API
    Route::get('/api/analytics/forecast', [DashboardController::class, 'getForecast'])->name('dashboard.analytics.forecast');
    Route::get('/api/analytics/clustering', [DashboardController::class, 'getClustering'])->name('dashboard.analytics.clustering');

    // RUTE PREFIX SUPERADMIN
    Route::prefix('superadmin')->group(function () {
        Route::get('/dashboard-logistik', [DashboardController::class, 'index']);
        Route::get('/dashboard-analitik', [DashboardController::class, 'indexManager']);
        Route::get('/daftar-customer', [AdminController::class, 'daftarCustomer']);
        Route::post('/register-customer', [AdminController::class, 'storeCustomer']);
        Route::post('/register-customer-excel', [AdminController::class, 'importExcel']);
        Route::get('/download-template', [AdminController::class, 'downloadTemplate']);
        Route::get('/edit-customer/{id}', [AdminController::class, 'editCustomer']);
        Route::put('/update-customer/{id}', [AdminController::class, 'updateCustomer']);
        Route::delete('/delete-customer/{id}', [AdminController::class, 'destroyCustomer']);
        Route::get('/pengiriman', [AdminController::class, 'daftarPengiriman']);
        Route::get('/pengiriman/{id}/detail', [AdminController::class, 'detailPengiriman']);
        Route::post('/pengiriman', [AdminController::class, 'storePengiriman']);
        Route::get('/retur', [ReturnController::class, 'indexManager']);
        Route::post('/retur/{id}/approve', [ReturnController::class, 'approve']);
        Route::get('/chatbot', function () {
            return view('shared.chatbot');
        });
        Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('superadmin.chat.send');
        Route::get('/unggah-data', [KnowledgeBaseController::class, 'index']);
        Route::post('/unggah-data', [KnowledgeBaseController::class, 'store']);
        Route::get('/mitra/{id}/print-qr', [AdminController::class, 'printQr'])->name('admin.print-qr');
        Route::get('/daftar-petugas-lapang', [AdminController::class, 'daftarPetugasLapang']);
        Route::post('/daftar-petugas-lapang/store', [AdminController::class, 'storePetugasLapang']);
        Route::put('/daftar-petugas-lapang/{id}', [AdminController::class, 'updatePetugasLapang']);
        Route::delete('/daftar-petugas-lapang/{id}', [AdminController::class, 'destroyPetugasLapang']);
        Route::get('/dashboard', function () {
            return redirect('/superadmin/dashboard-logistik');
        })->name('superadmin.dashboard');
        Route::get('/permintaan-reset-lokasi', [AdminController::class, 'daftarResetLokasi']);
        Route::post('/setujui-reset-lokasi/{id}', [AdminController::class, 'setujuiReset']);
    });

    // RUTE PREFIX ADMIN
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard-logistik', [DashboardController::class, 'index']);
        Route::get('/dashboard-analitik', [DashboardController::class, 'indexManager']);
        Route::get('/daftar-customer', [AdminController::class, 'daftarCustomer']);
        Route::post('/register-customer', [AdminController::class, 'storeCustomer']);
        Route::get('/edit-customer/{id}', [AdminController::class, 'editCustomer']);
        Route::put('/update-customer/{id}', [AdminController::class, 'updateCustomer']);
        Route::delete('/delete-customer/{id}', [AdminController::class, 'destroyCustomer']);
        Route::get('/pengiriman', [AdminController::class, 'daftarPengiriman']);
        Route::get('/pengiriman/{id}/detail', [AdminController::class, 'detailPengiriman']);
        Route::post('/pengiriman', [AdminController::class, 'storePengiriman']);
        Route::get('/retur', [ReturnController::class, 'indexManager']);
        Route::post('/retur/{id}/approve', [ReturnController::class, 'approve']);
        Route::get('/chatbot', function () {
            return view('shared.chatbot');
        });
        Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('admin.chat.send');
        Route::get('/unggah-data', [KnowledgeBaseController::class, 'index']);
        Route::post('/unggah-data', [KnowledgeBaseController::class, 'store']);
        Route::get('/daftar-petugas-lapang', [AdminController::class, 'daftarPetugasLapang']);
        Route::post('/daftar-petugas-lapang/store', [AdminController::class, 'storePetugasLapang']);
        Route::get('/dashboard', function () {
            return redirect('/admin/dashboard-analitik');
        })->name('admin.dashboard');
        Route::get('/permintaan-reset-lokasi', [AdminController::class, 'daftarResetLokasi']);
        Route::post('/setujui-reset-lokasi/{id}', [AdminController::class, 'setujuiReset']);
    });
});

// 5. AKSES KHUSUS TOKO
Route::middleware(['auth', 'role:toko'])->prefix('toko')->group(function () {
    Route::get('/', function () {
        return redirect('/toko/retur');
    });
    Route::get('/', [ReturnController::class, 'indexToko']);
    Route::get('/riwayat', [ReturnController::class, 'indexToko']);

    // Rute Penerimaan Barang
    Route::get('/penerimaan', [ReturnController::class, 'penerimaan']);
    Route::get('/penerimaan/mulai/{logistic_id}', [ReturnController::class, 'mulaiTerima']);
    Route::post('/penerimaan/scan', [ReturnController::class, 'scanPenerimaan']);

    Route::post('/retur', [ReturnController::class, 'store']);
    Route::get('/retur/{id}/cetak', [ReturnController::class, 'printSuratJalan']);
    Route::delete('/retur/{id}/batal', [ReturnController::class, 'cancel']);
    Route::get('/panduan', [KnowledgeBaseController::class, 'panduanToko']);
    Route::post('/update-lokasi-otomatis', [ReturnController::class, 'updateLokasiOtomatis'])->name('toko.update.lokasi');
    Route::post('/toko/ajukan-reset-lokasi', [App\Http\Controllers\AdminController::class, 'ajukanReset'])->name('toko.ajukan.reset');
});

// 6. AKSES KHUSUS PEKERJA LAPANG
Route::middleware(['auth', 'role:pekerja_lapang'])->prefix('lapangan')->group(function () {
    Route::get('/retur', [ReturnController::class, 'createLapangan']);
    Route::post('/retur', [ReturnController::class, 'store']);
});
