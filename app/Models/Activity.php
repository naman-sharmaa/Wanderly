<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

/**
 * Activity Model - MongoDB Collection: activities
 *
 * Relationships:
 * - belongsTo Trip
 */
class Activity extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'activities';

    protected $fillable = [
        'trip_id',
        'activity_name',
        'activity_date',
        'activity_time',
        'category',     // sightseeing | food | adventure | culture | shopping | transport | other
        'description',
        'location',
        'cost',
        'duration_minutes',
        'booking_required',
        'booking_reference',
        'notes',
    ];

    protected $casts = [
        'activity_date'    => 'date',
        'activity_time'    => 'datetime',
        'cost'             => 'float',
        'duration_minutes' => 'integer',
        'booking_required' => 'boolean',
    ];

    // ----------------------------------------------------------------
    // Relationships
    // ----------------------------------------------------------------

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }

    // ----------------------------------------------------------------
    // Accessors
    // ----------------------------------------------------------------

    /**
     * Category emoji icon.
     */
    public function getCategoryIconAttribute(): string
    {
        return match($this->category) {
            'sightseeing' => '🏛️',
            'food'        => '🍽️',
            'adventure'   => '🧗',
            'culture'     => '🎭',
            'shopping'    => '🛍️',
            'transport'   => '🚂',
            'beach'       => '🏖️',
            'nightlife'   => '🌃',
            default       => '📍',
        };
    }

    /**
     * Category badge color.
     */
    public function getCategoryColorAttribute(): string
    {
        return match($this->category) {
            'sightseeing' => '#4361ee',
            'food'        => '#f72585',
            'adventure'   => '#7209b7',
            'culture'     => '#3a0ca3',
            'shopping'    => '#f77f00',
            'transport'   => '#4cc9f0',
            'beach'       => '#06d6a0',
            'nightlife'   => '#560bad',
            default       => '#adb5bd',
        };
    }

    /**
     * Duration in hours and minutes.
     */
    public function getDurationFormattedAttribute(): string
    {
        if (!$this->duration_minutes) return 'N/A';
        $hours   = intdiv($this->duration_minutes, 60);
        $minutes = $this->duration_minutes % 60;
        if ($hours > 0 && $minutes > 0) return "{$hours}h {$minutes}m";
        if ($hours > 0) return "{$hours}h";
        return "{$minutes}m";
    }

    // ----------------------------------------------------------------
    // Scopes
    // ----------------------------------------------------------------

    public function scopeForDate($query, $date)
    {
        return $query->where('activity_date', $date);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
