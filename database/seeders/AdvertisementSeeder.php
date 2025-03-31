<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use Illuminate\Database\Seeder;

class AdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        Advertisement::create([
            'title' => 'Modern Office Space',
            'description' => 'Bright and spacious office space in the city center. Perfect for small businesses.',
            'price' => 1250.00,
            'image_url' => 'storage/images/office.jpg',
            'rental_start_date' => '2024-04-01',
            'rental_end_date' => '2024-09-30',
            'expiry_date' => '2024-03-31',
        ]);

        Advertisement::create([
            'title' => 'Retail Shop Location',
            'description' => 'Prime retail location with high foot traffic. Recently renovated with modern facilities.',
            'price' => 2500.00,
            'image_url' => 'storage/images/office.jpg',
            'rental_start_date' => '2024-05-01',
            'rental_end_date' => '2025-04-30',
            'expiry_date' => '2024-04-15',
        ]);

        Advertisement::create([
            'title' => 'Storage Unit',
            'description' => 'Secure storage unit with 24/7 access. Climate controlled environment.',
            'price' => 450.00,
            'image_url' => 'storage/images/office.jpg',
            'rental_start_date' => '2024-04-15',
            'rental_end_date' => '2024-07-15',
            'expiry_date' => '2024-04-10',
        ]);
    }
}
