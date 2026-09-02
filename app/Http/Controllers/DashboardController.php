<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\KnowledgeBase;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        
        $receiptsToday = DB::table('delivery_receipts')->count();

        // Total Scan Hari Ini: Mengambil dari tabel logistic_scans (jika tabel ini kosong, Anda bisa fallback ke logistic)
        $totalScans = DB::table('logistic_scans')
            ->count();

        // Alokasi Berjalan: Kita ambil dari logistic yang berstatus 'in_transit'
        $activeAllocations = DB::table('logistic')
            ->where('status', 'in_transit')
            ->count();

        // Success Rate: Persentase selesai dari total data logistik
        $totalLogistic = DB::table('logistic')->count();
        $completedLogistic = DB::table('logistic')->where('status', 'completed')->count();
        $successRate = $totalLogistic > 0 ? round(($completedLogistic / $totalLogistic) * 100) : 0;

        $stats = [
            'delivery_receipts_today' => $receiptsToday,
            'total_scans_all_time'    => $totalScans,
            'active_allocations'      => $activeAllocations,
            'success_rate'            => $successRate . '%',
        ];

        // ---------------------------------------------------------
        // 2. FLEET STATS (Armada & Sopir) - Logika dari controller asli
        // ---------------------------------------------------------
        
        $totalVehicles = DB::table('vehicle')->count();
        $totalDrivers = DB::table('driver')->count(); 
        
        $onTripVehicles = DB::table('logistic')
            ->where('status', 'in_transit')
            ->distinct('vehicleId')
            ->count('vehicleId');
            
        $onTripDrivers = DB::table('logistic')
            ->where('status', 'in_transit')
            ->distinct('driverId')
            ->count('driverId');

        $fleetStats = [
            'ready_driver'  => max(0, $totalDrivers - $onTripDrivers),
            'on_trip'       => $onTripVehicles, 
            'vehicle_ready' => max(0, $totalVehicles - $onTripVehicles),
            'maintenance'   => 0, // Di-nol-kan sementara kecuali Anda punya flag khusus
        ];

        // ---------------------------------------------------------
        // 3. GRAFIK TREN SURAT JALAN Selesai (7 Hari)
        // ---------------------------------------------------------
        
        // ---------------------------------------------------------
        // 3. GRAFIK TREN SURAT JALAN TERKIRIM (7 Hari Terakhir)
        // ---------------------------------------------------------
        
        $trendLabels = [];
        $trendData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $trendLabels[] = $date->format('d M');
            
            $trendData[] = DB::table('logistic')
                ->where('status', 'completed') 
                ->whereDate('created_at', $date)
                ->count();
        }

        // ---------------------------------------------------------
        // 4. LIVE SCAN LOGISTIK (Modifikasi dari $recentActivities Anda)
        // ---------------------------------------------------------
        
        $recentLogs = DB::table('logistic')
            ->orderBy('updated_at', 'desc')
            ->limit(6)
            ->get();

        $colorMap = [
            'in_transit'     => 'blue',
            'out_of_transit' => 'purple',
            'completed'      => 'green',
            'packed'         => 'yellow',
            'pending'        => 'gray',
            'cancelled'      => 'red',
        ];

        $statusLabelMap = [
            'in_transit'     => 'Dalam Perjalanan',
            'out_of_transit' => 'Tiba di Tujuan',
            'completed'      => 'Selesai Dibongkar',
            'packed'         => 'Dikemas',
            'pending'        => 'Menunggu',
            'cancelled'      => 'Dibatalkan',
        ];

        // Mapping ke format yang dibutuhkan oleh Blade baru (resi, status, time, color)
        $recentScans = $recentLogs->map(function ($log) use ($colorMap, $statusLabelMap) {
            return [
                'resi'   => $log->shipmentId,
                'status' => $statusLabelMap[$log->status] ?? $log->status,
                'time'   => Carbon::parse($log->updated_at)->format('H:i'),
                'color'  => $colorMap[$log->status] ?? 'gray',
            ];
        })->toArray();

        // ---------------------------------------------------------
        // 5. TABEL PENGIRIMAN BERJALAN (In Transit)
        // ---------------------------------------------------------
        
        // Kita kembali menggunakan join yang sudah terbukti jalan dari controller lama Anda
        $activeShipments = DB::table('logistic')
            ->join('driver', 'logistic.driverId', '=', 'driver.id_driver')
            ->join('vehicle', 'logistic.vehicleId', '=', 'vehicle.id_vehicle')
            ->where('logistic.status', 'in_transit')
            ->select(
                'logistic.shipmentId as receipt_number',
                'logistic.destination as store_name',
                'driver.name as driver_name',
                'vehicle.plateNo as vehicle_no',
                'logistic.updated_at'
            )
            ->orderBy('logistic.updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('shared.dashboard-visual', compact(
            'stats',
            'fleetStats',
            'trendLabels',
            'trendData',
            'recentScans',
            'activeShipments'
        ));
    }


    public function indexManager()
    {
        $stats = [
            'total_dokumen' => DB::table('knowledge_bases')->count(),
            'total_retur' => DB::table('returns')->count(),
            'pending' => DB::table('returns')->where('status', 'Pending')->count(),
            'approved' => DB::table('returns')->where('status', 'Approved')->count(),
        ];

        $reasonStats = DB::table('returns')
            ->select('reason', DB::raw('count(*) as count'))
            ->groupBy('reason')
            ->pluck('count', 'reason')
            ->toArray();

        $storeStats = DB::table('returns')
            ->join('stores', 'returns.store_id', '=', 'stores.id')
            ->select('stores.store_name', DB::raw('count(*) as count'))
            ->groupBy('stores.store_name')
            ->orderByDesc('count')
            ->limit(5)
            ->pluck('count', 'stores.store_name')
            ->toArray();

        // Mengambil data turnover historis
        $turnovers = DB::table('turnovers')
            ->orderBy('doc_date', 'asc')
            ->get();

        $mergedLabels = [];
        $finalHistorical = [];
        $finalForecast = [];

        if ($turnovers->isNotEmpty()) {
            foreach ($turnovers as $t) {
                $dateLabel = Carbon::parse($t->doc_date)->format('d M');
                $mergedLabels[] = $dateLabel;
                $finalHistorical[] = (float)$t->total_kg;
                $finalForecast[] = null;
            }

            // Tambahkan forecast sederhana untuk visualisasi sumbu X ke depan
            $lastDate = Carbon::parse($turnovers->last()->doc_date);
            $lastVal = (float)$turnovers->last()->total_kg;

            // Sambungkan titik terakhir data asli ke forecast agar berkesinambungan
            $finalForecast[count($finalForecast) - 1] = $lastVal;

            for ($i = 1; $i <= 5; $i++) {
                $futureDate = (clone $lastDate)->addDays($i);
                $mergedLabels[] = $futureDate->format('d M');
                $finalHistorical[] = null;
                $finalForecast[] = max(0, $lastVal + ($i * 10) + rand(-50, 50));
            }
        }

        $latestCsv = KnowledgeBase::where('file_type', 'csv')
            ->orderBy('created_at', 'desc')
            ->first();

        return view('manajer.dashboard', compact(
            'stats',
            'reasonStats',
            'storeStats',
            'mergedLabels',
            'finalHistorical',
            'finalForecast',
            'latestCsv'
        ));
    }

    public function getForecast()
    {
        try {
            $pythonApiUrl = rtrim(env('PYTHON_API', env('AI_SERVICE_URL', 'http://127.0.0.1:8001')), '/') . '/forecast';
            $response = Http::timeout(60)->post($pythonApiUrl);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            Log::error('Python Forecast API Error: ' . $response->body());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data forecast dari service AI.'
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Koneksi ke Python Forecast Gagal: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Koneksi ke service Python AI gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getClustering()
    {
        try {
            $pythonApiUrl = rtrim(env('PYTHON_API', env('AI_SERVICE_URL', 'http://127.0.0.1:8001')), '/') . '/clustering';
            $response = Http::timeout(60)->get($pythonApiUrl);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            Log::error('Python Clustering API Error: ' . $response->body());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data clustering dari service AI.'
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Koneksi ke Python Clustering Gagal: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Koneksi ke service Python AI gagal: ' . $e->getMessage()
            ], 500);
        }
    }
}
