<?php

namespace Database\Factories;

use App\Models\Advertisement;
use App\Models\RentalCalendar;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RentalCalendarFactory extends Factory
{
    protected $model = RentalCalendar::class;

    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', '+2 months');
        $endDate = clone $startDate;
        $endDate->modify('+' . $this->faker->numberBetween(1, 14) . ' days');

        return [
            'user_id' => User::factory(),
            'advertisement_id' => Advertisement::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled', 'completed']),
            'note' => $this->faker->optional(0.7)->sentence(),
        ];
    }

    public function confirmed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'confirmed',
            ];
        });
    }

    public function cancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'cancelled',
            ];
        });
    }

    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
            ];
        });
    }
}
