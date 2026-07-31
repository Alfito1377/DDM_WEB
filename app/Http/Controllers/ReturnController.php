<?php

namespace App\Http\Controllers;

use App\Mail\ReturnApprovalMail;
use App\Models\AllocationDetail;
use App\Models\ReturnDetail;
use App\Models\ReturnOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\SageApiService;

class ReturnController extends Controller
{
    protected $sageApi;

    // 2. Masukkan service melalui Constructor (Dependency Injection)
    public function __construct(SageApiService $sageApi)
    {
        $this->sageApi = $sageApi;
    }

    /**
     * Menampilkan form retur
     */
   public function create()
    {
        return view('toko.retur-form');
    }
    /**
     * TAHAP A: Toko Mengajukan Retur (Mendukung Banyak Foto)
     */
    public function store(Request $request)
    {
        // 1. Validasi Diubah untuk mendukung Array Gambar
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'barcode' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
            'proof_images' => 'required|array|min:1',
            'proof_images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);

        try {

            // 2. LOGIKA UPLOAD BANYAK GAMBAR
            $imagePaths = [];
            if ($request->hasFile('proof_images')) {
                foreach ($request->file('proof_images') as $file) {
                    // Simpan file ke folder bukti_retur dan kumpulkan nama jalurnya (path)
                    $imagePaths[] = $file->store('bukti_retur', 'public');
                }
            }
            
            $returnCode = 'RET-' . strtoupper(Str::random(8));
            
            // 3. SIMPAN KE TABEL RETUR (DIPISAH)
            $returnId = DB::table('returns')->insertGetId([
                'return_code' => $returnCode,
                'store_id' => $request->store_id,
                'reason' => $request->reason, 
                'notes' => $request->notes,   
                'proof_image' => json_encode($imagePaths),
                'status' => 'Pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('return_details')->insert([
                'return_id' => $returnId,
                'barcode' => $request->barcode,
                'quantity' => $request->quantity,
                'proof_image_url' => json_encode($imagePaths),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 4. LOGIKA PENGIRIMAN NOTIFIKASI EMAIL KE MANAJER
            try {
                // Ambil nama toko untuk ditampilkan di email
                $store = DB::table('stores')->where('id', $request->store_id)->first();
                $returObj = DB::table('returns')->where('id', $returnId)->first();
                // Gabungkan details
                $returObj->barcode = $request->barcode;
                $returObj->quantity = $request->quantity;
                
                $storeName = $store->store_name ?? 'Mitra Toko';

                // Cari semua pengguna yang memiliki hak akses (role) sebagai 'superadmin' atau 'admin'
                $managers = User::whereHas('role', function($q) {
                    $q->whereIn('role_name', ['superadmin', 'admin', 'manajer']);
                })->get();

                // Kirim email ke masing-masing manajer
                foreach ($managers as $manager) {
                    Mail::to($manager->email)->send(new ReturnApprovalMail($returObj, $storeName));
                }
            } catch (\Exception $emailError) {
                // Jika gagal kirim email, diamkan saja agar retur tetap berhasil tersimpan
                Log::error('Gagal mengirim email notifikasi retur: ' . $emailError->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan retur dengan banyak foto berhasil dikirim!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan retur: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * TAHAP B: Manajer Memproses Retur
     */
    public function approve(Request $request, $id)
    {
        // Validasi input status keputusan
        $request->validate([
            'manager_id' => 'required|exists:users,id',
            'status' => 'required|in:Approved,Rejected'
        ]);

        $returnOrder = ReturnOrder::with('details')->findOrFail($id);

        if ($returnOrder->status !== 'Pending') {
            return response()->json(['message' => 'Retur ini sudah diproses sebelumnya.'], 400);
        }

        try {
            DB::beginTransaction();

            // 1. Update status di tabel returns
            $returnOrder->update([
                'status' => $request->status,
                'manager_id' => $request->manager_id,
            ]);

            // 2. Jika disetujui (Approved), kalkulasi ulang stok di tabel alokasi
            if ($request->status === 'Approved') {
                foreach ($returnOrder->details as $detail) {
                    // Cari data alokasi yang relevan untuk toko dan produk ini
                    $allocationDetail = AllocationDetail::whereHas('allocation', function ($q) use ($returnOrder) {
                        $q->where('store_id', $returnOrder->store_id);
                    })
                    ->where('barcode', $detail->barcode)
                    ->first();

                    if ($allocationDetail) {
                        // Tambahkan jumlah barang rusak ke kolom quantity_returned
                        $allocationDetail->increment('quantity_returned', $detail->quantity);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Keputusan retur berhasil disimpan.',
                'data' => $returnOrder
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal mengupdate keputusan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * TAHAP B (Email): Manajer Memproses Retur via Tautan Email (1-Click Approval)
     */
    public function emailApprove(Request $request, $id)
    {
        $status = $request->query('status'); // 'Approved' atau 'Rejected'
        
        if (!in_array($status, ['Approved', 'Rejected'])) {
            return view('toko.email-approval-result', [
                'status' => 'error',
                'message' => 'Status persetujuan tidak valid.'
            ]);
        }

        $returnOrder = ReturnOrder::with('details')->find($id);

        if (!$returnOrder) {
            return view('toko.email-approval-result', [
                'status' => 'error',
                'message' => 'Data retur tidak ditemukan.'
            ]);
        }

        if ($returnOrder->status !== 'Pending') {
            return view('toko.email-approval-result', [
                'status' => 'error',
                'retur' => $returnOrder,
                'message' => 'Retur ini sudah diproses sebelumnya (Status saat ini: ' . $returnOrder->status . ').'
            ]);
        }

        try {
            DB::beginTransaction();

            // 1. Update status di tabel returns
            // Karena ini via email, kita bisa menganggap user manager pertama/superadmin sebagai yang memproses, atau null (sistem)
            // Namun karena tabel returns mungkin mewajibkan manager_id, mari kita cari user superadmin pertama
            $manager = User::whereHas('role', function($q) {
                $q->where('role_name', 'superadmin');
            })->first();

            $returnOrder->update([
                'status' => $status,
                'manager_id' => $manager ? $manager->id : null,
            ]);

            // 2. Jika disetujui (Approved), kalkulasi ulang stok di tabel alokasi
            if ($status === 'Approved') {
                foreach ($returnOrder->details as $detail) {
                    $allocationDetail = AllocationDetail::whereHas('allocation', function ($q) use ($returnOrder) {
                        $q->where('store_id', $returnOrder->store_id);
                    })
                    ->where('barcode', $detail->barcode)
                    ->first();

                    if ($allocationDetail) {
                        $allocationDetail->increment('quantity_returned', $detail->quantity);
                    }
                }
            }

            DB::commit();

            return view('toko.email-approval-result', [
                'status' => 'success',
                'retur' => $returnOrder,
                'message' => 'Keputusan Anda ('.($status == 'Approved' ? 'Setuju' : 'Tolak').') telah berhasil disimpan.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return view('toko.email-approval-result', [
                'status' => 'error',
                'retur' => $returnOrder,
                'message' => 'Terjadi kesalahan sistem saat menyimpan keputusan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Menampilkan daftar retur untuk Manajer
     */
    public function indexManager()
    {
        $returns = DB::table('returns')
            ->join('stores', 'returns.store_id', '=', 'stores.id')
            ->join('return_details', 'returns.id', '=', 'return_details.return_id')
            ->select(
                'returns.*',
                'stores.store_name',
                'return_details.barcode',
                'return_details.quantity'
            )
            ->orderBy('returns.created_at', 'desc')
            ->get();

        return view('manajer.retur-approval', compact('returns'));
    }

    /**
     * Tampilkan Halaman Riwayat + Mini Dashboard Statistik untuk Toko
     */
    public function indexToko()
    {
        $storeId = Auth::user()->store_id;

        // Ambil data retur milik toko ini
        $returns = DB::table('returns')
            ->join('return_details', 'returns.id', '=', 'return_details.return_id')
            ->select('returns.*', 'return_details.barcode', 'return_details.quantity')
            ->where('returns.store_id', $storeId)
            ->orderBy('returns.created_at', 'desc')
            ->get();

        // Hitung statistik mini dashboard
        $stats = [
            'total' => DB::table('returns')->where('store_id', $storeId)->count(),
            'pending' => DB::table('returns')->where('store_id', $storeId)->where('status', 'Pending')->count(),
            'approved' => DB::table('returns')->where('store_id', $storeId)->where('status', 'Approved')->count(),
        ];

        return view('toko.riwayat', compact('returns', 'stats'));
    }

    /**
     * TAHAP C: Toko Mencetak Surat Jalan Bukti Retur
     */
    public function printSuratJalan($id)
    {
        // Pastikan hanya toko pemilik retur yang bisa mencetak
        $storeId = Auth::user()->store_id;

        $retur = DB::table('returns')
            ->join('stores', 'returns.store_id', '=', 'stores.id')
            ->join('return_details', 'returns.id', '=', 'return_details.return_id')
            ->select(
                'returns.*',
                'stores.store_name',
                'return_details.barcode',
                'return_details.quantity'
            )
            ->where('returns.id', $id)
            ->where('returns.store_id', $storeId) // Proteksi keamanan
            ->first();

        if (!$retur) {
            abort(404, 'Data retur tidak ditemukan atau Anda tidak memiliki hak akses.');
        }

        return view('toko.cetak-surat-jalan', compact('retur'));
    }

    public function cancel($id)
    {
        $storeId = Auth::user()->store_id;

        $retur = DB::table('returns')
            ->where('id', $id)
            ->where('store_id', $storeId)
            ->first();

        if (!$retur) {
            return response()->json(['success' => false, 'message' => 'Data retur tidak ditemukan.'], 404);
        }

        if ($retur->status !== 'Pending') {
            return response()->json(['success' => false, 'message' => 'Hanya pengajuan berstatus Pending yang dapat dibatalkan.'], 400);
        }

        try {
            DB::beginTransaction();

            DB::table('return_details')->where('return_id', $id)->delete();
            DB::table('returns')->where('id', $id)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan retur berhasil dibatalkan dan dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal membatalkan retur: ' . $e->getMessage()], 500);
        }
    }

    public function updatePasswordToko(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        // Cek apakah password lama cocok
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama Anda salah.']);
        }

        DB::table('users')->where('id', $user->id)->update([
            'password' => Hash::make($request->new_password),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Password akun berhasil diperbarui!');
    }

    public function profilToko()
    {
        $user = Auth::user();
        // Ambil detail data toko mitra dari database
        $store = DB::table('stores')->where('id', $user->store_id)->first();

        return view('toko.profil', compact('user', 'store'));
    }

    /**
     * Halaman Penerimaan Barang (Daftar Pengiriman)
     */
    public function penerimaan()
    {
        $storeId = Auth::user()->store_id;

        // Ambil data paket yang sedang dalam perjalanan ke toko ini
        $inTransit = DB::table('logistic')
            ->leftJoin('vehicle', 'logistic.vehicleId', '=', 'vehicle.id_vehicle')
            ->leftJoin('driver', 'logistic.driverId', '=', 'driver.id_driver')
            ->where('logistic.id_mitra', $storeId)
            ->whereIn('logistic.status', ['in_transit', 'out_of_transit'])
            ->select('logistic.*', 'vehicle.plateNo', 'vehicle.vehicleType', 'driver.name as driverName')
            ->orderBy('logistic.departedAt', 'desc')
            ->get();

        foreach ($inTransit as $logistic) {
            $logistic->total_items = DB::table('logistic_scans')->where('logistic_id', $logistic->id)->count();
            $logistic->received_items = DB::table('logistic_scans')->where('logistic_id', $logistic->id)->whereNotNull('received_at')->count();
        }

        return view('toko.penerimaan', compact('inTransit'));
    }

    /**
     * Halaman Mulai Terima (Scanner per Pengiriman)
     */
    public function mulaiTerima($logistic_id)
    {
        $storeId = Auth::user()->store_id;
        
        $logistic = DB::table('logistic')
            ->where('id_mitra', $storeId)
            ->where('id_logistic', $logistic_id)
            ->first();

        if (!$logistic) {
            return redirect('/toko/penerimaan')->with('error', 'Pengiriman tidak ditemukan atau bukan milik toko Anda.');
        }

        $scans = DB::table('logistic_scans')
            ->where('logistic_id', $logistic->id)
            ->orderBy('received_at', 'desc')
            ->get();

        return view('toko.mulai_terima', compact('logistic', 'scans'));
    }

    /**
     * Proses Pemindaian (Scan) Penerimaan Barang
     */
    public function scanPenerimaan(Request $request)
    {
        $request->validate([
            'logistic_id' => 'required|string',
            'barcode' => 'required|string',
        ]);

        $storeId = Auth::user()->store_id;
        $barcode = $request->barcode;
        $logisticIdStr = $request->logistic_id;

        // Validasi pengiriman milik toko ini
        $logistic = DB::table('logistic')
            ->where('id_mitra', $storeId)
            ->where('id_logistic', $logisticIdStr)
            ->first();

        if (!$logistic) {
            return response()->json(['status' => 'error', 'message' => 'Akses ditolak.'], 403);
        }

        // Cari record yang BELUM discan terlebih dahulu
        $scanRecord = DB::table('logistic_scans')
            ->where('logistic_id', $logistic->id)
            ->where('barcode', $barcode)
            ->whereNull('received_at')
            ->first();

        if (!$scanRecord) {
            // Jika tidak ketemu yang belum discan, cek apakah sebenarnya ada tapi sudah discan semua
            $alreadyScanned = DB::table('logistic_scans')
                ->where('logistic_id', $logistic->id)
                ->where('barcode', $barcode)
                ->exists();

            if ($alreadyScanned) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Semua barang dengan barcode ini sudah discan (diterima)!'
                ], 400);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Barcode ini TIDAK ADA di dalam manifes/daftar pengiriman ini!'
            ], 404);
        }

        // Langsung lanjutkan tanpa mengecek produk
        try {
            DB::beginTransaction();

            // 1. Update scan record menjadi diterima
            DB::table('logistic_scans')
                ->where('id', $scanRecord->id)
                ->update([
                    'received_at' => now(),
                    'updated_at' => now()
                ]);

            // 2. Cek apakah toko sudah memiliki record stok untuk produk ini
            $storeStock = DB::table('store_stocks')
                ->where('store_id', $storeId)
                ->where('barcode', $barcode)
                ->lockForUpdate() // Mencegah race condition
                ->first();

            if ($storeStock) {
                DB::table('store_stocks')->where('id', $storeStock->id)->increment('quantity', 1);
            } else {
                DB::table('store_stocks')->insert([
                    'store_id' => $storeId,
                    'barcode' => $barcode,
                    'quantity' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 3. Catat ke log stok
            DB::table('store_stock_logs')->insert([
                'store_id' => $storeId,
                'barcode' => $barcode,
                'type' => 'in',
                'quantity' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 4. Cek apakah semua barang sudah diterima
            $totalItems = DB::table('logistic_scans')->where('logistic_id', $logistic->id)->count();
            $receivedItems = DB::table('logistic_scans')->where('logistic_id', $logistic->id)->whereNotNull('received_at')->count();

            if ($totalItems > 0 && $totalItems == $receivedItems) {
                DB::table('logistic')->where('id', $logistic->id)->update([
                    'status' => 'completed',
                    'arrivedAt' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem saat memproses data: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil! 1x Barcode ' . $barcode . ' diterima.',
            'data' => [
                'barcode' => $barcode,
                'scanned_at' => now()->format('H:i')
            ]
        ]);
    }
}