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

        if (! User::where("email", "test@example.com")->exists()) {
            User::factory()->create([
                "name" => "Test User",
                "email" => "test@example.com",
            ]);
        }
    }
}
