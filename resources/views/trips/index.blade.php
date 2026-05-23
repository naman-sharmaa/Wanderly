@extends('layouts.app')

@section('title', 'My Trips — Wanderly')

@section('content')
<div class="trips-page">

    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-content">
            <div>
                <h1><i class="bi bi-map"></i> My Trips</h1>
                <p class="header-subtitle">{{ $trips->count() }} trip(s) planned</p>
            </div>
            <a href="{{ route('trips.create') }}" class="btn-primary-action">
                <i class="bi bi-plus-lg"></i> Plan New Trip
            </a>
        </div>
    </div>

    {{-- Search & Filter Bar --}}
    <div class="filter-bar">
        <form action="{{ route('trips.index') }}" method="GET" class="filter-form" id="filterForm">
            <div class="search-input-wrap">
                <i class="bi bi-search search-icon"></i>
                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="Search trips..."
                    value="{{ request('search') }}"
                    autocomplete="off"
                >
                @if(request('search'))
                    <a href="{{ route('trips.index') }}" class="clear-search"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>

            <div class="filter-selects">
                <select name="type" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                    <option value="">All Types</option>
                    @foreach(['solo', 'couple', 'family', 'group', 'business'] as $type)
                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                    <option value="">All Status</option>
                    @foreach(['planning', 'active', 'completed', 'cancelled'] as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>

                <select name="sort" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="start_date" {{ request('sort') === 'start_date' ? 'selected' : '' }}>By Start Date</option>
                    <option value="budget" {{ request('sort') === 'budget' ? 'selected' : '' }}>By Budget</option>
                </select>

                <button type="submit" class="filter-btn">
                    <i class="bi bi-funnel"></i> Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Trips Grid --}}
    @if($trips->isEmpty())
        <div class="empty-state large-empty">
            <div class="empty-illustration">
                <span>🌍</span>
                <div class="empty-plane">✈️</div>
            </div>
            <h2>No trips found</h2>
            @if(request()->hasAny(['search', 'type', 'status']))
                <p>Try adjusting your search or filters.</p>
                <a href="{{ route('trips.index') }}" class="btn-empty-action">Clear Filters</a>
            @else
                <p>Your travel adventures await. Create your first trip!</p>
                <a href="{{ route('trips.create') }}" class="btn-empty-action">
                    <i class="bi bi-plus-lg"></i> Plan My First Trip
                </a>
            @endif
        </div>
    @else
        <div class="trips-grid">
            @foreach($trips as $trip)
            <div class="trip-card-full" data-trip-id="{{ $trip->_id }}">
                <div class="tcf-header">
                    <div class="tcf-type-badge">{{ $trip->trip_type_label }}</div>
                    <span class="tcf-status-badge badge-{{ $trip->status_color }}">
                        {{ ucfirst($trip->status) }}
                    </span>
                </div>

                <div class="tcf-body">
                    <h3 class="tcf-title">{{ $trip->title }}</h3>
                    @if($trip->description)
                        <p class="tcf-desc">{{ Str::limit($trip->description, 100) }}</p>
                    @endif

                    <div class="tcf-meta">
                        <div class="tcf-meta-item">
                            <i class="bi bi-calendar3"></i>
                            <span>{{ $trip->start_date?->format('M d') }} – {{ $trip->end_date?->format('M d, Y') }}</span>
                        </div>
                        <div class="tcf-meta-item">
                            <i class="bi bi-clock"></i>
                            <span>{{ $trip->duration }} days</span>
                        </div>
                        <div class="tcf-meta-item">
                            <i class="bi bi-people"></i>
                            <span>{{ $trip->travelers_count }} traveler(s)</span>
                        </div>
                        @if($trip->budget)
                        <div class="tcf-meta-item">
                            <i class="bi bi-wallet2"></i>
                            <span>₹{{ number_format($trip->budget, 0) }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="tcf-footer">
                    <a href="{{ route('trips.show', $trip->_id) }}" class="tcf-btn-primary">
                        <i class="bi bi-eye"></i> View Trip
                    </a>
                    <a href="{{ route('trips.itinerary', $trip->_id) }}" class="tcf-btn-secondary">
                        <i class="bi bi-calendar3"></i> Itinerary
                    </a>
                    <div class="tcf-actions-menu">
                        <button class="tcf-dots-btn" onclick="toggleTripMenu('menu-{{ $trip->_id }}')">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="tcf-dropdown" id="menu-{{ $trip->_id }}">
                            <a href="{{ route('trips.edit', $trip->_id) }}" class="tcf-dd-item">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('trips.destroy', $trip->_id) }}" method="POST"
                                  onsubmit="return confirm('Delete trip \'{{ $trip->title }}\'? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="tcf-dd-item text-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function toggleTripMenu(id) {
    document.querySelectorAll('.tcf-dropdown').forEach(d => {
        if (d.id !== id) d.classList.remove('open');
    });
    document.getElementById(id).classList.toggle('open');
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.tcf-actions-menu')) {
        document.querySelectorAll('.tcf-dropdown').forEach(d => d.classList.remove('open'));
    }
});
</script>
@endpush
