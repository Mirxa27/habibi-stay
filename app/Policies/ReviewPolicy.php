<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Review $review)
    {
        return true; // Reviews are public
    }

    public function create(User $user)
    {
        return true; // Any authenticated user can create reviews
    }

    public function update(User $user, Review $review)
    {
        return $user->id === $review->user_id && 
               $review->created_at->addDays(3)->isFuture(); // Can edit within 3 days
    }

    public function delete(User $user, Review $review)
    {
        return $user->id === $review->user_id || 
               $user->id === $review->property->user_id; // Review author or property owner
    }

    public function restore(User $user, Review $review)
    {
        return $user->id === $review->user_id;
    }

    public function forceDelete(User $user, Review $review)
    {
        return false; // No one can force delete reviews
    }
} 