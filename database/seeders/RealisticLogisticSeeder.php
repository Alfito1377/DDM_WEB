<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RealisticLogisticSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('logistic_scans')->truncate();
        DB::table('logistic')->truncate();
        DB::table('products')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Siapkan Data Produk Master
        $products = [
            ['product_code' => 'P-JGG-01', 'barcode' => '8991234567891', 'product_name' => 'Benih Jagung Hibrida Bisi-18', 'base_stock' => 100],
            ['product_code' => 'P-PDI-02', 'barcode' => '8991234567892', 'product_name' => 'Benih Padi Inpari 32', 'base_stock' => 200],
            ['product_code' => 'P-PUP-03', 'barcode' => '8991234567893', 'product_name' => 'Pupuk Urea Non-Subsidi 50kg', 'base_stock' => 50],
        ];
        
        foreach ($products as $p) {
            DB::table('products')->insert(array_merge($p, ['created_at' => now(), 'updated_at' => now()]));
        }

        $driver = DB::table('driver')->first();
        $vehicle = DB::table('vehicle')->first();
        $storeId = 1; // Asumsi ID Toko = 1

        // 3. SKENARIO 1: Pengiriman Masih Di Jalan (in_transit)
        $id1 = DB::table('logistic')->insertGetId([
            'id_logistic' => 'LOG-' . strtoupper(Str::random(6)),
            'shipmentId' => 'SHIP-' . strtoupper(Str::random(6)),
            'status' => 'in_transit',
            'id_mitra' => $storeId,
            'destination' => 'Toko A',
            'driverId' => $driver->id_driver ?? null,
            'vehicleId' => $vehicle->id_vehicle ?? null,
            'departedAt' => now()->subHours(2),
            'arrivedAt' => null,
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subHours(2),
        ]);

        DB::table('logistic_scans')->insert([
            ['logistic_id' => $id1, 'sack_id' => 'KARDUS-A1', 'barcode' => '8991234567891', 'departed_at' => now()->subHours(2), 'received_at' => null],
            ['logistic_id' => $id1, 'sack_id' => 'KARDUS-A1', 'barcode' => '8991234567892', 'departed_at' => now()->subHours(2), 'received_at' => null],
            ['logistic_id' => $id1, 'sack_id' => 'KARUNG-B1', 'barcode' => '8991234567893', 'departed_at' => now()->subHours(2), 'received_at' => null],
        ]);

        // 4. SKENARIO 2: Pengiriman Sudah Tiba Tapi Belum Dibongkar Toko (out_of_transit)
        $id2 = DB::table('logistic')->insertGetId([
            'id_logistic' => 'LOG-' . strtoupper(Str::random(6)),
            'shipmentId' => 'SHIP-' . strtoupper(Str::random(6)),
            'status' => 'out_of_transit',
            'id_mitra' => $storeId,
            'destination' => 'Toko A',
            'driverId' => $driver->id_driver ?? null,
            'vehicleId' => $vehicle->id_vehicle ?? null,
            'departedAt' => now()->subHours(5),
            'arrivedAt' => now()->subMinutes(10), // Supir baru saja confirm tiba 10 menit lalu
            'created_at' => now()->subHours(5),
            'updated_at' => now()->subMinutes(10),
        ]);

        DB::table('logistic_scans')->insert([
            ['logistic_id' => $id2, 'sack_id' => 'KARUNG-C1', 'barcode' => '8991234567893', 'departed_at' => now()->subHours(5), 'received_at' => null],
            ['logistic_id' => $id2, 'sack_id' => 'KARUNG-C1', 'barcode' => '8991234567893', 'departed_at' => now()->subHours(5), 'received_at' => null],
        ]);

        // 5. SKENARIO 3: Pengiriman Sudah Selesai Dibongkar Toko (completed)
        $id3 = DB::table('logistic')->insertGetId([
            'id_logistic' => 'LOG-' . strtoupper(Str::random(6)),
            'shipmentId' => 'SHIP-' . strtoupper(Str::random(6)),
            'status' => 'completed',
            'id_mitra' => $storeId,
            'destination' => 'Toko A',
            'driverId' => $driver->id_driver ?? null,
            'vehicleId' => $vehicle->id_vehicle ?? null,
            'departedAt' => now()->subDays(1),
            'arrivedAt' => now()->subDays(1)->addHours(3),
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1)->addHours(4),
        ]);

        DB::table('logistic_scans')->insert([
            ['logistic_id' => $id3, 'sack_id' => 'KARDUS-X1', 'barcode' => '8991234567891', 'departed_at' => now()->subDays(1), 'received_at' => now()->subDays(1)->addHours(4)],
            ['logistic_id' => $id3, 'sack_id' => 'KARDUS-X1', 'barcode' => '8991234567892', 'departed_at' => now()->subDays(1), 'received_at' => now()->subDays(1)->addHours(4)],
        ]);
    }
}
