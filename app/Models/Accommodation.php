<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

/**
 * Accommodation Model - MongoDB Collection: accommodations
 *
 * Relationships:
 * - belongsTo Trip
 */
class Accommodation extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'accommodations';

    protected $fillable = [
        'trip_id',
        'hotel_name',
        'check_in',
        'check_out',
        'address',
        'city',
        'country',
        'booking_reference',
        'room_type',
        'price_per_night',
        'notes',
        'rating',
    ];

    protected $casts = [
        'check_in'       => 'date',
        'check_out'      => 'date',
        'price_per_night'=> 'float',
        'rating'         => 'integer',
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
     * Total nights stayed.
     */
    public function getNightsAttribute(): int
    {
        if ($this->check_in && $this->check_out) {
            return (int) $this->check_in->diffInDays($this->check_out);
        }
        return 0;
    }

    /**
     * Total accommodation cost.
     */
    public function getTotalCostAttribute(): float
    {
        return $this->nights * ($this->price_per_night ?? 0);
    }

    /**
     * Star rating display.
     */
    public function getStarsAttribute(): string
    {
        return str_repeat('★', $this->rating ?? 0) . str_repeat('☆', 5 - ($this->rating ?? 0));
    }
}
