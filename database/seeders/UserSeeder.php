<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        // Create regular user
        $user = new User();
        $user->name = 'Pieter';
        $user->email = 'Pieter@bazaar.nl';
        $user->password = $password;
        $user->save();
        $user->assignRole('user');

        $user = new User();
        $user->name = 'Jantje';
        $user->email = 'Jantje@bazaar.nl';
        $user->password = $password;
        $user->save();
        $user->assignRole('user');

        $user = new User();
        $user->name = 'Peter';
        $user->email = 'Peter@bazaar.nl';
        $user->password = $password;
        $user->save();
        $user->assignRole('user');

        $user = new User();
        $user->name = 'Pepijn';
        $user->email = 'Pepijn@bazaar.nl';
        $user->password = $password;
        $user->save();
        $user->assignRole('user');

        $user = new User();
        $user->name = 'Linda';
        $user->email = 'Linda@bazaar.nl';
        $user->password = $password;
        $user->save();
        $user->assignRole('user');


        // Create private advertiser
        $privateAdvertiser = new User();
        $privateAdvertiser->name = 'Private advertiser';
        $privateAdvertiser->email = 'private_advertiser@bazaar.nl';
        $privateAdvertiser->password = $password;
        $privateAdvertiser->save();
        $privateAdvertiser->assignRole('private_advertiser');


        // Create business advertiser
        $businessAdvertiser = new User();
        $businessAdvertiser->name = 'Business advertiser';
        $businessAdvertiser->email = 'business_advertiser@bazaar.nl';
        $businessAdvertiser->password = $password;
        $businessAdvertiser->save();
        $businessAdvertiser->assignRole('business_advertiser');


        // Create super admin user
        $superAdmin = new User();
        $superAdmin->name = 'Super Admin';
        $superAdmin->email = 'admin@bazaar.nl';
        $superAdmin->password = $password;
        $superAdmin->save();
        $superAdmin->assignRole('super_admin');
    }
}
