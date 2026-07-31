<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
 public function index()
    {
        $stats = [
            'activeShipments' => DB::table('logistic')->where('status', 'in_transit')->count(),
            'completedToday'  => DB::table('logistic')->where('status', 'completed')->whereDate('updated_at', Carbon::today())->count(),
            'avgDeliveryTime' => 4.5
        ];

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

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $chartLabels[] = Carbon::today()->subDays($i)->format('d M');
            $chartData[] = rand(5, 25); 
        }

        $totalVehicles = DB::table('vehicle')->count();
        $vehicleStats = [
            'available' => $totalVehicles > 0 ? $totalVehicles - 4 : 8,
            'on_trip' => 3,     
            'maintenance' => 1, 
        ];

        $statusDistribution = [
            'pending' => DB::table('logistic')->where('status', 'pending')->count() ?: 4,
            'packed' => DB::table('logistic')->where('status', 'packed')->count() ?: 5,
            'in_transit' => DB::table('logistic')->where('status', 'in_transit')->count() ?: 3,
            'completed' => DB::table('logistic')->where('status', 'completed')->count() ?: 12,
        ];

        $recentActivities = [
            ['time' => Carbon::now()->subMinutes(12)->format('H:i'), 'message' => 'Paket TRX-99812-A telah diberangkatkan ke Jember.', 'color' => 'blue'],
            ['time' => Carbon::now()->subMinutes(45)->format('H:i'), 'message' => 'Armada D 5678 XYZ selesai bongkar muat di Surabaya.', 'color' => 'green'],
            ['time' => Carbon::now()->subHours(1)->format('H:i'), 'message' => 'Status driver Budi Santoso berubah menjadi On Trip.', 'color' => 'purple'],
            ['time' => Carbon::now()->subHours(2)->format('H:i'), 'message' => 'Penjadwalan ulang rute untuk armada F 7890 GH.', 'color' => 'yellow'],
        ];

        return view('shared.dashboard-visual', compact(
            'stats', 'activeShipments', 'chartLabels', 'chartData', 
            'vehicleStats', 'statusDistribution', 'recentActivities'
        ));
    }
}