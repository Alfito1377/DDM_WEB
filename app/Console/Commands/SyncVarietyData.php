<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SageApiService;
use Illuminate\Support\Facades\DB;

class SyncVarietyData extends Command
{
    protected $signature = 'sage:sync-variety';
    protected $description = 'Menarik data benih (Variety) dari API SAGE dan menyimpannya ke database lokal';

    public function handle(SageApiService $sageApi)
    {
        $this->info('Memulai sinkronisasi data Variety...');

        $varieties = $sageApi->getVarieties();

        if (empty($varieties)) {
            $this->error('Gagal mengambil data dari API atau data kosong.');
            return;
        }

        $count = 0;
        foreach ($varieties as $item) {
            if (isset($item['IsActive']) && $item['IsActive'] == "0") {
                continue;
            }

            // PERBAIKAN: Melengkapi semua kolom yang wajib ada di tabel products
            DB::table('products')->updateOrInsert(
                ['barcode' => $item['VarietyCode']], 
                [
                    'product_code' => 'PRD-' . $item['VarietyCode'], // Format disamakan dengan contoh DB Anda
                    'product_name' => $item['VarietyName'],
                    'base_stock'   => 0, // Mencegah error jika base_stock tidak punya default value
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]
            );
            $count++;
        }

        $this->info("Sinkronisasi selesai! Berhasil memperbarui/menambah {$count} benih.");
    }
}