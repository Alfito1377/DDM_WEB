<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRoleId = DB::table('roles')->where('role_name', 'superadmin')->value('id');
        $manajerRoleId = DB::table('roles')->where('role_name', 'admin')->value('id');

        // 2. Buat Pengguna Sistem
        DB::table('users')->insertOrIgnore([
            [
                'role_id' => $adminRoleId,
                'store_id' => null,
                'name' => 'Super Admin',
                'email' => 'ddmsagemashlahat@gmail.com',
                'password' => Hash::make('admin1234'),
            ],
            // [
            //     'role_id' => $adminRoleId,
            //     'store_id' => null,
            //     'name' => 'Super Admin',
            //     'email' => 'muhammadyuuya888@gmail.com',
            //     'password' => Hash::make('admin1234'),
            // ],
            // [
            //     'role_id' => $manajerRoleId,
            //     'store_id' => null,
            //     'name' => 'Admin',
            //     'email' => 'admin@gmail.com',
            //     'password' => Hash::make('admin1234'),
            // ],
        ]);

        // 3. Buat User untuk Pekerja Lapang (Akun Dummy)
        $pekerjaLapangRoleId = DB::table('roles')->where('role_name', 'pekerja_lapang')->value('id');
        $virtualStoreId = DB::table('stores')->where('store_name', 'Operasional Lapangan')->value('id');

        $existUser = DB::table('users')->where('email', 'lapangan@gmail.com')->first();
        if (!$existUser && $pekerjaLapangRoleId && $virtualStoreId) {
            DB::table('users')->insert([
                'role_id' => $pekerjaLapangRoleId,
                'store_id' => $virtualStoreId,
                'name' => 'Pekerja Lapang 1',
                'email' => 'lapangan@gmail.com',
                'password' => Hash::make('password123'),
            ]);
        }
    }
}