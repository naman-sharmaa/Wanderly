<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    private array $activities = [
        ['name' => 'Visit the Eiffel Tower',        'category' => 'sightseeing', 'cost' => 25,  'duration' => 120],
        ['name' => 'Sunset Cruise on the Seine',    'category' => 'adventure',   'cost' => 45,  'duration' => 90],
        ['name' => 'Michelin Star Dinner',          'category' => 'food',        'cost' => 180, 'duration' => 150],
        ['name' => 'Louvre Museum Tour',            'category' => 'culture',     'cost' => 20,  'duration' => 180],
        ['name' => 'Cooking Class',                 'category' => 'culture',     'cost' => 75,  'duration' => 180],
        ['name' => 'Snorkeling Trip',               'category' => 'adventure',   'cost' => 60,  'duration' => 240],
        ['name' => 'Night Safari',                  'category' => 'adventure',   'cost' => 80,  'duration' => 180],
        ['name' => 'Street Food Tour',              'category' => 'food',        'cost' => 35,  'duration' => 120],
        ['name' => 'Local Market Shopping',         'category' => 'shopping',    'cost' => 50,  'duration' => 90],
        ['name' => 'Yoga at Sunrise',               'category' => 'other',       'cost' => 20,  'duration' => 60],
        ['name' => 'Spa & Wellness Day',            'category' => 'other',       'cost' => 150, 'duration' => 300],
        ['name' => 'Museum of Modern Art',          'category' => 'culture',     'cost' => 25,  'duration' => 120],
        ['name' => 'Helicopter City Tour',          'category' => 'adventure',   'cost' => 250, 'duration' => 60],
        ['name' => 'Wine Tasting Experience',       'category' => 'food',        'cost' => 65,  'duration' => 150],
        ['name' => 'Train to the Countryside',      'category' => 'transport',   'cost' => 40,  'duration' => 240],
        ['name' => 'Beach Day at Sunset Cove',      'category' => 'beach',       'cost' => 0,   'duration' => 360],
        ['name' => 'Rooftop Bar Night Out',         'category' => 'nightlife',   'cost' => 80,  'duration' => 180],
        ['name' => 'Guided Heritage Walk',          'category' => 'culture',     'cost' => 30,  'duration' => 120],
    ];

    public function definition(): array
    {
        $activity = fake()->randomElement($this->activities);
        $date     = fake()->dateTimeBetween('now', '+3 months');

        return [
            'trip_id'           => Trip::factory(),
            'activity_name'     => $activity['name'],
            'activity_date'     => $date,
            'activity_time'     => fake()->optional(0.7)->time('H:i'),
            'category'          => $activity['category'],
            'description'       => fake()->optional(0.6)->sentence(),
            'location'          => fake()->optional(0.7)->city(),
            'cost'              => $activity['cost'],
            'duration_minutes'  => $activity['duration'],
            'booking_required'  => fake()->boolean(30),
            'booking_reference' => fake()->optional(0.3)->bothify('BK#######'),
            'notes'             => fake()->optional(0.3)->sentence(),
        ];
    }
}
