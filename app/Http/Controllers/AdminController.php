<?php

namespace App\Http\Controllers;

use App\Models\JenisMitraModel;
use App\Models\StoresModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * 1. Menampilkan Halaman Daftar Mitra
     */
    public function daftarCustomer()
    {
        $stores = StoresModel::all();
        $jenisMitraList = JenisMitraModel::all();

        foreach ($stores as $store) {
            $store->jenis_mitra = JenisMitraModel::find($store->jenis_mitra_id)->nama_jenis_mitra;
        }

        // 3. Kirim keduanya ke view daftar-customer
        return view('admin.daftar_customer.index', compact('stores', 'jenisMitraList'));
    }

    /**
     * 2. Memproses Form Tambah Toko Baru
     */
    public function storeCustomer(Request $request)
    {
        // Validasi Input Form (Sudah ditambahkan owner, phone, dan sales_id)
        $request->validate([
            'store_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'jenis_mitra_id' => 'required|integer'
        ]);

        // Menggunakan transaksi DB agar aman
        DB::beginTransaction();
        try {
            // Buat Token QR Unik (40 Karakter Acak)
            $token_login = Str::random(40);
            $token_checkpoint = Str::random(40);

            // Simpan data toko ke database (Sudah ditambahkan kolom baru)
            $storeId = DB::table('stores')->insertGetId([
                'store_name' => $request->store_name,
                'owner_name' => $request->owner_name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'jenis_mitra_id' => $request->jenis_mitra_id,
                'qr_token_login' => $token_login,
                'qr_token_checkpoint' => $token_checkpoint,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            // Susun URL Login dan Gambar QR Code
            $loginUrl = url('/login/qr/' . $token_login);
            $checkpointUrl = url('/login/qr/checkpoint/' . $token_checkpoint);
            $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($loginUrl);
            $qrCheckpointUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($checkpointUrl);

            return response()->json([
                'success' => true,
                'qr_image_login' => $qrImageUrl,
                'qr_checkpoint_image' => $qrCheckpointUrl,
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
     * 3. Menampilkan Halaman Master Data Pengiriman
     */
    public function daftarPengiriman()
    {
        // Mengambil semua data produk dari database
        $products = DB::table('products')->orderBy('created_at', 'desc')->get();

        return view('admin.pengiriman.index', compact('products'));
    }

    /**
     * 4. Memproses Form Tambah Produk Baru
     */
    public function storePengiriman(Request $request)
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
                'base_stock' => 0, 
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

    /**
     * 5. Get data edit customer by ID
     */
    public function editCustomer($id)
    {
        $store = StoresModel::find($id);

        $store->jenis_mitra = JenisMitraModel::find($store->jenis_mitra_id)->nama_jenis_mitra;

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Customer tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $store
        ]);
    }

    /**
     * 6. Update data customer by ID
     */
    public function updateCustomer(Request $request, $id)
    {
        $store = StoresModel::find($id);
        
        $request->validate([
            'store_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'jenis_mitra_id' => 'required|integer'
        ]);

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Customer tidak ditemukan.'
            ], 404);
        }

        $store->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data customer berhasil diperbarui.'
        ]);
    }

    /**
     * 7. Delete data customer by ID
     */
    public function destroyCustomer($id)
    {
        $store = StoresModel::find($id);

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Customer tidak ditemukan.'
            ], 404);
        }

        $store->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data customer berhasil dihapus.'
        ]);
    }
}