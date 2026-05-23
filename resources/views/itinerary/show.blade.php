@extends('layouts.app')

@section('title', $trip->title . ' — Itinerary')

@section('content')
<div class="itinerary-page">

    {{-- Header --}}
    <div class="itinerary-header">
        <div class="itin-header-content">
            <div class="itin-breadcrumb">
                <a href="{{ route('trips.show', $trip->_id) }}">
                    <i class="bi bi-arrow-left"></i> {{ $trip->title }}
                </a>
            </div>
            <h1>📅 Day-by-Day Itinerary</h1>
            <p>{{ $trip->start_date?->format('M d') }} – {{ $trip->end_date?->format('M d, Y') }} · {{ $trip->duration }} Days</p>

            <div class="itin-header-actions">
                <a href="{{ route('trips.activities.create', $trip->_id) }}" class="btn-add-item">
                    <i class="bi bi-plus-lg"></i> Add Activity
                </a>
                <button onclick="window.print()" class="btn-print">
                    <i class="bi bi-printer"></i> Print
                </button>
            </div>
        </div>
    </div>

    {{-- Trip Summary --}}
    <div class="itin-summary-strip">
        <div class="iss-item">
            <i class="bi bi-geo-alt"></i>
            <span>{{ $trip->destinations->count() }} destination(s)</span>
        </div>
        <div class="iss-item">
            <i class="bi bi-lightning-charge"></i>
            <span>{{ $trip->activities->count() }} activit(ies)</span>
        </div>
        <div class="iss-item">
            <i class="bi bi-building"></i>
            <span>{{ $trip->accommodations->count() }} hotel(s)</span>
        </div>
        <div class="iss-item">
            <i class="bi bi-people"></i>
            <span>{{ $trip->travelers_count }} traveler(s)</span>
        </div>
        @if($trip->budget)
        <div class="iss-item">
            <i class="bi bi-wallet2"></i>
            <span>₹{{ number_format($trip->budget, 0) }} budget</span>
        </div>
        @endif
    </div>

    @if(empty($itinerary))
        <div class="empty-state">
            <span class="empty-icon">📅</span>
            <h3>No itinerary to display</h3>
            <p>Make sure your trip has valid start and end dates.</p>
        </div>
    @else
    {{-- Day-by-Day Timeline --}}
    <div class="itinerary-timeline">
        @foreach($itinerary as $day)
        <div class="itin-day {{ $day['is_today'] ? 'is-today' : '' }} {{ $day['is_past'] ? 'is-past' : '' }}"
             id="day-{{ $day['day_number'] }}">

            {{-- Day Header --}}
            <div class="itin-day-header">
                <div class="day-header-left">
                    <div class="day-number-badge {{ $day['is_today'] ? 'today-badge' : '' }}">
                        {{ $day['is_today'] ? 'Today' : $day['day_label'] }}
                    </div>
                    <div class="day-date">
                        <strong>{{ $day['date']->format('l') }}</strong>
                        <span>{{ $day['date']->format('M d, Y') }}</span>
                    </div>
                </div>

                {{-- Destinations active this day --}}
                @if($day['destinations']->isNotEmpty())
                    <div class="day-destinations">
                        @foreach($day['destinations'] as $dest)
                            <span class="day-dest-tag">
                                📍 {{ $dest->destination_name }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Day Content --}}
            <div class="itin-day-content">

                {{-- Check-in / Check-out indicators --}}
                @if($day['accommodation'])
                    <div class="itin-hotel-banner">
                        <i class="bi bi-building"></i>
                        <span>
                            Staying at <strong>{{ $day['accommodation']->hotel_name }}</strong>
                            @if($day['accommodation']->city)
                                · {{ $day['accommodation']->city }}
                            @endif
                        </span>
                        @if($day['accommodation']->booking_reference)
                            <span class="hotel-ref">Ref: {{ $day['accommodation']->booking_reference }}</span>
                        @endif
                        @if($day['accommodation']->price_per_night)
                            <span class="hotel-price">
                                <i class="bi bi-wallet2"></i>
                                ₹{{ number_format($day['accommodation']->price_per_night, 2) }}/night
                            </span>
                        @endif
                    </div>
                @endif

                {{-- Activities for this day --}}
                @if($day['activities']->isEmpty())
                    <div class="itin-day-empty">
                        <span>🌿</span>
                        <p>Free day — no activities planned</p>
                        <a href="{{ route('trips.activities.create', $trip->_id) }}?date={{ $day['date_str'] }}"
                           class="itin-add-activity-btn">
                            + Add Activity
                        </a>
                    </div>
                @else
                    <div class="itin-activities-list">
                        @foreach($day['activities'] as $index => $activity)
                        <div class="itin-activity-item {{ $index % 2 === 0 ? '' : 'itin-alt' }}">
                            {{-- Time --}}
                            <div class="itin-time-col">
                                @if($activity->activity_time)
                                    <span class="itin-time">
                                        {{ \Carbon\Carbon::parse($activity->activity_time)->format('H:i') }}
                                    </span>
                                @else
                                    <span class="itin-time itin-time-flexible">Flexible</span>
                                @endif
                                <div class="itin-timeline-dot" style="background: {{ $activity->category_color }}"></div>
                            </div>

                            {{-- Activity Card --}}
                            <div class="itin-activity-card" style="--act-color: {{ $activity->category_color }}">
                                <div class="iac-left">
                                    <div class="iac-category-icon" style="background: {{ $activity->category_color }}20; color: {{ $activity->category_color }}">
                                        {{ $activity->category_icon }}
                                    </div>
                                </div>
                                <div class="iac-body">
                                    <div class="iac-header">
                                        <h4 class="iac-name">{{ $activity->activity_name }}</h4>
                                        <div class="iac-badges">
                                            <span class="iac-cat-badge" style="background: {{ $activity->category_color }}20; color: {{ $activity->category_color }}">
                                                {{ ucfirst($activity->category) }}
                                            </span>
                                            @if($activity->booking_required)
                                                <span class="iac-booking-badge">
                                                    <i class="bi bi-ticket-perforated"></i> Booking req.
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($activity->description)
                                        <p class="iac-desc">{{ $activity->description }}</p>
                                    @endif

                                    <div class="iac-meta-row">
                                        @if($activity->location)
                                            <span class="iac-meta-item">
                                                <i class="bi bi-geo-alt"></i>
                                                {{ $activity->location }}
                                            </span>
                                        @endif
                                        @if($activity->duration_minutes)
                                            <span class="iac-meta-item">
                                                <i class="bi bi-clock"></i>
                                                {{ $activity->duration_formatted }}
                                            </span>
                                        @endif
                                        @if($activity->cost)
                                            <span class="iac-meta-item">
                                                <i class="bi bi-wallet2"></i>
                                                ₹{{ number_format($activity->cost, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="iac-actions">
                                    <a href="{{ route('trips.activities.edit', [$trip->_id, $activity->_id]) }}"
                                       class="iac-edit-btn" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        {{-- Daily cost total --}}
                            @php
                                $dayActivityCost = $day['activities']->sum('cost');
                                $dayTotalCost = $dayActivityCost + $day['accommodation_cost'];
                            @endphp

                            @if($dayActivityCost > 0 || $day['accommodation_cost'] > 0)
                                <div class="day-cost-total">
                                    <i class="bi bi-wallet2"></i>
                                    <span>
                                        Day Total:
                                        <strong>₹{{ number_format($dayTotalCost, 2) }}</strong>
                                        @if($day['accommodation_cost'] > 0)
                                            <span class="day-cost-breakdown"> · Accommodation ₹{{ number_format($day['accommodation_cost'], 2) }}</span>
                                        @endif
                                        @if($dayActivityCost > 0)
                                            <span class="day-cost-breakdown"> · Activities ₹{{ number_format($dayActivityCost, 2) }}</span>
                                        @endif
                                    </span>
                                </div>
                            @endif
                    </div>

                    {{-- Add more activities link --}}
                    <div class="itin-add-more">
                        <a href="{{ route('trips.activities.create', $trip->_id) }}">
                            <i class="bi bi-plus-circle"></i> Add more activities
                        </a>
                    </div>
                @endif
            </div>
        </div>
        @endforeach

        {{-- Grand Total --}}
        @if($totalTripCost > 0)
        <div class="itin-grand-total">
            <div class="igt-content">
                <span>Total Activities Cost:</span>
                <strong>₹{{ number_format($totalActivityCost, 2) }}</strong>
            </div>
            @if($totalAccommodationCost > 0)
            <div class="igt-content">
                <span>Total Accommodation Cost:</span>
                <strong>₹{{ number_format($totalAccommodationCost, 2) }}</strong>
            </div>
            @endif
            <div class="igt-content">
                <span>Total Trip Cost:</span>
                <strong>₹{{ number_format($totalTripCost, 2) }}</strong>
            </div>
            @if($trip->budget)
            <div class="igt-remaining">
                <span>Remaining Budget:</span>
                <strong style="color: {{ $trip->budget - $totalTripCost >= 0 ? '#22c55e' : '#ef4444' }}">
                    ₹{{ number_format($trip->budget - $totalTripCost, 2) }}
                </strong>
            </div>
            @endif
        </div>
        @endif
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
// Jump to today
document.addEventListener('DOMContentLoaded', function() {
    const today = document.querySelector('.is-today');
    if (today) {
        setTimeout(() => {
            today.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 300);
    }
});
</script>
@endpush
