<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * ItineraryController
 *
 * Generates day-by-day itinerary view for a trip.
 */
class ItineraryController extends Controller
{
    /**
     * Show day-wise itinerary for a trip.
     */
    public function show(Trip $trip)
    {
        // Authorize
        if ($trip->user_id !== Auth::id()) {
            abort(403, 'Unauthorized.');
        }

        // Load all related data
        $trip->load(['destinations', 'accommodations', 'activities']);

        // Build day-by-day itinerary
        $itinerary = $this->buildItinerary($trip);

        $totalActivityCost = $trip->activities->sum('cost');
        $totalAccommodationCost = $trip->accommodations->sum(function ($accommodation) {
            if (!$accommodation->price_per_night || !$accommodation->check_in || !$accommodation->check_out) {
                return 0;
            }

            $nights = max(1, $accommodation->check_in->diffInDays($accommodation->check_out));

            return $accommodation->price_per_night * $nights;
        });

        $totalTripCost = $totalActivityCost + $totalAccommodationCost;

        return view('itinerary.show', compact('trip', 'itinerary', 'totalActivityCost', 'totalAccommodationCost', 'totalTripCost'));
    }

    /**
     * Build structured day-wise itinerary array.
     *
     * @return array [date => [date, day_number, activities, accommodation, destinations]]
     */
    private function buildItinerary(Trip $trip): array
    {
        if (!$trip->start_date || !$trip->end_date) {
            return [];
        }

        $itinerary = [];
        $current   = $trip->start_date->copy();
        $dayNumber = 1;

        while ($current->lte($trip->end_date)) {
            $dateStr = $current->format('Y-m-d');

            // Activities for this day
            $activities = $trip->activities
                ->filter(fn($a) => $a->activity_date && $a->activity_date->format('Y-m-d') === $dateStr)
                ->sortBy(fn($a) => $a->activity_time ?? '00:00')
                ->values();

            // Active destinations (arrived but not yet departed)
            $destinations = $trip->destinations
                ->filter(fn($d) =>
                    $d->arrival_date && $d->departure_date &&
                    $current->gte($d->arrival_date) &&
                    $current->lte($d->departure_date)
                )->values();

            // Accommodation for this day
            $accommodation = $trip->accommodations
                ->first(fn($a) =>
                    $a->check_in && $a->check_out &&
                    $current->gte($a->check_in) &&
                    $current->lt($a->check_out)
                );

            $accommodationCost = $accommodation && $accommodation->price_per_night
                ? $accommodation->price_per_night
                : 0;

            $itinerary[] = [
                'date'          => $current->copy(),
                'date_str'      => $dateStr,
                'day_number'    => $dayNumber,
                'day_label'     => "Day {$dayNumber}",
                'activities'    => $activities,
                'destinations'  => $destinations,
                'accommodation' => $accommodation,
                'accommodation_cost' => $accommodationCost,
                'is_today'      => $current->isToday(),
                'is_past'       => $current->isPast() && !$current->isToday(),
            ];

            $current->addDay();
            $dayNumber++;
        }

        return $itinerary;
    }
}
