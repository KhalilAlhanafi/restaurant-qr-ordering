<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['table', 'orderItems'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['table', 'orderItems.item', 'checkout']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,preparing,ready,served,completed,cancelled',
        ]);

        $order->update($validated);

        // Update table status if order is completed or cancelled
        if (in_array($validated['status'], ['completed', 'cancelled'])) {
            $order->table?->update(['status' => 'available']);
        }

        return redirect()->back()->with('success', 'Order status updated successfully');
    }
}
