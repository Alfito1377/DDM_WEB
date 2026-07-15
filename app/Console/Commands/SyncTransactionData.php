<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SageApiService;
use Illuminate\Support\Facades\DB;

class SyncTransactionData extends Command
{
    protected $signature = 'sage:sync-transactions';
    protected $description = 'Menarik riwayat transaksi (Turn Over & Delivery) untuk Dashboard & Forecasting';

    public function handle(SageApiService $sageApi)
    {
        $this->info('Memulai sinkronisasi data transaksi...');

        // 1. Sinkronisasi Turn Over Finish Good
        $this->info('Menarik data Turn Over...');
        $turnovers = $sageApi->getTurnovers();
        $countTO = 0;

        if (!empty($turnovers)) {
            foreach ($turnovers as $item) {
                DB::table('turnovers')->updateOrInsert(
                    ['doc_no' => $item['DocNo']],
                    [
                        'api_id'     => $item['id'],
                        'doc_date'   => $item['DocDate'],
                        'location'   => $item['Location'] ?? null,
                        'warehouse'  => $item['Warehouse'] ?? null,
                        'total_kg'   => $item['TotalKg'] ?? 0,
                        'remark'     => $item['Remark'] ?? null,
                        'posted_at'  => $item['posted_at'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
                $countTO++;
            }
            $this->info("Berhasil sinkronisasi {$countTO} data Turn Over.");
        }

        // 2. Sinkronisasi Delivery Receipt
        $this->info('Menarik data Delivery Receipt...');
        $deliveries = $sageApi->getDeliveries();
        $countDR = 0;

        if (!empty($deliveries)) {
            foreach ($deliveries as $item) {
                DB::table('delivery_receipts')->updateOrInsert(
                    ['doc_no' => $item['DocNo']], 
                    [
                        'api_id'        => $item['id'],
                        'doc_date'      => $item['DocDate'],
                        'customer_name' => $item['CustomerName'] ?? null,
                        'order_no'      => $item['OrderNo'] ?? null,
                        'remark'        => $item['Remark'] ?? null,
                        'posted_at'     => $item['posted_at'] ?? null,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]
                );
                $countDR++;
            }
            $this->info("Berhasil sinkronisasi {$countDR} data Delivery Receipt.");
        }

        $this->info('Seluruh sinkronisasi transaksi selesai!');
    }
}