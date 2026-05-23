<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * DestinationController
 *
 * Manages destinations within a trip.
 */
class DestinationController extends Controller
{
    /**
     * Show form to add a destination to a trip.
     */
    public function create(Trip $trip)
    {
        $this->authorizeTrip($trip);
        return view('destinations.create', compact('trip'));
    }

    /**
     * Store a new destination.
     */
    public function store(Request $request, Trip $trip)
    {
        $this->authorizeTrip($trip);

        $validated = $request->validate([
            'destination_name' => ['required', 'string', 'max:255'],
            'country'          => ['nullable', 'string', 'max:100'],
            'city'             => ['nullable', 'string', 'max:100'],
            'arrival_date'     => ['required', 'date'],
            'departure_date'   => ['required', 'date', 'after_or_equal:arrival_date'],
            'notes'            => ['nullable', 'string', 'max:2000'],
        ]);

        Destination::create([
            ...$validated,
            'trip_id' => $trip->_id,
        ]);

        session()->flash('toast_success', "📍 Destination \"{$validated['destination_name']}\" added!");

        return redirect()->route('trips.show', $trip->_id);
    }

    /**
     * Show edit form for a destination.
     */
    public function edit(Trip $trip, Destination $destination)
    {
        $this->authorizeTrip($trip);
        return view('destinations.edit', compact('trip', 'destination'));
    }

    /**
     * Update a destination.
     */
    public function update(Request $request, Trip $trip, Destination $destination)
    {
        $this->authorizeTrip($trip);

        $validated = $request->validate([
            'destination_name' => ['required', 'string', 'max:255'],
            'country'          => ['nullable', 'string', 'max:100'],
            'city'             => ['nullable', 'string', 'max:100'],
            'arrival_date'     => ['required', 'date'],
            'departure_date'   => ['required', 'date', 'after_or_equal:arrival_date'],
            'notes'            => ['nullable', 'string', 'max:2000'],
        ]);

        $destination->update($validated);

        session()->flash('toast_success', "✅ Destination updated successfully!");

        return redirect()->route('trips.show', $trip->_id);
    }

    /**
     * Delete a destination.
     */
    public function destroy(Trip $trip, Destination $destination)
    {
        $this->authorizeTrip($trip);

        $name = $destination->destination_name;
        $destination->delete();

        session()->flash('toast_success', "🗑️ Destination \"{$name}\" removed.");

        return redirect()->route('trips.show', $trip->_id);
    }

    private function authorizeTrip(Trip $trip): void
    {
        if ($trip->user_id !== Auth::id()) {
            abort(403, 'Unauthorized.');
        }
    }
}
