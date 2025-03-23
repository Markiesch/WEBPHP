<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $roles = ['Admin', 'Editor', 'Viewer'];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Create users and assign roles
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' => Role::where('name', 'Admin')->first()->id,
        ]);
    }
}
