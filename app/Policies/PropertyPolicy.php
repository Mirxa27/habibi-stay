<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PropertyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any properties.
     */
    public function viewAny(?User $user): bool
    {
        return true; // Anyone can view properties
    }

    /**
     * Determine if the user can view the property.
     */
    public function view(?User $user, Property $property): bool
    {
        return true; // Anyone can view individual properties
    }

    /**
     * Determine if the user can create properties.
     */
    public function create(User $user): bool
    {
        return $user->isVerified() || $user->isAdmin();
    }

    /**
     * Determine if the user can update the property.
     */
    public function update(User $user, Property $property): bool
    {
        return $user->id === $property->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can delete the property.
     */
    public function delete(User $user, Property $property): bool
    {
        return $user->id === $property->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can manage property media.
     */
    public function manageMedia(User $user, Property $property): bool
    {
        return $user->id === $property->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can manage property availability.
     */
    public function manageAvailability(User $user, Property $property): bool
    {
        return $user->id === $property->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can manage property pricing.
     */
    public function managePricing(User $user, Property $property): bool
    {
        return $user->id === $property->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can view property analytics.
     */
    public function viewAnalytics(User $user, Property $property): bool
    {
        return $user->id === $property->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can export property data.
     */
    public function export(User $user, Property $property): bool
    {
        return $user->id === $property->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can request property verification.
     */
    public function requestVerification(User $user, Property $property): bool
    {
        return $user->id === $property->user_id && !$property->is_verified;
    }

    /**
     * Determine if the user can perform bulk actions on properties.
     */
    public function bulkAction(User $user): bool
    {
        return $user->isAdmin();
    }
} 