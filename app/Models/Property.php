<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\DateHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

/**
 * Property Model
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $zip_code
 * @property float $latitude
 * @property float $longitude
 * @property int $bedrooms
 * @property int $bathrooms
 * @property int $max_guests
 * @property array<string, mixed> $amenities
 * @property array<string> $images
 * @property string $status
 * @property float $base_price
 * @property float $cleaning_fee
 * @property float $security_deposit
 * @property array<string> $blocked_dates
 * @property int $min_nights
 * @property int $max_nights
 * @property int $advance_notice
 * @property array<string, string> $ical_feeds
 * @property bool $instant_booking
 * @property array<string, mixed> $sync_logs
 * @property Carbon|null $last_synced_at
 * @property float $total_revenue
 * @property float $occupancy_rate
 * @property int $total_bookings
 * @property-read User $user
 * @property-read Collection<int, Booking> $bookings
 * @property-read Collection<int, Review> $reviews
 * @property-read PropertyManager|null $propertyManager
 * @property-read Collection<int, SeasonalPrice> $seasonalPrices
 * @property-read Collection<int, SpecialPrice> $specialPrices
 */
class Property extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Saudi-specific property types
     */
    public const PROPERTY_TYPES = [
        'apartment' => 'Apartment',
        'villa' => 'Villa',
        'compound' => 'Compound',
        'traditional_house' => 'Traditional House',
        'farm_house' => 'Farm House',
        'duplex' => 'Duplex',
        'penthouse' => 'Penthouse',
        'studio' => 'Studio',
    ];

    /**
     * Saudi-specific amenities
     */
    public const SAUDI_AMENITIES = [
        'prayer_mat' => 'Prayer Mat',
        'qibla_finder' => 'Qibla Finder',
        'bidet' => 'Bidet/Shattaf',
        'quran' => 'Quran Available',
        'arabic_channels' => 'Arabic TV Channels',
        'dates' => 'Complimentary Dates',
        'arabic_coffee' => 'Arabic Coffee Set',
        'gender_segregated_pool' => 'Gender-segregated Pool',
        'gender_segregated_gym' => 'Gender-segregated Gym',
        'halal_restaurants' => 'Halal Restaurants Nearby',
        'mosque_nearby' => 'Mosque Nearby',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'latitude',
        'longitude',
        'bedrooms',
        'bathrooms',
        'max_guests',
        'amenities',
        'images',
        'status',
        'base_price',
        'cleaning_fee',
        'security_deposit',
        'blocked_dates',
        'min_nights',
        'max_nights',
        'advance_notice',
        'ical_feeds',
        'instant_booking',
        'sync_logs',
        'last_synced_at',
        'total_revenue',
        'occupancy_rate',
        'total_bookings',
        'property_type',
        'is_segregated',
        'has_driver_room',
        'has_maid_room',
        'has_majlis',
        'has_prayer_room',
        'distance_to_haram',
        'distance_to_metro',
        'nearby_landmarks',
        'district',
        'neighborhood',
        'saudi_amenities',
        'has_family_section',
        'has_bachelor_section',
        'is_halal_certified',
        'provides_prayer_essentials',
        'available_languages',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amenities' => 'array',
        'images' => 'array',
        'blocked_dates' => 'array',
        'ical_feeds' => 'array',
        'sync_logs' => 'array',
        'instant_booking' => 'boolean',
        'last_synced_at' => 'datetime',
        'base_price' => 'decimal:2',
        'cleaning_fee' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'occupancy_rate' => 'float',
        'total_bookings' => 'integer',
        'nearby_landmarks' => 'array',
        'saudi_amenities' => 'array',
        'available_languages' => 'array',
        'is_segregated' => 'boolean',
        'has_driver_room' => 'boolean',
        'has_maid_room' => 'boolean',
        'has_majlis' => 'boolean',
        'has_prayer_room' => 'boolean',
        'has_family_section' => 'boolean',
        'has_bachelor_section' => 'boolean',
        'is_halal_certified' => 'boolean',
        'provides_prayer_essentials' => 'boolean',
        'distance_to_haram' => 'float',
        'distance_to_metro' => 'float',
    ];

    /**
     * Get the user that owns the property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, \App\Models\Property>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the bookings for the property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Booking>
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the reviews for the property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Review>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the property manager for the property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\PropertyManager, \App\Models\Property>
     */
    public function propertyManager(): BelongsTo
    {
        return $this->belongsTo(PropertyManager::class, 'user_id', 'user_id');
    }

    /**
     * Get the seasonal prices for the property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\SeasonalPrice>
     */
    public function seasonalPrices(): HasMany
    {
        return $this->hasMany(SeasonalPrice::class);
    }

    /**
     * Get the special prices for the property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\SpecialPrice>
     */
    public function specialPrices(): HasMany
    {
        return $this->hasMany(SpecialPrice::class);
    }

    /**
     * Get the pending bookings for the property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Booking>
     */
    public function pendingBookings(): HasMany
    {
        return $this->hasMany(Booking::class)->where('status', 'pending');
    }

    /**
     * Get the latest bookings for the property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Booking>
     */
    public function latestBookings(): HasMany
    {
        return $this->hasMany(Booking::class)->latest();
    }

    /**
     * Get the price for a specific date.
     *
     * @param string|\DateTimeInterface $date
     * @return float
     */
    public function getPriceForDate($date): float
    {
        // Check for special price
        $specialPrice = $this->specialPrices()
            ->forDate($date)
            ->first();

        if ($specialPrice) {
            return $specialPrice->price;
        }

        // Check for seasonal price
        $seasonalPrice = $this->seasonalPrices()
            ->forDate($date)
            ->first();

        if ($seasonalPrice) {
            return $seasonalPrice->price;
        }

        // Return base price
        return $this->base_price;
    }

    /**
     * Check if the property is available for the given dates.
     *
     * @param string|\DateTimeInterface $startDate
     * @param string|\DateTimeInterface $endDate
     * @return bool
     */
    public function isAvailableForDates($startDate, $endDate): bool
    {
        // Check if dates are blocked
        $blockedDates = collect($this->blocked_dates);
        $requestedDates = collect(DateHelper::getDatesFromRange($startDate, $endDate));

        if ($blockedDates->intersect($requestedDates)->isNotEmpty()) {
            return false;
        }

        // Check for existing bookings
        $existingBooking = $this->bookings()
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('check_in', [$startDate, $endDate])
                    ->orWhereBetween('check_out', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('check_in', '<=', $startDate)
                            ->where('check_out', '>=', $endDate);
                    });
            })
            ->exists();

        return !$existingBooking;
    }

    /**
     * Calculate total price for a booking.
     *
     * @param string|\DateTimeInterface $startDate
     * @param string|\DateTimeInterface $endDate
     * @param int $guests
     * @return array<string, mixed>
     */
    public function calculateTotalPrice($startDate, $endDate, int $guests = 1): array
    {
        $nights = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));
        $subtotal = 0;

        // Calculate nightly rates
        for ($i = 0; $i < $nights; $i++) {
            $date = Carbon::parse($startDate)->addDays($i);
            $subtotal += $this->getPriceForDate($date);
        }

        return [
            'nights' => $nights,
            'nightly_rate' => $subtotal / $nights,
            'subtotal' => $subtotal,
            'cleaning_fee' => $this->cleaning_fee,
            'security_deposit' => $this->security_deposit,
            'total' => $subtotal + $this->cleaning_fee + $this->security_deposit,
        ];
    }

    /**
     * Update property statistics.
     */
    public function updateStatistics(): void
    {
        $this->update([
            'total_revenue' => $this->bookings()->where('status', 'completed')->sum('total_price'),
            'total_bookings' => $this->bookings()->where('status', 'completed')->count(),
            'occupancy_rate' => $this->calculateOccupancyRate(),
        ]);
    }

    /**
     * Calculate property occupancy rate.
     *
     * @return float
     */
    protected function calculateOccupancyRate(): float
    {
        $startDate = now()->subMonths(3);
        $endDate = now();
        $totalDays = $startDate->diffInDays($endDate);
        
        $bookedDays = $this->bookings()
            ->where('status', 'completed')
            ->whereBetween('check_in', [$startDate, $endDate])
            ->get()
            ->sum(function ($booking) {
                return $booking->check_in->diffInDays($booking->check_out);
            });

        return $totalDays > 0 ? ($bookedDays / $totalDays) * 100 : 0;
    }
}
