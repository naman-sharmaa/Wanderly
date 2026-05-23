<?php

namespace Database\Factories;

use App\Models\Accommodation;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Accommodation>
 */
class AccommodationFactory extends Factory
{
    protected $model = Accommodation::class;

    private array $hotels = [
        ['name' => 'The Ritz Paris',          'city' => 'Paris',     'country' => 'France'],
        ['name' => 'Hotel Danieli',            'city' => 'Venice',    'country' => 'Italy'],
        ['name' => 'Aman Tokyo',               'city' => 'Tokyo',     'country' => 'Japan'],
        ['name' => 'The Plaza',                'city' => 'New York',  'country' => 'USA'],
        ['name' => 'Burj Al Arab',             'city' => 'Dubai',     'country' => 'UAE'],
        ['name' => 'Four Seasons Bali',        'city' => 'Bali',      'country' => 'Indonesia'],
        ['name' => 'Hotel Arts Barcelona',     'city' => 'Barcelona', 'country' => 'Spain'],
        ['name' => 'Belmond Copacabana Palace','city' => 'Rio',       'country' => 'Brazil'],
        ['name' => 'Taj Palace',               'city' => 'New Delhi', 'country' => 'India'],
        ['name' => 'Eden Rock St Barths',      'city' => 'St Barths', 'country' => 'France'],
    ];

    private array $roomTypes = [
        'Deluxe Room', 'Superior Suite', 'Junior Suite', 'Standard Double',
        'Ocean View Room', 'Penthouse Suite', 'Garden View', 'Executive Room',
    ];

    public function definition(): array
    {
        $hotel    = fake()->randomElement($this->hotels);
        $checkIn  = fake()->dateTimeBetween('now', '+3 months');
        $checkOut = fake()->dateTimeBetween($checkIn, date('Y-m-d', strtotime($checkIn->format('Y-m-d').' +7 days')));

        return [
            'trip_id'           => Trip::factory(),
            'hotel_name'        => $hotel['name'],
            'check_in'          => $checkIn,
            'check_out'         => $checkOut,
            'address'           => fake()->streetAddress(),
            'city'              => $hotel['city'],
            'country'           => $hotel['country'],
            'booking_reference' => strtoupper(fake()->bothify('??######')),
            'room_type'         => fake()->randomElement($this->roomTypes),
            'price_per_night'   => fake()->randomFloat(2, 80, 800),
            'rating'            => fake()->numberBetween(3, 5),
            'notes'             => fake()->optional(0.4)->sentence(),
        ];
    }
}
