<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Calendar</h1>
                        <p class="mt-1 text-sm text-gray-500">
                            Manage your property availability and bookings
                        </p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div>
                            <select id="property-select" 
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
                                    onchange="window.location.href = this.value">
                                @foreach($properties as $p)
                                    <option value="{{ route('dashboard.calendar', ['property' => $p->id]) }}"
                                            {{ $property->id === $p->id ? 'selected' : '' }}>
                                        {{ $p->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" 
                                class="btn-primary"
                                onclick="Livewire.emit('openModal', 'block-dates')">
                            <i class="bi bi-calendar-x mr-2"></i>
                            Block Dates
                        </button>
                    </div>
                </div>
            </div>

            <!-- Calendar -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <!-- Calendar Navigation -->
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-4">
                            <button type="button" 
                                    class="p-2 text-gray-400 hover:text-gray-500"
                                    onclick="previousMonth()">
                                <i class="bi bi-chevron-left text-xl"></i>
                            </button>
                            <h2 class="text-xl font-semibold text-gray-900" id="current-month">
                                {{ $currentDate->format('F Y') }}
                            </h2>
                            <button type="button" 
                                    class="p-2 text-gray-400 hover:text-gray-500"
                                    onclick="nextMonth()">
                                <i class="bi bi-chevron-right text-xl"></i>
                            </button>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button type="button" 
                                    class="text-sm text-primary hover:text-primary-dark"
                                    onclick="setToday()">
                                Today
                            </button>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-1"></span>
                                    Available
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <span class="w-2 h-2 bg-red-400 rounded-full mr-1"></span>
                                    Blocked
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mr-1"></span>
                                    Booked
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="grid grid-cols-7 gap-px bg-gray-200 rounded-lg overflow-hidden">
                        <!-- Days of Week -->
                        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                            <div class="bg-gray-50 py-2">
                                <div class="text-center text-sm font-medium text-gray-500">
                                    {{ $day }}
                                </div>
                            </div>
                        @endforeach

                        <!-- Calendar Days -->
                        @foreach($calendar as $week)
                            @foreach($week as $day)
                                <div class="min-h-[120px] bg-white relative {{ $day['isToday'] ? 'ring-2 ring-primary ring-inset' : '' }}">
                                    <div class="px-2 py-1">
                                        <div class="text-sm {{ $day['isCurrentMonth'] ? 'text-gray-900' : 'text-gray-400' }}">
                                            {{ $day['date']->format('j') }}
                                        </div>
                                    </div>

                                    @if($day['isCurrentMonth'])
                                        <!-- Bookings -->
                                        @foreach($day['bookings'] as $booking)
                                            <div class="px-2 mb-1">
                                                <div class="text-xs bg-blue-50 text-blue-700 rounded px-1 py-0.5 truncate"
                                                     title="{{ $booking->user->name }} - {{ $booking->check_in->format('M d') }} to {{ $booking->check_out->format('M d') }}">
                                                    <i class="bi bi-calendar-check mr-1"></i>
                                                    {{ $booking->user->name }}
                                                </div>
                                            </div>
                                        @endforeach

                                        <!-- Blocked Dates -->
                                        @foreach($day['blockedDates'] as $block)
                                            <div class="px-2 mb-1">
                                                <div class="text-xs bg-red-50 text-red-700 rounded px-1 py-0.5 truncate"
                                                     title="Blocked: {{ $block->reason }}">
                                                    <i class="bi bi-x-circle mr-1"></i>
                                                    {{ Str::limit($block->reason, 20) }}
                                                </div>
                                            </div>
                                        @endforeach

                                        <!-- Price -->
                                        @if($day['price'])
                                            <div class="absolute bottom-1 right-2">
                                                <div class="text-xs font-medium text-gray-500">
                                                    SAR {{ number_format($day['price']) }}
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Upcoming Bookings -->
            <div class="mt-8">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Upcoming Bookings</h2>
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <ul role="list" class="divide-y divide-gray-200">
                        @forelse($upcomingBookings as $booking)
                            <li>
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <img class="h-10 w-10 rounded-full" 
                                                 src="{{ $booking->user->profile_photo_url }}" 
                                                 alt="{{ $booking->user->name }}">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $booking->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $booking->check_in->format('M d') }} - {{ $booking->check_out->format('M d, Y') }}
                                                    ({{ $booking->nights }} nights)
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                                @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                            <div class="text-sm text-gray-500">
                                                SAR {{ number_format($booking->total_price) }}
                                            </div>
                                            <a href="{{ route('bookings.show', $booking) }}" 
                                               class="text-primary hover:text-primary-dark">
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="px-4 py-5 text-center text-sm text-gray-500">
                                No upcoming bookings
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Calendar Navigation
        function previousMonth() {
            const date = new Date('{{ $currentDate->format('Y-m-d') }}');
            date.setMonth(date.getMonth() - 1);
            window.location.href = `{{ route('dashboard.calendar', ['property' => $property->id]) }}?date=${date.toISOString().split('T')[0]}`;
        }

        function nextMonth() {
            const date = new Date('{{ $currentDate->format('Y-m-d') }}');
            date.setMonth(date.getMonth() + 1);
            window.location.href = `{{ route('dashboard.calendar', ['property' => $property->id]) }}?date=${date.toISOString().split('T')[0]}`;
        }

        function setToday() {
            window.location.href = `{{ route('dashboard.calendar', ['property' => $property->id]) }}`;
        }

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            const tooltips = document.querySelectorAll('[title]');
            tooltips.forEach(el => {
                tippy(el, {
                    content: el.getAttribute('title'),
                    placement: 'top',
                });
                el.removeAttribute('title');
            });
        });
    </script>
    @endpush
</x-app-layout> 