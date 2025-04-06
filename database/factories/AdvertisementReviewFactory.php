<?php

namespace Database\Factories;

use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertisementReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'advertisement_id' => Advertisement::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->paragraph(),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            }
        ];
    }

    /**
     * Configure the factory to create a positive review.
     *
     * @return $this
     */
    public function positive()
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => $this->faker->numberBetween(4, 5),
                'comment' => $this->faker->randomElement([
                    'Great product, highly recommended!',
                    'Excellent quality and arrived quickly',
                    'Very satisfied with this purchase',
                    'Perfect condition, just as described'
                ])
            ];
        });
    }

    /**
     * Configure the factory to create a negative review.
     *
     * @return $this
     */
    public function negative()
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => $this->faker->numberBetween(1, 2),
                'comment' => $this->faker->randomElement([
                    'Not as described, very disappointed',
                    'Poor quality and overpriced',
                    'Would not recommend this product',
                    'Had issues with this item'
                ])
            ];
        });
    }
}
