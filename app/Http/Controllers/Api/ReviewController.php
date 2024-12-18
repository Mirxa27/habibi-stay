<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Property;
use App\Models\Booking;
use App\Http\Requests\StoreReviewRequest;
use App\Notifications\NewReviewNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Get reviews for a specific property.
     */
    public function propertyReviews(Property $property): JsonResponse
    {
        $reviews = $property->reviews()
            ->with('user')
            ->latest()
            ->paginate(10);

        return response()->json($reviews);
    }

    /**
     * Store a newly created review.
     */
    public function store(StoreReviewRequest $request, Booking $booking): JsonResponse
    {
        $this->authorize('create', [Review::class, $booking]);

        if ($booking->hasReview()) {
            return response()->json([
                'message' => 'You have already reviewed this booking'
            ], 422);
        }

        $review = new Review($request->validated());
        $review->user_id = $request->user()->id;
        $review->property_id = $booking->property_id;
        $review->booking_id = $booking->id;
        $review->save();

        // Notify property owner
        $booking->property->user->notify(new NewReviewNotification($review));

        return response()->json($review, 201);
    }

    /**
     * Update the specified review.
     */
    public function update(Request $request, Review $review): JsonResponse
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'cleanliness_rating' => 'required|integer|min:1|max:5',
            'communication_rating' => 'required|integer|min:1|max:5',
            'check_in_rating' => 'required|integer|min:1|max:5',
            'accuracy_rating' => 'required|integer|min:1|max:5',
            'location_rating' => 'required|integer|min:1|max:5',
            'value_rating' => 'required|integer|min:1|max:5'
        ]);

        $review->update($validated);
        return response()->json($review);
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review): JsonResponse
    {
        $this->authorize('delete', $review);
        $review->delete();
        return response()->json(null, 204);
    }

    /**
     * Report a review.
     */
    public function report(Request $request, Review $review): JsonResponse
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000'
        ]);

        $review->reports()->create([
            'user_id' => $request->user()->id,
            'reason' => $validated['reason']
        ]);

        if ($review->reports()->count() >= 3) {
            $review->is_hidden = true;
            $review->save();
        }

        return response()->json([
            'message' => 'Review reported successfully'
        ]);
    }
} 