<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    /**
     * Display all properties.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Property::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $properties = $query->with(['user', 'reviews'])
                           ->latest()
                           ->paginate(15);

        return response()->json($properties);
    }

    /**
     * Display featured properties.
     */
    public function featured(): JsonResponse
    {
        $properties = Property::where('is_featured', true)
            ->with(['user', 'reviews'])
            ->take(6)
            ->get();
        return response()->json($properties);
    }

    /**
     * Search properties.
     */
    public function search(Request $request): JsonResponse
    {
        $query = Property::query();

        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('price_min')) {
            $query->where('price_per_night', '>=', $request->price_min);
        }

        if ($request->has('price_max')) {
            $query->where('price_per_night', '<=', $request->price_max);
        }

        $properties = $query->with(['user', 'reviews'])->paginate(15);
        return response()->json($properties);
    }

    /**
     * Store a newly created property.
     */
    public function store(StorePropertyRequest $request): JsonResponse
    {
        $property = Property::create($request->validated());
        
        if ($request->hasFile('images')) {
            $paths = [];
            foreach ($request->file('images') as $image) {
                $paths[] = $image->store('properties', 'public');
            }
            $property->images = $paths;
            $property->save();
        }

        return response()->json($property, 201);
    }

    /**
     * Display the specified property.
     */
    public function show(Property $property): JsonResponse
    {
        $property->load(['user', 'reviews.user']);
        return response()->json($property);
    }

    /**
     * Update the specified property.
     */
    public function update(UpdatePropertyRequest $request, Property $property): JsonResponse
    {
        $this->authorize('update', $property);
        $property->update($request->validated());
        return response()->json($property);
    }

    /**
     * Remove the specified property.
     */
    public function destroy(Property $property): JsonResponse
    {
        $this->authorize('delete', $property);
        $property->delete();
        return response()->json(null, 204);
    }

    /**
     * Upload property images.
     */
    public function uploadImages(Request $request, Property $property): JsonResponse
    {
        $this->authorize('update', $property);
        
        $request->validate([
            'images.*' => 'required|image|max:2048'
        ]);

        $paths = [];
        foreach ($request->file('images') as $image) {
            $paths[] = $image->store('properties', 'public');
        }

        $property->images = array_merge($property->images ?? [], $paths);
        $property->save();

        return response()->json(['images' => $property->images]);
    }

    /**
     * Delete a property image.
     */
    public function deleteImage(Property $property, int $index): JsonResponse
    {
        $this->authorize('update', $property);
        
        $images = $property->images ?? [];
        if (isset($images[$index])) {
            Storage::disk('public')->delete($images[$index]);
            unset($images[$index]);
            $property->images = array_values($images);
            $property->save();
        }

        return response()->json(['images' => $property->images]);
    }

    /**
     * Update property availability.
     */
    public function updateAvailability(Request $request, Property $property): JsonResponse
    {
        $this->authorize('update', $property);
        
        $validated = $request->validate([
            'available_dates' => 'required|array',
            'available_dates.*' => 'date'
        ]);

        $property->available_dates = $validated['available_dates'];
        $property->save();

        return response()->json($property);
    }

    /**
     * Update property pricing.
     */
    public function updatePricing(Request $request, Property $property): JsonResponse
    {
        $this->authorize('update', $property);
        
        $validated = $request->validate([
            'price_per_night' => 'required|numeric|min:0',
            'cleaning_fee' => 'nullable|numeric|min:0',
            'service_fee' => 'nullable|numeric|min:0'
        ]);

        $property->update($validated);
        return response()->json($property);
    }

    /**
     * Request property verification.
     */
    public function requestVerification(Property $property): JsonResponse
    {
        $this->authorize('update', $property);
        
        if ($property->verification_status === 'pending') {
            return response()->json(['message' => 'Verification already requested'], 422);
        }

        $property->verification_status = 'pending';
        $property->save();

        return response()->json(['message' => 'Verification requested successfully']);
    }

    /**
     * Get property analytics.
     */
    public function analytics(Property $property): JsonResponse
    {
        $this->authorize('view', $property);
        
        $analytics = [
            'views' => $property->views_count,
            'bookings' => $property->bookings_count,
            'revenue' => $property->total_revenue,
            'rating' => $property->average_rating,
            'reviews_count' => $property->reviews_count
        ];

        return response()->json($analytics);
    }

    /**
     * Export property data.
     */
    public function export(Property $property): JsonResponse
    {
        $this->authorize('view', $property);
        
        $data = $property->load([
            'bookings.user',
            'reviews.user',
            'seasonalPrices',
            'specialPrices'
        ]);

        return response()->json($data);
    }

    /**
     * Display all available properties with optional filters.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function availableProperties(Request $request): JsonResponse
    {
        $query = Property::query();

        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('price_min')) {
            $query->where('price_per_night', '>=', $request->price_min);
        }

        if ($request->has('price_max')) {
            $query->where('price_per_night', '<=', $request->price_max);
        }

        $properties = $query->with(['user', 'reviews'])->paginate(15);
        return response()->json($properties);
    }
} 