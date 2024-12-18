<?php

namespace App\Listeners;

use App\Events\BookingConfirmed;
use App\Notifications\BookingConfirmationNotification;
use App\Notifications\CheckInReminderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBookingNotifications implements ShouldQueue
{
    public function handle(BookingConfirmed $event): void
    {
        $booking = $event->booking;
        
        // Send booking confirmation to guest
        $booking->user->notify(new BookingConfirmationNotification($booking));
        
        // Send booking notification to property owner
        $booking->property->user->notify(new BookingConfirmationNotification($booking));
        
        // Schedule check-in reminder for the day before check-in
        $checkInDate = $booking->check_in;
        $reminderDate = $checkInDate->copy()->subDay();
        
        // Schedule the check-in reminder notification
        $booking->user->notify((new CheckInReminderNotification($booking))->delay($reminderDate));
    }
} 