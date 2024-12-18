<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Http\Requests\UpdatePriceRequest;
use App\Http\Requests\UpdateAvailabilityRequest;
use App\Http\Requests\SyncCalendarRequest;
use App\Events\PropertyPriceUpdated;
use App\Events\PropertyAvailabilityUpdated;
use App\Events\CalendarSynced;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ChannelManagerController extends Controller
{
    /**
     * Display calendar data.
     */
    public function calendar(): JsonResponse
    {
        $properties = Property::where('user_id', auth()->id())
            ->with(['bookings', 'seasonalPrices', 'specialPrices'])
            ->get();

        $calendarData = $properties->map(function ($property) {
            return [
                'id' => $property->id,
                'name' => $property->name,
                'bookings' => $property->bookings->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'start' => $booking->check_in,
                        'end' => $booking->check_out,
                        'title' => "Booked: {$booking->guests} guests",
                        'status' => $booking->status
                    ];
                }),
                'prices' => $this->getPropertyPrices($property),
                'availability' => $property->available_dates
            ];
        });

        return response()->json($calendarData);
    }

    /**
     * Update property price.
     */
    public function updatePrice(UpdatePriceRequest $request, Property $property): JsonResponse
    {
        $this->authorize('managePricing', $property);
        $validated = $request->validated();

        if (isset($validated['base_price'])) {
            $property->price_per_night = $validated['base_price'];
            $property->save();
        }

        if (isset($validated['seasonal_prices'])) {
            $property->seasonalPrices()->delete();
            $property->seasonalPrices()->createMany($validated['seasonal_prices']);
        }

        if (isset($validated['special_prices'])) {
            $property->specialPrices()->delete();
            $property->specialPrices()->createMany($validated['special_prices']);
        }

        event(new PropertyPriceUpdated($property));
        Cache::tags(['property_prices'])->forget("property_{$property->id}_prices");

        return response()->json([
            'message' => 'Prices updated successfully',
            'property' => $property->load(['seasonalPrices', 'specialPrices'])
        ]);
    }

    /**
     * Update property availability.
     */
    public function updateAvailability(UpdateAvailabilityRequest $request, Property $property): JsonResponse
    {
        $this->authorize('manageAvailability', $property);
        $validated = $request->validated();

        $property->update([
            'available_dates' => $validated['available_dates'] ?? $property->available_dates,
            'blocked_dates' => $validated['blocked_dates'] ?? $property->blocked_dates,
            'minimum_stay' => $validated['minimum_stay'] ?? $property->minimum_stay,
            'maximum_stay' => $validated['maximum_stay'] ?? $property->maximum_stay
        ]);

        event(new PropertyAvailabilityUpdated($property));
        Cache::tags(['property_availability'])->forget("property_{$property->id}_availability");

        return response()->json([
            'message' => 'Availability updated successfully',
            'property' => $property
        ]);
    }

    /**
     * Sync calendar with external provider.
     */
    public function syncCalendar(SyncCalendarRequest $request, Property $property): JsonResponse
    {
        $this->authorize('manageAvailability', $property);
        $validated = $request->validated();

        $property->update(['ical_url' => $validated['ical_url']]);
        
        try {
            $icalContent = file_get_contents($property->ical_url);
            $events = $this->parseICalEvents($icalContent);
            
            $property->blocked_dates = array_merge(
                $property->blocked_dates ?? [],
                $events
            );
            $property->save();

            event(new CalendarSynced($property));

            return response()->json([
                'message' => 'Calendar synced successfully',
                'events_synced' => count($events)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to sync calendar',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get property prices including base, seasonal, and special prices.
     */
    private function getPropertyPrices(Property $property): array
    {
        return Cache::tags(['property_prices'])->remember(
            "property_{$property->id}_prices",
            now()->addHours(1),
            function () use ($property) {
                return [
                    'base_price' => $property->price_per_night,
                    'seasonal_prices' => $property->seasonalPrices,
                    'special_prices' => $property->specialPrices
                ];
            }
        );
    }

    /**
     * Parse iCal events from content.
     */
    private function parseICalEvents(string $content): array
    {
        preg_match_all('/DTSTART:(\d{8}).*?DTEND:(\d{8})/s', $content, $matches);
        
        $events = [];
        foreach ($matches[1] as $index => $start) {
            $events[] = [
                'start_date' => date('Y-m-d', strtotime($start)),
                'end_date' => date('Y-m-d', strtotime($matches[2][$index]))
            ];
        }
        
        return $events;
    }
} 