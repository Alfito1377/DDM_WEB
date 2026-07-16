<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LogisticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Data Dummy Drivers
        $drivers = [
            ['name' => 'Budi Santoso', 'phone' => '081234567890', 'status' => 'active', 'notes' => 'Driver berpengalaman rute Jawa Barat'],
            ['name' => 'Andi Wijaya', 'phone' => '082345678901', 'status' => 'active', 'notes' => 'Driver khusus kendaraan berat'],
            ['name' => 'Siti Aminah', 'phone' => '083456789012', 'status' => 'active', 'notes' => 'Driver rute dalam kota'],
            ['name' => 'Eko Prasetyo', 'phone' => '084567890123', 'status' => 'on_trip', 'notes' => 'Sedang mengirim ke Bandung'],
            ['name' => 'Joko Susilo', 'phone' => '085678901234', 'status' => 'active', 'notes' => 'Siap bertugas'],
            ['name' => 'Rian Hidayat', 'phone' => '086789012345', 'status' => 'on_trip', 'notes' => 'Sedang mengirim ke Surabaya'],
            ['name' => 'Dewi Lestari', 'phone' => '087890123456', 'status' => 'inactive', 'notes' => 'Cuti sakit'],
            ['name' => 'Hendra Wijaya', 'phone' => '088901234567', 'status' => 'active', 'notes' => 'Driver standby'],
            ['name' => 'Agus Setiawan', 'phone' => '089012345678', 'status' => 'active', 'notes' => 'Driver rute lintas Sumatera'],
            ['name' => 'Taufik Hidayat', 'phone' => '081122334455', 'status' => 'inactive', 'notes' => 'Cuti tahunan'],
        ];

        $driverIds = [];
        foreach ($drivers as $driver) {
            $uuid = (string) Str::uuid();
            $driverIds[] = $uuid;
            DB::table('driver')->insert([
                'id_driver' => $uuid,
                'name' => $driver['name'],
                'phone' => $driver['phone'],
                'status' => $driver['status'],
                'notes' => $driver['notes'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Data Dummy Vehicles
        $vehicles = [
            ['plateNo' => 'B 1234 AB', 'vehicleType' => 'Blind Van'],
            ['plateNo' => 'D 5678 XYZ', 'vehicleType' => 'Pick Up'],
            ['plateNo' => 'L 9012 CD', 'vehicleType' => 'CDE (Colt Diesel Engkel)'],
            ['plateNo' => 'B 3456 EF', 'vehicleType' => 'CDD (Colt Diesel Double)'],
            ['plateNo' => 'F 7890 GH', 'vehicleType' => 'Fuso'],
            ['plateNo' => 'H 1122 IJ', 'vehicleType' => 'Tronton'],
            ['plateNo' => 'B 4433 KL', 'vehicleType' => 'Blind Van'],
            ['plateNo' => 'D 8877 MN', 'vehicleType' => 'Pick Up'],
            ['plateNo' => 'L 5566 OP', 'vehicleType' => 'CDE (Colt Diesel Engkel)'],
            ['plateNo' => 'B 9900 QR', 'vehicleType' => 'Wingbox'],
        ];

        $vehicleIds = [];
        foreach ($vehicles as $vehicle) {
            $uuid = (string) Str::uuid();
            $vehicleIds[] = $uuid;
            DB::table('vehicle')->insert([
                'id_vehicle' => $uuid,
                'plateNo' => $vehicle['plateNo'],
                'vehicleType' => $vehicle['vehicleType'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Data Dummy Logistics
        $destinations = [
            'Jakarta Pusat, DKI Jakarta',
            'Bandung, Jawa Barat',
            'Surabaya, Jawa Timur',
            'Semarang, Jawa Tengah',
            'Yogyakarta, DIY',
            'Tangerang, Banten',
            'Bekasi, Jawa Barat',
            'Bogor, Jawa Barat',
            'Depok, Jawa Barat',
            'Malang, Jawa Timur',
        ];

        $statuses = ['pending', 'packed', 'out_of_transit', 'in_transit', 'completed', 'cancelled'];
        $jenisMitraIds = DB::table('jenis_mitra')->pluck('id')->toArray();

        for ($i = 0; $i < 10; $i++) {
            $status = $statuses[$i % count($statuses)];
            
            // Set departedAt dan arrivedAt berdasarkan status
            $departedAt = null;
            $arrivedAt = null;

            if (in_array($status, ['in_transit', 'completed'])) {
                $departedAt = Carbon::now()->subHours(rand(1, 48));
            }
            if ($status === 'completed' && $departedAt) {
                $arrivedAt = (clone $departedAt)->addHours(rand(2, 12));
            }

            DB::table('logistic')->insert([
                'id_logistic' => (string) Str::uuid(),
                'shipmentId' => (string) Str::uuid(),
                'status' => $status,
                'id_mitra' => !empty($jenisMitraIds) ? $jenisMitraIds[$i % count($jenisMitraIds)] : null,
                'destination' => $destinations[$i],
                'driverId' => $driverIds[$i],
                'vehicleId' => $vehicleIds[$i],
                'departedAt' => $departedAt,
                'arrivedAt' => $arrivedAt,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
