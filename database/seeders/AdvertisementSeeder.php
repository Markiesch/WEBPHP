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
        for ($i = 0; $i < 2; $i++) {
            for ($b = 1; $b < 3; $b++) {
                Advertisement::create([
                    'title' => $faker->words(3, true),
                    'description' => $faker->paragraph(2),
                    'price' => $faker->randomFloat(2, 200, 2000),
                    'wear_percentage' => $faker->numberBetween(0, 100),
                    'business_id' => $b,
                    'type' => Advertisement::TYPE_SALE,
                    'wear_per_day' => null,
                    'image_url' => 'https://picsum.photos/' . $faker->numberBetween(500, 800) . '/' . $faker->numberBetween(400, 700),
                    'expiry_date' => '2025-04-25',
                ]);
            }
        }

        // Create 4 rental advertisements
        for ($i = 0; $i < 2; $i++) {
            for ($b = 1; $b < 3; $b++) {
                Advertisement::create([
                    'title' => $faker->words(3, true),
                    'description' => $faker->paragraph(2),
                    'price' => $faker->randomFloat(2, 50, 500),
                    'wear_percentage' => $faker->numberBetween(0, 100),
                    'wear_per_day' => $faker->randomFloat(2, 0.1, 2.0),
                    'business_id' => $b,
                    'type' => Advertisement::TYPE_RENTAL,
                    'image_url' => 'https://picsum.photos/' . $faker->numberBetween(500, 800) . '/' . $faker->numberBetween(400, 700),
                    'rental_start_date' => '2025-04-08',
                    'rental_end_date' => '2025-04-25',
                    'expiry_date' => '2025-04-25',
                ]);
            }
        }
    }
}
