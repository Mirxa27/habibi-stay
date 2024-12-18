<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Str;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable): MailMessage
    {
        $conversation = $this->message->conversation;
        $property = $conversation->property;

        return (new MailMessage)
            ->subject('New Message Regarding ' . $property->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have received a new message from ' . $this->message->sender->name)
            ->line('Property: ' . $property->title)
            ->line('Message: "' . $this->message->content . '"')
            ->action('View Conversation', route('messages.show', $conversation))
            ->line('Thank you for using Habibi Stay!');
    }

    public function toArray($notifiable): array
    {
        return [
            'message_id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $this->message->sender->name,
            'property_id' => $this->message->conversation->property_id,
            'property_title' => $this->message->conversation->property->title,
            'content_preview' => Str::limit($this->message->content, 100),
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'message_id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_name' => $this->message->sender->name,
            'content_preview' => Str::limit($this->message->content, 100),
            'created_at' => $this->message->created_at->diffForHumans(),
        ]);
    }

    public function shouldQueue($notifiable): bool
    {
        return true;
    }

    public function viaQueues(): array
    {
        return [
            'mail' => 'notifications',
            'database' => 'default',
            'broadcast' => 'broadcasts',
        ];
    }
}
