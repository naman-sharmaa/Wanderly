<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Destination;
use App\Models\Accommodation;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * TripController
 *
 * Full CRUD for Trip management with search & filter capabilities.
 */
class TripController extends Controller
{
    /**
     * List all user trips with search and filters.
     */
    public function index(Request $request)
    {
        $query = Trip::forUser(Auth::id())->orderBy('created_at', 'desc');

        // Search
        if ($search = $request->get('search')) {
            $query->search($search);
        }

        // Filter by trip type
        if ($type = $request->get('type')) {
            $query->where('trip_type', $type);
        }

        // Filter by status
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Sort
        $sort = $request->get('sort', 'newest');
        match($sort) {
            'oldest'     => $query->orderBy('created_at', 'asc'),
            'start_date' => $query->orderBy('start_date', 'asc'),
            'budget'     => $query->orderBy('budget', 'desc'),
            default      => $query->orderBy('created_at', 'desc'),
        };

        $trips = $query->get();

        return view('trips.index', compact('trips'));
    }

    /**
     * Show trip creation form.
     */
    public function create()
    {
        return view('trips.create');
    }

    /**
     * Store a new trip.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string', 'max:2000'],
            'start_date'      => ['required', 'date'],
            'end_date'        => ['required', 'date', 'after_or_equal:start_date'],
            'budget'          => ['nullable', 'numeric', 'min:0'],
            'travelers_count' => ['required', 'integer', 'min:1', 'max:200'],
            'trip_type'       => ['required', 'in:solo,couple,family,group,business'],
        ], [
            'end_date.after_or_equal' => 'End date must be on or after the start date.',
            'trip_type.in'            => 'Please select a valid trip type.',
        ]);

        $trip = Trip::create([
            ...$validated,
            'user_id' => Auth::id(),
            'status'  => 'planning',
        ]);

        session()->flash('toast_success', "🎉 Trip \"{$trip->title}\" created! Let's start planning.");

        return redirect()->route('trips.show', $trip->_id);
    }

    /**
     * Show a single trip with all details.
     */
    public function show(Trip $trip)
    {
        $this->authorize($trip);

        $trip->load(['destinations', 'accommodations', 'activities']);

        return view('trips.show', compact('trip'));
    }

    /**
     * Show trip edit form.
     */
    public function edit(Trip $trip)
    {
        $this->authorize($trip);

        return view('trips.edit', compact('trip'));
    }

    /**
     * Update trip.
     */
    public function update(Request $request, Trip $trip)
    {
        $this->authorize($trip);

        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string', 'max:2000'],
            'start_date'      => ['required', 'date'],
            'end_date'        => ['required', 'date', 'after_or_equal:start_date'],
            'budget'          => ['nullable', 'numeric', 'min:0'],
            'travelers_count' => ['required', 'integer', 'min:1', 'max:200'],
            'trip_type'       => ['required', 'in:solo,couple,family,group,business'],
            'status'          => ['required', 'in:planning,active,completed,cancelled'],
        ]);

        $trip->update($validated);

        session()->flash('toast_success', "✅ Trip \"{$trip->title}\" updated successfully!");

        return redirect()->route('trips.show', $trip->_id);
    }

    /**
     * Delete a trip and all associated data.
     */
    public function destroy(Trip $trip)
    {
        $this->authorize($trip);

        $title = $trip->title;

        // Cascade delete related records
        $trip->destinations()->delete();
        $trip->accommodations()->delete();
        $trip->activities()->delete();
        $trip->delete();

        session()->flash('toast_success', "🗑️ Trip \"{$title}\" has been deleted.");

        return redirect()->route('trips.index');
    }

    // ----------------------------------------------------------------
    // Private helpers
    // ----------------------------------------------------------------

    /**
     * Authorize that the trip belongs to the authenticated user.
     */
    private function authorize(Trip $trip): void
    {
        if ($trip->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this trip.');
        }
    }
}
