<?php

namespace App\Http\Controllers\Admin;

use App\Events\OrderPlaced;
use App\Events\OrderStatusUpdated;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['table', 'orderItems.item', 'checkout'])
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function recent(Request $request)
    {
        $since = $request->query('since', 0);
        $lastUpdate = $request->query('last_update', '1970-01-01');
        
        // Get all active orders - include everything that's not completed/cancelled
        $orders = Order::with(['table', 'orderItems.item'])
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->where(function($query) use ($since, $lastUpdate) {
                $query->where('id', '>', $since)
                      ->orWhere('updated_at', '>', $lastUpdate);
            })
            ->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) use ($lastUpdate) {
                return [
                    'id' => $order->id,
                    'table_number' => $order->table?->table_number ?? 'N/A',
                    'total_items' => $order->orderItems->sum('quantity'),
                    'items_list' => $order->orderItems->map(fn($oi) => $oi->item->name)->implode(', '),
                    'total_amount' => $order->total_amount,
                    'status' => $order->status,
                    'is_checked_out' => $order->is_checked_out,
                    'updated_at' => $order->updated_at->toISOString(),
                    'created_at' => $order->created_at->toISOString(),
                    'created_at_human' => $order->created_at->diffForHumans(),
                    'is_new' => $order->created_at->gt(now()->subSeconds(30)),
                    'was_updated' => $order->updated_at > $lastUpdate,
                    'unseen_items_count' => $order->unseenItemsCount(),
                ];
            });

        return response()->json([
            'orders' => $orders,
            'max_id' => Order::max('id'),
            'timestamp' => now()->toISOString(),
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['table', 'orderItems.item', 'checkout']);
        // Don't auto-mark as seen - let admin do it manually with the button
        return view('admin.orders.show', compact('order'));
    }

    public function markAsSeen(Order $order)
    {
        $order->markAsSeen();
        return response()->json(['success' => true]);
    }

    public function markItemAsSeen(Order $order, OrderItem $item)
    {
        // Ensure the item belongs to the order
        if ($item->order_id !== $order->id) {
            return response()->json(['success' => false, 'error' => 'Item does not belong to this order'], 403);
        }
        
        $order->markItemAsSeen($item->id);
        return response()->json(['success' => true]);
    }

    public function getOrderData(Order $order)
    {
        $order->load(['table', 'orderItems.item']);
        
        return response()->json([
            'id' => $order->id,
            'status' => $order->status,
            'total_amount' => $order->total_amount,
            'total_items' => $order->orderItems->sum('quantity'),
            'is_checked_out' => $order->is_checked_out,
            'admin_seen_at' => $order->admin_seen_at,
            'has_unseen_updates' => $order->hasUnseenUpdates(),
            'unseen_items_count' => $order->unseenItemsCount(),
            'updated_at' => $order->updated_at->toISOString(),
            'items' => $order->orderItems->map(function($oi) {
                return [
                    'id' => $oi->id,
                    'name' => $oi->item->name,
                    'quantity' => $oi->quantity,
                    'unit_price' => $oi->unit_price,
                    'subtotal' => $oi->subtotal,
                    'special_instructions' => $oi->special_instructions,
                    'is_new' => $oi->created_at->gt(now()->subSeconds(30)),
                    'is_unseen' => !$oi->admin_seen_at,
                ];
            }),
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,preparing,ready,served,completed,cancelled',
        ]);

        $previousStatus = $order->status;
        $order->update($validated);

        // Broadcast status update event
        broadcast(new OrderStatusUpdated($order, $previousStatus));

        // Update table status if order is completed or cancelled
        if (in_array($validated['status'], ['completed', 'cancelled'])) {
            $order->table?->update(['status' => 'available']);
        }

        return redirect()->back()->with('success', 'Order status updated successfully');
    }

    public function addItems(Order $order)
    {
        $order->load(['table', 'orderItems.item']);
        $categories = \App\Models\ItemCategory::with(['items' => function($query) {
            $query->where('is_available', true);
        }])->where('is_active', true)->get();
        
        return view('admin.orders.add-items', compact('order', 'categories'));
    }

    public function storeItems(Request $request, Order $order)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Load existing order items to check for duplicates
        $order->load('orderItems');
        $totalAmount = 0;

        foreach ($validated['items'] as $itemData) {
            $item = Item::find($itemData['id']);
            $quantity = $itemData['quantity'];
            $unitPrice = $item->show_price ? ($item->price ?? 0) : 0;
            $subtotal = $unitPrice * $quantity;
            $totalAmount += $subtotal;

            // Check if item already exists in order using the loaded relation
            $existingItem = $order->orderItems->firstWhere('item_id', $item->id);
            
            if ($existingItem) {
                // Update existing item quantity
                $existingItem->quantity += $quantity;
                $existingItem->subtotal = $existingItem->unit_price * $existingItem->quantity;
                $existingItem->save();
            } else {
                // Create new order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);
            }
        }

        // Update order total
        $order->total_amount += $totalAmount;
        $order->touch(); // Update the updated_at timestamp
        $order->save();

        // Broadcast order update event (as items were added)
        broadcast(new OrderPlaced($order, false));

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Items added to order successfully');
    }

    public function endService(Order $order)
    {
        $previousStatus = $order->status;
        
        // Mark order as completed
        $order->update(['status' => 'completed', 'completed_at' => now()]);
        
        // Broadcast status update event
        broadcast(new OrderStatusUpdated($order, $previousStatus));
        
        // Set table back to available
        if ($order->table) {
            $order->table->update(['status' => 'available']);
        }
        
        return response()->json(['success' => true, 'message' => 'Service ended successfully']);
    }
}
