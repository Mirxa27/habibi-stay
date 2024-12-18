<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class PropertyController extends Controller
{
    /**
     * Display all properties.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $query = Property::query()->with(['user', 'reviews']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->input('max_price'));
        }

        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->input('bedrooms'));
        }

        if ($request->filled('bathrooms')) {
            $query->where('bathrooms', '>=', $request->input('bathrooms'));
        }

        $properties = $query->paginate(12);

        return view('properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new property.
     */
    public function create(): View
    {
        $this->authorize('create', Property::class);
        return view('properties.create');
    }

    /**
     * Store a newly created property in storage.
     */
    public function store(StorePropertyRequest $request): RedirectResponse
    {
        $this->authorize('create', Property::class);

        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('properties', 'public');
            $validated['images'] = [$path];
        }

        if ($request->hasFile('images')) {
            $additionalImages = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties', 'public');
                $additionalImages[] = $path;
            }
            $validated['images'] = array_merge($validated['images'] ?? [], $additionalImages);
        }

        $property = Property::create($validated);

        return redirect()
            ->route('properties.show', $property)
            ->with('success', 'Property listed successfully!');
    }

    /**
     * Display the specified property.
     */
    public function show(Property $property): View
    {
        $property->load(['user', 'reviews.user', 'propertyManager']);
        return view('properties.show', compact('property'));
    }

    /**
     * Show the form for editing the specified property.
     */
    public function edit(Property $property): View
    {
        $this->authorize('update', $property);
        return view('properties.edit', compact('property'));
    }

    /**
     * Update the specified property in storage.
     */
    public function update(UpdatePropertyRequest $request, Property $property): RedirectResponse
    {
        $this->authorize('update', $property);

        $validated = $request->validated();

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('properties', 'public');
            $validated['images'] = array_merge([$path], array_slice($property->images ?? [], 1));
        }

        if ($request->hasFile('images')) {
            $additionalImages = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties', 'public');
                $additionalImages[] = $path;
            }
            $validated['images'] = array_merge($property->images ?? [], $additionalImages);
        }

        $property->update($validated);

        return redirect()
            ->route('properties.show', $property)
            ->with('success', 'Property updated successfully!');
    }

    /**
     * Remove the specified property from storage.
     */
    public function destroy(Property $property): RedirectResponse
    {
        $this->authorize('delete', $property);

        if (!empty($property->images)) {
            foreach ($property->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $property->delete();

        return redirect()
            ->route('dashboard.properties')
            ->with('success', 'Property deleted successfully!');
    }

    /**
     * Delete a specific image from a property.
     */
    public function deleteImage(Property $property, int $imageIndex): RedirectResponse
    {
        $this->authorize('update', $property);

        $images = $property->images;
        if (isset($images[$imageIndex])) {
            Storage::disk('public')->delete($images[$imageIndex]);
            unset($images[$imageIndex]);
            $property->update(['images' => array_values($images)]);
        }

        return back()->with('success', 'Image deleted successfully!');
    }

    /**
     * Filter properties based on advanced criteria.
     */
    public function filter(Request $request): View
    {
        $query = Property::query()->with(['user', 'reviews', 'propertyManager']);

        // ... existing filter logic ...

        $properties = $query->paginate(12);
        $properties->appends($request->except('page'));

        return view('properties.index', [
            'properties' => $properties,
            'filters' => $request->all(),
        ]);
    }

    /**
     * Toggle property's featured status.
     */
    public function toggleFeatured(Property $property): RedirectResponse
    {
        $this->authorize('update', $property);

        try {
            DB::transaction(function () use ($property) {
                $property->update([
                    'is_featured' => !$property->is_featured,
                    'featured_at' => $property->is_featured ? null : now(),
                ]);
            });

            return back()->with('success', 
                $property->is_featured ? 'Property marked as featured!' : 'Property removed from featured properties.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update featured status.');
        }
    }

    /**
     * Update property availability.
     */
    public function updateAvailability(Request $request, Property $property): RedirectResponse
    {
        $this->authorize('update', $property);

        try {
            $validated = $request->validate([
                'blocked_dates' => 'array',
                'blocked_dates.*' => 'date',
                'min_nights' => 'integer|min:1',
                'max_nights' => 'integer|min:1|gte:min_nights',
                'advance_notice' => 'integer|min:0',
            ]);

            DB::transaction(function () use ($property, $validated) {
                $property->update($validated);

                if ($property->ical_feeds) {
                    // Implement calendar sync logic
                }
            });

            return back()->with('success', 'Availability updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update availability.');
        }
    }

    /**
     * Update property pricing.
     */
    public function updatePricing(Request $request, Property $property): RedirectResponse
    {
        $this->authorize('update', $property);

        try {
            $validated = $request->validate([
                'base_price' => 'required|numeric|min:0',
                'cleaning_fee' => 'numeric|min:0',
                'security_deposit' => 'numeric|min:0',
                'extra_guest_fee' => 'numeric|min:0',
                'weekend_price' => 'numeric|min:0',
                'weekly_discount' => 'numeric|min:0|max:100',
                'monthly_discount' => 'numeric|min:0|max:100',
            ]);

            $property->update($validated);

            return back()->with('success', 'Pricing updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update pricing.');
        }
    }

    /**
     * Get similar properties.
     */
    public function getSimilarProperties(Property $property): View
    {
        $similarProperties = Property::query()
            ->where('id', '!=', $property->id)
            ->where(function ($query) use ($property) {
                $query->where('city', $property->city)
                    ->orWhere('bedrooms', $property->bedrooms)
                    ->orWhereBetween('base_price', [
                        $property->base_price * 0.8,
                        $property->base_price * 1.2
                    ]);
            })
            ->with(['user', 'reviews'])
            ->take(3)
            ->get();

        return view('properties.similar', compact('similarProperties'));
    }

    /**
     * Export property data.
     */
    public function export(Property $property): RedirectResponse
    {
        $this->authorize('view', $property);

        try {
            $data = [
                'property' => $property->toArray(),
                'bookings' => $property->bookings()->with('user')->get()->toArray(),
                'reviews' => $property->reviews()->with('user')->get()->toArray(),
                'revenue' => $property->bookings()
                    ->where('status', 'completed')
                    ->sum('total_price'),
            ];

            $filename = Str::slug($property->title) . '_' . now()->format('Y-m-d') . '.json';
            Storage::disk('public')->put('exports/' . $filename, json_encode($data, JSON_PRETTY_PRINT));

            return response()->download(storage_path('app/public/exports/' . $filename));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to export property data.');
        }
    }
} 