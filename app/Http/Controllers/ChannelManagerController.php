<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Booking;
use App\Http\Requests\UpdatePriceRequest;
use App\Http\Requests\UpdateAvailabilityRequest;
use App\Http\Requests\SyncCalendarRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Events\PropertyPriceUpdated;
use App\Events\PropertyAvailabilityUpdated;
use App\Events\CalendarSynced;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use ICal\ICal;

class ChannelManagerController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage-properties');
    }

    public function index(): View
    {
        try {
            $properties = Auth::user()->properties()
                ->withCount(['bookings', 'pendingBookings'])
                ->with(['latestBookings' => function($query) {
                    $query->latest()->take(5);
                }])
                ->get();

            $stats = [
                'total_revenue' => $properties->sum('total_revenue'),
                'occupancy_rate' => $properties->avg('occupancy_rate'),
                'pending_bookings' => $properties->sum('pending_bookings_count'),
                'total_bookings' => $properties->sum('bookings_count'),
            ];

            return view('channel-manager.index', compact('properties', 'stats'));
        } catch (\Exception $e) {
            Log::error('Failed to load channel manager index: ' . $e->getMessage());
            return view('channel-manager.index')
                ->with('error', 'Failed to load channel manager data.');
        }
    }

    public function calendar(): View
    {
        try {
            $properties = Auth::user()->properties()
                ->with(['bookings' => function($query) {
                    $query->whereBetween('check_in', [now(), now()->addMonths(3)]);
                }])
                ->get();

            return view('channel-manager.calendar', compact('properties'));
        } catch (\Exception $e) {
            Log::error('Failed to load calendar view: ' . $e->getMessage());
            return view('channel-manager.calendar')
                ->with('error', 'Failed to load calendar data.');
        }
    }

    public function pricing(): View
    {
        try {
            $properties = Auth::user()->properties()
                ->with(['seasonalPrices', 'specialPrices'])
                ->get();

            return view('channel-manager.pricing', compact('properties'));
        } catch (\Exception $e) {
            Log::error('Failed to load pricing view: ' . $e->getMessage());
            return view('channel-manager.pricing')
                ->with('error', 'Failed to load pricing data.');
        }
    }

    public function bookings(): View
    {
        try {
            $bookings = Booking::whereIn('property_id', Auth::user()->properties()->pluck('id'))
                ->with(['property', 'guest'])
                ->latest()
                ->paginate(20);

            return view('channel-manager.bookings', compact('bookings'));
        } catch (\Exception $e) {
            Log::error('Failed to load bookings view: ' . $e->getMessage());
            return view('channel-manager.bookings')
                ->with('error', 'Failed to load bookings data.');
        }
    }

    public function content(): View
    {
        try {
            $properties = Auth::user()->properties()
                ->with(['images', 'amenities'])
                ->get();

            return view('channel-manager.content', compact('properties'));
        } catch (\Exception $e) {
            Log::error('Failed to load content view: ' . $e->getMessage());
            return view('channel-manager.content')
                ->with('error', 'Failed to load content data.');
        }
    }

    public function updatePrice(UpdatePriceRequest $request, Property $property): JsonResponse
    {
        try {
            $this->authorize('update', $property);
            $validated = $request->validated();

            DB::beginTransaction();

            $property->update([
                'base_price' => $validated['base_price'],
            ]);

            $property->seasonalPrices()->delete();
            $property->seasonalPrices()->createMany($validated['seasonal_prices']);

            broadcast(new PropertyPriceUpdated($property))->toOthers();

            Cache::tags(['property_prices'])->forget("property:{$property->id}:price");

            DB::commit();

            return response()->json([
                'message' => 'Prices updated successfully',
                'property' => $property->fresh(['seasonalPrices']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update property prices: ' . $e->getMessage(), [
                'property_id' => $property->id,
                'user_id' => Auth::id(),
            ]);
            return response()->json([
                'message' => 'Failed to update prices',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateAvailability(UpdateAvailabilityRequest $request, Property $property): JsonResponse
    {
        try {
            $this->authorize('update', $property);
            $validated = $request->validated();

            $property->update([
                'blocked_dates' => $validated['blocked_dates'],
                'min_nights' => $validated['min_nights'],
                'max_nights' => $validated['max_nights'],
                'advance_notice' => $validated['advance_notice'],
            ]);

            broadcast(new PropertyAvailabilityUpdated($property))->toOthers();

            Cache::tags(['property_availability'])->forget("property:{$property->id}:availability");

            return response()->json([
                'message' => 'Availability updated successfully',
                'property' => $property->fresh(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update property availability: ' . $e->getMessage(), [
                'property_id' => $property->id,
                'user_id' => Auth::id(),
            ]);
            return response()->json([
                'message' => 'Failed to update availability',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function syncCalendar(SyncCalendarRequest $request, Property $property): JsonResponse
    {
        try {
            $this->authorize('update', $property);
            $validated = $request->validated();
            $blockedDates = collect($property->blocked_dates);
            $syncErrors = [];

            // Process each iCal feed
            foreach ($validated['ical_urls'] as $url) {
                try {
                    $ical = new ICal();
                    $ical->initUrl($url, [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ]);

                    foreach ($ical->events() as $event) {
                        $startDate = Carbon::parse($event->dtstart);
                        $endDate = Carbon::parse($event->dtend);

                        // Add each date in the range to blocked dates
                        $currentDate = $startDate->copy();
                        while ($currentDate->lte($endDate)) {
                            $dateString = $currentDate->format('Y-m-d');
                            if (!$blockedDates->contains($dateString)) {
                                $blockedDates->push($dateString);
                            }
                            $currentDate->addDay();
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Calendar sync failed for URL: ' . $url, [
                        'property_id' => $property->id,
                        'error' => $e->getMessage(),
                    ]);
                    $syncErrors[] = "Failed to sync calendar from URL: {$url}";
                }
            }

            // Update property with new blocked dates
            $property->update([
                'blocked_dates' => $blockedDates->values()->all(),
                'last_synced_at' => now(),
                'sync_logs' => array_merge(
                    $property->sync_logs ?? [],
                    [['timestamp' => now()->toIso8601String(), 'errors' => $syncErrors]]
                ),
            ]);

            broadcast(new CalendarSynced($property))->toOthers();

            Cache::tags(['property_availability'])->forget("property:{$property->id}:availability");

            return response()->json([
                'message' => 'Calendar synchronized successfully' . 
                    (count($syncErrors) > 0 ? ' with some errors' : ''),
                'property' => $property->fresh(),
                'errors' => $syncErrors,
            ]);
        } catch (\Exception $e) {
            Log::error('Calendar sync failed: ' . $e->getMessage(), [
                'property_id' => $property->id,
                'user_id' => Auth::id(),
            ]);
            return response()->json([
                'message' => 'Failed to synchronize calendar',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
