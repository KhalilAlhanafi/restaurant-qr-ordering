@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl text-amber-500 tracking-wider mb-1">Orders</h1>
                <p class="text-xs uppercase tracking-[0.2em] text-gray-500">Manage customer orders</p>
            </div>
        </div>
    </div>

    <!-- New Order Notification Banner -->
    <div id="new-order-alert"
        style="display: none; background: #059669; color: white; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; text-align: center; animation: slideDown 0.3s ease;">
        <span style="font-size: 20px; margin-right: 10px;">🔔</span>
        <strong>New Order Received!</strong>
        <span id="new-order-details"></span>
    </div>

    <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-[#0f0f0f]">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800" id="orders-tbody">
                @forelse($orders as $order)
                    <tr id="order-{{ $order->id }}"
                        class="hover:bg-[#252525] transition-colors {{ $order->is_checked_out ? 'bg-green-500/5' : '' }} {{ $order->hasUnseenUpdates() ? 'has-unseen-updates' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                            #{{ $order->id }}
                            @if (!$order->is_checked_out)
                                <span class="ml-2 px-2 py-0.5 text-xs bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 rounded-full">Active</span>
                            @endif
                            @if ($order->hasUnseenUpdates())
                                <span class="new-items-badge"
                                    title="{{ $order->unseenItemsCount() }} new items">{{ $order->unseenItemsCount() }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 table-number"
                            data-table="{{ $order->table->table_number ?? 'N/A' }}">
                            Table {{ $order->table->table_number ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-400 items-cell">
                            <div class="font-medium text-white">{{ $order->orderItems->sum('quantity') }} items</div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $order->orderItems->map(fn($oi) => $oi->item->name)->implode(', ') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-amber-500 total-amount">
                            ${{ number_format($order->total_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap status-cell">
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
                            <span class="px-2 py-1 text-xs font-medium rounded-full border {{ $statusColors[$order->status] ?? 'bg-gray-500/10 text-gray-400 border-gray-500/20' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 type-cell">
                            @if ($order->is_checked_out)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-500/10 text-green-400 border border-green-500/20">
                                    ✓ Checked Out
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                    Open (Adding Items)
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 time-cell">
                            {{ $order->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-amber-500 hover:text-amber-400 transition-colors">View</a>
                            <button onclick="printOrder({{ $order->id }}, 'receipt')" class="ml-3 text-gray-400 hover:text-gray-300 transition-colors" title="Print Receipt">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                            </button>
                            <button onclick="printOrder({{ $order->id }}, 'kitchen')" class="ml-3 text-orange-400 hover:text-orange-300 transition-colors" title="Print Kitchen">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                            @if ($order->is_checked_out)
                                <button onclick="endService({{ $order->id }}, this)" class="ml-3 text-red-400 hover:text-red-300 font-semibold transition-colors">End Service</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr id="no-orders">
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 bg-amber-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-white mb-2">No Orders Yet</h3>
                            <p class="text-gray-500 mb-4">Orders will appear here when customers place them.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <style>
        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
            }

            50% {
                box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
            }
        }

        @keyframes highlight {
            0% {
                background-color: #fff3cd;
            }

            50% {
                background-color: #28a745;
            }

            100% {
                background-color: transparent;
            }
        }

        .new-items-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            height: 24px;
            background: #dc3545;
            color: white;
            font-size: 12px;
            font-weight: bold;
            padding: 0 6px;
            border-radius: 12px;
            margin-left: 8px;
            animation: pulse 2s infinite;
        }

        .has-unseen-updates {
            background-color: rgba(245, 158, 11, 0.15) !important;
            border-left: 4px solid #f59e0b;
        }

        .has-unseen-updates td {
            color: #f59e0b;
        }

        .new-order-row {
            animation: highlight 3s ease;
            border-left: 4px solid #28a745;
        }

        .new-order-row td {
            font-weight: 600;
        }

        .new-badge {
            animation: pulse 2s infinite;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
    </style>

    <script>
        let lastMaxId = {{ $orders->max('id') ?? 0 }};
        let lastUpdateTime = '{{ now()->subMinutes(5)->toISOString() }}';
        let knownOrderIds = new Set([{{ $orders->pluck('id')->implode(',') }}]);

        const statusColors = {
            'pending': 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
            'preparing': 'bg-orange-500/10 text-orange-400 border-orange-500/20',
            'ready': 'bg-blue-500/10 text-blue-400 border-blue-500/20',
            'served': 'bg-purple-500/10 text-purple-400 border-purple-500/20',
            'completed': 'bg-green-500/10 text-green-400 border-green-500/20',
            'cancelled': 'bg-red-500/10 text-red-400 border-red-500/20',
        };

        function showNotification(order) {
            const alert = document.getElementById('new-order-alert');
            const details = document.getElementById('new-order-details');
            const message = order.was_updated && !order.is_new ?
                `Table ${order.table_number} - Order updated!` :
                `Table ${order.table_number} - ${order.total_items} items - $${parseFloat(order.total_amount).toFixed(2)}`;
            details.textContent = message;
            alert.style.display = 'block';

            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        }

        function showCheckoutNotification(order) {
            const alert = document.getElementById('new-order-alert');
            const details = document.getElementById('new-order-details');
            alert.style.background = '#28a745'; // Green for checkout
            details.textContent = `Table ${order.table_number} - Order Checked Out! Ready for payment`;
            alert.style.display = 'block';

            setTimeout(() => {
                alert.style.display = 'none';
                // Reset to original color
                alert.style.background = '#28a745';
            }, 5000);
        }

        function createOrderRow(order) {
            const isCheckedOut = order.is_checked_out;
            const typeBadge = isCheckedOut ?
                '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-500/10 text-green-400 border border-green-500/20">✓ Checked Out</span>' :
                '<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20">Open (Adding Items)</span>';

            const activeBadge = !isCheckedOut ?
                '<span class="ml-2 px-2 py-0.5 text-xs bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 rounded-full">Active</span>' :
                '';

            const unseenBadge = (order.unseen_items_count > 0) ?
                `<span class="new-items-badge" title="${order.unseen_items_count} new items">${order.unseen_items_count}</span>` :
                '';

            let rowClass = order.is_new ? 'new-order-row' : (isCheckedOut ? 'bg-green-500/5' : '');
            if (order.unseen_items_count > 0) {
                rowClass += ' has-unseen-updates';
            }

            return `
                <tr id="order-${order.id}" class="${rowClass} hover:bg-[#252525] transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                        #${order.id}${activeBadge}${unseenBadge}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 table-number" data-table="${order.table_number}">
                        Table ${order.table_number}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-400 items-cell">
                        <div class="font-medium text-white">${order.total_items} items</div>
                        ${order.items_list ? `<div class="text-xs text-gray-500 mt-1">${order.items_list}</div>` : ''}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-amber-500 total-amount">
                        $${parseFloat(order.total_amount).toFixed(2)}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap status-cell">
                        <span class="px-2 py-1 text-xs font-medium rounded-full border ${statusColors[order.status] || 'bg-gray-500/10 text-gray-400 border-gray-500/20'}">
                            ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 type-cell">
                        ${typeBadge}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 time-cell">
                        ${order.created_at_human}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <a href="/admin/orders/${order.id}" class="text-amber-500 hover:text-amber-400 transition-colors">View</a>
                        <button onclick="printOrder(${order.id}, 'receipt')" class="ml-3 text-gray-400 hover:text-gray-300 transition-colors" title="Print Receipt">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        </button>
                        <button onclick="printOrder(${order.id}, 'kitchen')" class="ml-3 text-orange-400 hover:text-orange-300 transition-colors" title="Print Kitchen">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </button>
                        ${order.is_checked_out ? `<button onclick="endService(${order.id}, this)" class="ml-3 text-red-400 hover:text-red-300 font-semibold transition-colors">End Service</button>` : ''}
                    </td>
                </tr>
            `;
        }

        function updateExistingRow(order) {
            console.log('updateExistingRow called for order:', order.id, 'checked_out:', order.is_checked_out);
            const row = document.getElementById(`order-${order.id}`);
            if (!row) {
                console.log('Row not found for order:', order.id);
                return;
            }

            // Update item count and items list
            const itemsCell = row.querySelector('.items-cell');
            itemsCell.innerHTML = `
                <div class="font-medium text-white">${order.total_items} items</div>
                ${order.items_list ? `<div class="text-xs text-gray-500 mt-1">${order.items_list}</div>` : ''}
            `;

            // Update total
            row.querySelector('.total-amount').textContent = `$${parseFloat(order.total_amount).toFixed(2)}`;
            row.querySelector('.time-cell').textContent = order.created_at_human;

            // Update status
            const statusCell = row.querySelector('.status-cell span');
            statusCell.className =
                `px-2 py-1 text-xs font-medium rounded-full border ${statusColors[order.status] || 'bg-gray-500/10 text-gray-400 border-gray-500/20'}`;
            statusCell.textContent = order.status.charAt(0).toUpperCase() + order.status.slice(1);

            // Update type (checked out status) - this is the key part
            const typeCell = row.querySelector('.type-cell');
            const actionCell = row.querySelector('td:last-child');
            const orderNumberCell = row.querySelector('td:first-child');

            if (order.is_checked_out) {
                // Update to checked out state
                typeCell.innerHTML =
                    '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-500/10 text-green-400 border border-green-500/20">✓ Checked Out</span>';
                row.classList.add('bg-green-500/5');

                // Remove Active badge if it exists
                const activeBadge = orderNumberCell.querySelector('[class*="bg-yellow-500/10"]');
                if (activeBadge) {
                    activeBadge.remove();
                }

                // Add End Service button if not already present
                if (!actionCell.querySelector('button[class*="text-red-400"]')) {
                    actionCell.insertAdjacentHTML('beforeend',
                        `<button onclick="endService(${order.id}, this)" class="ml-3 text-red-400 hover:text-red-300 font-semibold transition-colors">End Service</button>`
                    );
                }

                // Show notification for checkout
                if (order.was_updated && !order.is_new) {
                    showCheckoutNotification(order);
                }
            } else {
                // Update to open state
                typeCell.innerHTML =
                    '<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20">Open (Adding Items)</span>';
                row.classList.remove('bg-green-500/5');

                // Add Active badge if not present
                if (!orderNumberCell.querySelector('[class*="bg-yellow-500/10"]')) {
                    orderNumberCell.insertAdjacentHTML('beforeend',
                        '<span class="ml-2 px-2 py-0.5 text-xs bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 rounded-full">Active</span>'
                    );
                }

                // Remove End Service button if present
                const endServiceBtn = actionCell.querySelector('button[class*="text-red-400"]');
                if (endServiceBtn) {
                    endServiceBtn.remove();
                }
            }

            // Update or remove unseen items badge
            let existingBadge = orderNumberCell.querySelector('.new-items-badge');

            if (order.unseen_items_count > 0) {
                if (!existingBadge) {
                    orderNumberCell.insertAdjacentHTML('beforeend',
                        `<span class="new-items-badge" title="${order.unseen_items_count} new items">${order.unseen_items_count}</span>`
                    );
                } else {
                    const currentCount = parseInt(existingBadge.textContent);
                    if (currentCount !== order.unseen_items_count) {
                        existingBadge.textContent = order.unseen_items_count;
                        existingBadge.title = `${order.unseen_items_count} new items`;
                    }
                }
                row.classList.add('has-unseen-updates');
            } else {
                if (existingBadge) {
                    existingBadge.remove();
                }
                row.classList.remove('has-unseen-updates');
            }
        }

        // WebSocket removed for simplicity - using polling only
        // Polling runs every 3 seconds for new orders

        async function checkForNewOrders() {
            try {
                console.log('Polling... lastMaxId:', lastMaxId, 'lastUpdate:', lastUpdateTime, 'knownOrders:', Array
                    .from(knownOrderIds));
                const response = await fetch(
                    `/admin/orders/recent?since=${lastMaxId}&last_update=${encodeURIComponent(lastUpdateTime)}`);
                const data = await response.json();
                console.log('Got', data.orders.length, 'orders:', data.orders.map(o => ({
                    id: o.id,
                    status: o.status,
                    checked_out: o.is_checked_out,
                    was_updated: o.was_updated,
                    unseen: o.unseen_items_count
                })));

                if (data.orders && data.orders.length > 0) {
                    const tbody = document.getElementById('orders-tbody');
                    const noOrdersRow = document.getElementById('no-orders');

                    // Always remove "no orders" row when we have any orders
                    if (noOrdersRow) {
                        noOrdersRow.remove();
                        console.log('Removed "no orders" row');
                    }

                    data.orders.forEach(order => {
                        const existingRow = document.getElementById(`order-${order.id}`);

                        if (!existingRow) {
                            // Brand new order - ALWAYS add if not in DOM
                            console.log('New order detected:', order.id);
                            const rowHtml = createOrderRow(order);
                            tbody.insertAdjacentHTML('afterbegin', rowHtml);
                            knownOrderIds.add(order.id);
                            showNotification(order);
                        } else {
                            // Existing order - check for any changes
                            const currentCheckedOut = existingRow.querySelector('.type-cell').textContent
                                .includes('Checked Out');
                            const currentUnseenCount = existingRow.querySelector('.new-items-badge');
                            const hasUnseenChanges = order.unseen_items_count !== (currentUnseenCount ?
                                parseInt(currentUnseenCount.textContent) : 0);

                            // Also check for item count and amount changes
                            const currentItemsText = existingRow.querySelector('.items-cell .font-medium').textContent;
                            const currentItemsCount = parseInt(currentItemsText) || 0;
                            const currentAmountText = existingRow.querySelector('.total-amount').textContent.replace('$', '').replace(',', '');
                            const currentAmount = parseFloat(currentAmountText) || 0;
                            
                            const hasItemChanges = order.total_items !== currentItemsCount;
                            const hasAmountChanges = Math.abs(order.total_amount - currentAmount) > 0.01;

                            const needsUpdate = order.is_checked_out !== currentCheckedOut ||
                                order.was_updated ||
                                hasUnseenChanges ||
                                hasItemChanges ||
                                hasAmountChanges;

                            console.log('Order check:', order.id, {
                                exists: true,
                                checked_out: {
                                    from: currentCheckedOut,
                                    to: order.is_checked_out
                                },
                                unseen: {
                                    from: currentUnseenCount?.textContent,
                                    to: order.unseen_items_count
                                },
                                items: {
                                    from: currentItemsCount,
                                    to: order.total_items,
                                    changed: hasItemChanges
                                },
                                amount: {
                                    from: currentAmount,
                                    to: order.total_amount,
                                    changed: hasAmountChanges
                                },
                                was_updated: order.was_updated,
                                needs_update: needsUpdate
                            });

                            if (needsUpdate) {
                                updateExistingRow(order);

                                // Show checkout notification specifically
                                if (order.is_checked_out && !currentCheckedOut) {
                                    showCheckoutNotification(order);
                                }
                            }
                        }
                    });

                    // Update tracking
                    lastMaxId = data.max_id;
                    lastUpdateTime = data.timestamp;
                    console.log('Updated tracking - maxId:', lastMaxId, 'timestamp:', lastUpdateTime);
                }

                // Always update timestamp for next poll
                if (data.timestamp) {
                    lastUpdateTime = data.timestamp;
                }
            } catch (error) {
                console.error('Error checking for new orders:', error);
            }
        }

        async function endService(orderId, btn) {
            if (!confirm(
                    'Are you sure you want to end service for this table? This will complete the order and free up the table.'
                )) {
                return;
            }

            try {
                const response = await fetch(`/admin/orders/${orderId}/end-service`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    // Remove the row from the table
                    const row = document.getElementById(`order-${orderId}`);
                    if (row) {
                        row.remove();
                    }
                    // Remove from known orders
                    knownOrderIds.delete(orderId);
                } else {
                    const errorText = await response.text();
                    console.error('Error ending service:', response.status, errorText);
                    alert('Failed to end service. Please try again.');
                }
            } catch (error) {
                console.error('Error ending service:', error);
                alert('Error: ' + error.message);
            }
        }

        // Check for new orders every 3 seconds (simple polling, no WebSocket needed)
        setInterval(checkForNewOrders, 3000);
        checkForNewOrders();

        async function printOrder(id, type) {
            let url = type === 'receipt' ? `/admin/orders/${id}/print-receipt` : `/admin/orders/${id}/print-kitchen`;

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

        // Listen for storage events to sync across tabs
        window.addEventListener('storage', function(e) {
            if (e.key === 'orderSeenUpdated') {
                const data = JSON.parse(e.newValue);
                console.log('Storage event - updating order:', data.orderId, 'seen items:', data.seenItems);
                updateOrderSeenStatus(data.orderId, data.seenItems);
            }
        });

        // Function to update order seen status
        function updateOrderSeenStatus(orderId, seenItemsCount) {
            const row = document.getElementById(`order-${orderId}`);
            if (row) {
                const orderNumberCell = row.querySelector('td:first-child');
                const existingBadge = orderNumberCell.querySelector('.new-items-badge');

                if (existingBadge && seenItemsCount === 0) {
                    // Remove badge if all items are seen
                    existingBadge.remove();
                    row.classList.remove('has-unseen-updates');
                } else if (existingBadge && seenItemsCount > 0) {
                    // Update badge count
                    existingBadge.textContent = seenItemsCount;
                    existingBadge.title = `${seenItemsCount} new items`;
                }
            }
        }

        // Function to end service for an order
        async function endService(orderId, button) {
            if (!confirm('Are you sure you want to end service for this order?')) {
                return;
            }

            button.disabled = true;
            button.textContent = 'Ending...';

            try {
                const response = await fetch(`/admin/orders/${orderId}/end-service`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    // Remove the order row with animation
                    const row = document.getElementById(`order-${orderId}`);
                    if (row) {
                        row.style.transition = 'opacity 0.3s, transform 0.3s';
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(-20px)';
                        
                        setTimeout(() => {
                            row.remove();
                        }, 300);
                    }
                    
                    // Show success message
                    showNotification({ message: 'Service ended successfully!', type: 'success' });
                } else {
                    button.disabled = false;
                    button.textContent = 'End Service';
                    alert(data.message || 'Failed to end service');
                }
            } catch (error) {
                console.error('End service error:', error);
                button.disabled = false;
                button.textContent = 'End Service';
                alert('An error occurred while ending service.');
            }
        }

        // Function to show notifications
        function showNotification({ message, type = 'info' }) {
            const colors = {
                success: '#10b981',
                error: '#ef4444',
                info: '#3b82f6'
            };

            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${colors[type]};
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 9999;
                animation: slideIn 0.3s ease;
            `;
            notification.textContent = message;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    </script>
@endsection
