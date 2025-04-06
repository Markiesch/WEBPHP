<?php

namespace Database\Factories;

use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BidFactory extends Factory
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
            'advertisement_id' => Advertisement::factory()->auction(),
            'amount' => $this->faker->randomFloat(2, 50, 2000),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            }
        ];
    }

    /**
     * Configure the bid to be higher than a previous amount.
     *
     * @param float $previousAmount
     * @return $this
     */
    public function higherThan(float $previousAmount)
    {
        return $this->state(function (array $attributes) use ($previousAmount) {
            return [
                'amount' => $previousAmount + $this->faker->randomFloat(2, 1, 100),
            ];
        });
    }
}
