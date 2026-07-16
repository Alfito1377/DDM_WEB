<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['role_name' => 'superadmin'],
            ['role_name' => 'admin']
        ];

        DB::table('roles')->insert($roles);
    }
}