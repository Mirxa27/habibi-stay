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

class PropertyPriceUpdated implements ShouldBroadcast
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
                'base_price' => $this->property->base_price,
                'seasonal_prices' => $this->property->seasonalPrices,
            ],
        ];
    }
}
