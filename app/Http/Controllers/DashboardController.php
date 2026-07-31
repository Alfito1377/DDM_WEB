<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Menghitung data statistik dari tabel logistic
        $stats = [
            'activeShipments' => DB::table('logistic')
                                    ->where('status', 'in_transit')
                                    ->count(),
            
            'completedToday'  => DB::table('logistic')
                                    // Berdasarkan gambar, status selesai menggunakan string 'completed'
                                    ->where('status', 'completed')
                                    ->whereDate('updated_at', Carbon::today())
                                    ->count(),
            
            'avgDeliveryTime' => 4.5 // Nilai statis sesuai desain awal
        ];

        // ... (kode sebelumnya)
        $activeShipments = DB::table('logistic')
            ->join('driver', 'logistic.driverId', '=', 'driver.id_driver')
            ->join('vehicle', 'logistic.vehicleId', '=', 'vehicle.id_vehicle') 
            ->where('logistic.status', 'in_transit')
            ->select(
                'logistic.shipmentId as shipment_id',
                'logistic.destination',
                'logistic.departedAt as departed_at',
                'driver.name as driver_name',
                'vehicle.plateNo as vehicle_no'
            )
            ->orderBy('logistic.departedAt', 'desc')
            ->get();
        // ... (kode setelahnya)
        // 3. Mengirim data ke view
        return view('shared.dashboard-visual', compact('stats', 'activeShipments'));
    }
}