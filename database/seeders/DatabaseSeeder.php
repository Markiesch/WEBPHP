<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'owner']);
        Role::create(['name' => 'private_advertiser']);
        Role::create(['name' => 'business_advertiser']);


        $user = new User();
        $user->name = 'Admin';
        $user->email = 'admin@bazaar.nl';
        $user->password = '$2y$12$RRFILOFFad.VuxS44qX7I.mUJxb1cqlO8exnjs9oqXRGpZi0XIqJW';
        $user->save();
        $user->assignRole('admin');
//        // Role creation
//        $roles = [
//            'admin',
//            'user',
//            'private_advertiser',
//            'business_advertiser'
//        ];
//
//        foreach ($roles as $role) {
//            Role::create(['name' => $role]);
//        }

//        User::factory()->create([
//            'name' => 'Koray Yilmaz',
//            'email' => 'admin@example.com',
//            'role' => 'admin',
//        ]);
//        User::factory()->create([
//            'name' => 'John Doe',
//            'email' => 'user@example.com',
//            'role' => 'user',
//        ]);
//        User::factory()->create([
//            'name' => 'Maarten Pal',
//            'email' => 'private_advertiser@example.com',
//            'role' => 'private_advertiser',
//        ]);
//        User::factory()->create([
//            'name' => 'Bas Pruim',
//            'email' => 'business_advertiser@example.com',
//            'role' => 'business_advertiser',
//        ]);
    }
}
