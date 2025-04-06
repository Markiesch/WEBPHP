<?php

namespace Database\Factories;

use App\Models\LandingPage;
use Illuminate\Database\Eloquent\Factories\Factory;

class LandingPageFactory extends Factory
{
    protected $model = LandingPage::class;

    public function definition(): array
    {
        $slugs = ['welcome', 'special-offer', 'promo', 'summer-sale', 'new-collection', 'launch'];

        return [
            'url' => $this->faker->randomElement($slugs) . '-' . $this->faker->slug(2),
        ];
    }
}
