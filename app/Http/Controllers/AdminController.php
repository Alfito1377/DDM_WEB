<?php

namespace App\Http\Controllers;

use App\Models\DriversModel;
use App\Models\JenisMitraModel;
use App\Models\LogisticModel;
use App\Models\StoresModel;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{

    /**
     * Variabel
     */
    public $bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    /**
     * Format Data Tanggal
     */
    function formatDate($date)
    {
        if($date == null) {
            return "-";
        }
        $date = explode(" ", $date);
        $tanggal = explode("-", $date[0]);
        $jam = explode(":", $date[1]);
        return $tanggal[2] . " " . $this->bulan[(int)$tanggal[1]] . " " . $tanggal[0] . " " . $jam[0] . ":" . $jam[1] . ":" . $jam[2];
    }

    /**
     * Format Data Tanggal
     */
    function formatStatus($status)
    {
        if($status == null) {
            return "-";
        }

        if($status == 'pending') {
            return [
                'label' => 'Pending',
                'color' => 'bg-yellow-100 text-yellow-800'
            ];
        } else if($status == 'packed') {
            return [
                'label' => 'Packed',
                'color' => 'bg-blue-100 text-blue-800'
            ];
        } else if($status == 'out_of_transit') {
            return [
                'label' => 'Out of Transit',
                'color' => 'bg-purple-100 text-purple-800'
            ];
        } else if($status == 'in_transit') {
            return [
                'label' => 'In Transit',
                'color' => 'bg-yellow-100 text-yellow-800'
            ];
        } else if($status == 'completed') {
            return [
                'label' => 'Completed',
                'color' => 'bg-green-100 text-green-800'
            ];
        } else if($status == 'cancelled') {
            return [
                'label' => 'Cancelled',
                'color' => 'bg-red-100 text-red-800'
            ];
        }
        return $status;
    }


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
        // Mengambil semua data pengiriman dari database
        $logistics = LogisticModel::latest()->get();

        foreach ($logistics as $logistic) {
            $logistic->departedAt = $this->formatDate($logistic->departedAt);
            $logistic->status = $this->formatStatus($logistic->status);
            if ($logistic->id_mitra) {
                $mitra = StoresModel::find($logistic->id_mitra);
                $logistic->mitra = $mitra;
            } else {
                $logistic->mitra = null;
            }
            if($logistic->driverId) {
                $driver = DriversModel::where('id_driver', $logistic->driverId)->first();
                $logistic->driver = $driver;
            } else {
                $logistic->driver = null;
            }
            if($logistic->vehicleId) {
                $vehicle = VehicleModel::where('id_vehicle', $logistic->vehicleId)->first();
                $logistic->vehicle = $vehicle;
            } else {
                $logistic->vehicle = null;
            }
        }

        return view('admin.pengiriman.index', compact('logistics'));
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

        $store->update($request->only(['store_name', 'owner_name', 'phone_number', 'address', 'jenis_mitra_id']));

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

    /**
     * 8. Get detail pengiriman by ID
     */
    public function detailPengiriman($id)
    {
        // Mengambil semua data pengiriman dari database
        $logistics = LogisticModel::find($id);

        if (!$logistics) {
            return response()->json([
                'success' => false,
                'message' => 'Pengiriman tidak ditemukan.'
            ], 404);
        }

        $logistics->departedAt = $this->formatDate($logistics->departedAt);
        $logistics->status = $this->formatStatus($logistics->status);
        if ($logistics->id_mitra) {
            $mitra = StoresModel::find($logistics->id_mitra);
            $logistics->mitra = $mitra;
            $logistics->mitra->jenis_mitra = JenisMitraModel::find($mitra->jenis_mitra_id)->nama_jenis_mitra;
        } else {
            $logistics->mitra = null;
        }
        if($logistics->driverId) {
            $driver = DriversModel::where('id_driver', $logistics->driverId)->first();
            $logistics->driver = $driver;
        } else {
            $logistics->driver = null;
        }
        if($logistics->vehicleId) {
            $vehicle = VehicleModel::where('id_vehicle', $logistics->vehicleId)->first();
            $logistics->vehicle = $vehicle;
        } else {
            $logistics->vehicle = null;
        }
        return response()->json([
            'success' => true,
            'data' => $logistics
        ]);
    }
    /**
     * 9. Cetak QR Code Mitra dengan Background
     */
    public function printQr($id, Request $request)
    {
        $toko = StoresModel::find($id);

        if (!$toko) {
            abort(404, 'Data Mitra tidak ditemukan');
        }
        
        $type = $request->query('type', 'login');
        
        if ($type === 'checkpoint') {
            $url = urlencode(url('/login/qr/checkpoint?token=' . $toko->qr_token_checkpoint));
            $title = "QR Code Checkpoint";
        } else {
            $url = urlencode(url('/login/qr?token=' . $toko->qr_token_login));
            $title = "QR Code Login";
        }
        
        $qrImage = "https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=" . $url;

        return view('admin.daftar_customer.print-qr', compact('toko', 'qrImage', 'title'));
    }
}