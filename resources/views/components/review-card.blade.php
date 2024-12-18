@props(['review'])

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="d-flex align-items-center">
                <img src="{{ $review->user->profile_photo_url }}" 
                     class="rounded-circle me-3"
                     width="48"
                     height="48"
                     alt="{{ $review->user->name }}">
                <div>
                    <h6 class="mb-0">{{ $review->user->name }}</h6>
                    <div class="text-muted small">
                        {{ $review->created_at->format('M d, Y') }}
                    </div>
                </div>
            </div>
            <div class="text-warning">
                @for($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                @endfor
            </div>
        </div>

        <p class="mb-2">{{ $review->comment }}</p>

        @if($review->photos && count($review->photos) > 0)
            <div class="review-photos d-flex gap-2 mb-3">
                @foreach($review->photos as $photo)
                    <img src="{{ Storage::url($photo) }}" 
                         class="img-thumbnail"
                         style="width: 100px; height: 100px; object-fit: cover;"
                         alt="Review photo">
                @endforeach
            </div>
        @endif

        @if($review->response)
            <div class="border-start border-4 border-primary ps-3 mt-3">
                <div class="fw-bold mb-1">Response from host</div>
                <p class="mb-0 text-muted">{{ $review->response }}</p>
            </div>
        @endif

        @can('update', $review)
            <div class="mt-3 pt-3 border-top">
                <div class="btn-group">
                    <button type="button" 
                            class="btn btn-sm btn-outline-primary" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editReview{{ $review->id }}">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </button>
                    <form action="{{ route('reviews.destroy', $review) }}" 
                          method="POST" 
                          class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="btn btn-sm btn-outline-danger" 
                                onclick="return confirm('Are you sure you want to delete this review?')">
                            <i class="bi bi-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        @endcan
    </div>
</div>

@push('styles')
<style>
.review-photos img:hover {
    transform: scale(1.05);
    transition: transform 0.2s ease;
    cursor: pointer;
}
</style>
@endpush 