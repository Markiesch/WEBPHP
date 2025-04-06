<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\BusinessReview;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessReviewFactory extends Factory
{
    protected $model = BusinessReview::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'user_id' => User::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'content' => $this->faker->paragraphs(2, true),
        ];
    }

    public function positive()
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => $this->faker->numberBetween(4, 5),
                'content' => $this->faker->sentence(10) . ' ' . $this->faker->randomElement([
                    'Highly recommend!',
                    'Excellent service!',
                    'Would use again!',
                    'Very impressed!',
                    'Great experience overall!'
                ])
            ];
        });
    }

    public function negative()
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => $this->faker->numberBetween(1, 2),
                'content' => $this->faker->sentence(10) . ' ' . $this->faker->randomElement([
                    'Would not recommend.',
                    'Poor service quality.',
                    'Disappointed with the experience.',
                    'Not worth the money.',
                    'Expected much better.'
                ])
            ];
        });
    }
}
