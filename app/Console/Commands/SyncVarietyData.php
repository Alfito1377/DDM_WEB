<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SageApiService;
use Illuminate\Support\Facades\DB;

class SyncVarietyData extends Command
{
    // Nama perintah yang akan diketik di terminal
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
            // Kita abaikan jika data tidak aktif (IsActive = 0)
            if (isset($item['IsActive']) && $item['IsActive'] == "0") {
                continue;
            }

            // Simpan atau update ke tabel products lokal
            // updateOrInsert akan mengecek: jika barcode sudah ada, maka update namanya. Jika belum, buat baru.
            DB::table('products')->updateOrInsert(
                ['barcode' => $item['VarietyCode']], // Acuan pencarian
                [
                    'product_name' => $item['VarietyName'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            $count++;
        }

        $this->info("Sinkronisasi selesai! Berhasil memperbarui/menambah {$count} benih.");
    }
}