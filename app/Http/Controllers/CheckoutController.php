<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $tableId = session('table_id');

        if (!$tableId) {
            return redirect()->route('qr.required');
        }

        $table = RestaurantTable::find($tableId);

        // Calculate kitchen load - estimated time based on current active orders
        $activeOrders = Order::whereIn('status', ['pending', 'preparing'])->count();
        $basePrepTime = 15; // Base preparation time in minutes
        $kitchenLoadDelay = min($activeOrders * 5, 30); // Max 30 min delay
        $estimatedTime = $basePrepTime + $kitchenLoadDelay;

        return view('menu.checkout', compact('table', 'estimatedTime'));
    }

    public function store(Request $request)
    {
        $tableId = session('table_id');

        if (!$tableId) {
            return redirect()->route('qr.required');
        }

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:500',
        ]);

        // Calculate totals
        $totalAmount = 0;
        $totalPrepTime = 0;

        foreach ($validated['items'] as $itemData) {
            $item = Item::find($itemData['id']);
            if ($item && $item->show_price) {
                $totalAmount += $item->price * $itemData['quantity'];
            }
            if ($item) {
                $totalPrepTime = max($totalPrepTime, $item->preparation_time ?? 15);
            }
        }

        // Calculate kitchen load delay
        $activeOrders = Order::whereIn('status', ['pending', 'preparing'])->count();
        $kitchenLoadDelay = min($activeOrders * 5, 30);
        $estimatedMinutes = $totalPrepTime + $kitchenLoadDelay;

        // Check for existing active order for this table
        $existingOrder = Order::activeForTable($tableId)->first();

        if ($existingOrder) {
            // Add items to existing order
            foreach ($validated['items'] as $itemData) {
                $item = Item::find($itemData['id']);
                $unitPrice = $item->show_price ? ($item->price ?? 0) : 0;
                $quantity = $itemData['quantity'] ?? 1;
                $subtotal = $unitPrice * $quantity;
                
                OrderItem::create([
                    'order_id' => $existingOrder->id,
                    'item_id' => $itemData['id'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'special_instructions' => $itemData['notes'] ?? null,
                ]);
            }

            // Update order totals
            $existingOrder->total_amount += $totalAmount;
            if ($validated['special_requests'] ?? null) {
                $existingOrder->special_requests = ($existingOrder->special_requests ? $existingOrder->special_requests . '; ' : '') . $validated['special_requests'];
            }
            $existingOrder->estimated_minutes = max($existingOrder->estimated_minutes, $estimatedMinutes);
            $existingOrder->save();

            $order = $existingOrder;
        } else {
            // Create new order
            $order = Order::create([
                'table_id' => $tableId,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'special_requests' => $validated['special_requests'] ?? null,
                'estimated_minutes' => $estimatedMinutes,
                'is_checked_out' => false,
            ]);

            // Create order items
            foreach ($validated['items'] as $itemData) {
                $item = Item::find($itemData['id']);
                $unitPrice = $item->show_price ? ($item->price ?? 0) : 0;
                $quantity = $itemData['quantity'] ?? 1;
                $subtotal = $unitPrice * $quantity;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $itemData['id'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'special_instructions' => $itemData['notes'] ?? null,
                ]);
            }
        }

        // Clear cart from session
        session()->forget('cart');

        // Always return JSON since request comes from JavaScript fetch
        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'is_existing' => isset($existingOrder),
            'redirect' => route('order.confirmation', $order)
        ]);
    }

    public function checkout(Request $request)
    {
        $tableId = session('table_id');

        if (!$tableId) {
            return redirect()->route('qr.required');
        }

        // Find the active order for this table and mark as checked out
        $order = Order::activeForTable($tableId)->first();

        if ($order) {
            $order->update(['is_checked_out' => true]);
            return redirect()->route('order.confirmation', $order)
                ->with('success', 'Order finalized! Thank you for dining with us.');
        }

        return redirect()->route('menu.index')
            ->with('error', 'No active order found to checkout.');
    }

    public function confirmation(Order $order)
    {
        // Verify the order belongs to the current table session
        if ($order->table_id != session('table_id')) {
            abort(403);
        }

        $order->load(['orderItems.item', 'table']);

        return view('menu.confirmation', compact('order'));
    }
}
