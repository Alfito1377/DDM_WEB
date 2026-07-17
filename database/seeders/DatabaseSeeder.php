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
            StoreSeeder::class,
            UserSeeder::class,
            LogisticSeeder::class,
        ]);
    }
}