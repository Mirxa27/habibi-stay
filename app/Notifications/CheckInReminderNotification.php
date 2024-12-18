<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CheckInReminderNotification extends Notification implements ShouldQueue
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
            ->subject('Check-in Reminder - Habibi Stay')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('This is a reminder that your check-in at ' . $this->booking->property->title . ' is tomorrow.')
            ->line('Check-in time: ' . $this->booking->check_in->format('D, M d, Y'))
            ->line('Property Address: ' . $this->booking->property->address)
            ->line('Important Information:')
            ->line('- Please have your ID ready for verification')
            ->line('- Check-in code: ' . $this->booking->check_in_code)
            ->line('- Host contact: ' . $this->booking->property->user->phone)
            ->action('View Booking Details', route('bookings.show', $this->booking))
            ->line('We hope you have a wonderful stay!');
    }

    public function toArray($notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'property_title' => $this->booking->property->title,
            'check_in' => $this->booking->check_in->format('Y-m-d'),
            'message' => 'Your check-in is tomorrow.',
            'type' => 'check_in_reminder'
        ];
    }
} 