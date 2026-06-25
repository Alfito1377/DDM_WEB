<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['role_name' => 'Admin'],
            ['role_name' => 'Manajer'],
            ['role_name' => 'Sales'],
            ['role_name' => 'Toko'],
        ];

        DB::table('roles')->insert($roles);
    }
}