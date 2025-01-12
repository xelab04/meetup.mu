<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class MeetupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "type" => fake()->name(),
            "community" => fake()->name(),
            "title" => fake()->text(),
            "abstract" => fake()->paragraph(),
            "location" => fake()->company(),
            "registration" => fake()->url(),
            "date" => fake()->date(),
        ];
    }
}
