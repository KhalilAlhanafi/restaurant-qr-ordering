@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
    <!-- Breadcrumb -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.orders.index') }}" class="hover:text-amber-500 transition-colors">Orders</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-400">Order #{{ $order->id }}</span>
        </div>
    </div>

    <!-- New Items Alert Banner -->
    @if ($order->hasUnseenUpdates())
        <div id="new-items-banner" class="bg-amber-500/10 border-l-4 border-amber-500 p-4 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <span class="font-bold text-amber-500">🔴 New items added to this order!</span>
                    <span class="text-sm text-amber-400 ml-2">{{ $order->unseenItemsCount() }} item(s) waiting to be
                        acknowledged</span>
                </div>
                <button id="mark-all-seen-btn" class="bg-amber-500 hover:bg-amber-600 text-[#0a0a0a] px-4 py-2 rounded font-medium"
                    onclick="markAllAsSeen()">
                    ✓ Mark All as Seen
                </button>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2">
            <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-6 mb-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-sm text-gray-500">Table {{ $order->table->table_number ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="flex gap-2 items-center">
                        @if ($order->hasUnseenUpdates())
                            <span id="unseen-badge"
                                class="px-3 py-1 text-sm font-semibold rounded-full bg-amber-500/10 text-amber-500 animate-pulse">
                                🔴 {{ $order->unseenItemsCount() }} New
                            </span>
                        @endif
                        <a href="{{ route('admin.orders.add-items', $order) }}"
                            class="bg-green-500 hover:bg-green-600 text-[#0a0a0a] px-4 py-2 rounded text-sm font-medium">
                            + Add Items
                        </a>
                        <button onclick="printOrder('receipt')"
                            class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                </path>
                            </svg>
                            Receipt
                        </button>
                        <button onclick="printOrder('kitchen')"
                            class="bg-orange-500 hover:bg-orange-600 text-[#0a0a0a] px-4 py-2 rounded text-sm font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Kitchen
                        </button>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                                'preparing' => 'bg-orange-500/10 text-orange-400 border-orange-500/20',
                                'ready' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                'served' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                'completed' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                'cancelled' => 'bg-red-500/10 text-red-400 border-red-500/20',
                            ];
                        @endphp
                        <span
                            class="px-3 py-1 text-sm font-semibold rounded-full border {{ $statusColors[$order->status] ?? 'bg-gray-500/10 text-gray-400 border-gray-500/20' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                <h3 class="font-semibold text-white mb-3">Order Items</h3>
                <table class="w-full">
                    <thead class="border-b border-gray-700">
                        <tr>
                            <th class="text-left py-2 text-gray-400">Item</th>
                            <th class="text-center py-2 text-gray-400">Qty</th>
                            <th class="text-right py-2 text-gray-400">Price</th>
                            <th class="text-right py-2 text-gray-400">Subtotal</th>
                            <th class="text-center py-2 text-gray-400">Action</th>
                        </tr>
                    </thead>
                    <tbody id="order-items-tbody">
                        @foreach ($order->orderItems as $orderItem)
                            <tr id="item-row-{{ $orderItem->id }}"
                                class="border-b border-gray-700 order-item-row {{ $orderItem->admin_seen_at ? '' : 'new-item-highlight' }}"
                                data-item-id="{{ $orderItem->id }}"
                                data-is-unseen="{{ $orderItem->admin_seen_at ? 'false' : 'true' }}">
                                <td class="py-3">
                                    <div class="font-medium text-white">{{ $orderItem->item->name ?? 'N/A' }}</div>
                                    @if ($orderItem->special_instructions)
                                        <div class="text-xs text-gray-500">Note: {{ $orderItem->special_instructions }}
                                        </div>
                                    @endif
                                    @if (!$orderItem->admin_seen_at)
                                        <span class="new-item-badge">NEW</span>
                                    @endif
                                </td>
                                <td class="text-center py-3 text-gray-300">{{ $orderItem->quantity }}</td>
                                <td class="text-right py-3 text-gray-300">${{ number_format($orderItem->unit_price, 2) }}</td>
                                <td class="text-right py-3 font-medium text-amber-500">${{ number_format($orderItem->subtotal, 2) }}</td>
                                <td class="text-center py-3">
                                    @if (!$orderItem->admin_seen_at)
                                        <button
                                            class="mark-item-seen-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm font-medium"
                                            data-item-id="{{ $orderItem->id }}"
                                            onclick="markItemAsSeen({{ $orderItem->id }}, this)">
                                            ✓ Check
                                        </button>
                                    @else
                                        <span class="text-green-500">✓</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right py-3 font-semibold text-white">Subtotal:</td>
                            <td class="text-right py-3 font-medium text-gray-300">
                                ${{ number_format($order->orderItems->sum('subtotal'), 2) }}
                            </td>
                        </tr>
                        @if ($order->taxes->count() > 0)
                            @foreach ($order->taxes as $tax)
                                <tr>
                                    <td colspan="4" class="text-right py-2 text-sm text-gray-400">
                                        {{ $tax->title }}
                                        {{ $tax->type === 'percentage' ? '(' . $tax->value . '%)' : '' }}
                                    </td>
                                    <td class="text-right py-2 text-sm text-amber-400">
                                        ${{ number_format($tax->pivot->tax_amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        <tr class="border-t-2 border-gray-600">
                            <td colspan="4" class="text-right py-3 font-semibold text-white">Total:</td>
                            <td class="text-right py-3 font-bold text-lg text-amber-500">${{ number_format($order->total_amount, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Update Status -->
            <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-6">
                <h3 class="font-semibold text-white mb-4">Update Order Status</h3>
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="flex gap-2">
                    @csrf
                    @method('PUT')
                    <select name="status"
                        class="px-4 py-2 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white focus:outline-none focus:border-amber-500/50">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="served" {{ $order->status == 'served' ? 'selected' : '' }}>Served</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-[#0a0a0a] font-semibold py-2 px-4 rounded">
                        Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Customer Info -->
        <div>
            <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-6">
                <h3 class="font-semibold text-white mb-4">Customer Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="font-medium text-white">{{ $order->customer_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="font-medium text-white">{{ $order->customer_phone ?? 'N/A' }}</p>
                    </div>
                    @if ($order->special_requests)
                        <div>
                            <p class="text-sm text-gray-500">Special Requests</p>
                            <p class="font-medium text-white">{{ $order->special_requests }}</p>
                        </div>
                    @endif
                    @if ($order->notes)
                        <div>
                            <p class="text-sm text-gray-500">Notes</p>
                            <p class="font-medium text-white">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if ($order->checkout)
                <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-6 mt-6">
                    <h3 class="font-semibold text-white mb-4">Payment</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Method</p>
                            <p class="font-medium text-white">{{ ucfirst($order->checkout->payment_method) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Amount Paid</p>
                            <p class="font-medium text-amber-500">${{ number_format($order->checkout->amount_paid, 2) }}</p>
                        </div>
                        @if ($order->checkout->tip_amount > 0)
                            <div>
                                <p class="text-sm text-gray-500">Tip</p>
                                <p class="font-medium text-amber-500">${{ number_format($order->checkout->tip_amount, 2) }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .animate-pulse {
        animation: pulse 1.5s ease-in-out infinite;
    }

    .new-item-highlight {
        background-color: rgba(245, 158, 11, 0.1);
        border-left: 3px solid #f59e0b;
    }

    .new-item-badge {
        display: inline-block;
        background: #f59e0b;
        color: #0a0a0a;
        font-size: 10px;
        font-weight: bold;
        padding: 2px 6px;
        border-radius: 4px;
        margin-top: 4px;
    }
</style>

<script>
    const orderId = {{ $order->id }};
    let lastOrderUpdate = '{{ $order->updated_at->toISOString() }}';

    // Global function for inline onclick
    async function markItemAsSeen(itemId, btn) {
        console.log('markItemAsSeen called for item:', itemId);
        try {
            const url = `/admin/orders/${orderId}/items/${itemId}/mark-seen`;
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                // Update the row styling
                const row = document.getElementById(`item-row-${itemId}`);
                row.classList.remove('new-item-highlight');
                row.dataset.isUnseen = 'false';

                // Remove NEW badge
                const badge = row.querySelector('.new-item-badge');
                if (badge) badge.remove();

                // Replace button with checkmark
                btn.parentElement.innerHTML = '<span class="text-green-600">✓</span>';

                // Update counts
                updateUnseenCounts();

                // Clear notification badge if no more unseen items
                if (document.querySelectorAll('[data-is-unseen="true"]').length === 0) {
                    clearNotificationBadge();
                }

                // Trigger storage event to update main orders list
                const remainingUnseen = document.querySelectorAll('[data-is-unseen="true"]').length;
                localStorage.setItem('orderSeenUpdated', JSON.stringify({
                    orderId: orderId,
                    seenItems: remainingUnseen,
                    timestamp: Date.now()
                }));

                // Remove the storage item after a short delay
                setTimeout(() => {
                    localStorage.removeItem('orderSeenUpdated');
                }, 100);
            } else {
                const errorText = await response.text();
                console.error('Server error:', response.status, errorText);
                alert('Failed to mark item as seen. Check console.');
            }
        } catch (error) {
            console.error('Error marking item as seen:', error);
            alert('Error: ' + error.message);
        }
    }

    // Mark all items as seen
    const markAllBtn = document.getElementById('mark-all-seen-btn');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', async function() {
            try {
                const response = await fetch(`/admin/orders/${orderId}/mark-seen`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });

                if (response.ok) {
                    // Remove new item highlights from all rows
                    document.querySelectorAll('.new-item-highlight').forEach(row => {
                        row.classList.remove('new-item-highlight');
                        row.dataset.isUnseen = 'false';
                    });

                    // Remove all NEW badges
                    document.querySelectorAll('.new-item-badge').forEach(badge => {
                        badge.remove();
                    });

                    // Replace all check buttons with checkmarks
                    document.querySelectorAll('.mark-item-seen-btn').forEach(btn => {
                        btn.parentElement.innerHTML = '<span class="text-green-600">✓</span>';
                    });

                    // Remove the banner
                    const banner = document.getElementById('new-items-banner');
                    if (banner) banner.remove();

                    // Remove unseen badge
                    const unseenBadge = document.getElementById('unseen-badge');
                    if (unseenBadge) unseenBadge.remove();
                }
            } catch (error) {
                console.error('Error marking all as seen:', error);
            }
        });
    }

    // Poll for new items
    async function checkForOrderUpdates() {
        try {
            const response = await fetch(`/admin/orders/${orderId}/data`);
            const data = await response.json();

            if (data.updated_at !== lastOrderUpdate) {
                // Order was updated
                lastOrderUpdate = data.updated_at;

                // Add new items to table if any
                data.items.forEach(item => {
                    const existingRow = document.querySelector(`[data-item-id="${item.id}"]`);
                    if (!existingRow) {
                        // This is a new item - add it to table
                        const tbody = document.getElementById('order-items-tbody');
                        const newRow = `
                            <tr id="item-row-${item.id}" class="border-b order-item-row new-item-highlight"
                                data-item-id="${item.id}"
                                data-is-unseen="true">
                                <td class="py-3">
                                    <div class="font-medium">${item.name}</div>
                                    ${item.special_instructions ? `<div class="text-xs text-gray-500">Note: ${item.special_instructions}</div>` : ''}
                                    <span class="new-item-badge">NEW</span>
                                </td>
                                <td class="text-center py-3">${item.quantity}</td>
                                <td class="text-right py-3">$${parseFloat(item.unit_price).toFixed(2)}</td>
                                <td class="text-right py-3 font-medium">$${parseFloat(item.subtotal).toFixed(2)}</td>
                                <td class="text-center py-3">
                                    <button class="mark-item-seen-btn bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm font-medium"
                                            data-item-id="${item.id}">
                                        ✓ Check
                                    </button>
                                </td>
                            </tr>
                        `;
                        tbody.insertAdjacentHTML('beforeend', newRow);

                        // Add event listener to the new button
                        const newBtn = tbody.querySelector(`button[data-item-id="${item.id}"]`);
                        if (newBtn) {
                            newBtn.addEventListener('click', async function() {
                                const newItemId = this.dataset.itemId;
                                try {
                                    const markResponse = await fetch(
                                        `/admin/orders/${orderId}/items/${newItemId}/mark-seen`, {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Content-Type': 'application/json'
                                            }
                                        });

                                    if (markResponse.ok) {
                                        const newRowEl = document.getElementById(
                                            `item-row-${newItemId}`);
                                        newRowEl.classList.remove('new-item-highlight');
                                        newRowEl.dataset.isUnseen = 'false';
                                        const newBadge = newRowEl.querySelector(
                                            '.new-item-badge');
                                        if (newBadge) newBadge.remove();
                                        this.parentElement.innerHTML =
                                            '<span class="text-green-600">✓</span>';
                                        updateUnseenCounts();
                                    }
                                } catch (error) {
                                    console.error('Error marking item as seen:', error);
                                }
                            });
                        }
                    }
                });

                // Show banner and badge if there are unseen items
                if (data.unseen_items_count > 0) {
                    let banner = document.getElementById('new-items-banner');
                    if (!banner) {
                        const content = document.querySelector('.lg\\:col-span-2');
                        content.insertAdjacentHTML('beforebegin', `
                            <div id="new-items-banner" class="bg-red-100 border-l-4 border-red-500 p-4 mb-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="font-bold text-red-800">🔴 New items added to this order!</span>
                                        <span class="text-sm text-red-600 ml-2">${data.unseen_items_count} item(s) waiting to be acknowledged</span>
                                    </div>
                                    <button id="mark-all-seen-btn" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded font-medium" onclick="markAllAsSeen()">
                                        ✓ Mark All as Seen
                                    </button>
                                </div>
                            </div>
                        `);
                    }

                    let badge = document.getElementById('unseen-badge');
                    if (!badge) {
                        const headerDiv = document.querySelector('.flex.gap-2.items-center');
                        headerDiv.insertAdjacentHTML('afterbegin',
                            `<span id="unseen-badge" class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800 animate-pulse">🔴 ${data.unseen_items_count} New</span>`
                        );
                    } else {
                        badge.textContent = `🔴 ${data.unseen_items_count} New`;
                    }
                }

                // Update total
                document.querySelector('tfoot td:last-child').textContent = '$' + parseFloat(data.total_amount)
                    .toFixed(2);
            }
        } catch (error) {
            console.error('Error checking for order updates:', error);
        }
    }

    async function markAllAsSeen() {
        try {
            const response = await fetch(`/admin/orders/${orderId}/mark-seen`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                document.querySelectorAll('.new-item-highlight').forEach(row => {
                    row.classList.remove('new-item-highlight');
                    row.dataset.isUnseen = 'false';
                });
                document.querySelectorAll('.new-item-badge').forEach(badge => {
                    badge.remove();
                });
                document.querySelectorAll('.mark-item-seen-btn').forEach(btn => {
                    btn.parentElement.innerHTML = '<span class="text-green-600">✓</span>';
                });
                const banner = document.getElementById('new-items-banner');
                if (banner) banner.remove();
                const unseenBadge = document.getElementById('unseen-badge');
                if (unseenBadge) unseenBadge.remove();

                // Trigger storage event to update main orders list
                localStorage.setItem('orderSeenUpdated', JSON.stringify({
                    orderId: orderId,
                    seenItems: 0,
                    timestamp: Date.now()
                }));

                // Remove the storage item after a short delay to prevent re-triggering
                setTimeout(() => {
                    localStorage.removeItem('orderSeenUpdated');
                }, 100);
            }
        } catch (error) {
            console.error('Error marking all as seen:', error);
        }
    }

    function updateUnseenCounts() {
        const remainingUnseen = document.querySelectorAll('[data-is-unseen="true"]');
        if (remainingUnseen.length === 0) {
            const banner = document.getElementById('new-items-banner');
            if (banner) banner.remove();
            const unseenBadge = document.getElementById('unseen-badge');
            if (unseenBadge) unseenBadge.remove();
        } else {
            const unseenBadge = document.getElementById('unseen-badge');
            if (unseenBadge) {
                unseenBadge.textContent = `🔴 ${remainingUnseen.length} New`;
            }
            const bannerCount = document.querySelector('#new-items-banner span.text-sm');
            if (bannerCount) {
                bannerCount.textContent = `${remainingUnseen.length} item(s) waiting to be acknowledged`;
            }
        }
    }

    // Check for updates every 3 seconds
    setInterval(checkForOrderUpdates, 3000);

    // Clear notification badge when viewing order details
    function clearNotificationBadge() {
        if (typeof clearNotifications === 'function') {
            clearNotifications();
        }
    }

    // Clear badge on page load
    clearNotificationBadge();

    // Also clear badge when marking all as seen
    const originalMarkAllAsSeen = markAllAsSeen;
    markAllAsSeen = async function() {
        await originalMarkAllAsSeen.call(this);
        clearNotificationBadge();
    };

    async function printOrder(type) {
        let url = type === 'receipt' ? `/admin/orders/${orderId}/print-receipt` :
            `/admin/orders/${orderId}/print-kitchen`;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            if (data.success) {
                alert(data.message);
            } else {
                alert(data.message || 'Printing failed');
            }
        } catch (error) {
            console.error('Print error:', error);
            alert('An error occurred while printing.');
        }
    }
</script>
