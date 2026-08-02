<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $activeCount = DB::table('logistic')->where('status', 'in_transit')->count();
        $completedToday = DB::table('logistic')
            ->where('status', 'completed')
            ->whereDate('updated_at', Carbon::today())
            ->count();

        $avgHours = DB::table('logistic')
            ->where('status', 'completed')
            ->whereNotNull('departedAt')
            ->whereNotNull('arrivedAt')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, departedAt, arrivedAt)) as avg_hours')
            ->value('avg_hours');

        $stats = [
            'activeShipments' => $activeCount,
            'completedToday'  => $completedToday,
            'avgDeliveryTime' => round($avgHours ?? 0, 1),
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
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartData[] = DB::table('logistic')
                ->where('status', 'completed')
                ->whereDate('arrivedAt', $date)
                ->count();
        }

        $totalVehicles = DB::table('vehicle')->count();
        $onTrip = DB::table('logistic')
            ->where('status', 'in_transit')
            ->distinct('vehicleId')
            ->count('vehicleId');

        $vehicleStats = [
            'available' => max(0, $totalVehicles - $onTrip),
            'on_trip' => $onTrip,
            'maintenance' => 0,
        ];

        $statusDistribution = [
            'pending' => DB::table('logistic')->where('status', 'pending')->count(),
            'packed' => DB::table('logistic')->where('status', 'packed')->count(),
            'in_transit' => $activeCount,
            'completed' => DB::table('logistic')->where('status', 'completed')->count(),
        ];

        $recentLogs = DB::table('logistic')
            ->join('driver', 'logistic.driverId', '=', 'driver.id_driver')
            ->join('vehicle', 'logistic.vehicleId', '=', 'vehicle.id_vehicle')
            ->orderBy('logistic.updated_at', 'desc')
            ->select('logistic.*', 'driver.name as driver_name', 'vehicle.plateNo')
            ->limit(5)
            ->get();

        $colorMap = [
            'in_transit' => 'blue',
            'out_of_transit' => 'purple',
            'completed' => 'green',
            'packed' => 'yellow',
            'pending' => 'gray',
            'cancelled' => 'red',
        ];

        $statusLabelMap = [
            'in_transit' => 'dalam perjalanan',
            'out_of_transit' => 'tiba di tujuan',
            'completed' => 'selesai dibongkar',
            'packed' => 'dikemas',
            'pending' => 'menunggu',
            'cancelled' => 'dibatalkan',
        ];

        $recentActivities = $recentLogs->map(function ($log) use ($colorMap, $statusLabelMap) {
            $label = $statusLabelMap[$log->status] ?? $log->status;
            return [
                'time' => Carbon::parse($log->updated_at)->format('H:i'),
                'message' => "Pengiriman {$log->shipmentId} ke {$log->destination} - {$label}. Driver: {$log->driver_name} ({$log->plateNo})",
                'color' => $colorMap[$log->status] ?? 'gray',
            ];
        })->toArray();

        return view('shared.dashboard-visual', compact(
            'stats',
            'activeShipments',
            'chartLabels',
            'chartData',
            'vehicleStats',
            'statusDistribution',
            'recentActivities'
        ));
    }
}
