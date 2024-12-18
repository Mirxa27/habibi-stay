<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $property = $this->review->property;
        
        return (new MailMessage)
            ->subject('New Review for ' . $property->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new review has been posted for your property: ' . $property->title)
            ->line('Rating: ' . $this->review->rating . ' stars')
            ->line('Comment: "' . $this->review->comment . '"')
            ->action('View Review', route('properties.show', $property))
            ->line('Thank you for using Habibi Stay!');
    }

    public function toArray($notifiable)
    {
        return [
            'review_id' => $this->review->id,
            'property_id' => $this->review->property_id,
            'property_title' => $this->review->property->title,
            'rating' => $this->review->rating,
            'reviewer_name' => $this->review->user->name,
        ];
    }
} 