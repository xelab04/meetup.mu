<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class MeetupFactory extends Factory
{
    public function definition(): array
    {
        $community = fake()->randomElement(array_keys(config('communities.list')));

        return [
            "type" => fake()->randomElement(['meetup', 'workshop', 'talk', 'conference']),
            "community" => $community,
            "title" => $this->titleFor($community),
            // Most events on meetup.mu only have a title — abstracts are the exception.
            "abstract" => fake()->boolean(10) ? fake()->paragraph() : null,
            // Venues appear on roughly a quarter of cards in production.
            "location" => fake()->boolean(25) ? fake()->randomElement($this->venues()) : null,
            "registration" => fake()->boolean(65) ? fake()->url() : null,
            "date" => fake()->dateTimeBetween('-14 months', '+2 months')->format('Y-m-d'),
        ];
    }

    private function titleFor(string $community): string
    {
        $month = fake()->monthName();

        $templates = [
            'pymug' => [
                "Python Meetup {$month}",
                "PYMUG {$month} Meetup",
                "PyMUG Workshop",
            ],
            'cloudnativemu' => [
                "Cloud Native Mauritius {$month} Meetup",
                "Cloud Native Hacktoberfest",
                "Cloud Native Community Day",
                "CNCF {$month} Meetup",
            ],
            'frontendmu' => [
                "FrontendMU The {$month} Meetup",
                "Frontend MU {$month}",
                "FrontendMU Workshop",
            ],
            'mscc' => [
                "MSCC Monthly Meetup",
                "MSCC Developers Conference",
                "MSCC {$month} Gathering",
            ],
            'laravelmoris' => [
                "LaravelMoris {$month}",
                "Laravel Mauritius Meetup",
                "LaravelMoris Tinker Night",
            ],
            'nugm' => [
                ".NET User Group {$month} Meetup",
                "NUGM {$month} Session",
                "Azure & .NET Meetup",
            ],
            'gophersmu' => [
                "Gophers MU {$month} Meetup",
                "Go Mauritius Monthly",
                "Gophers {$month}",
            ],
            'mobilehorizon' => [
                "Mobile Horizon {$month}",
                "Mobile Horizon Meetup",
                "iOS & Android Night",
            ],
            'dodocore' => [
                "DodoCore Systems Night",
                "DodoCore Low-level Meetup",
                "DodoCore {$month}",
            ],
            'pydata' => [
                "PyData MU {$month}",
                "PyData Mauritius",
                "PyData Meetup",
            ],
            'standalone' => [
                "Tech Mauritius {$month} Gathering",
                "Community Tech Night",
            ],
        ];

        return fake()->randomElement($templates[$community] ?? $templates['standalone']);
    }

    private function venues(): array
    {
        return [
            'Astek Mauritius',
            'Ebene Cybercity',
            'Flying Dodo',
            'Voilà Bagatelle',
            'La Turbine',
            'Accenture Ebene',
            'SIDEC',
            'The Pier',
            'Turbine Incubator',
        ];
    }
}
