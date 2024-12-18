<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Edit Property</h2>
                        <form action="{{ route('properties.destroy', $property) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this property?')"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Delete Property
                            </button>
                        </form>
                    </div>

                    <form action="{{ route('properties.update', $property) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Property Title</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $property->title) }}" required
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
                                    <option value="apartment" {{ old('property_type', $property->property_type) == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                    <option value="villa" {{ old('property_type', $property->property_type) == 'villa' ? 'selected' : '' }}>Villa</option>
                                    <option value="house" {{ old('property_type', $property->property_type) == 'house' ? 'selected' : '' }}>House</option>
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
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $property->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <input type="text" name="address" id="address" value="{{ old('address', $property->address) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                                    <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude', $property->latitude) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @error('latitude')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                                    <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude', $property->longitude) }}" required
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
                                <input type="number" name="bedrooms" id="bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}" required min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('bedrooms')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="bathrooms" class="block text-sm font-medium text-gray-700">Bathrooms</label>
                                <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" required min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('bathrooms')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="price_per_night" class="block text-sm font-medium text-gray-700">Price per Night (SAR)</label>
                                <input type="number" name="price_per_night" id="price_per_night" value="{{ old('price_per_night', $property->price_per_night) }}" required min="0"
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
                                            {{ in_array($value, old('amenities', $property->amenities)) ? 'checked' : '' }}
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
                            <div class="space-y-2" id="rules-container">
                                @foreach(old('rules', $property->rules ?? []) as $rule)
                                    <div class="relative">
                                        <input type="text" name="rules[]" value="{{ $rule }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <button type="button" class="delete-rule absolute right-2 top-1/2 transform -translate-y-1/2 text-red-500 hover:text-red-700">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                                <div>
                                    <input type="text" name="rules[]" placeholder="Add a rule"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <button type="button" class="text-sm text-indigo-600 hover:text-indigo-500" data-action="add-rule">
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
                                    {{ old('is_halal_friendly', $property->is_halal_friendly) ? 'checked' : '' }}
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_halal_friendly" class="ml-2 text-sm text-gray-700">Halal Friendly</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_family_friendly" id="is_family_friendly" value="1"
                                    {{ old('is_family_friendly', $property->is_family_friendly) ? 'checked' : '' }}
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_family_friendly" class="ml-2 text-sm text-gray-700">Family Friendly</label>
                            </div>
                        </div>

                        <!-- Current Images -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="current-images">
                                @foreach($property->images as $index => $image)
                                    <div class="relative" data-image-index="{{ $index }}">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Property image {{ $index + 1 }}" 
                                            class="w-full h-32 object-cover rounded-lg">
                                        <input type="hidden" name="existing_images[]" value="{{ $image }}">
                                        <button type="button" 
                                            class="delete-image absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700 focus:outline-none">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Add New Images -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Add New Images</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md" 
                                id="dropzone">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload images</span>
                                            <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                                </div>
                            </div>
                            <div id="preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                            @error('images')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Update Property
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image deletion
        const currentImages = document.getElementById('current-images');
        if (currentImages) {
            currentImages.addEventListener('click', function(e) {
                if (e.target.closest('.delete-image')) {
                    const imageContainer = e.target.closest('[data-image-index]');
                    if (imageContainer && confirm('Are you sure you want to delete this image?')) {
                        const imageIndex = imageContainer.dataset.imageIndex;
                        fetch(`{{ route('properties.deleteImage', $property) }}/${imageIndex}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                imageContainer.remove();
                            } else {
                                alert('Failed to delete image. Please try again.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while deleting the image.');
                        });
                    }
                }
            });
        }

        // House rules
        const addRuleButton = document.querySelector('[data-action="add-rule"]');
        if (addRuleButton) {
            addRuleButton.addEventListener('click', function() {
                const rulesContainer = document.getElementById('rules-container');
                const newRule = document.createElement('div');
                newRule.className = 'relative';
                newRule.innerHTML = `
                    <input type="text" name="rules[]" placeholder="Add a rule"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <button type="button" class="delete-rule absolute right-2 top-1/2 transform -translate-y-1/2 text-red-500 hover:text-red-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                `;
                rulesContainer.appendChild(newRule);
            });
        }

        // Delete rule
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-rule')) {
                e.target.closest('.relative').remove();
            }
        });

        // File upload preview
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('images');
        const preview = document.getElementById('preview');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropzone.classList.add('border-indigo-500');
        }

        function unhighlight(e) {
            dropzone.classList.remove('border-indigo-500');
        }

        dropzone.addEventListener('drop', handleDrop, false);
        fileInput.addEventListener('change', handleFiles, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles({ target: { files } });
        }

        function handleFiles(e) {
            const files = [...e.target.files];
            files.forEach(previewFile);
        }

        function previewFile(file) {
            if (!file.type.startsWith('image/')) return;
            
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onloadend = function() {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${reader.result}" class="w-full h-32 object-cover rounded-lg" alt="Preview">
                    <button type="button" class="remove-preview absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700 focus:outline-none">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                `;
                preview.appendChild(div);
            }
        }

        // Remove preview
        preview.addEventListener('click', function(e) {
            if (e.target.closest('.remove-preview')) {
                e.target.closest('.relative').remove();
            }
        });
    });
    </script>
    @endpush
</x-app-layout> 