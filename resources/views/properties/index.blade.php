@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2>Properties in Kuwait</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Properties</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Search Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <x-search-filters :currentFilters="$filters" />
        </div>
    </div>

    <!-- Properties Grid -->
    <div class="row g-4">
        <!-- Filters Sidebar -->
        <div class="col-lg-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">Refine Search</h5>
                    <form action="{{ route('properties.index') }}" method="GET" id="filters-form">
                        <!-- Price Range -->
                        <div class="mb-4">
                            <label class="form-label">Price Range (KWD)</label>
                            <div class="row g-2">
                                <div class="col">
                                    <input type="number" class="form-control" name="min_price" 
                                           value="{{ request('min_price') }}" placeholder="Min">
                                </div>
                                <div class="col">
                                    <input type="number" class="form-control" name="max_price" 
                                           value="{{ request('max_price') }}" placeholder="Max">
                                </div>
                            </div>
                        </div>

                        <!-- Property Type -->
                        <div class="mb-4">
                            <label class="form-label">Property Type</label>
                            @foreach($propertyTypes as $type)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="property_types[]" 
                                           value="{{ $type }}" id="type-{{ $type }}"
                                           {{ in_array($type, request('property_types', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type-{{ $type }}">
                                        {{ ucfirst($type) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Amenities -->
                        <div class="mb-4">
                            <label class="form-label">Amenities</label>
                            @foreach($amenities as $amenity)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="amenities[]" 
                                           value="{{ $amenity }}" id="amenity-{{ $amenity }}"
                                           {{ in_array($amenity, request('amenities', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="amenity-{{ $amenity }}">
                                        {{ ucfirst($amenity) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Bedrooms -->
                        <div class="mb-4">
                            <label class="form-label">Bedrooms</label>
                            <select class="form-select" name="bedrooms">
                                <option value="">Any</option>
                                @foreach(range(1, 6) as $count)
                                    <option value="{{ $count }}" {{ request('bedrooms') == $count ? 'selected' : '' }}>
                                        {{ $count }}+ Bedrooms
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Bathrooms -->
                        <div class="mb-4">
                            <label class="form-label">Bathrooms</label>
                            <select class="form-select" name="bathrooms">
                                <option value="">Any</option>
                                @foreach(range(1, 4) as $count)
                                    <option value="{{ $count }}" {{ request('bathrooms') == $count ? 'selected' : '' }}>
                                        {{ $count }}+ Bathrooms
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Rating -->
                        <div class="mb-4">
                            <label class="form-label">Minimum Rating</label>
                            <select class="form-select" name="min_rating">
                                <option value="">Any</option>
                                @foreach(range(4, 1) as $rating)
                                    <option value="{{ $rating }}" {{ request('min_rating') == $rating ? 'selected' : '' }}>
                                        {{ $rating }}+ Stars
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        @if(request()->anyFilled(['min_price', 'max_price', 'property_types', 'amenities', 'bedrooms', 'bathrooms', 'min_rating']))
                            <a href="{{ route('properties.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                                Clear Filters
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Properties List -->
        <div class="col-lg-9">
            <!-- Sort Options -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <p class="mb-0">{{ $properties->total() }} properties found</p>
                <div class="d-flex align-items-center">
                    <label class="me-2">Sort by:</label>
                    <select class="form-select form-select-sm w-auto" name="sort" form="filters-form">
                        <option value="recommended" {{ request('sort') == 'recommended' ? 'selected' : '' }}>Recommended</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating</option>
                    </select>
                </div>
            </div>

            <!-- Properties Grid -->
            <div class="row g-4">
                @forelse($properties as $property)
                    <div class="col-md-6 col-xl-4">
                        <x-property-card :property="$property" />
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-house-x display-1 text-muted"></i>
                            <h3 class="mt-3">No Properties Found</h3>
                            <p class="text-muted">Try adjusting your search criteria</p>
                            <a href="{{ route('properties.index') }}" class="btn btn-primary">
                                Clear All Filters
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $properties->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit form when sort option changes
    document.querySelector('select[name="sort"]').addEventListener('change', function() {
        document.getElementById('filters-form').submit();
    });

    // Initialize price range slider
    if (typeof noUiSlider !== 'undefined') {
        const priceRange = document.getElementById('price-range');
        if (priceRange) {
            const minPrice = {!! request('min_price', 0) !!};
            const maxPrice = {!! request('max_price', 1000) !!};
            
            noUiSlider.create(priceRange, {
                start: [minPrice, maxPrice],
                connect: true,
                range: {
                    'min': 0,
                    'max': 1000
                },
                format: {
                    to: function(value) {
                        return Math.round(value);
                    },
                    from: function(value) {
                        return value;
                    }
                }
            });

            // Update hidden inputs when slider changes
            priceRange.noUiSlider.on('update', function(values) {
                document.querySelector('input[name="min_price"]').value = values[0];
                document.querySelector('input[name="max_price"]').value = values[1];
            });
        }
    }
</script>
@endpush 