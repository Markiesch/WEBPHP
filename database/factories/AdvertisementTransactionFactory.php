<?php

namespace Database\Factories;

use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertisementTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $advertisement = Advertisement::factory()->create();
        $type = $advertisement->type === Advertisement::TYPE_RENTAL ? 'rental' : 'purchase';
        $status = $type === 'rental' ? 'active' : 'sold';

        return [
            'user_id' => User::factory(),
            'advertisement_id' => $advertisement->id,
            'price' => $advertisement->price ?? $advertisement->current_bid ?? $this->faker->randomFloat(2, 10, 1000),
            'type' => $type,
            'status' => $status,
            'returned' => false,
            'rental_days' => $type === 'rental' ? $this->faker->numberBetween(1, 30) : null,
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            }
        ];
    }

    /**
     * Configure the factory for purchase transactions.
     *
     * @return $this
     */
    public function purchase()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'purchase',
                'status' => 'sold',
                'rental_days' => null,
            ];
        });
    }

    /**
     * Configure the factory for rental transactions.
     *
     * @return $this
     */
    public function rental()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'rental',
                'status' => 'active',
                'rental_days' => $this->faker->numberBetween(1, 30),
            ];
        });
    }

    /**
     * Configure the factory for returned rentals.
     *
     * @return $this
     */
    public function returned()
    {
        return $this->state(function (array $attributes) {
            $returnDate = $this->faker->dateTimeBetween($attributes['created_at'], 'now');

            return [
                'type' => 'rental',
                'status' => 'returned',
                'returned' => true,
                'return_date' => $returnDate,
                'return_photo' => $this->faker->imageUrl(640, 480),
                'calculated_wear' => $this->faker->randomFloat(2, 0, 20),
            ];
        });
    }

    /**
     * Configure the factory for auction transactions.
     *
     * @return $this
     */
    public function auction()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'auction',
                'status' => 'sold',
            ];
        });
    }
}
