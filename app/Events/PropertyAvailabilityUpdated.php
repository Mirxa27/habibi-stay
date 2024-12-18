<?php

namespace App\Events;

use App\Models\Property;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PropertyAvailabilityUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $property;

    public function __construct(Property $property)
    {
        $this->property = $property;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('property.' . $this->property->id);
    }

    public function broadcastWith()
    {
        return [
            'property' => [
                'id' => $this->property->id,
                'blocked_dates' => $this->property->blocked_dates,
                'min_nights' => $this->property->min_nights,
                'max_nights' => $this->property->max_nights,
                'advance_notice' => $this->property->advance_notice,
            ],
        ];
    }
}
