<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // PERHATIAN: Urutan sangat penting!
        // RoleSeeder harus dijalankan lebih dulu karena User butuh role_id
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}