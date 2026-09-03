<?php

namespace App\Http\Controllers;

use App\Models\DriversModel;
use App\Models\JenisMitraModel;
use App\Models\LogisticModel;
use App\Models\StoresModel;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


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
        if ($date == null) {
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
        if ($status == null) {
            return "-";
        }

        if ($status == 'pending') {
            return [
                'label' => 'Pending',
                'color' => 'bg-yellow-100 text-yellow-800'
            ];
        } else if ($status == 'packed') {
            return [
                'label' => 'Packed',
                'color' => 'bg-blue-100 text-blue-800'
            ];
        } else if ($status == 'out_of_transit') {
            return [
                'label' => 'Out of Transit',
                'color' => 'bg-purple-100 text-purple-800'
            ];
        } else if ($status == 'in_transit') {
            return [
                'label' => 'In Transit',
                'color' => 'bg-yellow-100 text-yellow-800'
            ];
        } else if ($status == 'completed') {
            return [
                'label' => 'Completed',
                'color' => 'bg-green-100 text-green-800'
            ];
        } else if ($status == 'cancelled') {
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
    public function daftarCustomer(Request $request)
    {
        // 1. Tangkap kata kunci pencarian dari URL
        $search = $request->input('search');

        // 2. Buat query pencarian dan pagination
        $query = StoresModel::query();

        if ($search) {
            $query->where('store_name', 'like', "%{$search}%")
                ->orWhere('owner_name', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%");
        }

        // Ambil data dengan pagination (contoh: 10 data per halaman)
        // withQueryString() agar parameter ?search=... tidak hilang saat pindah halaman
        $stores = $query->latest()->paginate(10)->withQueryString();

        // 3. Ambil semua jenis mitra untuk dropdown di modal
        $jenisMitraList = JenisMitraModel::all();

        // 4. Optimasi penamaan jenis_mitra (Menghindari N+1 Query / Query berulang)
        // Kita ubah list jenis mitra menjadi array dengan key 'id' agar pencarian lebih cepat
        $jenisMitraMap = $jenisMitraList->keyBy('id');

        foreach ($stores as $store) {
            // Cek apakah ID jenis mitra ada di mapping, jika ada ambil namanya, jika tidak tampilkan '-'
            $store->jenis_mitra = isset($jenisMitraMap[$store->jenis_mitra_id])
                ? $jenisMitraMap[$store->jenis_mitra_id]->nama_jenis_mitra
                : '-';
        }

        // 5. Kirim data ke view
        return view('admin.daftar_customer.index', compact('stores', 'jenisMitraList', 'search'));
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
     * Download Template Excel
     */
    public function downloadTemplate()
    {
        // Path menuju file excel di dalam folder public/templates
        $filePath = public_path('templates/template_excel_input_mitra.xlsx');

        // Cek apakah file benar-benar ada untuk mencegah error
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File template tidak ditemukan.');
        }

        // Return response download
        return response()->download($filePath, 'template_excel_input_mitra.xlsx');
    }

    /**
     * Import data mitra dari excel
     */
    public function importExcel(Request $request)
    {
        // 1. Validasi file Excel
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240', // Maksimal 10MB
        ]);

        $file = $request->file('file');

        try {
            // 2. Kirim file ke API Python menggunakan HTTP Facade
            // Kita menggunakan fopen() agar memori server PHP tidak terbebani jika file Excelnya besar
            $response = Http::attach(
                'file', // Nama field yang diharapkan oleh API Python
                fopen($file->path(), 'r'), // Membuka stream file sementara
                $file->getClientOriginalName() // Mengirimkan nama file aslinya
            )->post(env('PYTHON_API', 'http://analisis_ddm_api:8000') . '/upload-excel'); // URL API Python Anda

            // 3. Cek apakah response dari Python berhasil
            if ($response->successful()) {

                // Ambil data JSON balasan dari Python
                $dataDariPython = $response->json();

                // Lakukan sesuatu dengan data tersebut (misal: simpan ke database)
                // ... logic penyimpanan ke database MySQL via Eloquent ...
                // Menggunakan transaksi DB agar aman
                DB::beginTransaction();
                // dd($dataDariPython);
                try {
                    // dd($dataDariPython['data']);
                    foreach ($dataDariPython['data'] as $data) {
                        // dd($data);
                        // Buat Token QR Unik (40 Karakter Acak)
                        $token_login = Str::random(40);
                        $token_checkpoint = Str::random(40);

                        // Simpan data toko ke database (Sudah ditambahkan kolom baru)
                        $storeId = DB::table('stores')->insertGetId([
                            'store_name' => $data['STORE_NAME'],
                            'owner_name' => $data['OWNER_NAME'],
                            'phone_number' => $data['PHONE_NUMBER'],
                            'address' => $data['ADDRESS'],
                            'jenis_mitra_id' => $data['JENIS_MITRA_ID'],
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
                    }

                    // return response()->json([
                    //     'success' => true,
                    //     'qr_image_login' => $qrImageUrl,
                    //     'qr_checkpoint_image' => $qrCheckpointUrl,
                    //     'login_url' => $loginUrl,
                    // ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal mendaftarkan toko: ' . $e->getMessage()
                    ], 500);
                }

                return redirect()->back()->with('success', 'File Excel berhasil diproses oleh Python!');
            } else {
                // Jika Python mengembalikan error (misal: format isi excel salah)
                return redirect()->back()->with('error', 'Gagal memproses di Python: ' . $response->body());
            }
        } catch (\Exception $e) {
            // Tangkap error jika API Python mati atau tidak bisa dihubungi
            Log::error('Error koneksi ke API Python: ' . $e->getMessage());
            return redirect()->back()->with('error', 'API Python tidak dapat dihubungi.');
        }
    }

    /**
     * Menampilkan Halaman Daftar Petugas Lapang
     */
    public function daftarPetugasLapang(Request $request)
    {
        $search = $request->input('search');

        // Filter menggunakan exact role_name dari seeder ('pekerja_lapang')
        $query = \App\Models\User::with('store')->whereHas('role', function ($q) {
            $q->where('role_name', 'pekerja_lapang');
        });

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $petugasLapang = $query->latest()->paginate(10)->withQueryString();

        return view('admin.daftar_petugas_lapang.index', compact('petugasLapang', 'search'));
    }

    /**
     * Memproses Form Tambah Petugas Lapang Baru
     */
    public function storePetugasLapang(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        try {
            // 2. Ambil ID Role dan ID Store otomatis berdasarkan seeder
            $pekerjaLapangRoleId = \App\Models\Role::where('role_name', 'pekerja_lapang')->value('id');
            $virtualStoreId = \App\Models\StoresModel::where('store_name', 'Operasional Lapangan')->value('id');

            // Cek jika role atau store virtual belum ada
            if (!$pekerjaLapangRoleId || !$virtualStoreId) {
                return back()->with('error', 'Data Role atau Gudang Virtual belum disetup di database.');
            }

            // 3. Simpan ke database
            \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                'role_id' => $pekerjaLapangRoleId,
                'store_id' => $virtualStoreId,
            ]);

            return back()->with('success', 'Petugas Lapang berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan petugas: ' . $e->getMessage());
        }
    }

    /**
     * Memproses Form Edit Petugas Lapang
     */
    public function updatePetugasLapang(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Pengecualian email agar tidak error jika tidak diubah
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8', // Password opsional saat edit
        ]);

        try {
            $user = \App\Models\User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;

            // Hanya update password jika form password diisi
            if ($request->filled('password')) {
                $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
            }

            // Jika checkbox reset_device_id dicentang, kosongkan device_id
            if ($request->has('reset_device_id')) {
                $user->device_id = null;
            }

            $user->save();

            return back()->with('success', 'Data Petugas Lapang berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui petugas: ' . $e->getMessage());
        }
    }

    /**
     * Memproses Hapus Petugas Lapang
     */
    public function destroyPetugasLapang($id)
    {
        try {
            $user = \App\Models\User::findOrFail($id);
            $user->delete();

            return back()->with('success', 'Petugas Lapang berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus petugas: ' . $e->getMessage());
        }
    }

    public function daftarPengiriman(Request $request)
    {
        $process_status = [
            'fetch_data' => [
                'status' => false,
                'msg' => ''
            ],
            'filter' => [
                'status' => false,
                'msg' => ''
            ],
        ];
        $query = LogisticModel::latest();

        if($request->fetch_data == 'true') {
            $wms_data = Http::withToken(env('WMS_API_TOKEN'))->get(env('WMS_API_URL') . 'internal/logistics/active');
            if($wms_data->successful()) {
                $data_fetching = $wms_data->json();
                DB::beginTransaction();
                try {
                    foreach($data_fetching['items'] as $data) {
                        // Pertama tuh Validate Store
                        $validate_store = StoresModel::where('store_name', $data['customer']['name'])->doesntExist();
                        if($validate_store) {
                            $token_login = Str::random(40);
                            $token_checkpoint = Str::random(40);
                            $validate_store = StoresModel::create([
                                'jenis_mitra_id' => 2,
                                'store_name' => $data['customer']['name'],
                                'owner_name' => $data['customer']['name'],
                                'phone_number' => '-',
                                'address' => '-',
                                'latitude' => null,
                                'longitude' => null,
                                'qr_token_login' => $token_login,
                                'qr_token_checkpoint' => $token_checkpoint
                            ]);
                        } else {
                            $validate_store = StoresModel::where('store_name', $data['customer']['name'])->latest()->first();
                        }
                        // Kedua Validate Driver
                        $validate_driver = DriversModel::where('id_driver', $data['driver']['id'])->doesntExist();
                        if($validate_driver) {
                            $validate_driver = DriversModel::create([
                                'id_driver' => $data['driver']['id'],
                                'name' => $data['driver']['name'],
                                'phone' => $data['driver']['phone'],
                                'status' => $data['driver']['status'],
                                'notes' => '-',
                            ]);
                        } else {
                            $validate_driver = DriversModel::where('id_driver', $data['driver']['id'])->latest()->first();
                        }
                        // Ketiga Validate Vehicle
                        $validate_vehicle = VehicleModel::where('id_vehicle', $data['vehicle']['id'])->doesntExist();
                        if($validate_vehicle) {
                            $validate_vehicle = VehicleModel::create([
                                'id_vehicle' => $data['vehicle']['id'],
                                'plateNo' => $data['vehicle']['plateNo'],
                                'vehicleType' => $data['vehicle']['vehicleType'],
                            ]);
                        } else {
                            $validate_vehicle = VehicleModel::where('id_vehicle', $data['vehicle']['id'])->latest()->first();
                        }
                        // Terakhir Insert Logistic
                        $validate_logistic = LogisticModel::where('id_logistic', $data['id'])->doesntExist();
                        if($validate_logistic) {
                            $ins_logistic = LogisticModel::create([
                                'id_logistic' => $data['id'],
                                'shipmentId' => $data['shipmentId'],
                                'status' => $data['status'],
                                'id_mitra' => $validate_store->id,
                                'destination' => '-',
                                'driverId' => $validate_driver->id_driver,
                                'vehicleId' => $validate_vehicle->id_vehicle,
                                'departedAt' => Carbon::parse($data['departedAt'])->toDateTimeString(),
                            ]);
                        }
                    }
                    DB::commit();
                    $process_status['fetch_data']['status'] = true;
                    $process_status['fetch_data']['msg'] = 'Sukses memasukkan data baru dari WMS, total ' . $data_fetching['total'] . '.';
                } catch (\Exception $e) {
                    DB::rollBack();
                    // $process_status['fetch_data']['msg'] = ''. $e->getMessage();
                    $process_status['fetch_data']['status'] = true;
                    $process_status['fetch_data']['msg'] = 'Terjadi Kesalahan saat memperbarui data dari WMS!';
                }
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
            $process_status['filter']['status'] = true;
            $process_status['filter']['msg'] = 'Filter diterapkan';
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $matchedDriverIds = DriversModel::where('name', 'like', "%{$search}%")
                ->pluck('id_driver');

            $matchedVehicleIds = VehicleModel::where('plateNo', 'like', "%{$search}%")
                ->orWhere('vehicleType', 'like', "%{$search}%")
                ->pluck('id_vehicle');

            $query->where(function ($q) use ($search, $matchedDriverIds, $matchedVehicleIds) {
                $q->where('destination', 'like', "%{$search}%")
                    ->orWhereIn('driverId', $matchedDriverIds)
                    ->orWhereIn('vehicleId', $matchedVehicleIds);
            });
            $process_status['filter']['status'] = true;
            $process_status['filter']['msg'] = 'Filter diterapkan';
        }

        $logistics = $query->paginate(20);

        foreach ($logistics as $logistic) {
            $logistic->departedAt = $this->formatDate($logistic->departedAt);
            $logistic->status = $this->formatStatus($logistic->status);

            if ($logistic->id_mitra) {
                $logistic->mitra = StoresModel::find($logistic->id_mitra);
            } else {
                $logistic->mitra = null;
            }

            if ($logistic->driverId) {
                $logistic->driver = DriversModel::where('id_driver', $logistic->driverId)->first();
            } else {
                $logistic->driver = null;
            }

            if ($logistic->vehicleId) {
                $logistic->vehicle = VehicleModel::where('id_vehicle', $logistic->vehicleId)->first();
            } else {
                $logistic->vehicle = null;
            }
        }

        // 6. Return data ke view
        return view('admin.pengiriman.index', compact('logistics', 'process_status'));
    }

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
        if ($logistics->driverId) {
            $driver = DriversModel::where('id_driver', $logistics->driverId)->first();
            $logistics->driver = $driver;
        } else {
            $logistics->driver = null;
        }
        if ($logistics->vehicleId) {
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
        $toko = \App\Models\StoresModel::find($id);

        if (!$toko) {
            abort(404, 'Data Mitra tidak ditemukan');
        }

        $type = $request->query('type', 'login');

        if ($type === 'checkpoint') {
            $url = urlencode(url('/login/qr/checkpoint?token=' . $toko->qr_token_checkpoint));
            $title = "QR CHECKPOINT KURIR";
            $theme = "amber";
        } else {
            $url = urlencode(url('/login/qr?token=' . $toko->qr_token_login));
            $title = "QR AKSES LOGIN TOKO";
            $theme = "green";
        }

        // Gunakan kode warna Hitam (000000)
        $qrColor = "000000";
        $qrImage = "https://api.qrserver.com/v1/create-qr-code/?size=500x500&color=" . $qrColor . "&data=" . $url;
        return view('admin.daftar_customer.print-qr', compact('toko', 'qrImage', 'title', 'type', 'theme'));
    }
    public function ajukanReset(Request $request)
    {
        $toko = \App\Models\StoresModel::find(Auth::user()->store_id);

        if ($toko) {
            // CEK ATURAN COOLDOWN 30 HARI
            if ($toko->last_location_set_at) {
                $batasHari = 30; // Anda bisa mengubah angka ini sesuai kebutuhan
                $tanggalBisaReset = \Carbon\Carbon::parse($toko->last_location_set_at)->addDays($batasHari);

                if (now()->lessThan($tanggalBisaReset)) {
                    $sisaHari = now()->diffInDays($tanggalBisaReset) ?: 1; // Jika < 1 hari, tampilkan 1
                    return response()->json([
                        'success' => false,
                        'message' => "Titik lokasi baru saja diatur. Anda harus menunggu {$sisaHari} hari lagi untuk bisa mengajukan reset lokasi."
                    ]);
                }
            }

            $toko->request_reset_lokasi = true;
            $toko->save();
            return response()->json(['success' => true, 'message' => 'Pengajuan reset berhasil dikirim ke Admin.']);
        }

        return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan.']);
    }
    public function daftarResetLokasi()
    {
        // Ambil toko yang sedang mengajukan reset
        $stores = \App\Models\StoresModel::where('request_reset_lokasi', true)->get();
        return view('admin.reset_lokasi.index', compact('stores'));
    }

    public function setujuiReset($id)
    {
        $toko = \App\Models\StoresModel::findOrFail($id);

        // Kosongkan lokasi dan matikan status request
        $toko->latitude = null;
        $toko->longitude = null;
        $toko->request_reset_lokasi = false;
        $toko->save();

        return back()->with('success', 'Reset lokasi disetujui. Toko sekarang harus mengatur ulang lokasinya.');
    }
}
