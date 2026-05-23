<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;

/**
 * User Model - MongoDB Collection: users
 *
 * Relationships:
 * - hasMany Trips
 */
class User extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable, Notifiable, Authorizable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'firebase_uid',
        'auth_provider',
        'preferences', // JSON: dark_mode, currency, timezone
    ];

    /**
     * Hidden attributes (never serialized)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'preferences'       => 'array',
        ];
    }

    // ----------------------------------------------------------------
    // Relationships
    // ----------------------------------------------------------------

    /**
     * A user can have many trips.
     */
    public function trips()
    {
        return $this->hasMany(Trip::class, 'user_id');
    }

    // ----------------------------------------------------------------
    // Accessors & Helpers
    // ----------------------------------------------------------------

    /**
     * Get user initials for avatar fallback.
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials ?: 'U';
    }
}
