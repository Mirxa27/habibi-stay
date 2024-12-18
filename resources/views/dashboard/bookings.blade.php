<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Booking Management</h1>
                        <p class="mt-1 text-sm text-gray-500">
                            Manage your bookings and reservations
                        </p>
                    </div>
                </div>
            </div>

            <!-- Booking List -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <!-- Filters and Search -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex-1">
                            <form action="{{ route('dashboard.bookings') }}" method="GET" class="flex gap-4">
                                <div class="flex-1">
                                    <label for="search" class="sr-only">Search bookings</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="bi bi-search text-gray-400"></i>
                                        </div>
                                        <input type="text" name="search" id="search" 
                                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm"
                                               placeholder="Search bookings..."
                                               value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div>
                                    <label for="status" class="sr-only">Status</label>
                                    <select name="status" id="status" 
                                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="date_range" class="sr-only">Date Range</label>
                                    <select name="date_range" id="date_range" 
                                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md">
                                        <option value="">All Dates</option>
                                        <option value="upcoming" {{ request('date_range') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                        <option value="past" {{ request('date_range') === 'past' ? 'selected' : '' }}>Past</option>
                                        <option value="today" {{ request('date_range') === 'today' ? 'selected' : '' }}>Today</option>
                                        <option value="this_week" {{ request('date_range') === 'this_week' ? 'selected' : '' }}>This Week</option>
                                        <option value="this_month" {{ request('date_range') === 'this_month' ? 'selected' : '' }}>This Month</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn-primary">
                                    Filter
                                </button>
                            </form>
                        </div>
                        <div class="flex items-center gap-4">
                            <select name="sort" id="sort" 
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
                                    onchange="window.location.href = this.value">
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}"
                                        {{ request('sort') === 'newest' ? 'selected' : '' }}>
                                    Newest First
                                </option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}"
                                        {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                                    Oldest First
                                </option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'check_in']) }}"
                                        {{ request('sort') === 'check_in' ? 'selected' : '' }}>
                                    Check-in Date
                                </option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'total_price']) }}"
                                        {{ request('sort') === 'total_price' ? 'selected' : '' }}>
                                    Total Price
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Booking Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Booking Details
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Guest
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Payment
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dates
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bookings as $booking)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-lg object-cover" 
                                                     src="{{ asset('storage/' . ($booking->property->images[0] ?? 'properties/default.jpg')) }}" 
                                                     alt="{{ $booking->property->title }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $booking->property->title }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    Booking #{{ $booking->booking_number }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <img class="h-8 w-8 rounded-full" 
                                                     src="{{ $booking->user->profile_photo_url }}" 
                                                     alt="{{ $booking->user->name }}">
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $booking->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $booking->guests_count }} guests
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                            @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status === 'completed') bg-blue-100 text-blue-800
                                            @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            SAR {{ number_format($booking->total_price) }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            @if($booking->payment_status === 'paid')
                                                <span class="text-green-600">
                                                    <i class="bi bi-check-circle mr-1"></i>
                                                    Paid
                                                </span>
                                            @elseif($booking->payment_status === 'pending')
                                                <span class="text-yellow-600">
                                                    <i class="bi bi-clock mr-1"></i>
                                                    Payment Pending
                                                </span>
                                            @else
                                                <span class="text-red-600">
                                                    <i class="bi bi-x-circle mr-1"></i>
                                                    {{ ucfirst($booking->payment_status) }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $booking->check_in->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $booking->nights }} nights
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-3">
                                            <a href="{{ route('bookings.show', $booking) }}" 
                                               class="text-gray-400 hover:text-primary"
                                               title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($booking->status === 'pending')
                                                <form action="{{ route('bookings.confirm', $booking) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="text-gray-400 hover:text-green-500"
                                                            title="Confirm Booking">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if(in_array($booking->status, ['pending', 'confirmed']))
                                                <form action="{{ route('bookings.cancel', $booking) }}" 
                                                      method="POST" 
                                                      class="inline"
                                                      onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="text-gray-400 hover:text-red-500"
                                                            title="Cancel Booking">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('messages.show', $booking) }}" 
                                               class="text-gray-400 hover:text-primary"
                                               title="Message Guest">
                                                <i class="bi bi-chat"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        <div class="py-12">
                                            <i class="bi bi-calendar-x text-gray-400 text-4xl mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900">No bookings found</h3>
                                            <p class="mt-1 text-gray-500">No bookings match your current filters</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($bookings->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 