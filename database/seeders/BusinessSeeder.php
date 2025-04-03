<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    public function run(): void
    {
        // get all users with role private_advertiser and business_advertiser
        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['private_advertiser', 'business_advertiser']);
        })->get();

        $faker = Factory::create();

        foreach ($users as $user) {
            $user->business()->create([
                'user_id' => $user->id,
                'url' => $faker->domainWord(),
            ]);
        }
    }
}
