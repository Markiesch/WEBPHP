<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            BusinessSeeder::class,
            BusinessBlockSeeder::class,
            AdvertisementSeeder::class,
            AdvertisementReviewSeeder::class,
            AdvertisementFavoriteSeeder::class,
        ]);
    }
}
