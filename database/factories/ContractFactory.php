<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractFactory extends Factory
{
    protected $model = Contract::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'description' => $this->faker->sentence(),
            'file_path' => 'contracts/' . $this->faker->uuid() . '.pdf',
            'status' => Contract::STATUSES['pending'],
            'feedback' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ];
    }

    public function approved()
    {
        return $this->state(function (array $attributes) {
            $reviewer = User::factory()->create(['role' => 'admin']);

            return [
                'status' => Contract::STATUSES['approved'],
                'feedback' => $this->faker->optional(0.7)->paragraph(),
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            ];
        });
    }

    public function rejected()
    {
        return $this->state(function (array $attributes) {
            $reviewer = User::factory()->create(['role' => 'admin']);

            return [
                'status' => Contract::STATUSES['rejected'],
                'feedback' => $this->faker->paragraph(),
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            ];
        });
    }
}
