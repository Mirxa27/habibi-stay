<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Confirmation - Habibi Stay')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your booking has been confirmed for ' . $this->booking->property->title)
            ->line('Check-in: ' . $this->booking->check_in->format('D, M d, Y'))
            ->line('Check-out: ' . $this->booking->check_out->format('D, M d, Y'))
            ->line('Total Amount: SAR ' . number_format($this->booking->total_price, 2))
            ->action('View Booking Details', route('bookings.show', $this->booking))
            ->line('Thank you for choosing Habibi Stay!');
    }

    public function toArray($notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'property_title' => $this->booking->property->title,
            'check_in' => $this->booking->check_in->format('Y-m-d'),
            'check_out' => $this->booking->check_out->format('Y-m-d'),
            'total_price' => $this->booking->total_price,
            'message' => 'Your booking has been confirmed.',
            'type' => 'booking_confirmation'
        ];
    }
} 