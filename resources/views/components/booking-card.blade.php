@props(['booking'])

<div class="card booking-card mb-3 shadow-sm">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <img src="{{ $booking->property->featured_image }}" class="img-fluid rounded" alt="{{ $booking->property->title }}">
            </div>
            <div class="col-md-6">
                <h5 class="card-title mb-1">{{ $booking->property->title }}</h5>
                <p class="text-muted mb-2">
                    <i class="bi bi-geo-alt-fill"></i> {{ $booking->property->location }}
                </p>
                <div class="booking-dates mb-2">
                    <span class="me-3">
                        <i class="bi bi-calendar-check"></i> Check-in: {{ $booking->check_in->format('M d, Y') }}
                    </span>
                    <span>
                        <i class="bi bi-calendar-x"></i> Check-out: {{ $booking->check_out->format('M d, Y') }}
                    </span>
                </div>
                <div class="booking-details">
                    <span class="me-3">
                        <i class="bi bi-people"></i> {{ $booking->guests }} guests
                    </span>
                    <span class="me-3">
                        <i class="bi bi-moon"></i> {{ $booking->check_in->diffInDays($booking->check_out) }} nights
                    </span>
                </div>
            </div>
            <div class="col-md-3 text-end">
                <div class="booking-status mb-2">
                    <span class="badge bg-{{ $booking->status_color }}">{{ $booking->status }}</span>
                </div>
                <div class="booking-price mb-3">
                    <span class="fw-bold text-primary">{{ number_format($booking->total_price, 2) }} KWD</span>
                </div>
                <div class="booking-actions">
                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-primary btn-sm">
                        View Details
                    </a>
                    @if($booking->can_cancel)
                        <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                Cancel
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div> 