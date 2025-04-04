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

        // Sale advertisements
        $saleProducts = [
            [
                'title' => 'Canon EOS 5D Mark IV',
                'description' => 'Professionele DSLR camera in uitstekende staat. Inclusief originele doos en accessoires. Slechts 15.000 clicks.',
                'price' => 1899.99,
                'wear_percentage' => 15,
            ],
            [
                'title' => 'DJI Mavic 3 Pro',
                'description' => 'High-end drone met Hasselblad camera. 1 jaar oud en in perfecte staat. Inclusief extra accu\'s en ND filters.',
                'price' => 1599.00,
                'wear_percentage' => 10,
            ],
            [
                'title' => 'Sony FE 70-200mm f/2.8 GM',
                'description' => 'Professionele telezoom lens. Optisch perfect, minimale gebruikssporen op de behuizing.',
                'price' => 1799.00,
                'wear_percentage' => 20,
            ],
            [
                'title' => 'MacBook Pro 16" M2 Pro',
                'description' => 'Krachtige laptop voor videobewerking. 32GB RAM, 1TB SSD. In nieuwstaat met AppleCare+ tot 2025.',
                'price' => 2499.00,
                'wear_percentage' => 5,
            ],
        ];

        // Rental advertisements
        $rentalProducts = [
            [
                'title' => 'Sony A7S III',
                'description' => 'Perfecte camera voor videoproducties. Beschikbaar voor verhuur inclusief basis lens kit en 3 accu\'s.',
                'price' => 125.00,
                'wear_percentage' => 25,
                'wear_per_day' => 0.2,
            ],
            [
                'title' => 'Aputure 600d Pro',
                'description' => 'Professionele LED studio lamp met Bowens mount. Inclusief lichttent en grid.',
                'price' => 75.00,
                'wear_percentage' => 15,
                'wear_per_day' => 0.1,
            ],
            [
                'title' => 'DJI Ronin 4D-6K',
                'description' => 'Complete cinema camera met geïntegreerde gimbal. Perfect voor professionele filmproducties.',
                'price' => 250.00,
                'wear_percentage' => 20,
                'wear_per_day' => 0.3,
            ],
            [
                'title' => 'RED Komodo 6K',
                'description' => 'Compacte cinema camera. Komt met V-mount adapter, monitor en 480GB RED SSD.',
                'price' => 300.00,
                'wear_percentage' => 30,
                'wear_per_day' => 0.4,
            ],
        ];

        // Create sale advertisements
        for ($i = 0; $i < 4; $i++) {
            Advertisement::create([
                'title' => $saleProducts[$i]['title'],
                'description' => $saleProducts[$i]['description'],
                'price' => $saleProducts[$i]['price'],
                'wear_percentage' => $saleProducts[$i]['wear_percentage'],
                'business_id' => ($i < 2) ? 1 : 2,
                'type' => Advertisement::TYPE_SALE,
                'wear_per_day' => null,
                'image_url' => 'https://picsum.photos/' . $faker->numberBetween(500, 800) . '/' . $faker->numberBetween(400, 700),
                'expiry_date' => '2025-04-25',
            ]);
        }

        // Create rental advertisements
        for ($i = 0; $i < 4; $i++) {
            Advertisement::create([
                'title' => $rentalProducts[$i]['title'],
                'description' => $rentalProducts[$i]['description'],
                'price' => $rentalProducts[$i]['price'],
                'wear_percentage' => $rentalProducts[$i]['wear_percentage'],
                'wear_per_day' => $rentalProducts[$i]['wear_per_day'],
                'business_id' => ($i < 2) ? 1 : 2,
                'type' => Advertisement::TYPE_RENTAL,
                'image_url' => 'https://picsum.photos/' . $faker->numberBetween(500, 800) . '/' . $faker->numberBetween(400, 700),
                'rental_start_date' => '2025-04-08',
                'rental_end_date' => '2025-04-25',
                'expiry_date' => '2025-04-25',
            ]);
        }
    }
}
