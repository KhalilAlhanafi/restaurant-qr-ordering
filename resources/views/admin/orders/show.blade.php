@extends('layouts.admin')

@section('title', 'Order Details')
@section('header', 'Order #{{ $order->id }}')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-sm text-gray-500">Table {{ $order->table->table_number ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'preparing' => 'bg-orange-100 text-orange-800',
                            'ready' => 'bg-blue-100 text-blue-800',
                            'served' => 'bg-purple-100 text-purple-800',
                            'completed' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ];
                    @endphp
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                <h3 class="font-semibold mb-3">Order Items</h3>
                <table class="w-full">
                    <thead class="border-b">
                        <tr>
                            <th class="text-left py-2">Item</th>
                            <th class="text-center py-2">Qty</th>
                            <th class="text-right py-2">Price</th>
                            <th class="text-right py-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $orderItem)
                            <tr class="border-b">
                                <td class="py-3">
                                    <div class="font-medium">{{ $orderItem->item->name ?? 'N/A' }}</div>
                                    @if($orderItem->special_instructions)
                                        <div class="text-xs text-gray-500">Note: {{ $orderItem->special_instructions }}</div>
                                    @endif
                                </td>
                                <td class="text-center py-3">{{ $orderItem->quantity }}</td>
                                <td class="text-right py-3">${{ number_format($orderItem->unit_price, 2) }}</td>
                                <td class="text-right py-3 font-medium">${{ number_format($orderItem->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right py-3 font-semibold">Total:</td>
                            <td class="text-right py-3 font-bold text-lg">${{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Update Status -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold mb-4">Update Order Status</h3>
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="flex gap-2">
                    @csrf
                    @method('PUT')
                    <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="served" {{ $order->status == 'served' ? 'selected' : '' }}>Served</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                        Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Customer Info -->
        <div>
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold mb-4">Customer Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="font-medium">{{ $order->customer_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="font-medium">{{ $order->customer_phone ?? 'N/A' }}</p>
                    </div>
                    @if($order->notes)
                        <div>
                            <p class="text-sm text-gray-500">Notes</p>
                            <p class="font-medium">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($order->checkout)
                <div class="bg-white shadow rounded-lg p-6 mt-6">
                    <h3 class="font-semibold mb-4">Payment</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Method</p>
                            <p class="font-medium">{{ ucfirst($order->checkout->payment_method) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Amount Paid</p>
                            <p class="font-medium">${{ number_format($order->checkout->amount_paid, 2) }}</p>
                        </div>
                        @if($order->checkout->tip_amount > 0)
                            <div>
                                <p class="text-sm text-gray-500">Tip</p>
                                <p class="font-medium">${{ number_format($order->checkout->tip_amount, 2) }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
