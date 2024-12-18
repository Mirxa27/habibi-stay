<x-app-layout>
    <!-- Hero Section -->
    <div class="relative bg-gray-900">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover" src="{{ asset('images/hero.jpg') }}" alt="Hero background">
            <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                Find Your Perfect Stay
            </h1>
            <p class="mt-6 text-xl text-gray-300 max-w-3xl">
                Discover unique accommodations that feel like home, with all the amenities you need for a comfortable stay.
            </p>

            <!-- Search Form -->
            <div class="mt-10">
                <form id="property-search-form" action="{{ route('properties.index') }}" method="GET" 
                      class="bg-white rounded-lg shadow-md p-6 sm:flex sm:space-x-4">
                    <div class="flex-1 min-w-0 mb-4 sm:mb-0">
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-geo-alt text-gray-400"></i>
                            </div>
                            <input type="text" name="location" id="location" 
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                                   placeholder="Where are you going?">
                        </div>
                    </div>

                    <div class="flex-1 min-w-0 mb-4 sm:mb-0">
                        <label for="check_in" class="block text-sm font-medium text-gray-700">Check-in</label>
                        <div class="mt-1">
                            <input type="text" name="check_in" id="check_in" 
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm date-picker"
                                   placeholder="Add dates">
                        </div>
                    </div>

                    <div class="flex-1 min-w-0 mb-4 sm:mb-0">
                        <label for="check_out" class="block text-sm font-medium text-gray-700">Check-out</label>
                        <div class="mt-1">
                            <input type="text" name="check_out" id="check_out" 
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm date-picker"
                                   placeholder="Add dates">
                        </div>
                    </div>

                    <div class="flex-1 min-w-0 mb-4 sm:mb-0">
                        <label for="guests" class="block text-sm font-medium text-gray-700">Guests</label>
                        <div class="mt-1">
                            <select name="guests" id="guests" 
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }} guest{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="sm:flex sm:items-end">
                        <button type="submit" class="w-full sm:w-auto btn-primary mt-6 sm:mt-0">
                            <i class="bi bi-search mr-2"></i>
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Featured Properties -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Featured Properties
                </h2>
                <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                    Handpicked properties that offer exceptional experiences
                </p>
            </div>

            <div class="mt-12 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($featuredProperties as $property)
                    <div class="property-card card">
                        <img src="{{ asset('storage/' . ($property->images[0] ?? 'properties/default.jpg')) }}" 
                             alt="{{ $property->title }}"
                             class="w-full h-48 object-cover">
                        
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900">
                                    <a href="{{ route('properties.show', $property) }}" class="hover:text-primary">
                                        {{ $property->title }}
                                    </a>
                                </h3>
                                <p class="text-lg font-medium text-primary">
                                    SAR {{ number_format($property->base_price) }}
                                    <span class="text-sm text-gray-500">/night</span>
                                </p>
                            </div>

                            <p class="mt-2 text-sm text-gray-500">
                                <i class="bi bi-geo-alt"></i>
                                {{ $property->address }}
                            </p>

                            <div class="mt-4 flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center">
                                        <i class="bi bi-people text-gray-400 mr-1"></i>
                                        <span class="text-sm text-gray-500">{{ $property->max_guests }} guests</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="bi bi-door-closed text-gray-400 mr-1"></i>
                                        <span class="text-sm text-gray-500">{{ $property->bedrooms }} bedrooms</span>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <div class="star-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star-fill {{ $i <= $property->reviews->avg('rating') ? 'text-accent' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="ml-1 text-sm text-gray-500">
                                        ({{ $property->reviews->count() }})
                                    </span>
                                </div>
                            </div>

                            @if($property->is_halal_friendly || $property->is_family_friendly)
                                <div class="mt-4 flex space-x-2">
                                    @if($property->is_halal_friendly)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="bi bi-check-circle mr-1"></i>
                                            Halal Friendly
                                        </span>
                                    @endif
                                    @if($property->is_family_friendly)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="bi bi-house-heart mr-1"></i>
                                            Family Friendly
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('properties.index') }}" class="btn-primary">
                    View All Properties
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Why Choose Habibi Stay?
                </h2>
                <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                    Experience the best in comfort and convenience
                </p>
            </div>

            <div class="mt-12 grid gap-8 md:grid-cols-3">
                <div class="text-center">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white mx-auto">
                        <i class="bi bi-shield-check text-xl"></i>
                    </div>
                    <h3 class="mt-6 text-lg font-medium text-gray-900">Verified Properties</h3>
                    <p class="mt-2 text-base text-gray-500">
                        All properties are personally verified to ensure quality and comfort
                    </p>
                </div>

                <div class="text-center">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white mx-auto">
                        <i class="bi bi-heart text-xl"></i>
                    </div>
                    <h3 class="mt-6 text-lg font-medium text-gray-900">Halal & Family Friendly</h3>
                    <p class="mt-2 text-base text-gray-500">
                        Properties that cater to your specific needs and preferences
                    </p>
                </div>

                <div class="text-center">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white mx-auto">
                        <i class="bi bi-headset text-xl"></i>
                    </div>
                    <h3 class="mt-6 text-lg font-medium text-gray-900">24/7 Support</h3>
                    <p class="mt-2 text-base text-gray-500">
                        Our dedicated team is always here to help you
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-primary">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                <span class="block">Ready to list your property?</span>
                <span class="block text-accent">Join our growing community today.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                <div class="inline-flex rounded-md shadow">
                    <a href="{{ route('register') }}" class="btn-secondary">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 