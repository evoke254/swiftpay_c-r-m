<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockUpdateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $invoicedProducts;

    public function __construct($invoicedProducts)
    {
        $this->invoicedProducts = $invoicedProducts;
    }

    /**
     * Get the channels the event should broadcast on.
     *
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
     * 
     */
}
