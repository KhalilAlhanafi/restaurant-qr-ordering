<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RestaurantTable;
use App\Models\Tax;
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
        try {
            $tableId = session('table_id');

            if (!$tableId) {
                return response()->json([
                    'success' => false,
                    'error' => 'No table selected. Please scan the QR code again.'
                ], 403);
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

            // Calculate taxes
            $taxes = Tax::active()->get();
            $totalTaxAmount = 0;
            $taxDetails = [];

            foreach ($taxes as $tax) {
                $taxAmount = $tax->calculateTax($totalAmount);
                if ($taxAmount > 0) {
                    $totalTaxAmount += $taxAmount;
                    $taxDetails[] = [
                        'tax_id' => $tax->id,
                        'tax_amount' => $taxAmount
                    ];
                }
            }

            // Update total amount to include taxes
            $finalTotalAmount = $totalAmount + $totalTaxAmount;

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
                
                // Recalculate taxes for existing order based on new subtotal
                $newSubtotal = $existingOrder->orderItems->sum('subtotal');
                $existingOrder->taxes()->detach();
                
                foreach ($taxes as $tax) {
                    $taxAmount = $tax->calculateTax($newSubtotal);
                    if ($taxAmount > 0) {
                        $existingOrder->taxes()->attach($tax->id, [
                            'tax_amount' => $taxAmount
                        ]);
                        $existingOrder->total_amount += $taxAmount;
                    }
                }
                
                $existingOrder->save();
                $existingOrder->touch(); // Force timestamp update for polling

                $order = $existingOrder;
            } else {
                // Create new order
                $order = Order::create([
                    'table_id' => $tableId,
                    'total_amount' => $finalTotalAmount,
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
                
                // Attach taxes to new order
                foreach ($taxDetails as $taxDetail) {
                    $order->taxes()->attach($taxDetail['tax_id'], [
                        'tax_amount' => $taxDetail['tax_amount']
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
        } catch (\Exception $e) {
            \Log::error('Checkout error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
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
            $order->touch(); // Force update the timestamp to trigger polling
            
            // Clear session data for new customer
            session()->forget(['table_id', 'table_number', 'qr_token', 'cart', 'locale']);
            
            return redirect()->route('order.confirmation', $order)
                ->with('success', 'Order finalized! Thank you for dining with us.');
        }

        return redirect()->route('menu.index')
            ->with('error', 'No active order found to checkout.');
    }

    public function confirmation(Order $order)
    {
        // For checked out orders, allow access without table session
        if ($order->is_checked_out) {
            $order->load(['orderItems.item', 'table', 'taxes']);
            return view('menu.confirmation', compact('order'));
        }
        
        // For active orders, verify the order belongs to the current table session
        if ($order->table_id != session('table_id')) {
            abort(403);
        }

        $order->load(['orderItems.item', 'table', 'taxes']);

        return view('menu.confirmation', compact('order'));
    }
}
