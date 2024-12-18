<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold mb-4">List Your Property</h2>

                    <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Property Title</label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="property_type" class="block text-sm font-medium text-gray-700">Property Type</label>
                                <select name="property_type" id="property_type" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Select Type</option>
                                    <option value="apartment" {{ old('property_type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                    <option value="villa" {{ old('property_type') == 'villa' ? 'selected' : '' }}>Villa</option>
                                    <option value="house" {{ old('property_type') == 'house' ? 'selected' : '' }}>House</option>
                                </select>
                                @error('property_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <input type="text" name="address" id="address" value="{{ old('address') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                                    <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @error('latitude')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                                    <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @error('longitude')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="bedrooms" class="block text-sm font-medium text-gray-700">Bedrooms</label>
                                <input type="number" name="bedrooms" id="bedrooms" value="{{ old('bedrooms') }}" required min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('bedrooms')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="bathrooms" class="block text-sm font-medium text-gray-700">Bathrooms</label>
                                <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms') }}" required min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('bathrooms')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="price_per_night" class="block text-sm font-medium text-gray-700">Price per Night (SAR)</label>
                                <input type="number" name="price_per_night" id="price_per_night" value="{{ old('price_per_night') }}" required min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('price_per_night')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Amenities -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amenities</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @php
                                    $amenities = [
                                        'wifi' => 'Wi-Fi',
                                        'parking' => 'Parking',
                                        'pool' => 'Swimming Pool',
                                        'gym' => 'Gym',
                                        'air_conditioning' => 'Air Conditioning',
                                        'kitchen' => 'Kitchen',
                                        'tv' => 'TV',
                                        'washing_machine' => 'Washing Machine',
                                    ];
                                @endphp

                                @foreach($amenities as $value => $label)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="amenities[]" id="amenity_{{ $value }}" value="{{ $value }}"
                                            {{ in_array($value, old('amenities', [])) ? 'checked' : '' }}
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="amenity_{{ $value }}" class="ml-2 text-sm text-gray-700">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('amenities')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- House Rules -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">House Rules</label>
                            <div class="space-y-2">
                                <div>
                                    <input type="text" name="rules[]" placeholder="Add a rule"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <button type="button" class="text-sm text-indigo-600 hover:text-indigo-500" onclick="addRuleField()">
                                    + Add another rule
                                </button>
                            </div>
                            @error('rules')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Property Features -->
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_halal_friendly" id="is_halal_friendly" value="1"
                                    {{ old('is_halal_friendly') ? 'checked' : '' }}
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_halal_friendly" class="ml-2 text-sm text-gray-700">Halal Friendly</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_family_friendly" id="is_family_friendly" value="1"
                                    {{ old('is_family_friendly') ? 'checked' : '' }}
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_family_friendly" class="ml-2 text-sm text-gray-700">Family Friendly</label>
                            </div>
                        </div>

                        <!-- Images -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Property Images</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload images</span>
                                            <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" required>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                                </div>
                            </div>
                            @error('images')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                List Property
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function addRuleField() {
            const rulesContainer = document.querySelector('[name="rules[]"]').parentElement;
            const newInput = document.createElement('div');
            newInput.innerHTML = `
                <input type="text" name="rules[]" placeholder="Add a rule"
                    class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            `;
            rulesContainer.appendChild(newInput);
        }

        // Initialize Google Maps Autocomplete
        function initAutocomplete() {
            const input = document.getElementById('address');
            const autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function() {
                const place = autocomplete.getPlace();
                if (place.geometry) {
                    document.getElementById('latitude').value = place.geometry.location.lat();
                    document.getElementById('longitude').value = place.geometry.location.lng();
                }
            });
        }

        // Handle file input preview
        const imageInput = document.getElementById('images');
        imageInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const previewContainer = document.createElement('div');
            previewContainer.className = 'grid grid-cols-2 md:grid-cols-4 gap-4 mt-4';

            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('div');
                    preview.className = 'relative aspect-w-1 aspect-h-1';
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="object-cover rounded-lg">
                    `;
                    previewContainer.appendChild(preview);
                }
                reader.readAsDataURL(file);
            });

            const existingPreview = imageInput.parentElement.querySelector('.grid');
            if (existingPreview) {
                existingPreview.remove();
            }
            imageInput.parentElement.appendChild(previewContainer);
        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initAutocomplete" async defer></script>
    @endpush
</x-app-layout> 