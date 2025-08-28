<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Log;

class SendOrderEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        Log::info('OrderPlaced liner data:', $event->order);
        try {
        if (Mail::to(auth()->user()->email)->send(new OrderConfirmation($event->order))) {
            Log::info("Email is sent to " . auth()->user()->email);
        }  else{
            Log::error("Email is not able to send");
        }
        } catch (\Exception $e) {
            Log::error('Error sending order confirmation email: ' . $e->getMessage(), [
                'exception' => $e,
                'data' => $event->order,
                'class' => 'SendOrderEmail',
                'method' => 'handle'
            ]);
        }
    }
}
