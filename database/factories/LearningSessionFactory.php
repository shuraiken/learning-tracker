<?php

namespace Database\Factories;

use App\Models\Learning;
use App\Enums\LearningSessionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class LearningSessionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'learning_id' => Learning::factory(),
            'name' => null,
            'started_at' => now(),
            'ended_at' => null,
            'status' => LearningSessionStatus::ACTIVE->value,
            'total_duration' => 0,
            'note' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LearningSessionStatus::COMPLETED->value,
            'ended_at' => now(),
        ]);
    }
}
