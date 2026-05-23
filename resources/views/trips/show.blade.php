@extends('layouts.app')

@section('title', $trip->title . ' — Wanderly')

@section('content')
<div class="trip-detail-page">

    {{-- Trip Header --}}
    <div class="trip-detail-header">
        <div class="trip-detail-header-content">
            <div class="tdh-breadcrumb">
                <a href="{{ route('trips.index') }}"><i class="bi bi-map"></i> My Trips</a>
                <i class="bi bi-chevron-right"></i>
                <span>{{ $trip->title }}</span>
            </div>

            <div class="tdh-title-row">
                <div>
                    <div class="tdh-badges">
                        <span class="tdh-type-badge">{{ $trip->trip_type_label }}</span>
                        <span class="tdh-status-badge badge-{{ $trip->status_color }}">{{ ucfirst($trip->status) }}</span>
                    </div>
                    <h1 class="tdh-title">{{ $trip->title }}</h1>
                    @if($trip->description)
                        <p class="tdh-desc">{{ $trip->description }}</p>
                    @endif
                </div>
                <div class="tdh-actions">
                    <a href="{{ route('trips.itinerary', $trip->_id) }}" class="btn-itinerary">
                        <i class="bi bi-calendar3"></i> View Itinerary
                    </a>
                    <a href="{{ route('trips.edit', $trip->_id) }}" class="btn-edit-trip">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('trips.destroy', $trip->_id) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this trip? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete-trip">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Trip Meta --}}
            <div class="tdh-meta-row">
                <div class="tdh-meta-item">
                    <i class="bi bi-calendar3"></i>
                    {{ $trip->start_date?->format('M d, Y') }} – {{ $trip->end_date?->format('M d, Y') }}
                </div>
                <div class="tdh-meta-item">
                    <i class="bi bi-clock"></i>
                    {{ $trip->duration }} days
                </div>
                <div class="tdh-meta-item">
                    <i class="bi bi-people"></i>
                    {{ $trip->travelers_count }} traveler(s)
                </div>
                @if($trip->budget)
                <div class="tdh-meta-item">
                    <i class="bi bi-wallet2"></i>
                    ₹{{ number_format($trip->budget, 2) }} budget
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Content Tabs --}}
    <div class="trip-tabs">
        <div class="tabs-nav">
            <button class="tab-btn active" data-tab="destinations">
                <i class="bi bi-geo-alt"></i> Destinations
                <span class="tab-count">{{ $trip->destinations->count() }}</span>
            </button>
            <button class="tab-btn" data-tab="accommodations">
                <i class="bi bi-building"></i> Accommodations
                <span class="tab-count">{{ $trip->accommodations->count() }}</span>
            </button>
            <button class="tab-btn" data-tab="activities">
                <i class="bi bi-lightning-charge"></i> Activities
                <span class="tab-count">{{ $trip->activities->count() }}</span>
            </button>
        </div>

        {{-- DESTINATIONS TAB --}}
        <div class="tab-panel active" id="tab-destinations">
            <div class="tab-panel-header">
                <h2>Destinations</h2>
                <a href="{{ route('trips.destinations.create', $trip->_id) }}" class="btn-add-item">
                    <i class="bi bi-plus-lg"></i> Add Destination
                </a>
            </div>

            @if($trip->destinations->isEmpty())
                <div class="empty-state tab-empty">
                    <span class="empty-icon">📍</span>
                    <h3>No destinations yet</h3>
                    <p>Add the places you'll be visiting on this trip.</p>
                    <a href="{{ route('trips.destinations.create', $trip->_id) }}" class="btn-empty-action">Add Destination</a>
                </div>
            @else
                <div class="items-grid">
                    @foreach($trip->destinations as $dest)
                    <div class="item-card dest-card">
                        <div class="item-card-header">
                            <div class="item-icon dest-icon">📍</div>
                            <div class="item-card-actions">
                                <a href="{{ route('trips.destinations.edit', [$trip->_id, $dest->_id]) }}" class="item-action-btn">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('trips.destinations.destroy', [$trip->_id, $dest->_id]) }}" method="POST"
                                      onsubmit="return confirm('Remove this destination?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="item-action-btn danger-btn">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <h3 class="item-title">{{ $dest->destination_name }}</h3>
                        @if($dest->city || $dest->country)
                            <p class="item-subtitle">{{ implode(', ', array_filter([$dest->city, $dest->country])) }}</p>
                        @endif
                        <div class="item-meta">
                            <span><i class="bi bi-box-arrow-in-right"></i> {{ $dest->arrival_date?->format('M d, Y') }}</span>
                            <span><i class="bi bi-box-arrow-right"></i> {{ $dest->departure_date?->format('M d, Y') }}</span>
                            <span><i class="bi bi-moon"></i> {{ $dest->nights }} nights</span>
                        </div>
                        @if($dest->notes)
                            <p class="item-notes">{{ $dest->notes }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ACCOMMODATIONS TAB --}}
        <div class="tab-panel" id="tab-accommodations">
            <div class="tab-panel-header">
                <h2>Accommodations</h2>
                <a href="{{ route('trips.accommodations.create', $trip->_id) }}" class="btn-add-item">
                    <i class="bi bi-plus-lg"></i> Add Accommodation
                </a>
            </div>

            @if($trip->accommodations->isEmpty())
                <div class="empty-state tab-empty">
                    <span class="empty-icon">🏨</span>
                    <h3>No accommodations yet</h3>
                    <p>Add hotels, hostels or any place you'll be staying.</p>
                    <a href="{{ route('trips.accommodations.create', $trip->_id) }}" class="btn-empty-action">Add Hotel</a>
                </div>
            @else
                <div class="items-grid">
                    @foreach($trip->accommodations as $acc)
                    <div class="item-card hotel-card">
                        <div class="item-card-header">
                            <div class="item-icon hotel-icon">🏨</div>
                            <div class="item-card-actions">
                                <a href="{{ route('trips.accommodations.edit', [$trip->_id, $acc->_id]) }}" class="item-action-btn">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('trips.accommodations.destroy', [$trip->_id, $acc->_id]) }}" method="POST"
                                      onsubmit="return confirm('Remove this accommodation?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="item-action-btn danger-btn">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <h3 class="item-title">{{ $acc->hotel_name }}</h3>
                        @if($acc->city || $acc->country)
                            <p class="item-subtitle">{{ implode(', ', array_filter([$acc->city, $acc->country])) }}</p>
                        @endif
                        @if($acc->rating)
                            <div class="hotel-stars">{{ $acc->stars }}</div>
                        @endif
                        <div class="item-meta">
                            <span><i class="bi bi-box-arrow-in-right"></i> Check-in: {{ $acc->check_in?->format('M d, Y') }}</span>
                            <span><i class="bi bi-box-arrow-right"></i> Check-out: {{ $acc->check_out?->format('M d, Y') }}</span>
                            <span><i class="bi bi-moon"></i> {{ $acc->nights }} nights</span>
                            @if($acc->price_per_night)
                                <span><i class="bi bi-wallet2"></i> ₹{{ $acc->price_per_night }}/night</span>
                            @endif
                        </div>
                        @if($acc->booking_reference)
                            <div class="booking-ref">
                                <i class="bi bi-ticket"></i>
                                Ref: <strong>{{ $acc->booking_reference }}</strong>
                            </div>
                        @endif
                        @if($acc->address)
                            <p class="item-notes"><i class="bi bi-map-pin"></i> {{ $acc->address }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ACTIVITIES TAB --}}
        <div class="tab-panel" id="tab-activities">
            <div class="tab-panel-header">
                <h2>Activities</h2>
                <a href="{{ route('trips.activities.create', $trip->_id) }}" class="btn-add-item">
                    <i class="bi bi-plus-lg"></i> Add Activity
                </a>
            </div>

            @if($trip->activities->isEmpty())
                <div class="empty-state tab-empty">
                    <span class="empty-icon">🎯</span>
                    <h3>No activities yet</h3>
                    <p>Add sightseeing, food tours, adventures and more!</p>
                    <a href="{{ route('trips.activities.create', $trip->_id) }}" class="btn-empty-action">Add Activity</a>
                </div>
            @else
                <div class="activities-list">
                    @foreach($trip->activities->groupBy(fn($a) => $a->activity_date?->format('Y-m-d')) as $date => $dayActivities)
                    <div class="activities-day-group">
                        <div class="day-group-header">
                            <span class="day-dot"></span>
                            <h4>{{ \Carbon\Carbon::parse($date)->format('l, M d, Y') }}</h4>
                            <span class="day-count">{{ $dayActivities->count() }} activity(ies)</span>
                        </div>
                        <div class="activities-day-list">
                            @foreach($dayActivities->sortBy('activity_time') as $act)
                            <div class="activity-item-card">
                                <div class="aic-left">
                                    @if($act->activity_time)
                                        <div class="aic-time">{{ \Carbon\Carbon::parse($act->activity_time)->format('H:i') }}</div>
                                    @else
                                        <div class="aic-time">—</div>
                                    @endif
                                    <div class="aic-timeline-line"></div>
                                </div>
                                <div class="aic-body">
                                    <div class="aic-header">
                                        <div class="aic-icon-cat" style="background: {{ $act->category_color }}20; color: {{ $act->category_color }}">
                                            {{ $act->category_icon }}
                                        </div>
                                        <div class="aic-info">
                                            <h4>{{ $act->activity_name }}</h4>
                                            <div class="aic-meta">
                                                <span class="aic-category-tag" style="background: {{ $act->category_color }}20; color: {{ $act->category_color }}">
                                                    {{ ucfirst($act->category) }}
                                                </span>
                                                @if($act->location)
                                                    <span><i class="bi bi-geo-alt"></i> {{ $act->location }}</span>
                                                @endif
                                                @if($act->duration_minutes)
                                                    <span><i class="bi bi-clock"></i> {{ $act->duration_formatted }}</span>
                                                @endif
                                                @if($act->cost)
                                                    <span><i class="bi bi-wallet2"></i> ₹{{ number_format($act->cost, 2) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="aic-actions">
                                            <a href="{{ route('trips.activities.edit', [$trip->_id, $act->_id]) }}" class="item-action-btn">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('trips.activities.destroy', [$trip->_id, $act->_id]) }}" method="POST"
                                                  onsubmit="return confirm('Remove this activity?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="item-action-btn danger-btn">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @if($act->description)
                                        <p class="aic-description">{{ $act->description }}</p>
                                    @endif
                                    @if($act->booking_required)
                                        <div class="aic-booking-badge">
                                            <i class="bi bi-ticket-perforated"></i>
                                            Booking Required
                                            @if($act->booking_reference)
                                                — Ref: <strong>{{ $act->booking_reference }}</strong>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Tabs
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const tabId = this.dataset.tab;

        // Update buttons
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        // Update panels
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        document.getElementById('tab-' + tabId).classList.add('active');
    });
});
</script>
@endpush
