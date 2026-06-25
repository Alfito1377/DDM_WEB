<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * 1. Menampilkan Halaman Daftar Mitra Toko
     */
    public function daftarToko()
    {
        // Mengambil semua data toko dari database, diurutkan dari yang terbaru
        $stores = DB::table('stores')->orderBy('created_at', 'desc')->get();
        
        return view('admin.daftar-toko', compact('stores'));
    }

    /**
     * 2. Memproses Form Tambah Toko Baru
     */
    public function storeToko(Request $request)
    {
        // Validasi Input Form
        $request->validate([
            'store_name' => 'required|string|max:255',
            'address' => 'required|string',
            'sales_id' => 'required|integer'
        ]);

        // Menggunakan transaksi DB agar aman
        DB::beginTransaction();
        try {
            // Buat Token QR Unik (40 Karakter Acak)
            $token = Str::random(40);

            // Simpan data toko ke database
            $storeId = DB::table('stores')->insertGetId([
                'store_name' => $request->store_name,
                'address' => $request->address,
                'qr_token' => $token,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Buat Akun (User) untuk toko tersebut agar bisa login
            $tokoRoleId = DB::table('roles')->where('role_name', 'Toko')->value('id');
            $email = 'toko_' . strtolower(Str::random(5)) . '@jualbenih.co.id'; 

            DB::table('users')->insert([
                'role_id' => $tokoRoleId,
                'store_id' => $storeId,
                'name' => 'Admin ' . $request->store_name,
                'email' => $email,
                'password' => Hash::make('12345678'), // Password default
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            // Susun URL Login dan Gambar QR Code
            $loginUrl = url('/login/qr/' . $token);
            $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($loginUrl);

            return response()->json([
                'success' => true,
                'qr_image' => $qrImageUrl,
                'login_url' => $loginUrl,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendaftarkan toko: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 3. Menampilkan Halaman Master Data Produk
     */
    public function daftarProduk()
    {
        // Mengambil semua data produk dari database
        $products = DB::table('products')->orderBy('created_at', 'desc')->get();
        
        return view('admin.daftar-produk', compact('products'));
    }

    /**
     * 4. Memproses Form Tambah Produk Baru
     */
  public function storeProduk(Request $request)
    {
        // Validasi input produk
        $request->validate([
            'product_name' => 'required|string|max:255',
            'barcode' => 'required|string|max:100|unique:products,barcode'
        ]);

        try {
            $productCode = 'PRD-' . strtoupper(Str::random(6));

            DB::table('products')->insert([
                'product_code' => $productCode,
                'product_name' => $request->product_name,
                'barcode' => $request->barcode,
                'base_stock' => 0, // <--- Tambahkan baris ini untuk mengatasi error
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan produk: ' . $e->getMessage()
            ], 500);
        }
    }
}