<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessBlock;
use Illuminate\Database\Seeder;

class BusinessBlockSeeder extends Seeder
{
    public function run(): void
    {
        // Get all business IDs
        $businesses = Business::all();

        foreach ($businesses as $business) {
            // Intro text block
            BusinessBlock::create([
                'business_id' => $business->id,
                'type' => 'intro_text',
                'content' => [
                    'title' => 'Welcome to ' . $business->name,
                    'text' => '<p>We are dedicated to providing the best products and services to our customers. With years of experience in the industry, we have built a reputation for quality and reliability.</p><p>Browse our listings below to find what you need!</p>'
                ],
                'order' => 1,
            ]);

            // Featured ads block
            BusinessBlock::create([
                'business_id' => $business->id,
                'type' => 'featured_ads',
                'content' => [
                    'title' => 'Our Featured Listings',
                    'count' => 3
                ],
                'order' => 2,
            ]);

            // Image block
            BusinessBlock::create([
                'business_id' => $business->id,
                'type' => 'image',
                'content' => [
                    'title' => 'Our Location',
                    'url' => 'https://picsum.photos/800/400',
                    'alt' => 'Our business location',
                    'caption' => 'Our main office located in the city center',
                    'fullWidth' => false
                ],
                'order' => 3,
            ]);
        }
    }
}
