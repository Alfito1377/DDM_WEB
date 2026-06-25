<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRoleId = DB::table('roles')->where('role_name', 'Admin')->value('id');
        $manajerRoleId = DB::table('roles')->where('role_name', 'Manajer')->value('id');
        $tokoRoleId = DB::table('roles')->where('role_name', 'Toko')->value('id');

        // 1. Buat Toko Dummy Terlebih Dahulu di tabel stores
        $storeId = DB::table('stores')->insertGetId([
            'store_name' => 'Toko Berkah Tani Subang',
            'address' => 'Jl. Raya Subang No. 45',
            'qr_token' => 'token-toko-makmur', // <-- Token yang dicocokkan dengan tombol login tadi
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Buat Pengguna Sistem
        DB::table('users')->insert([
            [
                'role_id' => $adminRoleId,
                'store_id' => null,
                'name' => 'Ani Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('12345678'),
            ],
            [
                'role_id' => $manajerRoleId,
                'store_id' => null,
                'name' => 'Budi Manajer',
                'email' => 'manajer@gmail.com',
                'password' => Hash::make('12345678'),
            ],
            [
                'role_id' => $tokoRoleId,
                'store_id' => $storeId, // <-- Hubungkan user ini dengan toko di atas
                'name' => 'Pemilik Berkah Tani',
                'email' => 'berkahtani@gmail.com', // Tetap punya email untuk jaga-jaga login manual
                'password' => Hash::make('12345678'),
            ]
        ]);
    }
}