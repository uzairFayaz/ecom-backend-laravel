<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRolesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user_roles')->insert([
            ['role_name' => 'user', 'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
