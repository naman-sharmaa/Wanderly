@extends('layouts.app')
@section('title', 'Edit Destination — Wanderly')

@section('content')
<div class="form-page">
    <div class="form-page-header">
        <a href="{{ route('trips.show', $trip->_id) }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to {{ $trip->title }}
        </a>
        <h1>Edit Destination ✏️</h1>
        <p class="header-subtitle">Update destination details</p>
    </div>

    <div class="form-card">
        @include('components.errors')

        <form action="{{ route('trips.destinations.update', [$trip->_id, $destination->_id]) }}" method="POST" class="trip-form">
            @csrf
            @method('PUT')

            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">01</span>
                    <div><h2>Destination Info</h2></div>
                </div>

                <div class="form-group">
                    <label for="destination_name" class="form-label required">Destination Name</label>
                    <input type="text" id="destination_name" name="destination_name"
                        class="form-input @error('destination_name') is-invalid @enderror"
                        value="{{ old('destination_name', $destination->destination_name) }}"
                        required maxlength="255">
                    @error('destination_name')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="city" class="form-label">City</label>
                        <input type="text" id="city" name="city"
                            class="form-input" value="{{ old('city', $destination->city) }}" maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" id="country" name="country"
                            class="form-input" value="{{ old('country', $destination->country) }}" maxlength="100">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">02</span>
                    <div><h2>Stay Duration</h2></div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="arrival_date" class="form-label required">Arrival Date</label>
                        <input type="date" id="arrival_date" name="arrival_date"
                            class="form-input @error('arrival_date') is-invalid @enderror"
                            value="{{ old('arrival_date', $destination->arrival_date?->format('Y-m-d')) }}" required>
                        @error('arrival_date')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="departure_date" class="form-label required">Departure Date</label>
                        <input type="date" id="departure_date" name="departure_date"
                            class="form-input @error('departure_date') is-invalid @enderror"
                            value="{{ old('departure_date', $destination->departure_date?->format('Y-m-d')) }}" required>
                        @error('departure_date')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">03</span>
                    <div><h2>Notes</h2></div>
                </div>
                <div class="form-group">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea id="notes" name="notes" class="form-input form-textarea"
                        rows="3" maxlength="2000">{{ old('notes', $destination->notes) }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('trips.show', $trip->_id) }}" class="btn-cancel">
                    <i class="bi bi-x"></i> Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-lg"></i> Update Destination
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
