<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Order $order;
    public string $previousStatus;

    public function __construct(Order $order, string $previousStatus = '')
    {
        $this->order = $order;
        $this->previousStatus = $previousStatus;
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
        return 'order.status.updated';
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
                'previous_status' => $this->previousStatus,
                'total_amount' => $this->order->total_amount,
                'total_items' => $this->order->orderItems->sum('quantity'),
                'items_list' => $this->order->orderItems->map(fn($oi) => $oi->item->name)->implode(', '),
                'special_requests' => $this->order->special_requests,
                'estimated_minutes' => $this->order->estimated_minutes,
                'is_checked_out' => $this->order->is_checked_out,
                'is_completed' => in_array($this->order->status, ['completed', 'cancelled']),
                'unseen_items_count' => $this->order->unseenItemsCount(),
                'updated_at' => $this->order->updated_at->toISOString(),
                'updated_at_human' => $this->order->updated_at->diffForHumans(),
            ],
        ];
    }
}
