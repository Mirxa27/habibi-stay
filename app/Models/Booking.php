<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'user_id',
        'check_in',
        'check_out',
        'guests',
        'total_price',
        'status',
        'payment_status',
        'payment_id',
        'special_requests',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the property associated with the booking.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the user who made the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the review associated with the booking.
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Get the duration of the booking in days.
     */
    public function getDurationAttribute(): int
    {
        return $this->check_in->diffInDays($this->check_out);
    }

    /**
     * Scope a query to only include pending bookings.
     */
    public function scopePending($query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include confirmed bookings.
     */
    public function scopeConfirmed($query): Builder
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope a query to only include cancelled bookings.
     */
    public function scopeCancelled($query): Builder
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope a query to only include completed bookings.
     */
    public function scopeCompleted($query): Builder
    {
        return $query->where('status', 'completed');
    }
}
