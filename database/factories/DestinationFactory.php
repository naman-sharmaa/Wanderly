<?php

namespace Database\Factories;

use App\Models\Destination;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Destination>
 */
class DestinationFactory extends Factory
{
    protected $model = Destination::class;

    private array $destinations = [
        ['name' => 'Eiffel Tower',     'city' => 'Paris',     'country' => 'France'],
        ['name' => 'Colosseum',        'city' => 'Rome',      'country' => 'Italy'],
        ['name' => 'Santorini Island', 'city' => 'Santorini', 'country' => 'Greece'],
        ['name' => 'Burj Khalifa',     'city' => 'Dubai',     'country' => 'UAE'],
        ['name' => 'Ubud',             'city' => 'Bali',      'country' => 'Indonesia'],
        ['name' => 'Times Square',     'city' => 'New York',  'country' => 'USA'],
        ['name' => 'Shibuya',          'city' => 'Tokyo',     'country' => 'Japan'],
        ['name' => 'Machu Picchu',     'city' => 'Cusco',     'country' => 'Peru'],
        ['name' => 'Taj Mahal',        'city' => 'Agra',      'country' => 'India'],
        ['name' => 'Sagrada Familia',  'city' => 'Barcelona', 'country' => 'Spain'],
    ];

    public function definition(): array
    {
        $dest      = fake()->randomElement($this->destinations);
        $arrival   = fake()->dateTimeBetween('now', '+3 months');
        $departure = fake()->dateTimeBetween($arrival, date('Y-m-d', strtotime($arrival->format('Y-m-d').' +7 days')));

        return [
            'trip_id'          => Trip::factory(),
            'destination_name' => $dest['name'],
            'city'             => $dest['city'],
            'country'          => $dest['country'],
            'arrival_date'     => $arrival,
            'departure_date'   => $departure,
            'notes'            => fake()->optional(0.5)->sentence(),
        ];
    }
}
