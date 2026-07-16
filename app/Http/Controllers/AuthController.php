<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Memproses data dari form login
     */
    public function qrLogin($token)
    {
        // 1. Cari toko berdasarkan qr_token di tabel stores
        $store = DB::table('stores')->where('qr_token', $token)->first();

        if (!$store) {
            abort(404, 'Token QR tidak valid atau toko tidak ditemukan.');
        }

        // 2. Cari user yang terhubung dengan toko tersebut dan memiliki role 'Toko'
        // (Asumsi: di tabel users Anda memiliki kolom store_id untuk menghubungkan ke toko)
        $tokoRoleId = DB::table('roles')->where('role_name', 'Toko')->value('id');
        
        $user = User::where('store_id', $store->id)
                    ->where('role_id', $tokoRoleId)
                    ->first();

        if (!$user) {
            return redirect('/')->withErrors([
                'email' => 'Akun pengguna untuk toko ini belum terdaftar.',
            ]);
        }

        // 3. Eksekusi login otomatis ke dalam sistem
        Auth::login($user);

        // 4. Perbarui session keamanan
        session()->regenerate();

        // 5. Alihkan langsung ke halaman form retur toko
        return redirect()->intended('/toko/retur');
    }
    public function login(Request $request)
    {
        // 1. Validasi input form
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Cek kecocokan email dan password di database
        if (Auth::attempt($credentials)) {
            // Jika berhasil, perbarui session keamanan
            $request->session()->regenerate();

            // 3. Ambil data role pengguna
            $roleName = strtolower(Auth::user()->role->role_name ?? '');

            // 4. Arahkan (redirect) ke halaman sesuai role masing-masing
            if ($roleName === 'superadmin') {
                return redirect()->intended('/superadmin/register-toko');
            } elseif ($roleName === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            // Fallback jika role tidak dikenali
            return redirect('/');
        }

        // Jika gagal (email/password salah), kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Memproses logout pengguna
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}