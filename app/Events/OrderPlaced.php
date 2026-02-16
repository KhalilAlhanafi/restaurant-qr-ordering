<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Order $order;
    public bool $isNewOrder;

    public function __construct(Order $order, bool $isNewOrder = true)
    {
        $this->order = $order;
        $this->isNewOrder = $isNewOrder;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('admin.orders'),
            new PrivateChannel('order.' . $this->order->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'order.placed';
    }

    public function broadcastWith(): array
    {
        $this->order->load(['table', 'orderItems.item']);

        return [
            'order' => [
                'id' => $this->order->id,
                'table_id' => $this->order->table_id,
                'table_number' => $this->order->table?->table_number ?? 'N/A',
                'status' => $this->order->status,
                'total_amount' => $this->order->total_amount,
                'total_items' => $this->order->orderItems->sum('quantity'),
                'items_list' => $this->order->orderItems->map(fn($oi) => $oi->item->name)->implode(', '),
                'special_requests' => $this->order->special_requests,
                'estimated_minutes' => $this->order->estimated_minutes,
                'is_checked_out' => $this->order->is_checked_out,
                'is_new' => $this->isNewOrder,
                'unseen_items_count' => $this->order->unseenItemsCount(),
                'created_at' => $this->order->created_at->toISOString(),
                'created_at_human' => $this->order->created_at->diffForHumans(),
                'updated_at' => $this->order->updated_at->toISOString(),
            ],
            'is_new_order' => $this->isNewOrder,
        ];
    }
}
