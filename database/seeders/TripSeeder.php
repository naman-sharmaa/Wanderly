<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Trip;
use App\Models\Destination;
use App\Models\Accommodation;
use App\Models\Activity;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

/**
 * TripSeeder
 *
 * Creates rich, realistic trip data for the demo user including:
 * - 3 hand-crafted trips with full itineraries
 * - 2 randomly generated trips
 */
class TripSeeder extends Seeder
{
    public function run(): void
    {
        $demo = User::where('email', 'demo@wanderly.app')->first();

        if (!$demo) {
            $this->command->warn('Demo user not found. Run UserSeeder first.');
            return;
        }

        // ── Trip 1: Paris & Rome ──────────────────────────────────────
        $trip1 = Trip::create([
            'user_id'         => $demo->_id,
            'title'           => 'European Dream — Paris & Rome',
            'description'     => 'A romantic 10-day journey through the most iconic cities of Europe. Art, cuisine, history, and love.',
            'start_date'      => Carbon::now()->addDays(30),
            'end_date'        => Carbon::now()->addDays(40),
            'budget'          => 5500.00,
            'travelers_count' => 2,
            'trip_type'       => 'couple',
            'status'          => 'planning',
        ]);

        // Destinations for Trip 1
        Destination::create([
            'trip_id'          => $trip1->_id,
            'destination_name' => 'Paris — City of Light',
            'city'             => 'Paris',
            'country'          => 'France',
            'arrival_date'     => Carbon::now()->addDays(30),
            'departure_date'   => Carbon::now()->addDays(35),
            'notes'            => 'Pre-book Eiffel Tower tickets. Best bakeries near Saint-Germain.',
        ]);

        Destination::create([
            'trip_id'          => $trip1->_id,
            'destination_name' => 'Rome — The Eternal City',
            'city'             => 'Rome',
            'country'          => 'Italy',
            'arrival_date'     => Carbon::now()->addDays(35),
            'departure_date'   => Carbon::now()->addDays(40),
            'notes'            => 'Book Vatican Museums skip-the-line tickets in advance.',
        ]);

        // Accommodations for Trip 1
        Accommodation::create([
            'trip_id'           => $trip1->_id,
            'hotel_name'        => 'Hotel Le Marais Boutique',
            'check_in'          => Carbon::now()->addDays(30),
            'check_out'         => Carbon::now()->addDays(35),
            'address'           => '12 Rue de Bretagne, 75003',
            'city'              => 'Paris',
            'country'           => 'France',
            'booking_reference' => 'LM87423',
            'room_type'         => 'Deluxe Double with Balcony',
            'price_per_night'   => 220.00,
            'rating'            => 4,
            'notes'             => 'Free breakfast included. Check-in from 3pm.',
        ]);

        Accommodation::create([
            'trip_id'           => $trip1->_id,
            'hotel_name'        => 'Hotel Artemide Rome',
            'check_in'          => Carbon::now()->addDays(35),
            'check_out'         => Carbon::now()->addDays(40),
            'address'           => 'Via Nazionale 22, 00184',
            'city'              => 'Rome',
            'country'           => 'Italy',
            'booking_reference' => 'AR55891',
            'room_type'         => 'Superior Queen Room',
            'price_per_night'   => 180.00,
            'rating'            => 4,
        ]);

        // Activities for Trip 1
        $activities1 = [
            ['name' => 'Eiffel Tower Skip-the-Line',   'date' => 31, 'time' => '10:00', 'cat' => 'sightseeing', 'cost' => 28,  'dur' => 120, 'loc' => 'Champ de Mars, Paris'],
            ['name' => 'Lunch at Café de Flore',       'date' => 31, 'time' => '13:00', 'cat' => 'food',        'cost' => 55,  'dur' => 90,  'loc' => 'Saint-Germain-des-Prés'],
            ['name' => 'Louvre Museum — Highlights',   'date' => 32, 'time' => '09:30', 'cat' => 'culture',     'cost' => 22,  'dur' => 180, 'loc' => 'Rue de Rivoli, Paris'],
            ['name' => 'Seine River Dinner Cruise',    'date' => 32, 'time' => '20:00', 'cat' => 'adventure',   'cost' => 85,  'dur' => 150, 'loc' => 'Pont de l\'Alma, Paris', 'booking' => true],
            ['name' => 'Versailles Palace Day Trip',   'date' => 33, 'time' => '09:00', 'cat' => 'culture',     'cost' => 35,  'dur' => 360, 'loc' => 'Palace of Versailles', 'booking' => true],
            ['name' => 'Montmartre & Sacré-Cœur Walk', 'date' => 34, 'time' => '10:00', 'cat' => 'sightseeing', 'cost' => 0,   'dur' => 180, 'loc' => 'Montmartre, Paris'],
            ['name' => 'Fine Dining — Guy Savoy',      'date' => 34, 'time' => '19:30', 'cat' => 'food',        'cost' => 220, 'dur' => 180, 'loc' => 'Monnaie de Paris', 'booking' => true],
            ['name' => 'Colosseum & Roman Forum Tour', 'date' => 36, 'time' => '09:00', 'cat' => 'culture',     'cost' => 30,  'dur' => 180, 'loc' => 'Piazza del Colosseo, Rome', 'booking' => true],
            ['name' => 'Vatican Museums & Sistine Chapel','date'=>37,'time' => '08:00', 'cat' => 'culture',     'cost' => 25,  'dur' => 240, 'loc' => 'Vatican City', 'booking' => true],
            ['name' => 'Trastevere Food Walk',         'date' => 38, 'time' => '18:30', 'cat' => 'food',        'cost' => 45,  'dur' => 180, 'loc' => 'Trastevere, Rome'],
            ['name' => 'Trevi Fountain & Gelato',      'date' => 39, 'time' => '11:00', 'cat' => 'sightseeing', 'cost' => 5,   'dur' => 60,  'loc' => 'Fontana di Trevi, Rome'],
        ];

        foreach ($activities1 as $a) {
            Activity::create([
                'trip_id'           => $trip1->_id,
                'activity_name'     => $a['name'],
                'activity_date'     => Carbon::now()->addDays($a['date']),
                'activity_time'     => $a['time'],
                'category'          => $a['cat'],
                'cost'              => $a['cost'],
                'duration_minutes'  => $a['dur'],
                'location'          => $a['loc'] ?? null,
                'booking_required'  => $a['booking'] ?? false,
            ]);
        }

        $this->command->info("✅ Trip 1 seeded: {$trip1->title}");

        // ── Trip 2: Bali Solo Retreat ─────────────────────────────────
        $trip2 = Trip::create([
            'user_id'         => $demo->_id,
            'title'           => 'Bali Soul Retreat 🌿',
            'description'     => 'A week of wellness, culture and nature in the heart of Bali. Rice terraces, temples, yoga, and world-class cuisine.',
            'start_date'      => Carbon::now()->addDays(60),
            'end_date'        => Carbon::now()->addDays(67),
            'budget'          => 2200.00,
            'travelers_count' => 1,
            'trip_type'       => 'solo',
            'status'          => 'planning',
        ]);

        Destination::create([
            'trip_id'          => $trip2->_id,
            'destination_name' => 'Ubud — Cultural Heart of Bali',
            'city'             => 'Ubud',
            'country'          => 'Indonesia',
            'arrival_date'     => Carbon::now()->addDays(60),
            'departure_date'   => Carbon::now()->addDays(63),
            'notes'            => 'Hire a scooter for the day. Avoid Monday — temple ceremonies.',
        ]);

        Destination::create([
            'trip_id'          => $trip2->_id,
            'destination_name' => 'Seminyak — Beach & Sunset',
            'city'             => 'Seminyak',
            'country'          => 'Indonesia',
            'arrival_date'     => Carbon::now()->addDays(63),
            'departure_date'   => Carbon::now()->addDays(67),
            'notes'            => 'Best sunset at Potato Head Beach Club.',
        ]);

        Accommodation::create([
            'trip_id'           => $trip2->_id,
            'hotel_name'        => 'Komaneka at Bisma',
            'check_in'          => Carbon::now()->addDays(60),
            'check_out'         => Carbon::now()->addDays(63),
            'city'              => 'Ubud',
            'country'           => 'Indonesia',
            'booking_reference' => 'KB20941',
            'room_type'         => 'Forest View Villa',
            'price_per_night'   => 195.00,
            'rating'            => 5,
        ]);

        $activities2 = [
            ['name' => 'Sunrise Yoga Session',           'date' => 60, 'time' => '06:00', 'cat' => 'other',       'cost' => 15,  'dur' => 60],
            ['name' => 'Tegallalang Rice Terraces',      'date' => 61, 'time' => '08:00', 'cat' => 'sightseeing', 'cost' => 5,   'dur' => 120, 'loc' => 'Tegallalang, Ubud'],
            ['name' => 'Balinese Cooking Class',         'date' => 61, 'time' => '14:00', 'cat' => 'culture',     'cost' => 45,  'dur' => 240, 'booking' => true],
            ['name' => 'Tanah Lot Temple at Sunset',     'date' => 62, 'time' => '16:00', 'cat' => 'culture',     'cost' => 5,   'dur' => 120],
            ['name' => 'Traditional Spa & Massage',      'date' => 63, 'time' => '11:00', 'cat' => 'other',       'cost' => 60,  'dur' => 180],
            ['name' => 'Seminyak Beach Sunset',          'date' => 64, 'time' => '17:30', 'cat' => 'beach',       'cost' => 0,   'dur' => 120],
            ['name' => 'Surfing Lesson',                 'date' => 65, 'time' => '07:00', 'cat' => 'adventure',   'cost' => 40,  'dur' => 120, 'booking' => true],
            ['name' => 'Potato Head Beach Club',         'date' => 66, 'time' => '16:00', 'cat' => 'nightlife',   'cost' => 25,  'dur' => 240],
        ];

        foreach ($activities2 as $a) {
            Activity::create([
                'trip_id'          => $trip2->_id,
                'activity_name'    => $a['name'],
                'activity_date'    => Carbon::now()->addDays($a['date']),
                'activity_time'    => $a['time'],
                'category'         => $a['cat'],
                'cost'             => $a['cost'],
                'duration_minutes' => $a['dur'],
                'location'         => $a['loc'] ?? null,
                'booking_required' => $a['booking'] ?? false,
            ]);
        }

        $this->command->info("✅ Trip 2 seeded: {$trip2->title}");

        // ── Trip 3: Completed Tokyo Trip ──────────────────────────────
        $trip3 = Trip::create([
            'user_id'         => $demo->_id,
            'title'           => 'Tokyo Family Adventure',
            'description'     => 'An unforgettable 8-day family adventure through the neon streets, ancient temples, and incredible food of Tokyo.',
            'start_date'      => Carbon::now()->subDays(20),
            'end_date'        => Carbon::now()->subDays(12),
            'budget'          => 8000.00,
            'travelers_count' => 4,
            'trip_type'       => 'family',
            'status'          => 'completed',
        ]);

        Destination::create([
            'trip_id'          => $trip3->_id,
            'destination_name' => 'Tokyo — Japan\'s Capital',
            'city'             => 'Tokyo',
            'country'          => 'Japan',
            'arrival_date'     => Carbon::now()->subDays(20),
            'departure_date'   => Carbon::now()->subDays(12),
            'notes'            => 'Get Suica card at airport. 7-Eleven for quick meals.',
        ]);

        Accommodation::create([
            'trip_id'           => $trip3->_id,
            'hotel_name'        => 'Park Hyatt Tokyo',
            'check_in'          => Carbon::now()->subDays(20),
            'check_out'         => Carbon::now()->subDays(12),
            'city'              => 'Tokyo',
            'country'           => 'Japan',
            'booking_reference' => 'PH74210',
            'room_type'         => 'Park Twin Room',
            'price_per_night'   => 380.00,
            'rating'            => 5,
        ]);

        $this->command->info("✅ Trip 3 seeded: {$trip3->title}");

        // ── Extra random trips for other users ────────────────────────
        $otherUsers = User::where('email', '!=', 'demo@wanderly.app')->take(3)->get();
        foreach ($otherUsers as $user) {
            $randomTrip = Trip::factory()->create(['user_id' => $user->_id]);
            Destination::factory()->create(['trip_id' => $randomTrip->_id]);
            Accommodation::factory()->create(['trip_id' => $randomTrip->_id]);
            Activity::factory()->count(3)->create(['trip_id' => $randomTrip->_id]);
        }

        $this->command->info('✅ All trips seeded successfully!');
        $this->command->newLine();
        $this->command->line('  🔐 Demo login: <comment>demo@wanderly.app</comment> / <comment>password</comment>');
    }
}
