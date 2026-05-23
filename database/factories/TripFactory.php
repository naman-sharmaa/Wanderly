<?php

namespace Database\Factories;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    protected $model = Trip::class;

    private array $tripTitles = [
        'Summer Adventure in Europe',
        'Tropical Bali Escape',
        'Cherry Blossom Japan Tour',
        'Safari in Kenya',
        'New York City Long Weekend',
        'Mediterranean Cruise',
        'Backpacking Southeast Asia',
        'Romantic Paris Getaway',
        'Road Trip through Patagonia',
        'Cultural Tour of India',
        'Northern Lights in Iceland',
        'Beach Hopping in Greece',
    ];

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now', '+6 months');
        $endDate   = fake()->dateTimeBetween($startDate, date('Y-m-d', strtotime($startDate->format('Y-m-d').' +14 days')));
        $tripType  = fake()->randomElement(['solo', 'couple', 'family', 'group', 'business']);

        $travelers = match($tripType) {
            'solo'     => 1,
            'couple'   => 2,
            'family'   => fake()->numberBetween(3, 5),
            'group'    => fake()->numberBetween(4, 12),
            'business' => fake()->numberBetween(2, 6),
        };

        return [
            'user_id'         => User::factory(),
            'title'           => fake()->randomElement($this->tripTitles),
            'description'     => fake()->optional(0.7)->paragraph(),
            'start_date'      => $startDate,
            'end_date'        => $endDate,
            'budget'          => fake()->optional(0.8)->randomFloat(2, 500, 15000),
            'travelers_count' => $travelers,
            'trip_type'       => $tripType,
            'status'          => fake()->randomElement(['planning', 'planning', 'active', 'completed']),
        ];
    }

    public function planning(): static
    {
        return $this->state(['status' => 'planning']);
    }

    public function upcoming(): static
    {
        return $this->state(function () {
            $start = fake()->dateTimeBetween('+7 days', '+3 months');
            $end   = fake()->dateTimeBetween($start, date('Y-m-d', strtotime($start->format('Y-m-d').' +10 days')));
            return ['start_date' => $start, 'end_date' => $end, 'status' => 'planning'];
        });
    }
}
