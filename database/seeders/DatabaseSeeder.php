<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $this->call([
            AdvertisementSeeder::class
        ]);
        // Create roles if they don't exist
        $roles = ['user', 'admin', 'owner', 'private_advertiser', 'business_advertiser'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Create admin user
        $admin = new User();
        $admin->name = 'Admin';
        $admin->email = 'admin@bazaar.nl';
        $admin->password = Hash::make('password');
        $admin->save();
        $admin->assignRole('admin');

        // Create regular user
        $user = new User();
        $user->name = 'Koray';
        $user->email = 'user@bazaar.nl';
        $user->password = Hash::make('plasplas');
        $user->save();
        $user->assignRole('user');


    }
}
