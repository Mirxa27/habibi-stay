<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialPrice extends Model
{
    protected $fillable = [
        'property_id',
        'date',
        'price',
        'reason',
    ];

    protected $casts = [
        'date' => 'date',
        'price' => 'decimal:2',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function scopeActive($query)
    {
        return $query->where('date', '>=', now());
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date);
    }
}
