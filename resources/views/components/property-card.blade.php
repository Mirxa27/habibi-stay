@props(['property'])

<div class="card h-100 shadow-sm hover-shadow">
    <!-- Property Image -->
    <div class="position-relative">
        <img src="{{ $property->images[0] ?? asset('images/placeholder.jpg') }}" 
             class="card-img-top"
             style="height: 200px; object-fit: cover;"
             alt="{{ $property->title }}">
        
        @if($property->is_featured)
            <div class="position-absolute top-0 start-0 m-2">
                <span class="badge bg-warning">
                    <i class="bi bi-star-fill me-1"></i> Featured
                </span>
            </div>
        @endif

        @if($property->instant_booking)
            <div class="position-absolute top-0 end-0 m-2">
                <span class="badge bg-success">
                    <i class="bi bi-lightning-fill me-1"></i> Instant Book
                </span>
            </div>
        @endif

        <div class="position-absolute bottom-0 start-0 end-0 p-3 bg-gradient-dark text-white">
            <h5 class="card-title mb-0 text-truncate">{{ $property->title }}</h5>
            <p class="card-text small mb-0">
                <i class="bi bi-geo-alt me-1"></i>
                {{ $property->city }}, {{ $property->country }}
            </p>
        </div>
    </div>

    <!-- Property Details -->
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="text-primary fw-bold">
                <span class="fs-4">SAR {{ number_format($property->base_price) }}</span>
                <span class="text-muted small">/night</span>
            </div>
            <div class="d-flex align-items-center">
                <div class="text-warning me-1">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= $property->reviews->avg('rating') ? '-fill' : '' }}"></i>
                    @endfor
                </div>
                <span class="text-muted small">
                    ({{ $property->reviews->count() }})
                </span>
            </div>
        </div>

        <div class="row g-2 text-center mb-3">
            <div class="col-4">
                <div class="p-2 bg-light rounded">
                    <i class="bi bi-people"></i>
                    <div class="small">{{ $property->max_guests }} Guests</div>
                </div>
            </div>
            <div class="col-4">
                <div class="p-2 bg-light rounded">
                    <i class="bi bi-door-closed"></i>
                    <div class="small">{{ $property->bedrooms }} Beds</div>
                </div>
            </div>
            <div class="col-4">
                <div class="p-2 bg-light rounded">
                    <i class="bi bi-droplet"></i>
                    <div class="small">{{ $property->bathrooms }} Baths</div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-1 mb-3">
            @foreach(array_slice($property->amenities, 0, 4) as $amenity)
                <span class="badge bg-light text-dark">
                    {{ ucfirst($amenity) }}
                </span>
            @endforeach
            @if(count($property->amenities) > 4)
                <span class="badge bg-light text-dark">
                    +{{ count($property->amenities) - 4 }} more
                </span>
            @endif
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="{{ $property->user->profile_photo_url }}" 
                     class="rounded-circle me-2"
                     width="32"
                     height="32"
                     alt="{{ $property->user->name }}">
                <div class="small">
                    <div class="fw-bold">{{ $property->user->name }}</div>
                    <div class="text-muted">{{ $property->user->properties->count() }} properties</div>
                </div>
            </div>
            <a href="{{ route('properties.show', $property) }}" 
               class="btn btn-outline-primary">
                View Details
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
.hover-shadow {
    transition: box-shadow 0.3s ease;
}
.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
.bg-gradient-dark {
    background: linear-gradient(0deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);
}
</style>
@endpush 