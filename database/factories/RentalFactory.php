<?php

namespace Database\Factories;

use App\Models\Advertisement;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RentalFactory extends Factory
{
    protected $model = Rental::class;

    public function definition()
    {
        $rentalStart = $this->faker->dateTimeBetween('-2 months', '+1 month');
        $rentalEnd = clone $rentalStart;
        $rentalEnd->modify('+' . $this->faker->numberBetween(1, 30) . ' days');

        return [
            'user_id' => User::factory(),
            'advertisement_id' => Advertisement::factory(),
            'rental_start' => $rentalStart,
            'rental_end' => $rentalEnd,
            'status' => $this->faker->randomElement(['pending', 'active', 'completed', 'cancelled']),
            'note' => $this->faker->optional(0.6)->paragraph(1),
        ];
    }

    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'active',
                'rental_start' => now()->subDays($this->faker->numberBetween(1, 5)),
                'rental_end' => now()->addDays($this->faker->numberBetween(1, 10)),
            ];
        });
    }

    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
                'rental_start' => now()->subDays($this->faker->numberBetween(10, 30)),
                'rental_end' => now()->subDays($this->faker->numberBetween(1, 9)),
            ];
        });
    }
}
