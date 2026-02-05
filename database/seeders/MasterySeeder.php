<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $masteries = ["Beginner", "Intermediate", "Advanced", "Expert"];
        foreach ($masteries as $mastery) {
            \App\Models\Mastery::create([
                "user_id" => 1,
                "name" => $mastery
            ]);
        }
    }
}
