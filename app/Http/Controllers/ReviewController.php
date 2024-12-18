<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Booking;
use App\Http\Requests\StoreReviewRequest;
use App\Notifications\NewReviewNotification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display all reviews for properties and bookings.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $reviews = Auth::user()->reviews()
            ->with(['property', 'booking'])
            ->latest()
            ->paginate(10);

        return view('reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new review.
     */
    public function create(Booking $booking): View
    {
        $this->authorize('create', [Review::class, $booking]);
        return view('reviews.create', compact('booking'));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(StoreReviewRequest $request, Booking $booking): RedirectResponse
    {
        $this->authorize('create', [Review::class, $booking]);

        try {
            DB::transaction(function () use ($request, $booking) {
                $review = Review::create([
                    'booking_id' => $booking->id,
                    'property_id' => $booking->property_id,
                    'user_id' => Auth::id(),
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                    'ratings_breakdown' => [
                        'cleanliness' => $request->cleanliness_rating,
                        'communication' => $request->communication_rating,
                        'check_in' => $request->check_in_rating,
                        'accuracy' => $request->accuracy_rating,
                        'location' => $request->location_rating,
                        'value' => $request->value_rating,
                    ],
                ]);

                $booking->property->user->notify(new NewReviewNotification($review));
            });

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Review submitted successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to submit review. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit(Review $review): View
    {
        $this->authorize('update', $review);
        return view('reviews.edit', compact('review'));
    }

    /**
     * Update the specified review in storage.
     */
    public function update(StoreReviewRequest $request, Review $review): RedirectResponse
    {
        $this->authorize('update', $review);

        try {
            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'ratings_breakdown' => [
                    'cleanliness' => $request->cleanliness_rating,
                    'communication' => $request->communication_rating,
                    'check_in' => $request->check_in_rating,
                    'accuracy' => $request->accuracy_rating,
                    'location' => $request->location_rating,
                    'value' => $request->value_rating,
                ],
            ]);

            return redirect()->route('bookings.show', $review->booking)
                ->with('success', 'Review updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to update review. Please try again.');
        }
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review): RedirectResponse
    {
        $this->authorize('delete', $review);

        try {
            $review->delete();
            return back()->with('success', 'Review deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete review. Please try again.');
        }
    }

    /**
     * Report the specified review.
     */
    public function report(Review $review): RedirectResponse
    {
        try {
            $review->update([
                'is_reported' => true,
                'reported_at' => now(),
                'reported_by' => Auth::id(),
            ]);

            return back()->with('success', 'Review reported successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to report review. Please try again.');
        }
    }
} 