<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyLogisticSeeder extends Seeder
{
    public function run(): void
    {
        $driver = DB::table('driver')->first();
        $vehicle = DB::table('vehicle')->first();

        $logisticId = DB::table('logistic')->insertGetId([
            'id_logistic' => 'LOG-12345',
            'shipmentId' => 'SHIP-12345',
            'status' => 'in_transit',
            'id_mitra' => 1, // Store ID 1 (Toko A)
            'destination' => 'Toko A',
            'driverId' => $driver->id_driver ?? null,
            'vehicleId' => $vehicle->id_vehicle ?? null,
            'departedAt' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('logistic_scans')->insert([
            [
                'logistic_id' => $logisticId,
                'sack_id' => 'SACK-001',
                'barcode' => 'B-001',
                'departed_at' => now(),
                'received_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'logistic_id' => $logisticId,
                'sack_id' => 'SACK-001',
                'barcode' => '123456789',
                'departed_at' => now(),
                'received_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'logistic_id' => $logisticId,
                'sack_id' => 'SACK-002',
                'barcode' => 'B-002',
                'departed_at' => now(),
                'received_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
