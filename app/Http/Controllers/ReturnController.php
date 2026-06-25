<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturnOrder;
use App\Models\ReturnDetail;
use App\Models\AllocationDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    /**
     * TAHAP A: Toko Mengajukan Retur
     */
    /**
     * TAHAP A: Toko Mengajukan Retur (Mendukung Banyak Foto)
     */
    public function store(Request $request)
    {
        // 1. Validasi Diubah untuk mendukung Array Gambar
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'product_id' => 'required|exists:products,barcode', 
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string',
            
            // Validasi bahwa proof_images harus berupa array dan tiap filenya maksimal 5MB
            'proof_images' => 'required|array|min:1',
            'proof_images.*' => 'image|mimes:jpeg,png,jpg|max:5120', 
        ]);

        try {
            $product = DB::table('products')->where('barcode', $request->product_id)->first();

            // 2. LOGIKA UPLOAD BANYAK GAMBAR
            $imagePaths = [];
            if ($request->hasFile('proof_images')) {
                foreach ($request->file('proof_images') as $file) {
                    // Simpan file ke folder bukti_retur dan kumpulkan nama jalurnya (path)
                    $imagePaths[] = $file->store('bukti_retur', 'public');
                }
            }
            $returnCode = 'RET-' . strtoupper(Str::random(8));
            // 3. SIMPAN KE TABEL RETUR
            $returnId = DB::table('returns')->insertGetId([
                'return_code' => $returnCode,
                'store_id' => $request->store_id,
                'reason' => $request->reason,
                
                // Simpan array path gambar ke dalam bentuk teks JSON di database
                'proof_image' => json_encode($imagePaths), 
                
                'status' => 'Pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('return_details')->insert([
                'return_id' => $returnId,
                'product_id' => $product->id, 
                'quantity' => $request->quantity,
                'proof_image_url' => json_encode($imagePaths),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

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
                    $allocationDetail = AllocationDetail::whereHas('allocation', function($q) use ($returnOrder) {
                        $q->where('store_id', $returnOrder->store_id);
                    })
                    ->where('product_id', $detail->product_id)
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
     * Menampilkan daftar retur untuk Manajer
     */
    public function indexManager()
    {
        // Menggabungkan data retur dengan toko, detail retur, dan nama produk
        $returns = DB::table('returns')
            ->join('stores', 'returns.store_id', '=', 'stores.id')
            ->join('return_details', 'returns.id', '=', 'return_details.return_id')
            ->join('products', 'return_details.product_id', '=', 'products.id')
            ->select(
                'returns.*', 
                'stores.store_name',
                'products.product_name',
                'return_details.quantity'
            )
            ->orderBy('returns.created_at', 'desc')
            ->get();
            
        return view('manajer.retur-approval', compact('returns'));
    }
    /**
     * Menampilkan riwayat retur khusus untuk Toko yang sedang login
     */
    public function indexToko()
    {
        // 1. Ambil store_id dari user yang sedang login
        $storeId = Auth::user()->store_id;

        // 2. Ambil data retur beserta detail produknya
        $returns = DB::table('returns')
            ->join('return_details', 'returns.id', '=', 'return_details.return_id')
            ->join('products', 'return_details.product_id', '=', 'products.id')
            ->select(
                'returns.*', 
                'products.product_name',
                'return_details.quantity'
            )
            ->where('returns.store_id', $storeId)
            ->orderBy('returns.created_at', 'desc')
            ->get();
            
        return view('toko.riwayat', compact('returns'));
    }
}