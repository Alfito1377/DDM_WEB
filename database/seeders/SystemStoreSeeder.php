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
        $jenisMitraId = DB::table('jenis_mitra')->where('nama_jenis_mitra', 'Internal / Sistem')->value('id') ?? 1;

        $virtualStoreId = DB::table('stores')->where('store_name', 'Operasional Lapangan')->value('id');
        if (!$virtualStoreId) {
            DB::table('stores')->insert([
                'store_name' => 'Operasional Lapangan',
                'owner_name' => 'Sistem Internal',
                'phone_number' => '00000000',
                'address' => 'Mobile / Virtual',
                'jenis_mitra_id' => $jenisMitraId,
                'qr_token_login' => Str::random(40),
                'qr_token_checkpoint' => Str::random(40),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
