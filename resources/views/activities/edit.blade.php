@extends('layouts.app')
@section('title', 'Edit Activity — Wanderly')

@section('content')
<div class="form-page">
    <div class="form-page-header">
        <a href="{{ route('trips.show', $trip->_id) }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to {{ $trip->title }}
        </a>
        <h1>Edit Activity ✏️</h1>
        <p class="header-subtitle">Update your activity details</p>
    </div>

    <div class="form-card">
        @include('components.errors')

        <form action="{{ route('trips.activities.update', [$trip->_id, $activity->_id]) }}" method="POST" class="trip-form">
            @csrf
            @method('PUT')

            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">01</span>
                    <div><h2>Activity Details</h2></div>
                </div>

                <div class="form-group">
                    <label for="activity_name" class="form-label required">Activity Name</label>
                    <input type="text" id="activity_name" name="activity_name"
                        class="form-input @error('activity_name') is-invalid @enderror"
                        value="{{ old('activity_name', $activity->activity_name) }}" required maxlength="255">
                    @error('activity_name')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label required">Category</label>
                    <div class="category-grid">
                        @foreach([
                            ['value' => 'sightseeing', 'emoji' => '🏛️', 'label' => 'Sightseeing'],
                            ['value' => 'food',        'emoji' => '🍽️', 'label' => 'Food & Drink'],
                            ['value' => 'adventure',   'emoji' => '🧗',  'label' => 'Adventure'],
                            ['value' => 'culture',     'emoji' => '🎭',  'label' => 'Culture'],
                            ['value' => 'shopping',    'emoji' => '🛍️', 'label' => 'Shopping'],
                            ['value' => 'transport',   'emoji' => '🚂',  'label' => 'Transport'],
                            ['value' => 'beach',       'emoji' => '🏖️', 'label' => 'Beach'],
                            ['value' => 'nightlife',   'emoji' => '🌃',  'label' => 'Nightlife'],
                            ['value' => 'other',       'emoji' => '📍',  'label' => 'Other'],
                        ] as $cat)
                        <label class="category-option">
                            <input type="radio" name="category" value="{{ $cat['value'] }}"
                                {{ old('category', $activity->category) === $cat['value'] ? 'checked' : '' }}>
                            <div class="cat-option-card">
                                <span class="cat-emoji">{{ $cat['emoji'] }}</span>
                                <span class="cat-label">{{ $cat['label'] }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-input form-textarea"
                        rows="3" maxlength="2000">{{ old('description', $activity->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="location" class="form-label">Location / Address</label>
                    <input type="text" id="location" name="location" class="form-input"
                        value="{{ old('location', $activity->location) }}" maxlength="255">
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">02</span>
                    <div><h2>Date &amp; Time</h2></div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="activity_date" class="form-label required">Date</label>
                        <input type="date" id="activity_date" name="activity_date"
                            class="form-input @error('activity_date') is-invalid @enderror"
                            value="{{ old('activity_date', $activity->activity_date?->format('Y-m-d')) }}" required>
                        @error('activity_date')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="activity_time" class="form-label">Time</label>
                        <input type="time" id="activity_time" name="activity_time"
                            class="form-input" value="{{ old('activity_time', $activity->activity_time) }}">
                    </div>
                </div>

                <div class="form-group" style="max-width: 260px;">
                    <label for="duration_minutes" class="form-label">Duration (minutes)</label>
                    <input type="number" id="duration_minutes" name="duration_minutes"
                        class="form-input"
                        value="{{ old('duration_minutes', $activity->duration_minutes) }}" min="1">
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">03</span>
                    <div><h2>Cost &amp; Booking</h2></div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="cost" class="form-label">Cost (INR)</label>
                        <div class="input-with-prefix">
                            <span class="input-prefix">₹</span>
                            <input type="number" id="cost" name="cost"
                                class="form-input with-prefix"
                                value="{{ old('cost', $activity->cost) }}" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="booking_reference" class="form-label">Booking Reference</label>
                        <input type="text" id="booking_reference" name="booking_reference"
                            class="form-input"
                            value="{{ old('booking_reference', $activity->booking_reference) }}" maxlength="100">
                    </div>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="booking_required" value="1"
                            {{ old('booking_required', $activity->booking_required) ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        Booking required for this activity
                    </label>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">04</span>
                    <div><h2>Notes</h2></div>
                </div>
                <div class="form-group">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea id="notes" name="notes" class="form-input form-textarea"
                        rows="3" maxlength="2000">{{ old('notes', $activity->notes) }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('trips.show', $trip->_id) }}" class="btn-cancel">
                    <i class="bi bi-x"></i> Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-lg"></i> Update Activity
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
