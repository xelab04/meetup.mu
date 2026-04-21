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
            "type" => fake()->randomElement(['meetup', 'workshop', 'talk', 'conference']),
            "community" => fake()->randomElement(array_keys(config('communities.list'))),
            "title" => fake()->text(),
            "abstract" => fake()->paragraph(),
            "location" => fake()->company(),
            "registration" => fake()->url(),
            "date" => fake()->dateTimeBetween('-14 months', '+2 months')->format('Y-m-d'),
        ];
    }
}
