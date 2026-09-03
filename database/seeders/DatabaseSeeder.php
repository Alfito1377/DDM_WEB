<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
 
        $this->call([
            RoleSeeder::class,
            JenisMitraSeeder::class,
            SystemStoreSeeder::class,
            StoreSeeder::class,
            UserSeeder::class,
            MassDataSeeder::class,
        ]);
    }
}