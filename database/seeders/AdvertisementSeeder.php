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

        // Sale advertisements data
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
            [
                'title' => 'iPad Pro 12.9" M2',
                'description' => 'Professionele tablet met 12.9" Liquid Retina XDR display. Perfect voor grafisch ontwerp. 1TB opslag.',
                'price' => 1899.00,
                'wear_percentage' => 8,
            ],
            [
                'title' => 'Sony A7 IV',
                'description' => 'Full-frame 33MP hybride camera. Uitstekend voor foto en video. Inclusief 24-70mm f/2.8 lens.',
                'price' => 2799.00,
                'wear_percentage' => 12,
            ]
        ];

        // Rental advertisements data
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

        // Auction advertisements data
        $auctionProducts = [
            [
                'title' => 'Leica M11 Limited Edition',
                'description' => 'Zeldzame limited edition Leica M11 in perfecte staat. Nummer 47/250. Inclusief originele doos en certificaat.',
                'starting_price' => 70.00,
                'wear_percentage' => 5,
            ],
            [
                'title' => 'Hasselblad 907X Anniversary Edition',
                'description' => 'Speciale 50-jarige maanlanding editie. Nieuw in doos, nooit gebruikt. Collector\'s item.',
                'starting_price' => 90.00,
                'wear_percentage' => 0,
            ],
        ];

        $createdAds = [];

        // Create sale advertisements
        foreach ($saleProducts as $i => $product) {
            $createdAds[] = Advertisement::create([
                'title' => $product['title'],
                'description' => $product['description'],
                'price' => $product['price'],
                'wear_percentage' => $product['wear_percentage'],
                'business_id' => ($i < 2) ? 1 : 2,
                'type' => Advertisement::TYPE_SALE,
                'wear_per_day' => null,
                'image_url' => 'https://picsum.photos/' . $faker->numberBetween(500, 800) . '/' . $faker->numberBetween(400, 700),
                'expiry_date' => '2025-04-25',
            ]);
        }

        // Create rental advertisements
        foreach ($rentalProducts as $i => $product) {
            $createdAds[] = Advertisement::create([
                'title' => $product['title'],
                'description' => $product['description'],
                'price' => $product['price'],
                'wear_percentage' => $product['wear_percentage'],
                'wear_per_day' => $product['wear_per_day'],
                'business_id' => ($i < 2) ? 1 : 2,
                'type' => Advertisement::TYPE_RENTAL,
                'image_url' => 'https://picsum.photos/' . $faker->numberBetween(500, 800) . '/' . $faker->numberBetween(400, 700),
                'rental_start_date' => '2025-04-08',
                'rental_end_date' => '2025-04-25',
                'expiry_date' => '2025-04-25',
            ]);
        }

        // Create auction advertisements
        foreach ($auctionProducts as $i => $product) {
            $createdAds[] = Advertisement::create([
                'title' => $product['title'],
                'description' => $product['description'],
                'price' => $product['starting_price'],
                'current_bid' => null,
                'wear_percentage' => $product['wear_percentage'],
                'business_id' => 1,
                'type' => 'auction',
                'image_url' => 'https://picsum.photos/' . $faker->numberBetween(500, 800) . '/' . $faker->numberBetween(400, 700),
                'auction_end_date' => '2025-04-25 20:00:00',
                'expiry_date' => '2025-04-25',
            ]);
        }

        // Create relationships between advertisements
        $createdAds[0]->relatedAdvertisements()->attach($createdAds[2]);
        $createdAds[2]->relatedAdvertisements()->attach($createdAds[0]);

        $createdAds[4]->relatedAdvertisements()->attach($createdAds[5]);
        $createdAds[5]->relatedAdvertisements()->attach($createdAds[4]);
    }
}
