@extends('layouts.admin')

@section('title', 'Orders')
@section('header', 'Orders')

@section('content')
    <!-- New Order Notification Banner -->
    <div id="new-order-alert"
        style="display: none; background: #28a745; color: white; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; text-align: center; animation: slideDown 0.3s ease;">
        <span style="font-size: 20px; margin-right: 10px;">🔔</span>
        <strong>New Order Received!</strong>
        <span id="new-order-details"></span>
    </div>

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
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
            <tbody class="bg-white divide-y divide-gray-200" id="orders-tbody">
                @forelse($orders as $order)
                    <tr id="order-{{ $order->id }}"
                        class="{{ $order->is_checked_out ? 'bg-green-50' : '' }} {{ $order->hasUnseenUpdates() ? 'has-unseen-updates' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $order->id }}
                            @if (!$order->is_checked_out)
                                <span
                                    class="ml-2 px-2 py-0.5 text-xs bg-yellow-100 text-yellow-800 rounded-full">Active</span>
                            @endif
                            @if ($order->hasUnseenUpdates())
                                <span class="new-items-badge"
                                    title="{{ $order->unseenItemsCount() }} new items">{{ $order->unseenItemsCount() }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 table-number"
                            data-table="{{ $order->table->table_number ?? 'N/A' }}">
                            Table {{ $order->table->table_number ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 items-cell">
                            <div class="font-medium">{{ $order->orderItems->sum('quantity') }} items</div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $order->orderItems->map(fn($oi) => $oi->item->name)->implode(', ') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 total-amount">
                            ${{ number_format($order->total_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap status-cell">
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
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 type-cell">
                            @if ($order->is_checked_out)
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    ✓ Checked Out
                                </span>
                            @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Open (Adding Items)
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 time-cell">
                            {{ $order->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <a href="{{ route('admin.orders.show', $order) }}"
                                class="text-blue-600 hover:text-blue-900">View</a>
                            <button onclick="printOrder({{ $order->id }}, 'receipt')"
                                class="ml-2 text-gray-600 hover:text-gray-900" title="Print Receipt">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                    </path>
                                </svg>
                            </button>
                            <button onclick="printOrder({{ $order->id }}, 'kitchen')"
                                class="ml-2 text-orange-600 hover:text-orange-900" title="Print Kitchen">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                            @if ($order->is_checked_out)
                                <button onclick="endService({{ $order->id }}, this)"
                                    class="ml-2 text-red-600 hover:text-red-900 font-semibold">End Service</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr id="no-orders">
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No orders found</td>
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
            background-color: #fff3cd !important;
            border-left: 4px solid #dc3545;
        }

        .has-unseen-updates td {
            color: #721c24;
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
    </style>

    <script>
        let lastMaxId = {{ $orders->max('id') ?? 0 }};
        let lastUpdateTime = '{{ now()->subMinutes(5)->toISOString() }}';
        let knownOrderIds = new Set([{{ $orders->pluck('id')->implode(',') }}]);

        const statusColors = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'preparing': 'bg-orange-100 text-orange-800',
            'ready': 'bg-blue-100 text-blue-800',
            'served': 'bg-purple-100 text-purple-800',
            'completed': 'bg-green-100 text-green-800',
            'cancelled': 'bg-red-100 text-red-800',
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
                '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">✓ Checked Out</span>' :
                '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Open (Adding Items)</span>';

            const activeBadge = !isCheckedOut ?
                '<span class="ml-2 px-2 py-0.5 text-xs bg-yellow-100 text-yellow-800 rounded-full">Active</span>' :
                '';

            const unseenBadge = (order.unseen_items_count > 0) ?
                `<span class="new-items-badge" title="${order.unseen_items_count} new items">${order.unseen_items_count}</span>` :
                '';

            let rowClass = order.is_new ? 'new-order-row' : (isCheckedOut ? 'bg-green-50' : '');
            if (order.unseen_items_count > 0) {
                rowClass += ' has-unseen-updates';
            }

            return `
                <tr id="order-${order.id}" class="${rowClass}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        #${order.id}${activeBadge}${unseenBadge}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 table-number" data-table="${order.table_number}">
                        Table ${order.table_number}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 items-cell">
                        <div class="font-medium">${order.total_items} items</div>
                        ${order.items_list ? `<div class="text-xs text-gray-500 mt-1">${order.items_list}</div>` : ''}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 total-amount">
                        $${parseFloat(order.total_amount).toFixed(2)}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap status-cell">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusColors[order.status] || 'bg-gray-100 text-gray-800'}">
                            ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 type-cell">
                        ${typeBadge}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 time-cell">
                        ${order.created_at_human}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <a href="/admin/orders/${order.id}" class="text-blue-600 hover:text-blue-900">View</a>
                        <button onclick="printOrder(${order.id}, 'receipt')" class="ml-2 text-gray-600 hover:text-gray-900" title="Print Receipt">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        </button>
                        <button onclick="printOrder(${order.id}, 'kitchen')" class="ml-2 text-orange-600 hover:text-orange-900" title="Print Kitchen">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </button>
                        ${order.is_checked_out ? `<button onclick="endService(${order.id}, this)" class="ml-2 text-red-600 hover:text-red-900 font-semibold">End Service</button>` : ''}
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
                <div class="font-medium">${order.total_items} items</div>
                ${order.items_list ? `<div class="text-xs text-gray-500 mt-1">${order.items_list}</div>` : ''}
            `;

            // Update total
            row.querySelector('.total-amount').textContent = `$${parseFloat(order.total_amount).toFixed(2)}`;
            row.querySelector('.time-cell').textContent = order.created_at_human;

            // Update status
            const statusCell = row.querySelector('.status-cell span');
            statusCell.className =
                `px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusColors[order.status] || 'bg-gray-100 text-gray-800'}`;
            statusCell.textContent = order.status.charAt(0).toUpperCase() + order.status.slice(1);

            // Update type (checked out status) - this is the key part
            const typeCell = row.querySelector('.type-cell');
            const actionCell = row.querySelector('td:last-child');
            const orderNumberCell = row.querySelector('td:first-child');

            if (order.is_checked_out) {
                // Update to checked out state
                typeCell.innerHTML =
                    '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">✓ Checked Out</span>';
                row.classList.add('bg-green-50');

                // Remove Active badge if it exists
                const activeBadge = orderNumberCell.querySelector('.bg-yellow-100');
                if (activeBadge) {
                    activeBadge.remove();
                }

                // Add End Service button if not already present
                if (!actionCell.querySelector('button')) {
                    actionCell.insertAdjacentHTML('beforeend',
                        `<button onclick="endService(${order.id}, this)" class="ml-2 text-red-600 hover:text-red-900 font-semibold">End Service</button>`
                    );
                }

                // Show notification for checkout
                if (order.was_updated && !order.is_new) {
                    showCheckoutNotification(order);
                }
            } else {
                // Update to open state
                typeCell.innerHTML =
                    '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Open (Adding Items)</span>';
                row.classList.remove('bg-green-50');

                // Add Active badge if not present
                if (!orderNumberCell.querySelector('.bg-yellow-100')) {
                    orderNumberCell.insertAdjacentHTML('beforeend',
                        '<span class="ml-2 px-2 py-0.5 text-xs bg-yellow-100 text-yellow-800 rounded-full">Active</span>'
                    );
                }

                // Remove End Service button if present
                const endServiceBtn = actionCell.querySelector('button');
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

                            const needsUpdate = order.is_checked_out !== currentCheckedOut ||
                                order.was_updated ||
                                hasUnseenChanges;

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
    </script>
@endsection
