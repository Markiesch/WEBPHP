<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        // Create random roles if needed
        return [
            'name' => $this->faker->unique()->randomElement([
                'admin', 'moderator', 'user', 'vendor', 'guest'
            ]),
            'description' => $this->faker->sentence(),
        ];
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'admin',
                'description' => 'Administrator with full privileges',
            ];
        });
    }

    public function user()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'user',
                'description' => 'Regular user',
            ];
        });
    }

    public function vendor()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'vendor',
                'description' => 'Seller/Renter with ability to create listings',
            ];
        });
    }
}
