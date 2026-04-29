<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ProductNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $product;
    public $message;

    public function __construct($product, $message)
    {
        $this->product = $product;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'product_id' => $this->product->id ?? $this->product,
            'product_name' => $this->product->name ?? null,
            'message' => $this->message,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => $this->toDatabase($notifiable),
            'created_at' => now(),
        ]);
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
