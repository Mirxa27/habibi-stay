@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Your Review</h3>
                <div class="mt-2 max-w-xl text-sm text-gray-500">
                    <p>Update your review for {{ $review->property->title }}</p>
                </div>
                <form action="{{ route('reviews.update', $review) }}" method="POST" class="mt-5">
                    @csrf
                    @method('PUT')
                    
                    <!-- Overall Rating -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Overall Rating</label>
                        <div class="flex items-center space-x-1">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="setRating({{ $i }})" 
                                    class="rating-star text-2xl {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 focus:outline-none">
                                    ★
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating" value="{{ $review->rating }}" required>
                        @error('rating')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Detailed Ratings -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Cleanliness -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cleanliness</label>
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" onclick="setDetailedRating('cleanliness', {{ $i }})" 
                                        class="cleanliness-star text-xl {{ $i <= $review->ratings_breakdown['cleanliness'] ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 focus:outline-none">
                                        ★
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="cleanliness_rating" id="cleanliness_rating" value="{{ $review->ratings_breakdown['cleanliness'] }}" required>
                        </div>

                        <!-- Communication -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Communication</label>
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" onclick="setDetailedRating('communication', {{ $i }})" 
                                        class="communication-star text-xl {{ $i <= $review->ratings_breakdown['communication'] ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 focus:outline-none">
                                        ★
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="communication_rating" id="communication_rating" value="{{ $review->ratings_breakdown['communication'] }}" required>
                        </div>

                        <!-- Check-in -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Check-in Experience</label>
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" onclick="setDetailedRating('check_in', {{ $i }})" 
                                        class="check_in-star text-xl {{ $i <= $review->ratings_breakdown['check_in'] ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 focus:outline-none">
                                        ★
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="check_in_rating" id="check_in_rating" value="{{ $review->ratings_breakdown['check_in'] }}" required>
                        </div>

                        <!-- Property Accuracy -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Property Accuracy</label>
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" onclick="setDetailedRating('accuracy', {{ $i }})" 
                                        class="accuracy-star text-xl {{ $i <= $review->ratings_breakdown['accuracy'] ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 focus:outline-none">
                                        ★
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="accuracy_rating" id="accuracy_rating" value="{{ $review->ratings_breakdown['accuracy'] }}" required>
                        </div>

                        <!-- Location -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" onclick="setDetailedRating('location', {{ $i }})" 
                                        class="location-star text-xl {{ $i <= $review->ratings_breakdown['location'] ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 focus:outline-none">
                                        ★
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="location_rating" id="location_rating" value="{{ $review->ratings_breakdown['location'] }}" required>
                        </div>

                        <!-- Value -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Value for Money</label>
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" onclick="setDetailedRating('value', {{ $i }})" 
                                        class="value-star text-xl {{ $i <= $review->ratings_breakdown['value'] ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 focus:outline-none">
                                        ★
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="value_rating" id="value_rating" value="{{ $review->ratings_breakdown['value'] }}" required>
                        </div>
                    </div>

                    <!-- Review Comment -->
                    <div class="mb-6">
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Your Review</label>
                        <textarea id="comment" name="comment" rows="4" required
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                            placeholder="Share your experience with this property...">{{ $review->comment }}</textarea>
                        @error('comment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('bookings.show', $review->booking) }}" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function setRating(value) {
    document.getElementById('rating').value = value;
    const stars = document.querySelectorAll('.rating-star');
    stars.forEach((star, index) => {
        star.classList.toggle('text-yellow-400', index < value);
        star.classList.toggle('text-gray-300', index >= value);
    });
}

function setDetailedRating(type, value) {
    document.getElementById(type + '_rating').value = value;
    const stars = document.querySelectorAll('.' + type + '-star');
    stars.forEach((star, index) => {
        star.classList.toggle('text-yellow-400', index < value);
        star.classList.toggle('text-gray-300', index >= value);
    });
}
</script>
@endpush 