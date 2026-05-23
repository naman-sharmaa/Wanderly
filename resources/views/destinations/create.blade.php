@extends('layouts.app')
@section('title', 'Add Destination — Wanderly')

@section('content')
<div class="form-page">
    <div class="form-page-header">
        <a href="{{ route('trips.show', $trip->_id) }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to {{ $trip->title }}
        </a>
        <h1>Add Destination 📍</h1>
        <p class="header-subtitle">Where are you heading on this trip?</p>
    </div>

    <div class="form-card">
        @include('components.errors')

        <form action="{{ route('trips.destinations.store', $trip->_id) }}" method="POST" class="trip-form">
            @csrf

            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">01</span>
                    <div><h2>Destination Info</h2><p>Where are you going?</p></div>
                </div>

                <div class="form-group">
                    <label for="destination_name" class="form-label required">Destination Name</label>
                    <input type="text" id="destination_name" name="destination_name"
                        class="form-input @error('destination_name') is-invalid @enderror"
                        value="{{ old('destination_name') }}"
                        placeholder="e.g. Paris, Eiffel Tower, Santorini..." required maxlength="255">
                    @error('destination_name')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="city" class="form-label">City</label>
                        <input type="text" id="city" name="city"
                            class="form-input @error('city') is-invalid @enderror"
                            value="{{ old('city') }}" placeholder="e.g. Paris" maxlength="100">
                        @error('city')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" id="country" name="country"
                            class="form-input @error('country') is-invalid @enderror"
                            value="{{ old('country') }}" placeholder="e.g. France" maxlength="100">
                        @error('country')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">02</span>
                    <div><h2>Stay Duration</h2><p>When do you arrive and leave?</p></div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="arrival_date" class="form-label required">Arrival Date</label>
                        <input type="date" id="arrival_date" name="arrival_date"
                            class="form-input @error('arrival_date') is-invalid @enderror"
                            value="{{ old('arrival_date', $trip->start_date?->format('Y-m-d')) }}"
                            min="{{ $trip->start_date?->format('Y-m-d') }}"
                            max="{{ $trip->end_date?->format('Y-m-d') }}" required>
                        @error('arrival_date')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="departure_date" class="form-label required">Departure Date</label>
                        <input type="date" id="departure_date" name="departure_date"
                            class="form-input @error('departure_date') is-invalid @enderror"
                            value="{{ old('departure_date', $trip->end_date?->format('Y-m-d')) }}"
                            min="{{ $trip->start_date?->format('Y-m-d') }}"
                            max="{{ $trip->end_date?->format('Y-m-d') }}" required>
                        @error('departure_date')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">03</span>
                    <div><h2>Notes</h2><p>Any additional info about this destination</p></div>
                </div>

                <div class="form-group">
                    <label for="notes" class="form-label">Notes <span class="optional">(optional)</span></label>
                    <textarea id="notes" name="notes"
                        class="form-input form-textarea @error('notes') is-invalid @enderror"
                        placeholder="Things to remember, visa info, local tips..."
                        rows="3" maxlength="2000">{{ old('notes') }}</textarea>
                    @error('notes')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('trips.show', $trip->_id) }}" class="btn-cancel">
                    <i class="bi bi-x"></i> Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <i class="bi bi-geo-alt"></i> Add Destination
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
