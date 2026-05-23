<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * DashboardController
 *
 * Personalized dashboard with trip summaries and quick actions.
 */
class DashboardController extends Controller
{
    /**
     * Show personalized dashboard.
     */
    public function index()
    {
        $userId = Auth::id();

        // Fetch user's trips with related data
        $allTrips = Trip::forUser($userId)
            ->orderBy('start_date', 'desc')
            ->get();

        // Upcoming trips (start date in the future)
        $upcomingTrips = Trip::forUser($userId)
            ->upcoming()
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        // Recent trips
        $recentTrips = Trip::forUser($userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Stats
        $stats = [
            'total_trips'       => $allTrips->count(),
            'upcoming_trips'    => $upcomingTrips->count(),
            'total_destinations'=> $allTrips->sum(fn($t) => $t->destinations()->count()),
            'total_spent'       => $allTrips->sum('budget'),
        ];

        // Recent activities across all trips
        $recentActivities = Activity::whereIn('trip_id', $allTrips->pluck('_id'))
            ->orderBy('activity_date', 'asc')
            ->where('activity_date', '>=', now())
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'upcomingTrips',
            'recentTrips',
            'stats',
            'recentActivities'
        ));
    }
}
