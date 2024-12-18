<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class PropertyManager extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_name',
        'bio',
        'phone',
        'license_number',
        'is_verified',
        'languages',
        'certifications',
        'rating',
        'years_of_experience',
    ];

    protected $casts = [
        'languages' => 'array',
        'certifications' => 'array',
        'is_verified' => 'boolean',
        'rating' => 'decimal:2',
    ];

    /**
     * Get the user associated with the property manager.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the properties managed by this property manager.
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'user_id', 'user_id');
    }

    /**
     * Scope a query to only include verified property managers.
     */
    public function scopeVerified($query): Builder
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope a query to only include top-rated property managers.
     */
    public function scopeTopRated($query): Builder
    {
        return $query->where('rating', '>=', 4.5);
    }

    /**
     * Get the average rating of all properties managed by this property manager.
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->properties()
            ->withAvg('reviews', 'rating')
            ->first()
            ->reviews_avg_rating ?? 0;
    }
}
