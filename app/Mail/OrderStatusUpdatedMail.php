<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function build()
    {
        return $this->subject('Order #' . $this->order->id . ' status: ' . ucfirst($this->order->status))
            ->markdown('mails.order_status_updated', ['order' => $this->order]);
    }
}
