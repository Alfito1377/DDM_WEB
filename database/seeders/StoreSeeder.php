<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stores')->insert([
            [
                'store_name' => 'Toko A',
                'owner_name' => 'Owner A',
                'phone_number' => '081234567890',
                'address' => 'Jl. Contoh Alamat No. 1',
                'jenis_mitra_id' => 2,
                'qr_token_login' => Str::random(40),
                'qr_token_checkpoint' => Str::random(40),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_name' => 'Toko B',
                'owner_name' => 'Owner B',
                'phone_number' => '081234567891',
                'address' => 'Jl. Contoh Alamat No. 2',
                'jenis_mitra_id' => 2,
                'qr_token_login' => Str::random(40),
                'qr_token_checkpoint' => Str::random(40),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_name' => 'Distributor A',
                'owner_name' => 'Owner A',
                'phone_number' => '081234567890',
                'address' => 'Jl. Contoh Alamat No. 1',
                'jenis_mitra_id' => 1,
                'qr_token_login' => Str::random(40),
                'qr_token_checkpoint' => Str::random(40),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_name' => 'Distributor B',
                'owner_name' => 'Owner B',
                'phone_number' => '081234567891',
                'address' => 'Jl. Contoh Alamat No. 2',
                'jenis_mitra_id' => 1,
                'qr_token_login' => Str::random(40),
                'qr_token_checkpoint' => Str::random(40),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
