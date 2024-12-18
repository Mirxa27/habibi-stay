<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Property Management</h1>
                        <p class="mt-1 text-sm text-gray-500">
                            Manage your properties, bookings, and settings
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('properties.create') }}" class="btn-primary">
                            <i class="bi bi-plus-lg mr-2"></i>
                            Add New Property
                        </a>
                    </div>
                </div>
            </div>

            <!-- Property List -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <!-- Filters and Search -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex-1">
                            <form action="{{ route('dashboard.properties') }}" method="GET" class="flex gap-4">
                                <div class="flex-1">
                                    <label for="search" class="sr-only">Search properties</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="bi bi-search text-gray-400"></i>
                                        </div>
                                        <input type="text" name="search" id="search" 
                                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm"
                                               placeholder="Search properties..."
                                               value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div>
                                    <label for="status" class="sr-only">Status</label>
                                    <select name="status" id="status" 
                                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md">
                                        <option value="">All Status</option>
                                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="unavailable" {{ request('status') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
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
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}"
                                        {{ request('sort') === 'price_high' ? 'selected' : '' }}>
                                    Price: High to Low
                                </option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}"
                                        {{ request('sort') === 'price_low' ? 'selected' : '' }}>
                                    Price: Low to High
                                </option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'bookings']) }}"
                                        {{ request('sort') === 'bookings' ? 'selected' : '' }}>
                                    Most Bookings
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Property Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Property
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bookings
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rating
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Last Updated
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($properties as $property)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-lg object-cover" 
                                                     src="{{ asset('storage/' . ($property->images[0] ?? 'properties/default.jpg')) }}" 
                                                     alt="{{ $property->title }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $property->title }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $property->address }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $property->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $property->is_available ? 'Available' : 'Unavailable' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">SAR {{ number_format($property->base_price) }}</div>
                                        <div class="text-xs text-gray-500">per night</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $property->bookings_count }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $property->active_bookings_count }} active
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="star-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star-fill {{ $i <= $property->reviews_avg_rating ? 'text-accent' : 'text-gray-300' }}"></i>
                                                @endfor
                                            </div>
                                            <span class="ml-1 text-sm text-gray-500">
                                                ({{ $property->reviews_count }})
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $property->updated_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-3">
                                            <a href="{{ route('properties.show', $property) }}" 
                                               class="text-gray-400 hover:text-primary"
                                               title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('properties.edit', $property) }}" 
                                               class="text-gray-400 hover:text-primary"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('dashboard.property.calendar', $property) }}" 
                                               class="text-gray-400 hover:text-primary"
                                               title="Calendar">
                                                <i class="bi bi-calendar-week"></i>
                                            </a>
                                            <form action="{{ route('properties.destroy', $property) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this property?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-gray-400 hover:text-red-500"
                                                        title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        <div class="py-12">
                                            <i class="bi bi-house-add text-gray-400 text-4xl mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900">No properties found</h3>
                                            <p class="mt-1 text-gray-500">Get started by adding your first property</p>
                                            <a href="{{ route('properties.create') }}" class="btn-primary mt-4">
                                                Add Property
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($properties->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $properties->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 