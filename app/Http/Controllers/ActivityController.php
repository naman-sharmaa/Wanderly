<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ActivityController
 *
 * Manages activities/excursions within a trip.
 */
class ActivityController extends Controller
{
    public function create(Trip $trip)
    {
        $this->authorizeTrip($trip);
        return view('activities.create', compact('trip'));
    }

    public function store(Request $request, Trip $trip)
    {
        $this->authorizeTrip($trip);

        $validated = $request->validate([
            'activity_name'     => ['required', 'string', 'max:255'],
            'activity_date'     => ['required', 'date'],
            'activity_time'     => ['nullable', 'string'],
            'category'          => ['required', 'in:sightseeing,food,adventure,culture,shopping,transport,beach,nightlife,other'],
            'description'       => ['nullable', 'string', 'max:2000'],
            'location'          => ['nullable', 'string', 'max:255'],
            'cost'              => ['nullable', 'numeric', 'min:0'],
            'duration_minutes'  => ['nullable', 'integer', 'min:1'],
            'booking_required'  => ['boolean'],
            'booking_reference' => ['nullable', 'string', 'max:100'],
            'notes'             => ['nullable', 'string', 'max:2000'],
        ]);

        Activity::create([
            ...$validated,
            'trip_id'          => $trip->_id,
            'booking_required' => $request->boolean('booking_required'),
        ]);

        session()->flash('toast_success', "🎯 Activity \"{$validated['activity_name']}\" added!");

        return redirect()->route('trips.show', $trip->_id);
    }

    public function edit(Trip $trip, Activity $activity)
    {
        $this->authorizeTrip($trip);
        return view('activities.edit', compact('trip', 'activity'));
    }

    public function update(Request $request, Trip $trip, Activity $activity)
    {
        $this->authorizeTrip($trip);

        $validated = $request->validate([
            'activity_name'     => ['required', 'string', 'max:255'],
            'activity_date'     => ['required', 'date'],
            'activity_time'     => ['nullable', 'string'],
            'category'          => ['required', 'in:sightseeing,food,adventure,culture,shopping,transport,beach,nightlife,other'],
            'description'       => ['nullable', 'string', 'max:2000'],
            'location'          => ['nullable', 'string', 'max:255'],
            'cost'              => ['nullable', 'numeric', 'min:0'],
            'duration_minutes'  => ['nullable', 'integer', 'min:1'],
            'booking_required'  => ['boolean'],
            'booking_reference' => ['nullable', 'string', 'max:100'],
            'notes'             => ['nullable', 'string', 'max:2000'],
        ]);

        $activity->update([
            ...$validated,
            'booking_required' => $request->boolean('booking_required'),
        ]);

        session()->flash('toast_success', "✅ Activity updated successfully!");

        return redirect()->route('trips.show', $trip->_id);
    }

    public function destroy(Trip $trip, Activity $activity)
    {
        $this->authorizeTrip($trip);

        $name = $activity->activity_name;
        $activity->delete();

        session()->flash('toast_success', "🗑️ Activity \"{$name}\" removed.");

        return redirect()->route('trips.show', $trip->_id);
    }

    private function authorizeTrip(Trip $trip): void
    {
        if ($trip->user_id !== Auth::id()) {
            abort(403, 'Unauthorized.');
        }
    }
}
