<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\AdvertisementReview;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class AdvertisementReviewSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $users = User::all();
        $advertisements = Advertisement::all();

        // Create random reviews
        foreach ($advertisements as $advertisement) {
            // Generate 0-5 reviews per advertisement
            $reviewCount = rand(0, 5);

            // Randomly select reviewers without duplication
            $reviewers = $users->random(min($reviewCount, $users->count()));

            foreach ($reviewers as $user) {
                AdvertisementReview::create([
                    'user_id' => $user->id,
                    'advertisement_id' => $advertisement->id,
                    'rating' => $faker->numberBetween(1, 5),
                    'comment' => $faker->paragraph(2),
                ]);
            }
        }
    }
}
