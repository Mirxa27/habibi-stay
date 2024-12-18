<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use App\Notifications\BookingConfirmation;
use App\Notifications\BookingReminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingService
{
    public function createBooking(array $data, Property $property, User $user): Booking
    {
        try {
            DB::beginTransaction();

            // Check availability
            if (!$this->isPropertyAvailable($property, $data['check_in'], $data['check_out'])) {
                throw new \Exception('Property is not available for the selected dates');
            }

            // Calculate total price
            $totalNights = Carbon::parse($data['check_in'])->diffInDays(Carbon::parse($data['check_out']));
            $totalPrice = $this->calculateTotalPrice($property, $data['check_in'], $data['check_out']);

            // Create booking
            $booking = new Booking([
                'property_id' => $property->id,
                'user_id' => $user->id,
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'guests' => $data['guests'],
                'total_price' => $totalPrice,
                'status' => 'pending',
                'booking_code' => $this->generateBookingCode(),
            ]);

            $booking->save();

            // Send confirmation notification
            $user->notify(new BookingConfirmation($booking));

            DB::commit();
            return $booking;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking creation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function isPropertyAvailable(Property $property, string $checkIn, string $checkOut): bool
    {
        return !Booking::where('property_id', $property->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<=', $checkIn)
                            ->where('check_out', '>=', $checkOut);
                    });
            })->exists();
    }

    protected function calculateTotalPrice(Property $property, string $checkIn, string $checkOut): float
    {
        $totalNights = Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut));
        $basePrice = $property->base_price;
        
        // Apply seasonal pricing if applicable
        $seasonalMultiplier = $this->getSeasonalPriceMultiplier($checkIn, $checkOut);
        
        return $basePrice * $totalNights * $seasonalMultiplier;
    }

    protected function getSeasonalPriceMultiplier(string $checkIn, string $checkOut): float
    {
        // Implement seasonal pricing logic here
        // For now, return default multiplier
        return 1.0;
    }

    protected function generateBookingCode(): string
    {
        return strtoupper(uniqid('BK'));
    }

    public function cancelBooking(Booking $booking): bool
    {
        try {
            DB::beginTransaction();

            $booking->status = 'cancelled';
            $booking->cancelled_at = now();
            $booking->save();

            // Process refund if applicable
            if ($this->isEligibleForRefund($booking)) {
                $this->processRefund($booking);
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking cancellation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function isEligibleForRefund(Booking $booking): bool
    {
        $cancellationDeadline = Carbon::parse($booking->check_in)->subDays(2);
        return Carbon::now()->lt($cancellationDeadline);
    }

    protected function processRefund(Booking $booking): void
    {
        // Implement refund logic here
        Log::info('Processing refund for booking: ' . $booking->id);
    }
}
