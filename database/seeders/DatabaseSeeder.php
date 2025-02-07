<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Role creation
        $roles = [
            'admin',
            'user',
            'private_advertiser',
            'business_advertiser'
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        User::factory()->create([
            'name' => 'Koray Yilmaz',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'user@example.com',
            'role' => 'user',
        ]);
        User::factory()->create([
            'name' => 'Maarten Pal',
            'email' => 'private_advertiser@example.com',
            'role' => 'private_advertiser',
        ]);
        User::factory()->create([
            'name' => 'Bas Pruim',
            'email' => 'business_advertiser@example.com',
            'role' => 'business_advertiser',
        ]);
    }
}
