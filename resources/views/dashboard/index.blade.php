@extends('layouts.app')

@section('title', 'Dashboard — Wanderly')

@section('content')
    <div class="dashboard-page">

        {{-- Dashboard Header --}}
        <div class="page-header dashboard-header"
            style="position: relative; overflow: hidden; color: #fff; border-radius: 16px; min-height: 250px; display: flex; align-items: center; margin-bottom: 20px;">
            <video autoplay loop muted playsinline
                style="position: absolute; top:0; left:0; width:100%; height:100%; object-fit: cover; z-index: 0; opacity: 0.7;">
                <source src="{{ asset('videos/login.mp4') }}" type="video/mp4">
            </video>
            <div class="page-header-content" style="position: relative; z-index: 2; padding: 0 40px; width: 100%;">
                <div class="welcome-text" style="text-shadow: 0 2px 4px rgba(0,0,0,0.8);">
                    <span class="greeting" style="color: #eee;">{{ $greeting ?? 'Good day' }},</span>
                    <h1 style="color: #fff;">{{ Auth::user()->name }} <span class="wave">👋</span></h1>
                    <p class="header-subtitle" style="color: #eee;">Here's an overview of your travel plans.</p>
                </div>
                <a href="{{ route('trips.create') }}" class="btn-primary-action"
                    style="box-shadow: 0 4px 10px rgba(0,0,0,0.5);">
                    <i class="bi bi-plus-lg"></i>
                    Plan New Trip
                </a>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="stats-grid">
            <div class="stat-card" style="--stat-color: #4361ee;">
                <div class="stat-icon-wrap"><i class="bi bi-map"></i></div>
                <div class="stat-info">
                    <span class="stat-value">{{ $stats['total_trips'] }}</span>
                    <span class="stat-label">Total Trips</span>
                </div>
                <div class="stat-bg-icon"><i class="bi bi-map"></i></div>
            </div>

            <div class="stat-card" style="--stat-color: #f72585;">
                <div class="stat-icon-wrap"><i class="bi bi-calendar-event"></i></div>
                <div class="stat-info">
                    <span class="stat-value">{{ $stats['upcoming_trips'] }}</span>
                    <span class="stat-label">Upcoming Trips</span>
                </div>
                <div class="stat-bg-icon"><i class="bi bi-calendar-event"></i></div>
            </div>

            <div class="stat-card" style="--stat-color: #7209b7;">
                <div class="stat-icon-wrap"><i class="bi bi-geo-alt"></i></div>
                <div class="stat-info">
                    <span class="stat-value">{{ $stats['total_destinations'] }}</span>
                    <span class="stat-label">Destinations Visited</span>
                </div>
                <div class="stat-bg-icon"><i class="bi bi-geo-alt"></i></div>
            </div>

            <div class="stat-card" style="--stat-color: #06d6a0;">
                <div class="stat-icon-wrap"><i class="bi bi-wallet2"></i></div>
                <div class="stat-info">
                    <span class="stat-value">₹{{ number_format($stats['total_spent'], 0) }}</span>
                    <span class="stat-label">Total Budgeted</span>
                </div>
                <div class="stat-bg-icon"><i class="bi bi-wallet2"></i></div>
            </div>
        </div>

        <div class="dashboard-grid">

            {{-- Upcoming Trips --}}
            <div class="dashboard-section">
                <div class="section-title-row">
                    <h2><i class="bi bi-calendar-check"></i> Upcoming Trips</h2>
                    <a href="{{ route('trips.index') }}" class="view-all-link">View all <i
                            class="bi bi-arrow-right"></i></a>
                </div>

                @if($upcomingTrips->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon">🌍</div>
                        <h3>No upcoming trips</h3>
                        <p>Start planning your next adventure!</p>
                        <a href="{{ route('trips.create') }}" class="btn-empty-action">Plan a Trip</a>
                    </div>
                @else
                    <div class="trips-cards">
                        @foreach($upcomingTrips as $trip)
                            <div class="trip-card" onclick="window.location='{{ route('trips.show', $trip->_id) }}'">
                                <div class="trip-card-header">
                                    <div class="trip-type-badge">{{ $trip->trip_type_label }}</div>
                                    <span
                                        class="trip-status-badge badge-{{ $trip->status_color }}">{{ ucfirst($trip->status) }}</span>
                                </div>
                                <h3 class="trip-card-title">{{ $trip->title }}</h3>
                                <div class="trip-card-meta">
                                    <span><i class="bi bi-calendar3"></i>
                                        {{ $trip->start_date->format('M d') }} – {{ $trip->end_date->format('M d, Y') }}
                                    </span>
                                    <span><i class="bi bi-people"></i> {{ $trip->travelers_count }} traveler(s)</span>
                                </div>
                                @if($trip->budget)
                                    <div class="trip-card-budget">
                                        <i class="bi bi-wallet2"></i>
                                        Budget: ₹{{ number_format($trip->budget, 0) }}
                                    </div>
                                @endif
                                <div class="trip-card-footer">
                                    <span class="days-away">
                                        @if($trip->start_date->isFuture())
                                            <i class="bi bi-clock"></i>
                                            {{ (int) now()->diffInDays($trip->start_date) }} days away
                                        @else
                                            <i class="bi bi-airplane"></i> En Route!
                                        @endif
                                    </span>
                                    <span class="trip-duration">{{ $trip->duration }} days</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Quick Actions + Recent Activities --}}
            <div class="dashboard-sidebar">

                {{-- Quick Actions --}}
                <div class="quick-actions-card">
                    <h3><i class="bi bi-lightning-charge"></i> Quick Actions</h3>
                    <div class="quick-actions-list">
                        <a href="{{ route('trips.create') }}" class="quick-action-item">
                            <div class="qa-icon" style="--qa-color: #4361ee;"><i class="bi bi-plus-circle"></i></div>
                            <span>New Trip</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="{{ route('trips.index') }}" class="quick-action-item">
                            <div class="qa-icon" style="--qa-color: #f72585;"><i class="bi bi-map"></i></div>
                            <span>My Trips</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="{{ route('trips.index') }}?status=planning" class="quick-action-item">
                            <div class="qa-icon" style="--qa-color: #7209b7;"><i class="bi bi-pencil-square"></i></div>
                            <span>In Planning</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Upcoming Activities --}}
                <div class="upcoming-activities-card">
                    <h3><i class="bi bi-calendar3"></i> Coming Up</h3>
                    @if($recentActivities->isEmpty())
                        <div class="empty-mini">
                            <span>🎯</span>
                            <p>No upcoming activities</p>
                        </div>
                    @else
                        <div class="activities-mini-list">
                            @foreach($recentActivities as $activity)
                                <div class="activity-mini-item">
                                    <div class="activity-mini-icon">{{ $activity->category_icon }}</div>
                                    <div class="activity-mini-info">
                                        <strong>{{ $activity->activity_name }}</strong>
                                        <span>{{ $activity->activity_date?->format('M d') }}</span>
                                    </div>
                                    @if($activity->cost)
                                        <span class="activity-mini-cost">₹{{ number_format($activity->cost, 0) }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent Trips Table --}}
        @if($recentTrips->isNotEmpty())
            <div class="recent-trips-section">
                <div class="section-title-row">
                    <h2><i class="bi bi-clock-history"></i> Recent Trips</h2>
                    <a href="{{ route('trips.index') }}" class="view-all-link">View all <i class="bi bi-arrow-right"></i></a>
                </div>

                <div class="trips-table-wrap">
                    <table class="trips-table">
                        <thead>
                            <tr>
                                <th>Trip</th>
                                <th>Type</th>
                                <th>Dates</th>
                                <th>Travelers</th>
                                <th>Budget</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTrips as $trip)
                                <tr>
                                    <td>
                                        <div class="table-trip-name">
                                            <strong>{{ $trip->title }}</strong>
                                            @if($trip->description)
                                                <span>{{ Str::limit($trip->description, 50) }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $trip->trip_type_label }}</td>
                                    <td>
                                        {{ $trip->start_date?->format('M d') }} –
                                        {{ $trip->end_date?->format('M d, Y') }}
                                    </td>
                                    <td>{{ $trip->travelers_count }}</td>
                                    <td>{{ $trip->budget ? '₹' . number_format($trip->budget, 0) : '—' }}</td>
                                    <td>
                                        <span class="status-pill pill-{{ $trip->status_color }}">
                                            {{ ucfirst($trip->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('trips.show', $trip->_id) }}" class="table-action-btn">
                                            View <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
@endsection