<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LearningFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'group_id' => null,
            'mastery_id' => null,
            'name' => fake()->word(),
            'description' => fake()->sentence(),
        ];
    }
}
