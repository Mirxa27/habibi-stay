@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('properties.index') }}">Properties</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $property->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Image Gallery -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-0">
                    <div class="position-relative">
                        <img src="{{ $property->featured_image }}" 
                             alt="{{ $property->title }}" 
                             class="gallery-main-image w-100"
                             style="height: 500px; object-fit: cover;">
                        
                        @if($property->images->count() > 1)
                            <button class="btn btn-light position-absolute bottom-0 end-0 m-3" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#galleryModal">
                                <i class="bi bi-grid-3x3-gap"></i> Show all photos
                            </button>
                        @endif
                    </div>
                    
                    @if($property->images->count() > 1)
                        <div class="row g-2 p-2">
                            @foreach($property->images->take(4) as $image)
                                <div class="col-3">
                                    <img src="{{ $image->url }}" 
                                         alt="Property image" 
                                         class="gallery-thumb w-100 rounded cursor-pointer"
                                         style="height: 100px; object-fit: cover;"
                                         data-image="{{ $image->url }}">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Property Details -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h1 class="h3 mb-2">{{ $property->title }}</h1>
                            <p class="text-muted mb-0">
                                <i class="bi bi-geo-alt"></i> {{ $property->location }}
                            </p>
                        </div>
                        <div class="text-end">
                            <div class="d-flex align-items-center mb-2">
                                <div class="rating-stars me-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star-fill {{ $i <= $property->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-muted">({{ $property->reviews_count }} reviews)</span>
                            </div>
                            <div class="property-price">
                                <span class="h4 text-primary mb-0">{{ number_format($property->price_per_night, 2) }} KWD</span>
                                <small class="text-muted">/ night</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Quick Info -->
                    <div class="row g-4 mb-4">
                        <div class="col-sm-6 col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-people fs-4 text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">Guests</small>
                                    <span>{{ $property->max_guests }} max</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-door-closed fs-4 text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">Bedrooms</small>
                                    <span>{{ $property->bedrooms }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-water fs-4 text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">Bathrooms</small>
                                    <span>{{ $property->bathrooms }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-rulers fs-4 text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">Size</small>
                                    <span>{{ $property->size }} m²</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <h5 class="mb-3">About this property</h5>
                    <p class="mb-4">{{ $property->description }}</p>

                    <!-- Amenities -->
                    <h5 class="mb-3">Amenities</h5>
                    <div class="row g-4 mb-4">
                        @foreach($property->amenities as $amenity)
                            <div class="col-sm-6 col-lg-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <span>{{ $amenity->name }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Location -->
                    <h5 class="mb-3">Location</h5>
                    <div class="mb-4">
                        <div id="map" class="rounded" style="height: 400px;"></div>
                    </div>

                    <!-- House Rules -->
                    <h5 class="mb-3">House Rules</h5>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-clock fs-4 text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">Check-in</small>
                                    <span>{{ $property->check_in_time }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock-history fs-4 text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">Check-out</small>
                                    <span>{{ $property->check_out_time }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                @foreach($property->rules as $rule)
                                    <li class="mb-2">
                                        <i class="bi bi-info-circle text-primary me-2"></i>
                                        {{ $rule }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Reviews -->
                    <h5 class="mb-3">Reviews</h5>
                    <div class="mb-4">
                        @forelse($property->reviews as $review)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $review->user->profile_photo_url }}" 
                                                 alt="{{ $review->user->name }}"
                                                 class="rounded-circle me-2"
                                                 width="40"
                                                 height="40">
                                            <div>
                                                <h6 class="mb-0">{{ $review->user->name }}</h6>
                                                <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star-fill {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="mb-0">{{ $review->comment }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-4">No reviews yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Booking Form -->
            <div class="card shadow-sm mb-4 sticky-top" style="top: 2rem;">
                <div class="card-body">
                    <form action="{{ route('bookings.store') }}" method="POST" id="booking-form" data-price="{{ $property->price_per_night }}">
                        @csrf
                        <input type="hidden" name="property_id" value="{{ $property->id }}">
                        
                        <div class="mb-3">
                            <label for="check_in" class="form-label">Check-in</label>
                            <input type="date" class="form-control" id="check_in" name="check_in" required
                                   min="{{ date('Y-m-d') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="check_out" class="form-label">Check-out</label>
                            <input type="date" class="form-control" id="check_out" name="check_out" required
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        </div>
                        
                        <div class="mb-4">
                            <label for="guests" class="form-label">Guests</label>
                            <select class="form-select" id="guests" name="guests" required>
                                @for($i = 1; $i <= $property->max_guests; $i++)
                                    <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'guest' : 'guests' }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="price-breakdown mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ number_format($property->price_per_night, 2) }} KWD × <span id="nights-count">0</span> nights</span>
                                <span id="subtotal">0.00 KWD</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Cleaning fee</span>
                                <span>{{ number_format($property->cleaning_fee, 2) }} KWD</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Service fee</span>
                                <span>{{ number_format($property->service_fee, 2) }} KWD</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total</span>
                                <span id="total-price">0.00 KWD</span>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">Book Now</button>
                        
                        <p class="text-muted small text-center mb-0">
                            You won't be charged yet
                        </p>
                    </form>
                </div>
            </div>

            <!-- Host Info -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $property->propertyManager->user->profile_photo_url }}" 
                             alt="{{ $property->propertyManager->user->name }}"
                             class="rounded-circle me-3"
                             width="60"
                             height="60">
                        <div>
                            <h5 class="mb-1">Hosted by {{ $property->propertyManager->user->name }}</h5>
                            <p class="text-muted mb-0">
                                <i class="bi bi-star-fill text-warning"></i>
                                {{ number_format($property->propertyManager->rating, 1) }} ·
                                {{ $property->propertyManager->reviews_count }} reviews
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#contactHostModal">
                            <i class="bi bi-chat-dots me-2"></i>
                            Contact Host
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gallery Modal -->
<div class="modal fade" id="galleryModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Property Gallery</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    @foreach($property->images as $image)
                        <div class="col-md-4">
                            <img src="{{ $image->url }}" 
                                 alt="Property image" 
                                 class="w-100 rounded"
                                 style="height: 250px; object-fit: cover;">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Host Modal -->
<div class="modal fade" id="contactHostModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Contact Host</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('messages.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="property_id" value="{{ $property->id }}">
                    <input type="hidden" name="recipient_id" value="{{ $property->propertyManager->user->id }}">
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Property data for JavaScript
    const propertyData = {
        latitude: {!! $property->latitude !!},
        longitude: {!! $property->longitude !!},
        pricePerNight: {!! $property->price_per_night !!},
        cleaningFee: {!! $property->cleaning_fee !!},
        serviceFee: {!! $property->service_fee !!}
    };

    // Initialize map
    function initMap() {
        if (typeof google === 'undefined' || !google.maps) {
            console.error('Google Maps JavaScript API not loaded');
            return;
        }

        const mapElement = document.getElementById('map');
        if (!mapElement) return;

        const map = new google.maps.Map(mapElement, {
            center: { 
                lat: propertyData.latitude, 
                lng: propertyData.longitude 
            },
            zoom: 15,
            scrollwheel: false
        });

        new google.maps.Marker({
            position: { 
                lat: propertyData.latitude, 
                lng: propertyData.longitude 
            },
            map: map
        });
    }

    // Calculate booking price
    function calculatePrice() {
        const checkIn = document.getElementById('check_in');
        const checkOut = document.getElementById('check_out');
        const nightsCount = document.getElementById('nights-count');
        const subtotalElement = document.getElementById('subtotal');
        const totalPriceElement = document.getElementById('total-price');

        if (!checkIn || !checkOut || !nightsCount || !subtotalElement || !totalPriceElement) return;

        const checkInDate = new Date(checkIn.value);
        const checkOutDate = new Date(checkOut.value);

        if (isNaN(checkInDate.getTime()) || isNaN(checkOutDate.getTime())) return;
        if (checkOutDate <= checkInDate) return;

        const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
        const subtotal = nights * propertyData.pricePerNight;
        const total = subtotal + propertyData.cleaningFee + propertyData.serviceFee;

        nightsCount.textContent = nights;
        subtotalElement.textContent = subtotal.toFixed(2) + ' KWD';
        totalPriceElement.textContent = total.toFixed(2) + ' KWD';
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map
        initMap();

        // Handle date inputs
        const checkIn = document.getElementById('check_in');
        const checkOut = document.getElementById('check_out');

        if (checkIn && checkOut) {
            checkIn.addEventListener('change', function() {
                if (checkOut) {
                    checkOut.min = this.value;
                }
                calculatePrice();
            });

            checkOut.addEventListener('change', calculatePrice);
        }

        // Handle gallery thumbnails
        const galleryThumbs = document.querySelectorAll('.gallery-thumb');
        const mainImage = document.querySelector('.gallery-main-image');

        if (mainImage && galleryThumbs.length > 0) {
            galleryThumbs.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    const imageUrl = this.dataset.image;
                    if (imageUrl) {
                        mainImage.src = imageUrl;
                        galleryThumbs.forEach(t => t.classList.remove('active'));
                        this.classList.add('active');
                    }
                });
            });
        }
    });
</script>
@endpush 