@extends('layouts.app')

@section('title', 'Plan New Trip — Wanderly')

@section('content')
<div class="form-page">
    <div class="form-page-header">
        <a href="{{ route('trips.index') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> My Trips
        </a>
        <h1>Plan New Trip ✈️</h1>
        <p class="header-subtitle">Fill in the details and let's start your adventure!</p>
    </div>

    <div class="form-card">
        @include('components.errors')

        <form action="{{ route('trips.store') }}" method="POST" class="trip-form" id="tripForm">
            @csrf

            {{-- Basic Info --}}
            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">01</span>
                    <div>
                        <h2>Trip Details</h2>
                        <p>Give your trip a name and description</p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="title" class="form-label required">Trip Title</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        class="form-input @error('title') is-invalid @enderror"
                        value="{{ old('title') }}"
                        placeholder="e.g. Summer in Europe 2025"
                        required
                        maxlength="255"
                    >
                    @error('title')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description <span class="optional">(optional)</span></label>
                    <textarea
                        id="description"
                        name="description"
                        class="form-input form-textarea @error('description') is-invalid @enderror"
                        placeholder="What's this trip about? Any special plans..."
                        rows="3"
                        maxlength="2000"
                    >{{ old('description') }}</textarea>
                    @error('description')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- Dates --}}
            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">02</span>
                    <div>
                        <h2>Travel Dates</h2>
                        <p>When are you leaving and returning?</p>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="start_date" class="form-label required">Departure Date</label>
                        <input
                            type="date"
                            id="start_date"
                            name="start_date"
                            class="form-input @error('start_date') is-invalid @enderror"
                            value="{{ old('start_date') }}"
                            required
                        >
                        @error('start_date')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="end_date" class="form-label required">Return Date</label>
                        <input
                            type="date"
                            id="end_date"
                            name="end_date"
                            class="form-input @error('end_date') is-invalid @enderror"
                            value="{{ old('end_date') }}"
                            required
                        >
                        @error('end_date')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="date-preview" id="datePreview" style="display:none;">
                    <i class="bi bi-info-circle"></i>
                    <span id="datePreviewText"></span>
                </div>
            </div>

            {{-- Trip Type & Travelers --}}
            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">03</span>
                    <div>
                        <h2>Travelers</h2>
                        <p>Who's coming along?</p>
                    </div>
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
                                {{ old('trip_type', 'solo') === $type['value'] ? 'checked' : '' }}>
                            <div class="type-option-card">
                                <span class="type-emoji">{{ $type['emoji'] }}</span>
                                <span class="type-label">{{ $type['label'] }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('trip_type')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group" style="max-width: 250px;">
                    <label for="travelers_count" class="form-label required">Number of Travelers</label>
                    <input
                        type="number"
                        id="travelers_count"
                        name="travelers_count"
                        class="form-input @error('travelers_count') is-invalid @enderror"
                        value="{{ old('travelers_count', 1) }}"
                        min="1"
                        max="200"
                        required
                    >
                    @error('travelers_count')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- Budget --}}
            <div class="form-section">
                <div class="form-section-header">
                    <span class="section-num">04</span>
                    <div>
                        <h2>Budget</h2>
                        <p>What's your estimated total budget?</p>
                    </div>
                </div>

                <div class="form-group" style="max-width: 350px;">
                    <label for="budget" class="form-label">Total Budget (INR) <span class="optional">(optional)</span></label>
                    <div class="input-with-prefix">
                        <span class="input-prefix">₹</span>
                        <input
                            type="number"
                            id="budget"
                            name="budget"
                            class="form-input with-prefix @error('budget') is-invalid @enderror"
                            value="{{ old('budget') }}"
                            placeholder="2500"
                            min="0"
                            step="0.01"
                        >
                    </div>
                    @error('budget')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="form-actions">
                <a href="{{ route('trips.index') }}" class="btn-cancel">
                    <i class="bi bi-x"></i> Cancel
                </a>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <span class="btn-text">
                        <i class="bi bi-rocket-takeoff"></i> Create Trip
                    </span>
                    <span class="btn-loading" style="display:none;">
                        <i class="bi bi-arrow-repeat spin"></i> Creating...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Date preview
const startDate = document.getElementById('start_date');
const endDate = document.getElementById('end_date');
const preview = document.getElementById('datePreview');
const previewText = document.getElementById('datePreviewText');

function updateDatePreview() {
    if (startDate.value && endDate.value) {
        const start = new Date(startDate.value);
        const end = new Date(endDate.value);
        const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
        if (days >= 1) {
            preview.style.display = 'flex';
            previewText.textContent = `Trip duration: ${days} day(s)`;
        } else {
            preview.style.display = 'none';
        }
    }
}

startDate.addEventListener('change', function() {
    if (endDate.value && endDate.value < this.value) {
        endDate.value = this.value;
    }
    endDate.min = this.value;
    updateDatePreview();
});
endDate.addEventListener('change', updateDatePreview);

// Form loading state
document.getElementById('tripForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.querySelector('.btn-text').style.display = 'none';
    btn.querySelector('.btn-loading').style.display = 'inline-flex';
    btn.disabled = true;
});
</script>
@endpush
