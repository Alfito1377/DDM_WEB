<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class ForecastingController extends Controller
{
    /**
     * Menyediakan data historis Turn Over untuk dikonsumsi oleh mesin Python (Prophet)
     */
    public function getHistoricalTurnover(): JsonResponse
    {
        // Prophet mewajibkan dua nama kolom: 'ds' (datestamp) dan 'y' (value)
        $turnovers = DB::table('turnovers')
            ->selectRaw('DATE_FORMAT(doc_date, "%Y-%m-%d") as ds, SUM(total_kg) as y')
            ->groupBy('ds')
            ->orderBy('ds', 'asc')
            ->get();

        if ($turnovers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data historis belum tersedia.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $turnovers
        ]);
    }
}