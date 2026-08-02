<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisMitraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jenis_mitra')->insert([
            [
                'nama_jenis_mitra' => 'Distributor',
                'deskripsi' => 'Distributor Barang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis_mitra' => 'Toko',
                'deskripsi' => 'Toko Retail',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
