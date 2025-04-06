<?php

namespace Database\Factories;

use App\Models\Advertisement;
use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertisementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(3),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'wear_percentage' => $this->faker->numberBetween(0, 100),
            'wear_per_day' => $this->faker->randomFloat(2, 0, 5),
            'image_url' => $this->faker->imageUrl(640, 480),
            'business_id' => Business::factory(),
            'type' => $this->faker->randomElement([
                Advertisement::TYPE_SALE,
                Advertisement::TYPE_RENTAL,
                Advertisement::TYPE_AUCTION
            ]),
            'expiry_date' => $this->faker->dateTimeBetween('now', '+30 days'),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            }
        ];
    }

    /**
     * Configure the model factory for sale type advertisements.
     *
     * @return $this
     */
    public function sale()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Advertisement::TYPE_SALE,
            ];
        });
    }

    /**
     * Configure the model factory for rental type advertisements.
     *
     * @return $this
     */
    public function rental()
    {
        return $this->state(function (array $attributes) {
            $startDate = $this->faker->dateTimeBetween('now', '+15 days');
            $endDate = $this->faker->dateTimeBetween($startDate, '+30 days');

            return [
                'type' => Advertisement::TYPE_RENTAL,
                'rental_start_date' => $startDate,
                'rental_end_date' => $endDate,
            ];
        });
    }

    /**
     * Configure the model factory for auction type advertisements.
     *
     * @return $this
     */
    public function auction()
    {
        return $this->state(function (array $attributes) {
            $startingPrice = $this->faker->randomFloat(2, 10, 500);

            return [
                'type' => Advertisement::TYPE_AUCTION,
                'starting_price' => $startingPrice,
                'current_bid' => $startingPrice,
                'auction_end_date' => $this->faker->dateTimeBetween('+1 day', '+30 days'),
            ];
        });
    }

    /**
     * Configure the model factory for expired advertisements.
     *
     * @return $this
     */
    public function expired()
    {
        return $this->state(function (array $attributes) {
            if ($attributes['type'] === Advertisement::TYPE_AUCTION) {
                return [
                    'auction_end_date' => $this->faker->dateTimeBetween('-30 days', '-1 day'),
                ];
            }

            return [
                'expiry_date' => $this->faker->dateTimeBetween('-30 days', '-1 day'),
            ];
        });
    }
}
