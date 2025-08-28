<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastWith(): array
    {
        Log::info('🔊 Broadcasting message:', ['message' => $this->message]);
        return ['message' => $this->message];
    }

    public function broadcastOn()
    {
        return new Channel('chat-demo');
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}
