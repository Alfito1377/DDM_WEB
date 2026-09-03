<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SystemStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $virtualStoreId = DB::table('stores')->where('store_name', 'Operasional Lapangan')->value('id');
        if (!$virtualStoreId) {
            DB::table('stores')->insert([
                'store_name' => 'Operasional Lapangan',
                'owner_name' => 'Sistem Internal',
                'phone_number' => '00000000',
                'address' => 'Mobile / Virtual',
                'jenis_mitra_id' => 1,
                'qr_token_login' => Str::random(40),
                'qr_token_checkpoint' => Str::random(40),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
