<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_id',
        'user_id',
        'property_id',
        'rating',
        'comment',
        'ratings_breakdown',
        'is_verified',
    ];

    protected $casts = [
        'ratings_breakdown' => 'array',
        'is_verified' => 'boolean',
    ];

    /**
     * Get the booking associated with the review.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user who wrote the review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the property being reviewed.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Scope a query to only include verified reviews.
     */
    public function scopeVerified($query): Builder
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope a query to only include high-rated reviews.
     */
    public function scopeHighRated($query): Builder
    {
        return $query->where('rating', '>=', 4);
    }

    /**
     * Scope a query to only include low-rated reviews.
     */
    public function scopeLowRated($query): Builder
    {
        return $query->where('rating', '<=', 2);
    }
}
