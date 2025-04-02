<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\AdvertisementFavorite;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdvertisementFavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $advertisements = Advertisement::all();

        // Create random reviews
        foreach ($advertisements as $advertisement) {
            $reviewers = $users->random($users->count());

            foreach ($reviewers as $user) {
                AdvertisementFavorite::create([
                    'user_id' => $user->id,
                    'advertisement_id' => $advertisement->id,
                ]);
            }
        }
    }
}
