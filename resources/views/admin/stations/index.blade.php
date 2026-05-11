@extends('layouts.admin')

@section('title', ucfirst($station) . ' Station')

@section('content')
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl text-white font-bold tracking-wider mb-1">{{ ucfirst($station) }} Station</h1>
            <p class="text-xs uppercase tracking-[0.2em] text-gray-500">Real-time order management</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 px-4 py-2 bg-green-500/10 border border-green-500/20 rounded-full">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-xs font-semibold text-green-500 uppercase tracking-wider">Live Connection</span>
            </div>
            <button onclick="fetchOrders()" class="p-2 bg-white/5 hover:bg-white/10 border border-gray-800 rounded-lg text-gray-400 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>
        </div>
    </div>

    <div id="orders-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <!-- Orders will be loaded here via JS -->
        <div class="col-span-full flex flex-col items-center justify-center py-20 bg-[#1a1a1a] border border-gray-800 rounded-2xl">
            <div class="w-16 h-16 bg-gray-800/50 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </div>
            <p class="text-gray-500 font-medium italic">Connecting to station stream...</p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const station = "{{ $station }}";
    let orders = [];

    async function fetchOrders() {
        try {
            const response = await fetch(`{{ route('admin.stations.data', $station) }}`);
            orders = await response.json();
            renderOrders();
        } catch (error) {
            console.error('Error fetching orders:', error);
        }
    }

    function renderOrders() {
        const container = document.getElementById('orders-container');
        
        if (orders.length === 0) {
            container.innerHTML = `
                <div class="col-span-full flex flex-col items-center justify-center py-20 bg-[#1a1a1a] border border-gray-800 rounded-2xl">
                    <div class="w-16 h-16 bg-gray-800/50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium italic">No active orders for this station</p>
                </div>
            `;
            return;
        }

        container.innerHTML = orders.map(order => `
            <div class="bg-[#1a1a1a] border border-gray-800 rounded-2xl overflow-hidden flex flex-col">
                <!-- Card Header -->
                <div class="p-4 bg-white/5 border-b border-gray-800 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Table</span>
                        <h3 class="text-xl text-white font-bold">#${order.table ? order.table.table_number : 'N/A'}</h3>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Order</span>
                        <p class="text-xs text-amber-500 font-mono">#${order.id}</p>
                    </div>
                </div>

                <!-- Card Items -->
                <div class="p-4 flex-1 space-y-4">
                    ${order.order_items.map(item => `
                        <div class="flex flex-col gap-1 p-3 rounded-xl ${item.admin_seen_at ? 'bg-black/20 opacity-60' : 'bg-amber-500/5 border border-amber-500/10'}">
                            <div class="flex items-start justify-between">
                                <div class="flex gap-3">
                                    <span class="text-amber-500 font-bold text-lg">${item.quantity}x</span>
                                    <div>
                                        <p class="text-white font-semibold leading-tight">${item.item ? item.item.name : 'Unknown Item'}</p>
                                        ${item.special_instructions ? `<p class="text-xs text-amber-400 mt-1 italic font-medium">"${item.special_instructions}"</p>` : ''}
                                    </div>
                                </div>
                                ${!item.admin_seen_at ? `
                                    <button onclick="markItemSeen(${order.id}, ${item.id})" class="p-1.5 bg-amber-500 text-[#0a0a0a] rounded-lg hover:bg-amber-600 transition-colors" title="Mark as Ready">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                ` : `
                                    <span class="text-green-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </span>
                                `}
                            </div>
                            <p class="text-[10px] text-gray-600 mt-1">${new Date(item.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                        </div>
                    `).join('')}
                </div>

                <!-- Card Footer -->
                <div class="p-4 bg-black/40 border-t border-gray-800 flex gap-2">
                    <button onclick="printStationTicket(${order.id})" class="flex-1 py-2.5 bg-gray-800 hover:bg-gray-700 text-white text-xs font-bold rounded-xl transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        PRINT TICKET
                    </button>
                </div>
            </div>
        `).join('');
    }

    async function markItemSeen(orderId, itemId) {
        try {
            const response = await fetch(`{{ url('/admin/stations') }}/${orderId}/items/${itemId}/mark-seen`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            if (response.ok) {
                fetchOrders(); // Refresh
            }
        } catch (error) {
            console.error('Error marking item seen:', error);
        }
    }

    async function printStationTicket(orderId) {
        try {
            const response = await fetch(`{{ url('/admin/orders') }}/${orderId}/print-station/${station}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            const data = await response.json();
            if (data.success) {
                alert('Ticket sent to printer');
            } else {
                alert('Printing failed: ' + data.message);
            }
        } catch (error) {
            console.error('Error printing ticket:', error);
            alert('Printing error occurred');
        }
    }

    // Initial load
    fetchOrders();

    // Poll every 10 seconds for real-time feel (if WebSocket not used)
    setInterval(fetchOrders, 10000);

    // Listen for WebSocket events if available
    if (typeof window.Echo !== 'undefined') {
        window.Echo.channel('admin.orders')
            .listen('.order.placed', (e) => {
                console.log('New order received via WebSocket');
                fetchOrders();
            });
    }
</script>
@endpush
