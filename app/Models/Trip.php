<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

/**
 * Trip Model - MongoDB Collection: trips
 *
 * Relationships:
 * - belongsTo User
 * - hasMany Destination
 * - hasMany Accommodation
 * - hasMany Activity
 */
class Trip extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'trips';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'budget',
        'travelers_count',
        'trip_type',   // solo | couple | family | group | business
        'status',      // planning | active | completed | cancelled
        'cover_image',
        'tags',        // array of strings
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'start_date'     => 'date',
        'end_date'       => 'date',
        'budget'         => 'float',
        'travelers_count'=> 'integer',
        'tags'           => 'array',
    ];

    // ----------------------------------------------------------------
    // Relationships
    // ----------------------------------------------------------------

    /**
     * Trip belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Trip has many destinations.
     */
    public function destinations()
    {
        return $this->hasMany(Destination::class, 'trip_id')->orderBy('arrival_date', 'asc');
    }

    /**
     * Trip has many accommodations.
     */
    public function accommodations()
    {
        return $this->hasMany(Accommodation::class, 'trip_id')->orderBy('check_in', 'asc');
    }

    /**
     * Trip has many activities.
     */
    public function activities()
    {
        return $this->hasMany(Activity::class, 'trip_id')->orderBy('activity_time', 'asc');
    }

    // ----------------------------------------------------------------
    // Accessors & Helpers
    // ----------------------------------------------------------------

    /**
     * Duration in days.
     */
    public function getDurationAttribute(): int
    {
        if ($this->start_date && $this->end_date) {
            return (int) $this->start_date->diffInDays($this->end_date) + 1;
        }
        return 0;
    }

    /**
     * Human-readable trip type label.
     */
    public function getTripTypeLabelAttribute(): string
    {
        return match($this->trip_type) {
            'solo'     => '🧳 Solo',
            'couple'   => '💑 Couple',
            'family'   => '👨‍👩‍👧 Family',
            'group'    => '👥 Group',
            'business' => '💼 Business',
            default    => ucfirst($this->trip_type ?? 'Unknown'),
        };
    }

    /**
     * Status badge color for UI.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'planning'  => 'warning',
            'active'    => 'success',
            'completed' => 'secondary',
            'cancelled' => 'danger',
            default     => 'info',
        };
    }

    /**
     * Check if trip is upcoming.
     */
    public function getIsUpcomingAttribute(): bool
    {
        return $this->start_date && $this->start_date->isFuture();
    }

    /**
     * Get total activities cost.
     */
    public function getTotalActivitiesCostAttribute(): float
    {
        return $this->activities()->sum('cost') ?? 0;
    }

    // ----------------------------------------------------------------
    // Scopes
    // ----------------------------------------------------------------

    /**
     * Filter by authenticated user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Filter upcoming trips.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now());
    }

    /**
     * Search by title or description.
     */
    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }
}
