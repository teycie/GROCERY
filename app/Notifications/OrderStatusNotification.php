<?php

namespace App\Notifications;

use App\Models\Delivery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class OrderStatusNotification extends Notification
{
    use Queueable;

    public $delivery;
    public $message;
    public $statusType;

    /**
     * @param Delivery $delivery
     * @param string   $message    Human-readable notification message
     * @param string   $statusType The tracking status that triggered this
     */
    public function __construct(Delivery $delivery, string $message, string $statusType = '')
    {
        $this->delivery = $delivery;
        $this->message = $message;
        $this->statusType = $statusType ?: $delivery->tracking_status;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'delivery_id' => $this->delivery->id,
            'order_id' => $this->delivery->order_id,
            'product_name' => optional($this->delivery->product)->name,
            'status' => $this->statusType,
            'message' => $this->message,
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
