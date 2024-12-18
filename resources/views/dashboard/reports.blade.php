<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Reports & Analytics</h1>
                        <p class="mt-1 text-sm text-gray-500">
                            View insights and performance metrics for your properties
                        </p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div>
                            <select id="property-select" 
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
                                    onchange="window.location.href = this.value">
                                <option value="{{ route('dashboard.reports') }}">All Properties</option>
                                @foreach($properties as $p)
                                    <option value="{{ route('dashboard.reports', ['property' => $p->id]) }}"
                                            {{ isset($property) && $property->id === $p->id ? 'selected' : '' }}>
                                        {{ $p->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select id="date-range" 
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
                                    onchange="updateDateRange(this.value)">
                                <option value="30" {{ request('days') == 30 ? 'selected' : '' }}>Last 30 Days</option>
                                <option value="90" {{ request('days') == 90 ? 'selected' : '' }}>Last 90 Days</option>
                                <option value="180" {{ request('days') == 180 ? 'selected' : '' }}>Last 6 Months</option>
                                <option value="365" {{ request('days') == 365 ? 'selected' : '' }}>Last Year</option>
                                <option value="custom" {{ request('custom_range') ? 'selected' : '' }}>Custom Range</option>
                            </select>
                        </div>
                        <button type="button" 
                                class="btn-primary"
                                onclick="downloadReport()">
                            <i class="bi bi-download mr-2"></i>
                            Download Report
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                <!-- Revenue -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-currency-dollar text-2xl text-primary"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                                    <dd class="text-lg font-semibold text-gray-900">SAR {{ number_format($stats['total_revenue']) }}</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    vs. previous period
                                </div>
                                <div class="text-sm {{ $stats['revenue_growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    <i class="bi {{ $stats['revenue_growth'] >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }} mr-1"></i>
                                    {{ abs($stats['revenue_growth']) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bookings -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-calendar-check text-2xl text-blue-500"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Bookings</dt>
                                    <dd class="text-lg font-semibold text-gray-900">{{ $stats['total_bookings'] }}</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    {{ $stats['completed_bookings'] }} completed
                                </div>
                                <div class="text-sm {{ $stats['booking_growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    <i class="bi {{ $stats['booking_growth'] >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }} mr-1"></i>
                                    {{ abs($stats['booking_growth']) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Occupancy Rate -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-house-check text-2xl text-green-500"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Occupancy Rate</dt>
                                    <dd class="text-lg font-semibold text-gray-900">{{ $stats['occupancy_rate'] }}%</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    {{ $stats['total_nights'] }} nights booked
                                </div>
                                <div class="text-sm {{ $stats['occupancy_growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    <i class="bi {{ $stats['occupancy_growth'] >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }} mr-1"></i>
                                    {{ abs($stats['occupancy_growth']) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Average Rating -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-star text-2xl text-yellow-500"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Average Rating</dt>
                                    <dd class="text-lg font-semibold text-gray-900">{{ number_format($stats['average_rating'], 1) }}</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    {{ $stats['total_reviews'] }} reviews
                                </div>
                                <div class="text-sm {{ $stats['rating_growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    <i class="bi {{ $stats['rating_growth'] >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }} mr-1"></i>
                                    {{ abs($stats['rating_growth']) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 mb-8">
                <!-- Revenue Chart -->
                <div class="bg-white shadow rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900">Revenue Overview</h3>
                        <div class="mt-4" style="height: 300px;">
                            <canvas id="revenue-chart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Bookings Chart -->
                <div class="bg-white shadow rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900">Booking Trends</h3>
                        <div class="mt-4" style="height: 300px;">
                            <canvas id="bookings-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Performance -->
            @if(!isset($property))
                <div class="bg-white shadow rounded-lg mb-8">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Property Performance</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Property
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Revenue
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Bookings
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Occupancy
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Rating
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($propertyStats as $propertyStat)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-lg object-cover" 
                                                             src="{{ asset('storage/' . ($propertyStat->images[0] ?? 'properties/default.jpg')) }}" 
                                                             alt="{{ $propertyStat->title }}">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $propertyStat->title }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $propertyStat->address }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">SAR {{ number_format($propertyStat->revenue) }}</div>
                                                <div class="text-xs text-{{ $propertyStat->revenue_growth >= 0 ? 'green' : 'red' }}-600">
                                                    <i class="bi {{ $propertyStat->revenue_growth >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }} mr-1"></i>
                                                    {{ abs($propertyStat->revenue_growth) }}%
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $propertyStat->bookings_count }}</div>
                                                <div class="text-xs text-gray-500">{{ $propertyStat->completed_bookings }} completed</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $propertyStat->occupancy_rate }}%</div>
                                                <div class="text-xs text-gray-500">{{ $propertyStat->total_nights }} nights</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex items-center">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="bi bi-star-fill {{ $i <= $propertyStat->average_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                        @endfor
                                                    </div>
                                                    <span class="ml-2 text-sm text-gray-500">
                                                        ({{ $propertyStat->reviews_count }})
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Reviews -->
            <div class="bg-white shadow rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Recent Reviews</h3>
                        <a href="{{ route('reviews.index') }}" class="text-sm text-primary hover:text-primary-dark">
                            View all
                        </a>
                    </div>
                    <div class="flow-root">
                        <ul role="list" class="-my-5 divide-y divide-gray-200">
                            @forelse($recentReviews as $review)
                                <li class="py-5">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <img class="h-10 w-10 rounded-full" 
                                                 src="{{ $review->user->profile_photo_url }}" 
                                                 alt="{{ $review->user->name }}">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h4 class="text-sm font-medium text-gray-900">{{ $review->user->name }}</h4>
                                                    <p class="text-sm text-gray-500">
                                                        Reviewed {{ $review->property->title }}
                                                    </p>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $review->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <div class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star-fill {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                    @endfor
                                                </div>
                                                <p class="mt-2 text-sm text-gray-700">
                                                    {{ $review->comment }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="py-5 text-center text-sm text-gray-500">
                                    No reviews yet
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenue-chart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($charts['revenue']['labels']) !!},
                datasets: [{
                    label: 'Revenue',
                    data: {!! json_encode($charts['revenue']['data']) !!},
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'SAR ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Bookings Chart
        const bookingsCtx = document.getElementById('bookings-chart').getContext('2d');
        new Chart(bookingsCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($charts['bookings']['labels']) !!},
                datasets: [{
                    label: 'Bookings',
                    data: {!! json_encode($charts['bookings']['data']) !!},
                    backgroundColor: '#3B82F6',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Date Range Selection
        function updateDateRange(value) {
            if (value === 'custom') {
                // Show date picker modal
                Livewire.emit('openModal', 'date-range-picker');
            } else {
                const url = new URL(window.location.href);
                url.searchParams.set('days', value);
                url.searchParams.delete('start_date');
                url.searchParams.delete('end_date');
                window.location.href = url.toString();
            }
        }

        // Download Report
        function downloadReport() {
            const url = new URL('{{ route('dashboard.reports.download') }}', window.location.href);
            url.searchParams.set('property_id', '{{ $property->id ?? '' }}');
            url.searchParams.set('days', document.getElementById('date-range').value);
            window.location.href = url.toString();
        }
    </script>
    @endpush
</x-app-layout> 