<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Advertisement;
use Faker\Factory as Faker;

class AdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Create 4 sale advertisements
        for ($i = 0; $i < 4; $i++) {
            Advertisement::create([
                'title' => $faker->words(3, true),
                'description' => $faker->paragraph(2),
                'price' => $faker->randomFloat(2, 200, 2000),
                'wear_percentage' => $faker->numberBetween(0, 100),
                'business_id' => 1,
                'type' => Advertisement::TYPE_SALE,
                'image_url' => 'https://picsum.photos/' . $faker->numberBetween(500, 800) . '/' . $faker->numberBetween(400, 700),
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
                'business_id' => 1,
                'type' => Advertisement::TYPE_RENTAL,
                'image_url' => 'https://picsum.photos/' . $faker->numberBetween(500, 800) . '/' . $faker->numberBetween(400, 700),
                'rental_start_date' => now()->addDays($faker->numberBetween(1, 30)),
                'rental_end_date' => now()->addMonths($faker->numberBetween(2, 6)),
                'expiry_date' => now()->addMonths(1),
            ]);
        }
    }
}
