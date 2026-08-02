<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Seeder data massal ~1000+ record untuk dashboard variatif.
 * Jalankan: php artisan migrate:fresh --seed
 */
class MassDataSeeder extends Seeder
{
    // Reusable data pools
    private array $driverIds = [];
    private array $vehicleIds = [];
    private array $storeIds = [];
    private array $barcodes = [];

    public function run(): void
    {
        $this->command->info('Seeding mass data...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('store_stock_logs')->truncate();
        DB::table('store_stocks')->truncate();
        DB::table('logistic_scans')->truncate();
        DB::table('return_details')->truncate();
        DB::table('returns')->truncate();
        DB::table('logistic')->truncate();
        DB::table('driver')->truncate();
        DB::table('vehicle')->truncate();
        DB::table('turnovers')->truncate();
        DB::table('turnover_details')->truncate();
        DB::table('delivery_receipts')->truncate();
        DB::table('delivery_receipt_details')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->seedDrivers();
        $this->seedVehicles();
        $this->seedBarcodes();
        $this->storeIds = DB::table('stores')->pluck('id')->toArray();

        $this->seedLogistics();    // ~600 shipments
        $this->seedReturns();      // ~200 returns
        $this->seedStoreStocks();  // stock records per store
        $this->seedTurnoversAndDeliveries(); // ~200 records

        $this->command->info('Mass data seeding complete!');
    }

    // ───────────────────────────────────────────────
    // DRIVERS (30)
    // ───────────────────────────────────────────────
    private function seedDrivers(): void
    {
        $names = [
            'Budi Santoso', 'Andi Wijaya', 'Siti Aminah', 'Eko Prasetyo', 'Joko Susilo',
            'Rian Hidayat', 'Dewi Lestari', 'Hendra Wijaya', 'Agus Setiawan', 'Taufik Hidayat',
            'Rahman Fauzi', 'Dedi Kurniawan', 'Wahyu Pratama', 'Irfan Maulana', 'Bambang Suryadi',
            'Arif Budiman', 'Fajar Nugroho', 'Galih Permana', 'Surya Darma', 'Rizal Firmansyah',
            'Dian Saputra', 'Yusuf Hakim', 'Bayu Aditya', 'Lukman Hakim', 'Nanda Prasetya',
            'Rendi Saputra', 'Teguh Wibowo', 'Sigit Purnomo', 'Hadi Santosa', 'Putra Ramadhan',
        ];
        $statuses = ['active', 'active', 'active', 'active', 'on_trip', 'inactive'];
        $rows = [];

        foreach ($names as $i => $name) {
            $uuid = (string) Str::uuid();
            $this->driverIds[] = $uuid;
            $rows[] = [
                'id_driver' => $uuid,
                'name' => $name,
                'phone' => '08' . str_pad((string)($i + 1), 10, rand(0, 9), STR_PAD_RIGHT),
                'status' => $statuses[array_rand($statuses)],
                'notes' => 'Driver rute ' . ['Jawa Barat', 'Jawa Timur', 'Jawa Tengah', 'DKI Jakarta', 'Banten', 'Sumatera'][rand(0, 5)],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('driver')->insert($rows);
        $this->command->info('  30 drivers created.');
    }

    // ───────────────────────────────────────────────
    // VEHICLES (25)
    // ───────────────────────────────────────────────
    private function seedVehicles(): void
    {
        $types = ['Blind Van', 'Pick Up', 'CDE (Colt Diesel Engkel)', 'CDD (Colt Diesel Double)', 'Fuso', 'Tronton', 'Wingbox'];
        $prefixes = ['B', 'D', 'L', 'F', 'H', 'AG', 'AD', 'AB', 'N', 'S'];
        $suffixes = ['AB', 'CD', 'EF', 'GH', 'IJ', 'KL', 'MN', 'OP', 'QR', 'ST', 'UV', 'WX', 'YZ'];
        $rows = [];

        for ($i = 0; $i < 25; $i++) {
            $uuid = (string) Str::uuid();
            $this->vehicleIds[] = $uuid;
            $plate = $prefixes[array_rand($prefixes)] . ' ' . rand(1000, 9999) . ' ' . $suffixes[array_rand($suffixes)];
            $rows[] = [
                'id_vehicle' => $uuid,
                'plateNo' => $plate,
                'vehicleType' => $types[array_rand($types)],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('vehicle')->insert($rows);
        $this->command->info('  25 vehicles created.');
    }

    // ───────────────────────────────────────────────
    // BARCODES POOL (100 product barcodes)
    // ───────────────────────────────────────────────
    private function seedBarcodes(): void
    {
        $varietyPrefixes = ['BNH', 'JGG', 'PDI', 'KCG', 'KDL', 'TMT', 'CBI', 'PTI', 'SGM', 'KTL'];
        for ($i = 1; $i <= 100; $i++) {
            $prefix = $varietyPrefixes[($i - 1) % count($varietyPrefixes)];
            $this->barcodes[] = $prefix . '-' . str_pad((string) $i, 5, '0', STR_PAD_LEFT);
        }
    }

    // ───────────────────────────────────────────────
    // LOGISTICS (~600 shipments, spread over 90 days)
    // ───────────────────────────────────────────────
    private function seedLogistics(): void
    {
        $destinations = [
            'Jakarta Pusat, DKI Jakarta', 'Jakarta Selatan, DKI Jakarta', 'Jakarta Barat, DKI Jakarta',
            'Bandung, Jawa Barat', 'Surabaya, Jawa Timur', 'Semarang, Jawa Tengah',
            'Yogyakarta, DIY', 'Tangerang, Banten', 'Bekasi, Jawa Barat', 'Bogor, Jawa Barat',
            'Depok, Jawa Barat', 'Malang, Jawa Timur', 'Solo, Jawa Tengah', 'Cirebon, Jawa Barat',
            'Jember, Jawa Timur', 'Kediri, Jawa Timur', 'Madiun, Jawa Timur', 'Purwokerto, Jawa Tengah',
            'Pekalongan, Jawa Tengah', 'Tasikmalaya, Jawa Barat', 'Garut, Jawa Barat',
            'Sukabumi, Jawa Barat', 'Karawang, Jawa Barat', 'Subang, Jawa Barat',
        ];

        // Weight distribution: mostly completed (realistic historical)
        $statusWeights = [
            'completed'      => 65,  // 65%
            'in_transit'     => 12,  // 12%
            'out_of_transit' => 5,   // 5%
            'packed'         => 8,   // 8%
            'pending'        => 7,   // 7%
            'cancelled'      => 3,   // 3%
        ];

        $logisticBatch = [];
        $scanBatch = [];
        $totalShipments = 600;

        for ($i = 0; $i < $totalShipments; $i++) {
            $status = $this->weightedRandom($statusWeights);
            $storeId = $this->storeIds[array_rand($this->storeIds)];
            $driverId = $this->driverIds[array_rand($this->driverIds)];
            $vehicleId = $this->vehicleIds[array_rand($this->vehicleIds)];
            $destination = $destinations[array_rand($destinations)];
            $logisticUuid = 'LOG-' . strtoupper(Str::random(8));
            $shipmentId = 'SHP-' . strtoupper(Str::random(8));

            // Spread over 90 days for good chart data
            // More recent = more likely to be active statuses
            $daysAgo = match ($status) {
                'completed'      => rand(0, 90),
                'in_transit'     => rand(0, 3),
                'out_of_transit' => rand(0, 2),
                'packed'         => rand(0, 5),
                'pending'        => rand(0, 7),
                'cancelled'      => rand(1, 60),
            };

            $departedAt = null;
            $arrivedAt = null;
            $createdAt = Carbon::now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            if (in_array($status, ['in_transit', 'out_of_transit', 'completed'])) {
                $departedAt = (clone $createdAt)->addHours(rand(1, 6));
            }

            if ($status === 'out_of_transit') {
                $arrivedAt = (clone $departedAt)->addHours(rand(2, 12));
            } elseif ($status === 'completed') {
                $arrivedAt = (clone $departedAt)->addHours(rand(2, 24));
            }

            $logisticRow = [
                'id_logistic' => $logisticUuid,
                'shipmentId' => $shipmentId,
                'status' => $status,
                'id_mitra' => $storeId,
                'destination' => $destination,
                'driverId' => $driverId,
                'vehicleId' => $vehicleId,
                'departedAt' => $departedAt,
                'arrivedAt' => $arrivedAt,
                'created_at' => $createdAt,
                'updated_at' => $arrivedAt ?? $departedAt ?? $createdAt,
            ];

            $logisticBatch[] = $logisticRow;

            // Insert in batches of 50 to get auto-increment IDs
            if (count($logisticBatch) >= 50 || $i === $totalShipments - 1) {
                DB::table('logistic')->insert($logisticBatch);

                // Now create scans for each logistic that has departed
                foreach ($logisticBatch as $log) {
                    if (!in_array($log['status'], ['in_transit', 'out_of_transit', 'completed'])) {
                        continue;
                    }

                    $logId = DB::table('logistic')->where('id_logistic', $log['id_logistic'])->value('id');
                    $numItems = rand(3, 15);
                    $sackPrefixes = ['KARDUS', 'KARUNG', 'PALET', 'BOX'];

                    for ($s = 0; $s < $numItems; $s++) {
                        $barcode = $this->barcodes[array_rand($this->barcodes)];
                        $sackId = $sackPrefixes[array_rand($sackPrefixes)] . '-' . strtoupper(Str::random(3));

                        $receivedAt = null;
                        if ($log['status'] === 'completed') {
                            $receivedAt = $log['arrivedAt'] ? Carbon::parse($log['arrivedAt'])->addMinutes(rand(5, 120)) : null;
                        } elseif ($log['status'] === 'out_of_transit' && rand(0, 3) === 0) {
                            // Some items partially received
                            $receivedAt = $log['arrivedAt'] ? Carbon::parse($log['arrivedAt'])->addMinutes(rand(5, 60)) : null;
                        }

                        $scanBatch[] = [
                            'logistic_id' => $logId,
                            'sack_id' => $sackId,
                            'barcode' => $barcode,
                            'departed_at' => $log['departedAt'],
                            'received_at' => $receivedAt,
                            'created_at' => $log['created_at'],
                            'updated_at' => $receivedAt ?? $log['created_at'],
                        ];
                    }

                    // Flush scans in batches
                    if (count($scanBatch) >= 200) {
                        DB::table('logistic_scans')->insert($scanBatch);
                        $scanBatch = [];
                    }
                }

                $logisticBatch = [];
            }
        }

        // Flush remaining scans
        if (!empty($scanBatch)) {
            DB::table('logistic_scans')->insert($scanBatch);
        }

        $logCount = DB::table('logistic')->count();
        $scanCount = DB::table('logistic_scans')->count();
        $this->command->info("  {$logCount} logistics + {$scanCount} scans created.");
    }

    // ───────────────────────────────────────────────
    // RETURNS (~200 returns, spread over 60 days)
    // ───────────────────────────────────────────────
    private function seedReturns(): void
    {
        $reasons = [
            'Barang rusak saat diterima',
            'Produk kadaluarsa',
            'Kemasan rusak/bocor',
            'Salah kirim produk',
            'Jumlah tidak sesuai pesanan',
            'Kualitas benih tidak sesuai standar',
            'Label produk tidak jelas',
            'Benih sudah berkecambah dalam kemasan',
            'Kontaminasi hama/penyakit',
            'Kadar air terlalu tinggi',
            'Tidak sesuai varietas yang dipesan',
            'Kemasan sobek saat pengiriman',
        ];

        $statusWeights = [
            'Pending'  => 20,
            'Approved' => 55,
            'Rejected' => 25,
        ];

        // Ambil manager IDs untuk approval
        $managerIds = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->whereIn('roles.role_name', ['superadmin', 'admin'])
            ->pluck('users.id')
            ->toArray();

        $totalReturns = 200;
        $returnBatch = [];
        $detailBatch = [];

        for ($i = 0; $i < $totalReturns; $i++) {
            $status = $this->weightedRandom($statusWeights);
            $storeId = $this->storeIds[array_rand($this->storeIds)];
            $daysAgo = rand(0, 60);
            $createdAt = Carbon::now()->subDays($daysAgo)->subHours(rand(0, 23));
            $returnCode = 'RET-' . strtoupper(Str::random(8));
            $reason = $reasons[array_rand($reasons)];
            $barcode = $this->barcodes[array_rand($this->barcodes)];
            $quantity = rand(1, 50);

            $managerId = null;
            if ($status !== 'Pending' && !empty($managerIds)) {
                $managerId = $managerIds[array_rand($managerIds)];
            }

            $returnBatch[] = [
                'return_code' => $returnCode,
                'store_id' => $storeId,
                'manager_id' => $managerId,
                'reason' => $reason,
                'notes' => rand(0, 1) ? 'Bukti foto terlampir. Mohon segera diproses.' : null,
                'proof_image' => json_encode([]),
                'status' => $status,
                'submitted_at' => $createdAt,
                'created_at' => $createdAt,
                'updated_at' => $status !== 'Pending'
                    ? (clone $createdAt)->addHours(rand(1, 48))
                    : $createdAt,
            ];

            $detailBatch[] = [
                '_return_code' => $returnCode, // temporary marker for linking
                'barcode' => $barcode,
                'quantity' => $quantity,
                'proof_image_url' => json_encode([]),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];

            // Flush every 50
            if (count($returnBatch) >= 50 || $i === $totalReturns - 1) {
                DB::table('returns')->insert($returnBatch);

                // Link details to return IDs
                foreach ($detailBatch as $detail) {
                    $returnId = DB::table('returns')
                        ->where('return_code', $detail['_return_code'])
                        ->value('id');

                    unset($detail['_return_code']);
                    $detail['return_id'] = $returnId;
                    DB::table('return_details')->insert($detail);
                }

                $returnBatch = [];
                $detailBatch = [];
            }
        }

        $this->command->info("  {$totalReturns} returns with details created.");
    }

    // ───────────────────────────────────────────────
    // STORE STOCKS (inventory dari completed shipments)
    // ───────────────────────────────────────────────
    private function seedStoreStocks(): void
    {
        $count = 0;

        foreach ($this->storeIds as $storeId) {
            // Generate 10-25 product stocks per store
            $numProducts = rand(10, 25);
            $usedBarcodes = array_rand(array_flip($this->barcodes), min($numProducts, count($this->barcodes)));
            if (!is_array($usedBarcodes)) $usedBarcodes = [$usedBarcodes];

            foreach ($usedBarcodes as $barcode) {
                $qty = rand(5, 500);
                DB::table('store_stocks')->insert([
                    'store_id' => $storeId,
                    'barcode' => $barcode,
                    'quantity' => $qty,
                    'created_at' => now()->subDays(rand(0, 30)),
                    'updated_at' => now(),
                ]);

                // Create some stock logs (in/out)
                $logCount = rand(3, 10);
                $logs = [];
                for ($l = 0; $l < $logCount; $l++) {
                    $type = rand(0, 4) > 0 ? 'in' : 'out'; // 80% in, 20% out
                    $logs[] = [
                        'store_id' => $storeId,
                        'barcode' => $barcode,
                        'type' => $type,
                        'quantity' => rand(1, 50),
                        'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
                        'updated_at' => now(),
                    ];
                }
                DB::table('store_stock_logs')->insert($logs);
                $count++;
            }
        }

        $this->command->info("  {$count} store stock records created.");
    }

    // ───────────────────────────────────────────────
    // HELPER: weighted random selection
    // ───────────────────────────────────────────────
    private function weightedRandom(array $weights): string
    {
        $total = array_sum($weights);
        $rand = rand(1, $total);
        $cumulative = 0;

        foreach ($weights as $key => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $key;
            }
        }

        return array_key_first($weights);
    }

    // ───────────────────────────────────────────────
    // TURNOVERS AND DELIVERIES (~200 records over 60 days)
    // ───────────────────────────────────────────────
    private function seedTurnoversAndDeliveries(): void
    {
        $warehouses = ['Gudang Utama', 'Gudang Cabang', 'Gudang Distribusi'];
        $locations = ['Rak A-1', 'Rak B-2', 'Rak C-3', 'Rak D-4'];

        $turnoverBatch = [];
        $turnoverDetailBatch = [];
        $deliveryBatch = [];
        $deliveryDetailBatch = [];

        // Seed over 60 days
        for ($i = 0; $i < 60; $i++) {
            $date = Carbon::now()->subDays(60 - $i);
            
            // Turnovers: 1-3 transactions per day
            $tCount = rand(1, 3);
            for ($j = 0; $j < $tCount; $j++) {
                $docNo = 'TO-' . $date->format('Ymd') . '-' . str_pad((string)$j, 3, '0', STR_PAD_LEFT);
                $totalKg = rand(100, 1500);

                $turnoverBatch[] = [
                    'doc_no' => $docNo,
                    'doc_date' => $date->toDateString(),
                    'location' => $locations[array_rand($locations)],
                    'warehouse' => $warehouses[array_rand($warehouses)],
                    'total_kg' => $totalKg,
                    'remark' => 'Produksi/Penerimaan Harian',
                    'posted_at' => $date->toDateString(),
                    'created_at' => $date,
                    'updated_at' => $date,
                ];

                // Details
                $detailCount = rand(1, 3);
                for ($d = 0; $d < $detailCount; $d++) {
                    $turnoverDetailBatch[] = [
                        'doc_no' => $docNo,
                        'batch_no' => 'BAT-' . strtoupper(Str::random(5)),
                        'variety_code' => $this->barcodes[array_rand($this->barcodes)],
                        'variety_name' => 'Benih Unggul ' . Str::random(3),
                        'product_name' => 'Produk Benih ' . Str::random(3),
                        'unit_code' => 'KG',
                        'qty' => $totalKg / $detailCount,
                        'total_kg' => $totalKg / $detailCount,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ];
                }
            }

            // Deliveries: 1-2 transactions per day
            $dCount = rand(1, 2);
            for ($j = 0; $j < $dCount; $j++) {
                $docNo = 'DR-' . $date->format('Ymd') . '-' . str_pad((string)$j, 3, '0', STR_PAD_LEFT);
                $totalKg = rand(50, 1000);

                $deliveryBatch[] = [
                    'doc_no' => $docNo,
                    'doc_date' => $date->toDateString(),
                    'customer_name' => 'Mitra Toko ' . Str::random(3),
                    'order_no' => 'ORD-' . strtoupper(Str::random(5)),
                    'remark' => 'Pengiriman barang ke mitra',
                    'posted_at' => $date->toDateString(),
                    'created_at' => $date,
                    'updated_at' => $date,
                ];

                // Details
                $detailCount = rand(1, 2);
                for ($d = 0; $d < $detailCount; $d++) {
                    $deliveryDetailBatch[] = [
                        'doc_no' => $docNo,
                        'product_name' => 'Produk Benih ' . Str::random(3),
                        'location' => $locations[array_rand($locations)],
                        'warehouse' => $warehouses[array_rand($warehouses)],
                        'lot_no' => 'LOT-' . strtoupper(Str::random(5)),
                        'lot_expired' => $date->copy()->addYear()->toDateString(),
                        'unit_code' => 'KG',
                        'qty' => $totalKg / $detailCount,
                        'total_kg' => $totalKg / $detailCount,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ];
                }
            }
        }

        // Insert in chunks
        foreach (array_chunk($turnoverBatch, 50) as $chunk) {
            DB::table('turnovers')->insert($chunk);
        }
        foreach (array_chunk($turnoverDetailBatch, 100) as $chunk) {
            DB::table('turnover_details')->insert($chunk);
        }
        foreach (array_chunk($deliveryBatch, 50) as $chunk) {
            DB::table('delivery_receipts')->insert($chunk);
        }
        foreach (array_chunk($deliveryDetailBatch, 100) as $chunk) {
            DB::table('delivery_receipt_details')->insert($chunk);
        }

        $this->command->info('  Turnover and Delivery records successfully seeded.');
    }
}
