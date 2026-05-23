@extends('layouts.app')
@section('title', 'Edit Accommodation — Wanderly')

@section('content')
<div class="form-page">
    <div class="form-page-header">
        <a href="{{ route('trips.show', $trip->_id) }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to {{ $trip->title }}
        </a>
        <h1>Edit Accommodation ✏️</h1>
    </div>

    <div class="form-card">
        @include('components.errors')

        <form action="{{ route('trips.accommodations.update', [$trip->_id, $accommodation->_id]) }}" method="POST" class="trip-form">
            @csrf
            @method('PUT')

            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">01</span>
                    <div><h2>Property Details</h2></div>
                </div>

                <div class="form-group">
                    <label for="hotel_name" class="form-label required">Hotel / Property Name</label>
                    <input type="text" id="hotel_name" name="hotel_name"
                        class="form-input @error('hotel_name') is-invalid @enderror"
                        value="{{ old('hotel_name', $accommodation->hotel_name) }}" required maxlength="255">
                    @error('hotel_name')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" id="address" name="address" class="form-input"
                        value="{{ old('address', $accommodation->address) }}" maxlength="500">
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="city" class="form-label">City</label>
                        <input type="text" id="city" name="city" class="form-input"
                            value="{{ old('city', $accommodation->city) }}" maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" id="country" name="country" class="form-input"
                            value="{{ old('country', $accommodation->country) }}" maxlength="100">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="room_type" class="form-label">Room Type</label>
                        <input type="text" id="room_type" name="room_type" class="form-input"
                            value="{{ old('room_type', $accommodation->room_type) }}" maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="rating" class="form-label">Star Rating</label>
                        <div class="star-rating-input">
                            @for($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                                {{ old('rating', $accommodation->rating) == $i ? 'checked' : '' }}>
                            <label for="star{{ $i }}" title="{{ $i }} stars">★</label>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">02</span>
                    <div><h2>Check-in / Check-out</h2></div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="check_in" class="form-label required">Check-in Date</label>
                        <input type="date" id="check_in" name="check_in"
                            class="form-input @error('check_in') is-invalid @enderror"
                            value="{{ old('check_in', $accommodation->check_in?->format('Y-m-d')) }}" required>
                        @error('check_in')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="check_out" class="form-label required">Check-out Date</label>
                        <input type="date" id="check_out" name="check_out"
                            class="form-input @error('check_out') is-invalid @enderror"
                            value="{{ old('check_out', $accommodation->check_out?->format('Y-m-d')) }}" required>
                        @error('check_out')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="price_per_night" class="form-label">Price per Night (INR)</label>
                        <div class="input-with-prefix">
                            <span class="input-prefix">₹</span>
                            <input type="number" id="price_per_night" name="price_per_night"
                                class="form-input with-prefix"
                                value="{{ old('price_per_night', $accommodation->price_per_night) }}"
                                min="0" step="0.01">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="booking_reference" class="form-label">Booking Reference</label>
                        <input type="text" id="booking_reference" name="booking_reference"
                            class="form-input"
                            value="{{ old('booking_reference', $accommodation->booking_reference) }}" maxlength="100">
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
                        rows="3" maxlength="2000">{{ old('notes', $accommodation->notes) }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('trips.show', $trip->_id) }}" class="btn-cancel">
                    <i class="bi bi-x"></i> Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-lg"></i> Update Accommodation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
