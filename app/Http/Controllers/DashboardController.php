<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; // Pastikan ini di-import
use Illuminate\Support\Facades\Log;
use App\Models\KnowledgeBase;

class DashboardController extends Controller
{
    public function indexManager()
    {
        // 1. Kartu Statistik Utama
        $stats = [
            'total_retur' => DB::table('returns')->count(),
            'pending' => DB::table('returns')->where('status', 'Pending')->count(),
            'approved' => DB::table('returns')->where('status', 'Approved')->count(),
            'total_dokumen' => KnowledgeBase::count(),
        ];

        // 2. Data Grafik: Alasan Retur (Pie Chart)
        $reasonStats = DB::table('returns')
            ->select('reason', DB::raw('count(*) as total'))
            ->groupBy('reason')
            ->pluck('total', 'reason')
            ->toArray();

        // 3. Data Grafik: Top 5 Toko Terbanyak Retur (Bar Chart)
        $storeStats = DB::table('returns')
            ->join('stores', 'returns.store_id', '=', 'stores.id')
            ->select('stores.store_name', DB::raw('count(returns.id) as total'))
            ->groupBy('stores.id', 'stores.store_name')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'store_name')
            ->toArray();

        // 4. Statistik Kategori Dokumen Knowledge Base
        $docStats = DB::table('knowledge_bases')
            ->select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        // 5. AMBIL FILE CSV TERBARU UNTUK VISUALISASI DINAMIS
        $latestCsv = DB::table('knowledge_bases')
            ->where('file_type', 'csv')
            ->orderBy('created_at', 'desc')
            ->first();

        // =========================================================================
        // PROSES DATA HISTORIS (GUDANG LOKAL)
        // =========================================================================
        $turnovers = DB::table('turnovers')
            ->selectRaw('DATE_FORMAT(doc_date, "%Y-%m-%d") as ds, SUM(total_kg) as y') // Format diubah sesuai standar Prophet
            ->groupBy('ds')
            ->orderBy('ds', 'asc')
            ->get();

        $historicalLabels = [];
        $historicalData = [];
        $payloadToPython = []; // Array paket data yang akan dikirim ke Python

        foreach ($turnovers as $to) {
            $historicalLabels[] = date('M Y', strtotime($to->ds)); 
            $historicalData[] = (float) $to->y;
            
            // Masukkan data ke dalam paket
            $payloadToPython[] = [
                'ds' => $to->ds,
                'y' => (float) $to->y
            ];
        }

        // =========================================================================
        // PROSES DATA PREDIKSI (KONSUMSI API PYTHON PROPHET)
        // =========================================================================
        $forecastLabels = [];
        $forecastData = [];

        try {
            // PERUBAHAN: Menggunakan POST dan mengirimkan data historis secara langsung
            $response = Http::timeout(60)->post('http://127.0.0.1:8001/predict-turnover', [
                'months' => 6,
                'historical_data' => $payloadToPython
            ]);

            if ($response->successful() && $response->json('success')) {
                $predictions = $response->json('data');
                foreach ($predictions as $pred) {
                    $forecastLabels[] = $pred['bulan'];
                    $forecastData[] = (float) $pred['prediksi_kg'];
                }
            } else {
                Log::error('API Python merespons error: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Gagal terhubung ke AI Engine (Python): ' . $e->getMessage());
        }

        // Gabungkan label sumbu X (Bulan Historis + Bulan Masa Depan)
        $mergedLabels = array_merge($historicalLabels, $forecastLabels);

        // Padding array agar grafik Chart.js merender garis di posisi yang tepat
        $finalHistorical = array_merge($historicalData, array_fill(0, count($forecastData), null));
        
        // Agar garis prediksi menyambung dari titik terakhir historis
        $padding = array_fill(0, count($historicalData), null);
        if (!empty($historicalData)) {
            $padding[count($historicalData) - 1] = end($historicalData);
        }
        $finalForecast = array_merge($padding, $forecastData);

        return view('manajer.dashboard', compact(
            'stats', 
            'reasonStats', 
            'storeStats', 
            'docStats', 
            'latestCsv',
            'mergedLabels',
            'finalHistorical',
            'finalForecast'
        ));
    }
}