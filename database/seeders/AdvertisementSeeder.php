<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\Business;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class AdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $userId = 3; // business_advertiser ID

        $business = Business::where('user_id', $userId)->first();

        // Create 4 sale advertisements
        for ($i = 0; $i < 4; $i++) {
            Advertisement::create([
                'title' => $faker->words(3, true),
                'description' => $faker->paragraph(2),
                'price' => $faker->randomFloat(2, 200, 2000),
                'wear_percentage' => $faker->numberBetween(0, 100),
                'user_id' => $userId,
                'business_id' => $business->id,
                'type' => Advertisement::TYPE_SALE,
                'image_url' => 'https://picsum.photos/800/600',
                'expiry_date' => now()->addMonths(3),
            ]);
        }

        // Create 3 rental advertisements
        for ($i = 0; $i < 3; $i++) {
            Advertisement::create([
                'title' => $faker->words(3, true),
                'description' => $faker->paragraph(2),
                'price' => $faker->randomFloat(2, 50, 500),
                'wear_percentage' => $faker->numberBetween(0, 100),
                'user_id' => $userId,
                'business_id' => $business->id,
                'type' => Advertisement::TYPE_RENTAL,
                'image_url' => 'https://picsum.photos/800/600',
                'rental_start_date' => now()->addDays($faker->numberBetween(1, 30)),
                'rental_end_date' => now()->addMonths($faker->numberBetween(2, 6)),
                'expiry_date' => now()->addMonths(1),
            ]);
        }
    }
}
