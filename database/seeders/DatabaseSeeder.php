<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

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

        // create 20 products using for loop and faker
        $faker = Faker::create();
        for ($i = 0; $i < 20; $i++) {
            Advertisement::create([
                'title' => $faker->title,
                'description' => $faker->sentence,
                'price' => $faker->randomFloat(2, 1, 100),
                'image_url' => 'https://picsum.photos/' . $faker->numberBetween(500, 800) . '/' . $faker->numberBetween(400, 700),
            ]);
        }
    }
}
