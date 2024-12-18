<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmation extends Notification implements ShouldQueue
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
            ->subject('Booking Confirmation - ' . $this->booking->booking_code)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your booking has been confirmed.')
            ->line('Booking Details:')
            ->line('- Booking Code: ' . $this->booking->booking_code)
            ->line('- Check-in: ' . $this->booking->check_in->format('M d, Y'))
            ->line('- Check-out: ' . $this->booking->check_out->format('M d, Y'))
            ->line('- Total Price: $' . number_format($this->booking->total_price, 2))
            ->action('View Booking Details', url('/bookings/' . $this->booking->id))
            ->line('Thank you for choosing our service!');
    }

    public function toArray($notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'message' => 'Your booking has been confirmed.',
            'check_in' => $this->booking->check_in,
            'check_out' => $this->booking->check_out,
        ];
    }
}
