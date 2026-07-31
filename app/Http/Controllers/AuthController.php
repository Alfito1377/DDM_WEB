<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use App\Models\User;

class AuthController extends Controller
{

    /**
     * Proses data dari qr checkpoint
     */
    public function qrLoginCheckpoint(Request $request) {
        $token = $request->query('token');
        $store = null;
        $shipments = collect();
        
        if ($token) {
            $store = DB::table('stores')->where('qr_token_checkpoint', $token)->first();
            if ($store) {
                $shipments = DB::table('logistic')
                    ->leftJoin('vehicle', 'logistic.vehicleId', '=', 'vehicle.id_vehicle')
                    ->leftJoin('driver', 'logistic.driverId', '=', 'driver.id_driver')
                    ->where('logistic.id_mitra', $store->id)
                    ->where('logistic.status', 'in_transit')
                    ->select('logistic.*', 'vehicle.plateNo', 'vehicle.vehicleType', 'driver.name as driverName')
                    ->get();
            }
        }

        return view('users.checkpoint.index', compact('store', 'token', 'shipments'));
    }

    /**
     * Memproses data submit checkpoint form via AJAX
     */
    public function storeCheckpoint(Request $request) {
        $request->validate([
            'token' => 'required|string',
            'nama_pengirim' => 'required|string|max:255',
            'nomor_handphone' => 'required|string|max:20',
            'catatan' => 'nullable|string|max:255',
            'selected_shipments' => 'required|string',
        ]);

        $selectedShipments = json_decode($request->selected_shipments, true);
        if (!is_array($selectedShipments) || empty($selectedShipments)) {
            return response()->json(['status' => 'error', 'message' => 'Silakan pilih minimal 1 paket untuk dikonfirmasi.'], 422);
        }

        $store = DB::table('stores')->where('qr_token_checkpoint', $request->token)->first();
        
        if (!$store) {
            return response()->json(['status' => 'error', 'message' => 'Token tidak valid.'], 404);
        }

        // Cari driver berdasarkan nomor HP (dan cocokkan namanya secara longgar)
        $driver = DB::table('driver')
            ->where('phone', $request->nomor_handphone)
            ->first();

        if (!$driver) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Data kurir dengan nomor HP tersebut tidak terdaftar di sistem.'
            ], 404);
        }

        // Hanya perbarui paket yang statusnya 'in_transit' DI toko ini DAN dibawa oleh KURIR INI
        $updated = DB::table('logistic')
            ->where('id_mitra', $store->id)
            ->where('status', 'in_transit')
            ->where('driverId', $driver->id_driver)
            ->whereIn('id_logistic', $selectedShipments)
            ->update([
                'status' => 'out_of_transit', // Driver sudah sampai, tapi toko belum bongkar muat
                'arrivedAt' => now(),
                'updated_at' => now(),
            ]);

        if ($updated === 0) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Tidak ada paket aktif yang ditugaskan kepada Anda (' . $driver->name . ') untuk toko ini.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Kedatangan Anda telah dicatat di sistem.',
            'updated_count' => $updated
        ]);
    }
    /**
     * Memproses data dari form login
     */
    public function qrLogin(Request $request, $token = null)
    {
        // Mendapatkan token baik dari URL parameter maupun query string (?token=...)
        $token = $token ?? $request->token ?? $request->query('token');

        // 1. Cari toko berdasarkan qr_token_login di tabel stores
        $store = DB::table('stores')->where('qr_token_login', $token)->first();

        if (!$store) {
            abort(404, 'Token QR tidak valid atau toko tidak ditemukan.');
        }

        // 2. Pastikan role 'toko' ada di tabel roles
        $tokoRole = DB::table('roles')->where('role_name', 'toko')->first();
        if (!$tokoRole) {
            $tokoRoleId = DB::table('roles')->insertGetId([
                'role_name' => 'toko',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $tokoRoleId = $tokoRole->id;
        }
        
        // 3. Cari user yang terhubung dengan toko tersebut
        $user = User::where('store_id', $store->id)
                    ->where('role_id', $tokoRoleId)
                    ->first();

        if (!$user) {
            // Buat user otomatis untuk toko ini jika belum ada
            $user = new User();
            $user->name = $store->store_name;
            $user->email = 'toko_' . $store->id . '_' . \Illuminate\Support\Str::random(5) . '@toko.com';
            $user->password = \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16));
            $user->role_id = $tokoRoleId;
            $user->store_id = $store->id;
            $user->save();
        }

        // 4. Eksekusi login otomatis ke dalam sistem
        Auth::login($user);

        // 5. Perbarui session keamanan
        session()->regenerate();

        // 6. Alihkan langsung ke halaman dashboard toko
        return redirect()->intended('/toko');
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
            } elseif ($roleName === 'toko') {
                return redirect()->intended('/toko/penerimaan');
            }

            // Fallback jika role tidak dikenali
            Auth::logout();
            return redirect('/')->withErrors(['email' => 'Role tidak valid atau tidak memiliki akses.']);
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