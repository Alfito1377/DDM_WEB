<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        return view('manajer.dashboard', compact('stats', 'reasonStats', 'storeStats', 'docStats', 'latestCsv'));
    }
}