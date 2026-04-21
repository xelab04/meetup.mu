<?php

namespace Database\Seeders;

use App\Models\Meetup;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        //
        Meetup::factory(14)
            ->state(fn () => ['date' => fake()->dateTimeBetween('-14 months', 'now')->format('Y-m-d')])
            ->create();

        Meetup::factory(3)
            ->state(fn () => ['date' => fake()->dateTimeBetween('now', '+2 months')->format('Y-m-d')])
            ->create();

        Meetup::factory()->create(['date' => now()->format('Y-m-d')]);

        // A few events without a registration URL — their title click routes
        // to the internal /meetup/{id} detail page instead of an external form.
        Meetup::factory(2)
            ->state(fn () => [
                'registration' => null,
                'date' => fake()->dateTimeBetween('now', '+2 months')->format('Y-m-d'),
            ])
            ->create();

        User::factory()->create([
            "name" => "Test User",
            "email" => "test@example.com",
        ]);
    }
}
