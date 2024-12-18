@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Booking Management</h5>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exportModal">
                                <i class="bi bi-download me-1"></i> Export
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form action="{{ route('bookings.index') }}" method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">All Statuses</option>
                                    @foreach(['pending', 'confirmed', 'cancelled', 'completed'] as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="date_range" class="form-label">Date Range</label>
                                <select class="form-select" id="date_range" name="date_range">
                                    <option value="">All Time</option>
                                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                                    <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                                    <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                                    <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3 custom-date-range {{ request('date_range') == 'custom' ? '' : 'd-none' }}">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ request('start_date') }}">
                            </div>
                            
                            <div class="col-md-3 custom-date-range {{ request('date_range') == 'custom' ? '' : 'd-none' }}">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ request('end_date') }}">
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Apply Filters</button>
                                @if(request()->anyFilled(['status', 'date_range', 'start_date', 'end_date']))
                                    <a href="{{ route('bookings.index') }}" class="btn btn-light">Clear Filters</a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <!-- Bookings Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Property</th>
                                    <th>Guest</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                    <tr>
                                        <td>{{ $booking->id }}</td>
                                        <td>
                                            <a href="{{ route('properties.show', $booking->property) }}" class="text-decoration-none">
                                                {{ $booking->property->title }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $booking->user->profile_photo_url }}" 
                                                     alt="{{ $booking->user->name }}"
                                                     class="rounded-circle me-2"
                                                     width="32"
                                                     height="32">
                                                {{ $booking->user->name }}
                                            </div>
                                        </td>
                                        <td>{{ $booking->check_in->format('M d, Y') }}</td>
                                        <td>{{ $booking->check_out->format('M d, Y') }}</td>
                                        <td>{{ number_format($booking->total_amount, 3) }} KWD</td>
                                        <td>
                                            <span class="badge bg-{{ $booking->status_color }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $booking->payment_status_color }}">
                                                {{ ucfirst($booking->payment_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-light dropdown-toggle" 
                                                        data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{ route('bookings.show', $booking) }}" class="dropdown-item">
                                                            <i class="bi bi-eye me-2"></i> View Details
                                                        </a>
                                                    </li>
                                                    @if($booking->status === 'pending')
                                                        <li>
                                                            <form action="{{ route('bookings.confirm', $booking) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-success">
                                                                    <i class="bi bi-check-circle me-2"></i> Confirm
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    @if(in_array($booking->status, ['pending', 'confirmed']))
                                                        <li>
                                                            <form action="{{ route('bookings.cancel', $booking) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-danger"
                                                                        onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                                    <i class="bi bi-x-circle me-2"></i> Cancel
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <a href="{{ route('bookings.invoice', $booking) }}" class="dropdown-item">
                                                            <i class="bi bi-file-text me-2"></i> Download Invoice
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <i class="bi bi-calendar-x display-1 text-muted"></i>
                                            <p class="mt-3">No bookings found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $bookings->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Bookings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('bookings.export') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="export_format" class="form-label">Format</label>
                        <select class="form-select" id="export_format" name="format" required>
                            <option value="csv">CSV</option>
                            <option value="xlsx">Excel</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="export_date_range" class="form-label">Date Range</label>
                        <select class="form-select" id="export_date_range" name="date_range" required>
                            <option value="all">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    
                    <div class="mb-3 export-custom-date-range d-none">
                        <div class="row g-2">
                            <div class="col-6">
                                <label for="export_start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="export_start_date" name="start_date">
                            </div>
                            <div class="col-6">
                                <label for="export_end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="export_end_date" name="end_date">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Export</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Handle date range selection
    document.getElementById('date_range').addEventListener('change', function() {
        const customDateRangeFields = document.querySelectorAll('.custom-date-range');
        customDateRangeFields.forEach(field => {
            field.classList.toggle('d-none', this.value !== 'custom');
        });
    });

    // Handle export date range selection
    document.getElementById('export_date_range').addEventListener('change', function() {
        const customDateRangeFields = document.querySelector('.export-custom-date-range');
        customDateRangeFields.classList.toggle('d-none', this.value !== 'custom');
    });
</script>
@endpush
@endsection 