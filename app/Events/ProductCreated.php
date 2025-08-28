<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProductCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Product $product)
    {
        //
    }

    /**
     * The channel the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        logger('Broadcast: broadcastOn hit');
        return new Channel('product-channel');
    }

    /**
     * The name the event will be broadcast as.
     */
    public function broadcastAs(): string
    {
        return 'add-new-product-event';
    }

    // Optional: You can remove this if you're not overriding the default
    public function broadcastVia(): array
    {
        Log::info('BroadcastVia used: ' . config('broadcasting.default'));
        return ['reverb']; // Force this
    }
}
