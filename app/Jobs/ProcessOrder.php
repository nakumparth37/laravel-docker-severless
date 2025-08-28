<?php

namespace App\Jobs;

use App\Mail\OrderConfirmation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('OrderPlaced liner data', ['order' => $this->order]);

        try {
            Mail::to($this->order['senderEmail'])->send(new OrderConfirmation($this->order));
            Log::info("Email sent to " . $this->order['senderEmail']);
        } catch (\Exception $e) {
            Log::error('Error sending order confirmation email: ' . $e->getMessage(), [
                'exception' => $e,
                'order' => $this->order,
                'class' => self::class,
                'method' => 'handle',
            ]);
        }
    }
}
