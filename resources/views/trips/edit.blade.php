@extends('layouts.app')

@section('title', 'Edit Trip — Wanderly')

@section('content')
<div class="form-page">
    <div class="form-page-header">
        <a href="{{ route('trips.show', $trip->_id) }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to Trip
        </a>
        <h1>Edit Trip ✏️</h1>
        <p class="header-subtitle">Update your trip details</p>
    </div>

    <div class="form-card">
        @include('components.errors')

        <form action="{{ route('trips.update', $trip->_id) }}" method="POST" class="trip-form" id="tripForm">
            @csrf
            @method('PUT')

            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">01</span>
                    <div><h2>Trip Details</h2></div>
                </div>

                <div class="form-group">
                    <label for="title" class="form-label required">Trip Title</label>
                    <input type="text" id="title" name="title"
                        class="form-input @error('title') is-invalid @enderror"
                        value="{{ old('title', $trip->title) }}" required maxlength="255">
                    @error('title')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description"
                        class="form-input form-textarea @error('description') is-invalid @enderror"
                        rows="3" maxlength="2000">{{ old('description', $trip->description) }}</textarea>
                    @error('description')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">02</span>
                    <div><h2>Travel Dates</h2></div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="start_date" class="form-label required">Departure Date</label>
                        <input type="date" id="start_date" name="start_date"
                            class="form-input @error('start_date') is-invalid @enderror"
                            value="{{ old('start_date', $trip->start_date?->format('Y-m-d')) }}" required>
                        @error('start_date')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="end_date" class="form-label required">Return Date</label>
                        <input type="date" id="end_date" name="end_date"
                            class="form-input @error('end_date') is-invalid @enderror"
                            value="{{ old('end_date', $trip->end_date?->format('Y-m-d')) }}" required>
                        @error('end_date')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">03</span>
                    <div><h2>Travelers</h2></div>
                </div>

                <div class="form-group">
                    <label class="form-label required">Trip Type</label>
                    <div class="trip-type-grid">
                        @foreach([
                            ['value' => 'solo',     'emoji' => '🧳', 'label' => 'Solo'],
                            ['value' => 'couple',   'emoji' => '💑', 'label' => 'Couple'],
                            ['value' => 'family',   'emoji' => '👨‍👩‍👧', 'label' => 'Family'],
                            ['value' => 'group',    'emoji' => '👥', 'label' => 'Group'],
                            ['value' => 'business', 'emoji' => '💼', 'label' => 'Business'],
                        ] as $type)
                        <label class="trip-type-option">
                            <input type="radio" name="trip_type" value="{{ $type['value'] }}"
                                {{ old('trip_type', $trip->trip_type) === $type['value'] ? 'checked' : '' }}>
                            <div class="type-option-card">
                                <span class="type-emoji">{{ $type['emoji'] }}</span>
                                <span class="type-label">{{ $type['label'] }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="travelers_count" class="form-label required">Number of Travelers</label>
                        <input type="number" id="travelers_count" name="travelers_count"
                            class="form-input @error('travelers_count') is-invalid @enderror"
                            value="{{ old('travelers_count', $trip->travelers_count) }}" min="1" max="200" required>
                        @error('travelers_count')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label required">Status</label>
                        <select id="status" name="status" class="form-input form-select @error('status') is-invalid @enderror">
                            @foreach(['planning', 'active', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ old('status', $trip->status) === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">04</span>
                    <div><h2>Budget</h2></div>
                </div>

                <div class="form-group" style="max-width: 350px;">
                    <label for="budget" class="form-label">Total Budget (INR)</label>
                    <div class="input-with-prefix">
                        <span class="input-prefix">₹</span>
                        <input type="number" id="budget" name="budget"
                            class="form-input with-prefix @error('budget') is-invalid @enderror"
                            value="{{ old('budget', $trip->budget) }}" placeholder="2500" min="0" step="0.01">
                    </div>
                    @error('budget')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('trips.show', $trip->_id) }}" class="btn-cancel">
                    <i class="bi bi-x"></i> Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-lg"></i> Update Trip
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
