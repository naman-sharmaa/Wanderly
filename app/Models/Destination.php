<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

/**
 * Destination Model - MongoDB Collection: destinations
 *
 * Relationships:
 * - belongsTo Trip
 */
class Destination extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'destinations';

    protected $fillable = [
        'trip_id',
        'destination_name',
        'country',
        'city',
        'arrival_date',
        'departure_date',
        'notes',
        'latitude',
        'longitude',
        'image_url',
    ];

    protected $casts = [
        'arrival_date'   => 'date',
        'departure_date' => 'date',
        'latitude'       => 'float',
        'longitude'      => 'float',
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
     * Number of nights at destination.
     */
    public function getNightsAttribute(): int
    {
        if ($this->arrival_date && $this->departure_date) {
            return (int) $this->arrival_date->diffInDays($this->departure_date);
        }
        return 0;
    }
}
