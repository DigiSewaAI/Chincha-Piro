<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewOrderNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        return new Channel('orders');
    }

    /**
     * Broadcast with this name.
     */
    public function broadcastAs(): string
    {
        return 'new.order';
    }

    /**
     * Data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->order->id,
            'dish' => $this->order->dish->name ?? 'N/A',
            'amount' => $this->order->amount,
            'customer_name' => $this->order->customer_name,
            'status' => $this->order->status,
            'created_at' => $this->order->created_at->format('Y-m-d H:i')
        ];
    }
}
