<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * AccommodationController
 *
 * Manages hotel/accommodation records within a trip.
 */
class AccommodationController extends Controller
{
    public function create(Trip $trip)
    {
        $this->authorizeTrip($trip);
        return view('accommodations.create', compact('trip'));
    }

    public function store(Request $request, Trip $trip)
    {
        $this->authorizeTrip($trip);

        $validated = $request->validate([
            'hotel_name'        => ['required', 'string', 'max:255'],
            'check_in'          => ['required', 'date'],
            'check_out'         => ['required', 'date', 'after_or_equal:check_in'],
            'address'           => ['nullable', 'string', 'max:500'],
            'city'              => ['nullable', 'string', 'max:100'],
            'country'           => ['nullable', 'string', 'max:100'],
            'booking_reference' => ['nullable', 'string', 'max:100'],
            'room_type'         => ['nullable', 'string', 'max:100'],
            'price_per_night'   => ['nullable', 'numeric', 'min:0'],
            'rating'            => ['nullable', 'integer', 'min:1', 'max:5'],
            'notes'             => ['nullable', 'string', 'max:2000'],
        ]);

        Accommodation::create([
            ...$validated,
            'trip_id' => $trip->_id,
        ]);

        session()->flash('toast_success', "🏨 Accommodation \"{$validated['hotel_name']}\" added!");

        return redirect()->route('trips.show', $trip->_id);
    }

    public function edit(Trip $trip, Accommodation $accommodation)
    {
        $this->authorizeTrip($trip);
        return view('accommodations.edit', compact('trip', 'accommodation'));
    }

    public function update(Request $request, Trip $trip, Accommodation $accommodation)
    {
        $this->authorizeTrip($trip);

        $validated = $request->validate([
            'hotel_name'        => ['required', 'string', 'max:255'],
            'check_in'          => ['required', 'date'],
            'check_out'         => ['required', 'date', 'after_or_equal:check_in'],
            'address'           => ['nullable', 'string', 'max:500'],
            'city'              => ['nullable', 'string', 'max:100'],
            'country'           => ['nullable', 'string', 'max:100'],
            'booking_reference' => ['nullable', 'string', 'max:100'],
            'room_type'         => ['nullable', 'string', 'max:100'],
            'price_per_night'   => ['nullable', 'numeric', 'min:0'],
            'rating'            => ['nullable', 'integer', 'min:1', 'max:5'],
            'notes'             => ['nullable', 'string', 'max:2000'],
        ]);

        $accommodation->update($validated);

        session()->flash('toast_success', "✅ Accommodation updated successfully!");

        return redirect()->route('trips.show', $trip->_id);
    }

    public function destroy(Trip $trip, Accommodation $accommodation)
    {
        $this->authorizeTrip($trip);

        $name = $accommodation->hotel_name;
        $accommodation->delete();

        session()->flash('toast_success', "🗑️ Accommodation \"{$name}\" removed.");

        return redirect()->route('trips.show', $trip->_id);
    }

    private function authorizeTrip(Trip $trip): void
    {
        if ($trip->user_id !== Auth::id()) {
            abort(403, 'Unauthorized.');
        }
    }
}
