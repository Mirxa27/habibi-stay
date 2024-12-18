@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <h2>Dashboard</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <x-stats-card 
                title="Total Revenue" 
                value="{{ number_format($currentMonthRevenue, 2) }} KWD"
                icon="cash-stack"
                color="success"
                :percentage="$revenueGrowth"
                :trend="$revenueGrowth > 0 ? 'up' : 'down'"
            />
        </div>
        <div class="col-xl-3 col-md-6">
            <x-stats-card 
                title="Total Bookings" 
                value="{{ $currentMonthBookings }}"
                icon="calendar-check"
                color="primary"
                :percentage="$bookingsGrowth"
                :trend="$bookingsGrowth > 0 ? 'up' : 'down'"
            />
        </div>
        <div class="col-xl-3 col-md-6">
            <x-stats-card 
                title="Active Properties" 
                value="{{ $activeProperties }}"
                icon="house-door"
                color="info"
            />
        </div>
        <div class="col-xl-3 col-md-6">
            <x-stats-card 
                title="Average Rating" 
                value="{{ number_format($averageRating, 1) }}"
                icon="star"
                color="warning"
            />
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Bookings</h5>
                </div>
                <div class="card-body">
                    @forelse($recentBookings as $booking)
                        <x-booking-card :booking="$booking" />
                    @empty
                        <p class="text-muted text-center py-4">No recent bookings found.</p>
                    @endforelse
                </div>
                @if($recentBookings->count() > 0)
                    <div class="card-footer text-end">
                        <a href="{{ route('bookings.index') }}" class="btn btn-primary">View All Bookings</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Properties and Calendar -->
    <div class="row g-3">
        <!-- Properties List -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Your Properties</h5>
                    @if(auth()->user()->isPropertyManager())
                        <a href="{{ route('properties.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg"></i> Add Property
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @forelse($properties as $property)
                            <div class="col-md-6">
                                <x-property-card :property="$property" />
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted text-center py-4">
                                    @if(auth()->user()->isPropertyManager())
                                        No properties found. Add your first property to get started!
                                    @else
                                        No properties found.
                                    @endif
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
                @if($properties->count() > 0)
                    <div class="card-footer text-end">
                        <a href="{{ route('properties.index') }}" class="btn btn-primary">Manage Properties</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Calendar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Upcoming Check-ins</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($upcomingCheckins as $checkin)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $checkin->property->title }}</h6>
                                    <small class="text-muted">{{ $checkin->check_in->format('M d') }}</small>
                                </div>
                                <p class="mb-1">{{ $checkin->guest_name }}</p>
                                <small class="text-muted">
                                    <i class="bi bi-people"></i> {{ $checkin->guests }} guests
                                    · {{ $checkin->check_in->diffInDays($checkin->check_out) }} nights
                                </small>
                            </div>
                        @empty
                            <p class="text-muted text-center py-4">No upcoming check-ins.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize any dashboard-specific JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Add any dashboard initialization code here
    });
</script>
@endpush 