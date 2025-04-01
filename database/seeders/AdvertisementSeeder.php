<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class AdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        for ($i = 0; $i < 20; $i++) {
            Advertisement::create([
                'title' => $faker->words(3, true),
                'description' => $faker->sentence,
                'price' => $faker->randomFloat(0, 200, 2000),
                'user_id' => 1,
                'image_url' => 'https://picsum.photos/' . $faker->numberBetween(500, 800) . '/' . $faker->numberBetween(400, 700),
            ]);
        }

        Advertisement::create([
            'title' => 'Modern Office Space',
            'description' => 'Bright and spacious office space in the city center. Perfect for small businesses.',
            'price' => 1250.00,
            'image_url' => 'storage/images/office.jpg',
            'rental_start_date' => '2024-04-01',
            'rental_end_date' => '2024-09-30',
            'expiry_date' => '2024-03-31',
            'user_id' => 1,
        ]);

        Advertisement::create([
            'title' => 'Retail Shop Location',
            'description' => 'Prime retail location with high foot traffic. Recently renovated with modern facilities.',
            'price' => 2500.00,
            'image_url' => 'storage/images/office.jpg',
            'rental_start_date' => '2024-05-01',
            'rental_end_date' => '2025-04-30',
            'expiry_date' => '2024-04-15',
            'user_id' => 1,
        ]);

        Advertisement::create([
            'title' => 'Storage Unit',
            'description' => 'Secure storage unit with 24/7 access. Climate controlled environment.',
            'price' => 450.00,
            'image_url' => 'storage/images/office.jpg',
            'rental_start_date' => '2024-04-15',
            'rental_end_date' => '2024-07-15',
            'expiry_date' => '2024-04-10',
            'user_id' => 1,
        ]);
    }
}
