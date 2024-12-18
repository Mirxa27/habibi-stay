@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Booking Details -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Booking Details</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('bookings.invoice', $booking) }}" class="btn btn-light">
                                <i class="bi bi-file-text me-1"></i> Download Invoice
                            </a>
                            @if($booking->status === 'pending')
                                <form action="{{ route('bookings.confirm', $booking) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle me-1"></i> Confirm Booking
                                    </button>
                                </form>
                            @endif
                            @if(in_array($booking->status, ['pending', 'confirmed']))
                                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Are you sure you want to cancel this booking?')">
                                        <i class="bi bi-x-circle me-1"></i> Cancel Booking
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Status Information -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <div>
                                    <span class="badge bg-{{ $booking->status_color }} me-2">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    <span class="badge bg-{{ $booking->payment_status_color }}">
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block">Booking ID</small>
                                    <strong>#{{ $booking->id }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Guest Information -->
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Guest Information</h6>
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ $booking->user->profile_photo_url }}" 
                                     alt="{{ $booking->user->name }}"
                                     class="rounded-circle me-3"
                                     width="64"
                                     height="64">
                                <div>
                                    <h6 class="mb-1">{{ $booking->user->name }}</h6>
                                    <p class="mb-0 text-muted">
                                        <i class="bi bi-envelope me-1"></i> {{ $booking->user->email }}
                                    </p>
                                    @if($booking->user->phone)
                                        <p class="mb-0 text-muted">
                                            <i class="bi bi-telephone me-1"></i> {{ $booking->user->phone }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex align-items-center text-muted">
                                <i class="bi bi-people me-2"></i>
                                {{ $booking->guests }} {{ Str::plural('guest', $booking->guests) }}
                            </div>
                        </div>

                        <!-- Dates Information -->
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Stay Details</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <small class="text-muted d-block">Check-in</small>
                                    <strong>{{ $booking->check_in->format('D, M d, Y') }}</strong>
                                    <small class="text-muted d-block">After {{ $booking->property->check_in_time }}</small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block">Check-out</small>
                                    <strong>{{ $booking->check_out->format('D, M d, Y') }}</strong>
                                    <small class="text-muted d-block">Before {{ $booking->property->check_out_time }}</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center text-muted">
                                <i class="bi bi-calendar me-2"></i>
                                {{ $booking->nights }} {{ Str::plural('night', $booking->nights) }}
                            </div>
                        </div>

                        <!-- Property Information -->
                        <div class="col-12">
                            <h6 class="fw-bold mb-3">Property Information</h6>
                            <div class="d-flex">
                                <img src="{{ $booking->property->featured_image }}" 
                                     alt="{{ $booking->property->title }}"
                                     class="rounded me-3"
                                     style="width: 120px; height: 80px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-1">
                                        <a href="{{ route('properties.show', $booking->property) }}" class="text-decoration-none">
                                            {{ $booking->property->title }}
                                        </a>
                                    </h6>
                                    <p class="mb-1 text-muted">
                                        <i class="bi bi-geo-alt me-1"></i> {{ $booking->property->location }}
                                    </p>
                                    <p class="mb-0">
                                        <span class="text-muted">Hosted by</span>
                                        <a href="#" class="text-decoration-none">
                                            {{ $booking->property->propertyManager->user->name }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="col-12">
                            <h6 class="fw-bold mb-3">Price Breakdown</h6>
                            <div class="bg-light p-3 rounded">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>{{ number_format($booking->price_per_night, 3) }} KWD × {{ $booking->nights }} nights</span>
                                    <span>{{ number_format($booking->subtotal, 3) }} KWD</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Cleaning fee</span>
                                    <span>{{ number_format($booking->cleaning_fee, 3) }} KWD</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Service fee</span>
                                    <span>{{ number_format($booking->service_fee, 3) }} KWD</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total</span>
                                    <span>{{ number_format($booking->total_amount, 3) }} KWD</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        @if($booking->payment)
                            <div class="col-12">
                                <h6 class="fw-bold mb-3">Payment Information</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tr>
                                            <th class="w-25">Payment ID</th>
                                            <td>{{ $booking->payment->payment_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Method</th>
                                            <td>{{ ucfirst($booking->payment->payment_method) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <span class="badge bg-{{ $booking->payment->status_color }}">
                                                    {{ ucfirst($booking->payment->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Date</th>
                                            <td>{{ $booking->payment->created_at->format('M d, Y H:i:s') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- Special Requests -->
                        @if($booking->special_requests)
                            <div class="col-12">
                                <h6 class="fw-bold mb-3">Special Requests</h6>
                                <p class="mb-0">{{ $booking->special_requests }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Messages</h5>
                </div>
                <div class="card-body">
                    @forelse($booking->messages as $message)
                        <div class="d-flex mb-3">
                            <img src="{{ $message->sender->profile_photo_url }}" 
                                 alt="{{ $message->sender->name }}"
                                 class="rounded-circle me-2"
                                 width="32"
                                 height="32">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong>{{ $message->sender->name }}</strong>
                                    <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0">{{ $message->content }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center mb-0">No messages yet</p>
                    @endforelse

                    <!-- Message Form -->
                    <form action="{{ route('messages.store') }}" method="POST" class="mt-3">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                        <div class="mb-3">
                            <label for="message" class="form-label">Send a Message</label>
                            <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Booking Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($booking->activities as $activity)
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong>{{ $activity->description }}</strong>
                                        <small class="text-muted">{{ $activity->created_at->format('M d, Y H:i') }}</small>
                                    </div>
                                    @if($activity->causer)
                                        <small class="text-muted">by {{ $activity->causer->name }}</small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 1.5rem;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .timeline-marker {
        position: absolute;
        left: -1.5rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #0d6efd;
    }
    
    .timeline-content {
        padding-left: 1rem;
    }
</style>
@endpush
@endsection 