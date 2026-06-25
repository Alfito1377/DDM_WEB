<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\DashboardController;

// 1. LOGIN & AUTH
Route::get('/', function () { return view('auth.login'); })->name('login');
Route::get('/login/qr/{token}', [AuthController::class, 'qrLogin'])->name('login.qr');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 2. MIDDLEWARE AUTH (Semua yang login bisa akses ini)
Route::middleware(['auth'])->group(function () {
    // Fitur Shared: Knowledge Base (Upload Data) & Chatbot
    Route::get('/unggah-data', [KnowledgeBaseController::class, 'index']);
    Route::post('/unggah-data', [KnowledgeBaseController::class, 'store']);
    Route::get('/chatbot', function () { return view('shared.chatbot'); });
});

// 3. AKSES KHUSUS ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/daftar-toko', [AdminController::class, 'daftarToko']);
    Route::post('/register-toko', [AdminController::class, 'storeToko']);
    Route::get('/produk', [AdminController::class, 'daftarProduk']);
    Route::post('/produk', [AdminController::class, 'storeProduk']);
});

// 4. AKSES KHUSUS MANAJER
Route::middleware(['auth', 'role:manajer'])->prefix('manajer')->group(function () {
    Route::get('/dashboard', function () { return view('manajer.dashboard'); });
    Route::get('/dashboard', [DashboardController::class, 'indexManager']);
    Route::get('/retur', [ReturnController::class, 'indexManager']);
    Route::post('/retur/{id}/approve', [ReturnController::class, 'approve']);
});

// 5. AKSES KHUSUS TOKO
Route::middleware(['auth', 'role:toko'])->prefix('toko')->group(function () {
    Route::get('/retur', function () { return view('toko.retur-form'); });
    Route::get('/riwayat', [ReturnController::class, 'indexToko']);
});