<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Reviews</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Manage and moderate property reviews
                </p>
            </div>

            <!-- Review List -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <!-- Filters -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex-1">
                            <form action="{{ route('reviews.index') }}" method="GET" class="flex gap-4">
                                <div class="flex-1">
                                    <label for="search" class="sr-only">Search reviews</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="bi bi-search text-gray-400"></i>
                                        </div>
                                        <input type="text" name="search" id="search" 
                                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm"
                                               placeholder="Search reviews..."
                                               value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div>
                                    <label for="rating" class="sr-only">Rating</label>
                                    <select name="rating" id="rating" 
                                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md">
                                        <option value="">All Ratings</option>
                                        @for($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                                {{ $i }} Stars
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label for="status" class="sr-only">Status</label>
                                    <select name="status" id="status" 
                                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md">
                                        <option value="">All Status</option>
                                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="reported" {{ request('status') === 'reported' ? 'selected' : '' }}>Reported</option>
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
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'rating_high']) }}"
                                        {{ request('sort') === 'rating_high' ? 'selected' : '' }}>
                                    Highest Rating
                                </option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'rating_low']) }}"
                                        {{ request('sort') === 'rating_low' ? 'selected' : '' }}>
                                    Lowest Rating
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                <div class="divide-y divide-gray-200">
                    @forelse($reviews as $review)
                        <div class="p-6">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <img class="h-10 w-10 rounded-full" 
                                         src="{{ $review->user->profile_photo_url }}" 
                                         alt="{{ $review->user->name }}">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">
                                                {{ $review->user->name }}
                                            </h3>
                                            <p class="text-sm text-gray-500">
                                                Reviewed {{ $review->property->title }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($review->status === 'published') bg-green-100 text-green-800
                                                @elseif($review->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($review->status === 'reported') bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($review->status) }}
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                {{ $review->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="flex items-center">
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star-fill {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-sm text-gray-500">
                                                {{ $review->rating }}.0 out of 5
                                            </span>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-700">
                                            {{ $review->comment }}
                                        </p>
                                        @if($review->response)
                                            <div class="mt-4 bg-gray-50 rounded-lg p-4">
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-shrink-0">
                                                        <img class="h-8 w-8 rounded-full" 
                                                             src="{{ $review->property->user->profile_photo_url }}" 
                                                             alt="{{ $review->property->user->name }}">
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            Response from {{ $review->property->user->name }}
                                                        </div>
                                                        <div class="mt-1 text-sm text-gray-700">
                                                            {{ $review->response }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mt-4 flex items-center justify-end space-x-4">
                                        @if($review->status === 'pending')
                                            <form action="{{ route('reviews.approve', $review) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-sm text-primary hover:text-primary-dark">
                                                    Approve
                                                </button>
                                            </form>
                                        @endif
                                        @if(!$review->response)
                                            <button type="button" 
                                                    class="text-sm text-primary hover:text-primary-dark"
                                                    onclick="Livewire.emit('openModal', 'respond-to-review', {{ json_encode(['reviewId' => $review->id]) }})">
                                                Respond
                                            </button>
                                        @endif
                                        @if($review->status !== 'reported')
                                            <form action="{{ route('reviews.report', $review) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-sm text-yellow-600 hover:text-yellow-900">
                                                    Report
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('reviews.destroy', $review) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this review?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-900">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="bi bi-star text-gray-400 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900">No reviews found</h3>
                            <p class="mt-1 text-gray-500">No reviews match your current filters</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($reviews->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $reviews->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Respond to Review Modal -->
    <div x-data="{ open: false }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 transition-opacity" 
                 aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Respond to Review
                        </h3>
                        <div class="mt-2">
                            <form action="{{ route('reviews.respond', $review) }}" method="POST">
                                @csrf
                                <textarea name="response" rows="4" 
                                          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm"
                                          placeholder="Write your response..."></textarea>
                                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                    <button type="submit" class="btn-primary">
                                        Submit Response
                                    </button>
                                    <button type="button" class="btn-secondary" @click="open = false">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 