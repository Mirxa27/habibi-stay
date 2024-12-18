@props(['currentFilters' => []])

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('properties.filter') }}" method="GET" class="row g-3">
            <!-- Search Input -->
            <div class="col-md-12">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Search properties..."
                           value="{{ request('search') }}">
                </div>
            </div>

            <!-- Price Range -->
            <div class="col-md-6">
                <label class="form-label">Min Price</label>
                <div class="input-group">
                    <span class="input-group-text">SAR</span>
                    <input type="number" 
                           name="min_price" 
                           class="form-control" 
                           placeholder="0"
                           value="{{ request('min_price') }}">
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Max Price</label>
                <div class="input-group">
                    <span class="input-group-text">SAR</span>
                    <input type="number" 
                           name="max_price" 
                           class="form-control" 
                           placeholder="Any"
                           value="{{ request('max_price') }}">
                </div>
            </div>

            <!-- Property Features -->
            <div class="col-md-4">
                <label class="form-label">Bedrooms</label>
                <select name="bedrooms" class="form-select">
                    <option value="">Any</option>
                    @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>
                            {{ $i }}+
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Bathrooms</label>
                <select name="bathrooms" class="form-select">
                    <option value="">Any</option>
                    @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ request('bathrooms') == $i ? 'selected' : '' }}>
                            {{ $i }}+
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Guests</label>
                <select name="max_guests" class="form-select">
                    <option value="">Any</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('max_guests') == $i ? 'selected' : '' }}>
                            {{ $i }}+
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Dates -->
            <div class="col-md-6">
                <label class="form-label">Check In</label>
                <input type="date" 
                       name="check_in" 
                       class="form-control"
                       min="{{ date('Y-m-d') }}"
                       value="{{ request('check_in') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Check Out</label>
                <input type="date" 
                       name="check_out" 
                       class="form-control"
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                       value="{{ request('check_out') }}">
            </div>

            <!-- Amenities -->
            <div class="col-12">
                <label class="form-label">Amenities</label>
                <div class="row g-2">
                    @foreach(['wifi', 'parking', 'pool', 'gym', 'ac', 'kitchen'] as $amenity)
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" 
                                       name="amenities[]" 
                                       value="{{ $amenity }}" 
                                       class="form-check-input"
                                       id="amenity_{{ $amenity }}"
                                       {{ in_array($amenity, request('amenities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="amenity_{{ $amenity }}">
                                    {{ ucfirst($amenity) }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Rating -->
            <div class="col-md-6">
                <label class="form-label">Minimum Rating</label>
                <select name="min_rating" class="form-select">
                    <option value="">Any</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('min_rating') == $i ? 'selected' : '' }}>
                            {{ $i }}+ Stars
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Sort Options -->
            <div class="col-md-6">
                <label class="form-label">Sort By</label>
                <select name="sort_by" class="form-select">
                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>
                        Newest First
                    </option>
                    <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>
                        Price
                    </option>
                    <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>
                        Rating
                    </option>
                </select>
            </div>

            <!-- Additional Options -->
            <div class="col-12">
                <div class="form-check">
                    <input type="checkbox" 
                           name="instant_booking" 
                           value="1" 
                           class="form-check-input"
                           id="instant_booking"
                           {{ request('instant_booking') ? 'checked' : '' }}>
                    <label class="form-check-label" for="instant_booking">
                        Instant Booking Available
                    </label>
                </div>
            </div>

            <!-- Property Type -->
            <div class="col-md-6">
                <label class="form-label">Property Type</label>
                <select name="property_type" class="form-select">
                    <option value="">Any Type</option>
                    @foreach(\App\Models\Property::PROPERTY_TYPES as $value => $label)
                        <option value="{{ $value }}" {{ request('property_type') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- District -->
            <div class="col-md-6">
                <label class="form-label">District</label>
                <input type="text" 
                       name="district" 
                       class="form-control" 
                       placeholder="Enter district name"
                       value="{{ request('district') }}">
            </div>

            <!-- Distance to Haram -->
            <div class="col-md-6">
                <label class="form-label">Max Distance to Haram (km)</label>
                <input type="number" 
                       name="max_distance_to_haram" 
                       class="form-control" 
                       placeholder="Any distance"
                       step="0.1"
                       value="{{ request('max_distance_to_haram') }}">
            </div>

            <!-- Distance to Metro -->
            <div class="col-md-6">
                <label class="form-label">Max Distance to Metro (km)</label>
                <input type="number" 
                       name="max_distance_to_metro" 
                       class="form-control" 
                       placeholder="Any distance"
                       step="0.1"
                       value="{{ request('max_distance_to_metro') }}">
            </div>

            <!-- Saudi-specific Amenities -->
            <div class="col-12">
                <label class="form-label">Special Amenities</label>
                <div class="row g-2">
                    @foreach(\App\Models\Property::SAUDI_AMENITIES as $value => $label)
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" 
                                       name="saudi_amenities[]" 
                                       value="{{ $value }}" 
                                       class="form-check-input"
                                       id="amenity_{{ $value }}"
                                       {{ in_array($value, request('saudi_amenities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="amenity_{{ $value }}">
                                    {{ $label }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Property Features -->
            <div class="col-12">
                <label class="form-label">Property Features</label>
                <div class="row g-2">
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" 
                                   name="is_segregated" 
                                   value="1" 
                                   class="form-check-input"
                                   id="is_segregated"
                                   {{ request('is_segregated') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_segregated">
                                Gender Segregated
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" 
                                   name="has_family_section" 
                                   value="1" 
                                   class="form-check-input"
                                   id="has_family_section"
                                   {{ request('has_family_section') ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_family_section">
                                Family Section
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" 
                                   name="has_prayer_room" 
                                   value="1" 
                                   class="form-check-input"
                                   id="has_prayer_room"
                                   {{ request('has_prayer_room') ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_prayer_room">
                                Prayer Room
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" 
                                   name="has_majlis" 
                                   value="1" 
                                   class="form-check-input"
                                   id="has_majlis"
                                   {{ request('has_majlis') ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_majlis">
                                Majlis
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" 
                                   name="has_driver_room" 
                                   value="1" 
                                   class="form-check-input"
                                   id="has_driver_room"
                                   {{ request('has_driver_room') ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_driver_room">
                                Driver Room
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" 
                                   name="has_maid_room" 
                                   value="1" 
                                   class="form-check-input"
                                   id="has_maid_room"
                                   {{ request('has_maid_room') ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_maid_room">
                                Maid Room
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Certification -->
            <div class="col-12">
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" 
                                   name="is_halal_certified" 
                                   value="1" 
                                   class="form-check-input"
                                   id="is_halal_certified"
                                   {{ request('is_halal_certified') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_halal_certified">
                                Halal Certified
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" 
                                   name="provides_prayer_essentials" 
                                   value="1" 
                                   class="form-check-input"
                                   id="provides_prayer_essentials"
                                   {{ request('provides_prayer_essentials') ? 'checked' : '' }}>
                            <label class="form-check-label" for="provides_prayer_essentials">
                                Prayer Essentials Provided
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-search me-1"></i> Search
                </button>
                <a href="{{ route('properties.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div> 