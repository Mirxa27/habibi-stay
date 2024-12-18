<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Conversation $conversation)
    {
        return $user->id === $conversation->guest_id || 
               $user->id === $conversation->host_id;
    }

    public function reply(User $user, Conversation $conversation)
    {
        return $user->id === $conversation->guest_id || 
               $user->id === $conversation->host_id;
    }

    public function markAsRead(User $user, Message $message)
    {
        return $user->id === $message->conversation->guest_id || 
               $user->id === $message->conversation->host_id;
    }

    public function archive(User $user, Conversation $conversation)
    {
        return $user->id === $conversation->guest_id || 
               $user->id === $conversation->host_id;
    }
}
