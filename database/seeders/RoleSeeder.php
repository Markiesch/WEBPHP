<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'user']);
        Role::create(['name' => 'private_advertiser']);
        Role::create(['name' => 'business_advertiser']);
        Role::create(['name' => 'super_admin']);
    }
}
