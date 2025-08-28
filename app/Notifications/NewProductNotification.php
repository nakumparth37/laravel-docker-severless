<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NewProductNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public  $product)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $this->onConnection('database')->onQueue('emails');
        return (new MailMessage)
                    ->subject('New Product Added')
                    ->greeting('Hello!')
                    ->line('A new product has been added to our catalog.')
                    ->line('Product Name: ' . $this->product->title)
                    ->line('Description: ' . $this->product->description)
                    ->action('View Product', url('/product/' . $this->product->id))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable)
    {
        $this->onConnection('database')->onQueue('notifications');
        return [
            'id' => $this->product->id,
            'title' => $this->product->title,
            'description' => $this->product->description,
        ];
    }

    public function toBroadcast($notifiable)
    {
        $this->onConnection('sync'); // Send real-time notifications
        return new BroadcastMessage([
            'id' => $this->product->id,
            'title' => $this->product->title,
            'description' => $this->product->description,
        ]);
    }
    public function shouldQueue()
    {
        // Only queue the database notification
        return true;
    }

    public function queueChannels()
    {
        return ['database', 'mail']; // Only queue database notifications
    }

    public function broadcastOn()
    {
        return ['product-channel'];
    }

    public function broadcastAs()
    {
        return 'add-new-product-event';
    }
}
