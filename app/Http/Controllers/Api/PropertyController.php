<?php

namespace App\Http\Controllers\Api;

use App\Models\Property;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PropertyController extends ApiController
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

        return $this->paginatedResponse($properties, 'Properties retrieved successfully');
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
        
        return $this->successResponse($properties, 'Featured properties retrieved successfully');
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
        return $this->paginatedResponse($properties, 'Search results retrieved successfully');
    }

    /**
     * Store a newly created property.
     */
    public function store(StorePropertyRequest $request): JsonResponse
    {
        try {
            $property = Property::create($request->validated());
            
            if ($request->hasFile('images')) {
                $paths = [];
                foreach ($request->file('images') as $image) {
                    $paths[] = $image->store('properties', 'public');
                }
                $property->images = $paths;
                $property->save();
            }

            return $this->successResponse($property, 'Property created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create property: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified property.
     */
    public function show(Property $property): JsonResponse
    {
        try {
            $property->load(['user', 'reviews.user']);
            return $this->successResponse($property, 'Property retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve property: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Request property verification.
     */
    public function requestVerification(Property $property): JsonResponse
    {
        try {
            $this->authorize('update', $property);
            
            if ($property->verification_status === 'pending') {
                return $this->errorResponse('Verification already requested', 422);
            }

            $property->verification_status = 'pending';
            $property->save();

            return $this->successResponse(null, 'Verification requested successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to request verification: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get property analytics.
     */
    public function analytics(Property $property): JsonResponse
    {
        try {
            $this->authorize('view', $property);
            
            $analytics = [
                'views' => $property->views_count,
                'bookings' => $property->bookings_count,
                'revenue' => $property->total_revenue,
                'rating' => $property->average_rating,
                'reviews_count' => $property->reviews_count
            ];

            return $this->successResponse($analytics, 'Analytics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve analytics: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Export property data.
     */
    public function export(Property $property): JsonResponse
    {
        try {
            $this->authorize('view', $property);
            
            $data = $property->load([
                'bookings.user',
                'reviews.user',
                'seasonalPrices',
                'specialPrices'
            ]);

            return $this->successResponse($data, 'Property data exported successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to export property data: ' . $e->getMessage(), 500);
        }
    }
} 