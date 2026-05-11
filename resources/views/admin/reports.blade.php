@extends('layouts.admin')

@section('title', 'Reports Dashboard')

@section('content')
<div class="space-y-8" x-data="{ activeTab: 'overview' }">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h1 class="text-4xl md:text-5xl font-serif italic text-white mb-2 tracking-wide">Reports Dashboard</h1>
            <p class="text-gray-400 text-sm tracking-wide">Comprehensive insights into your restaurant performance</p>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex flex-col gap-1">
                <label class="text-[10px] uppercase tracking-[0.2em] text-gray-500">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="bg-[#0f0f0f] border border-gray-700 text-white text-sm rounded-lg px-4 py-2 focus:outline-none focus:border-amber-500">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-[10px] uppercase tracking-[0.2em] text-gray-500">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="bg-[#0f0f0f] border border-gray-700 text-white text-sm rounded-lg px-4 py-2 focus:outline-none focus:border-amber-500">
            </div>
            <button type="submit"
                class="px-6 py-2 rounded-lg bg-amber-500 text-black text-xs font-bold hover:bg-amber-400 uppercase tracking-wider transition-colors">
                Filter
            </button>
            <a href="{{ route('admin.reports.index') }}"
                class="px-6 py-2 rounded-lg border border-gray-700 text-gray-300 text-xs font-medium hover:bg-white/5 uppercase tracking-wider transition-colors">
                Reset
            </a>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6">
            <span class="text-[10px] uppercase tracking-[0.2em] text-gray-500">Total Revenue</span>
            <div class="text-3xl font-serif text-white mt-2">${{ number_format($totalRevenue, 2) }}</div>
        </div>
        <div class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6">
            <span class="text-[10px] uppercase tracking-[0.2em] text-gray-500">Total Orders</span>
            <div class="text-3xl font-serif text-white mt-2">{{ $totalOrders }}</div>
        </div>
        <div class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6">
            <span class="text-[10px] uppercase tracking-[0.2em] text-gray-500">Avg Order Value</span>
            <div class="text-3xl font-serif text-white mt-2">${{ number_format($avgOrderValue, 2) }}</div>
        </div>
        <div class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6">
            <span class="text-[10px] uppercase tracking-[0.2em] text-gray-500">Items Sold</span>
            <div class="text-3xl font-serif text-white mt-2">{{ $totalItemsSold }}</div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex flex-wrap gap-2 border-b border-gray-800 pb-2">
        <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'text-amber-500 border-b-2 border-amber-500' : 'text-gray-500 hover:text-gray-300'"
            class="px-4 py-2 text-sm font-medium transition-colors">Overview</button>
        <button @click="activeTab = 'peak_hours'" :class="activeTab === 'peak_hours' ? 'text-amber-500 border-b-2 border-amber-500' : 'text-gray-500 hover:text-gray-300'"
            class="px-4 py-2 text-sm font-medium transition-colors">Peak Hours</button>
        <button @click="activeTab = 'top_items'" :class="activeTab === 'top_items' ? 'text-amber-500 border-b-2 border-amber-500' : 'text-gray-500 hover:text-gray-300'"
            class="px-4 py-2 text-sm font-medium transition-colors">Top Items</button>
        <button @click="activeTab = 'customers'" :class="activeTab === 'customers' ? 'text-amber-500 border-b-2 border-amber-500' : 'text-gray-500 hover:text-gray-300'"
            class="px-4 py-2 text-sm font-medium transition-colors">Customers</button>
        <button @click="activeTab = 'categories'" :class="activeTab === 'categories' ? 'text-amber-500 border-b-2 border-amber-500' : 'text-gray-500 hover:text-gray-300'"
            class="px-4 py-2 text-sm font-medium transition-colors">Categories</button>
    </div>

    <!-- Tab: Overview (Sales Charts) -->
    <div x-show="activeTab === 'overview'" x-cloak class="space-y-6">
        <!-- Daily Revenue Chart -->
        <div class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-serif text-white italic">Daily Revenue</h2>
                <div class="flex gap-2">
                    <a href="{{ route('admin.reports.export.csv', ['type' => 'sales', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                        class="px-3 py-1.5 rounded border border-gray-700 text-gray-300 text-[10px] uppercase tracking-wider hover:bg-white/5 transition-colors">
                        Export CSV
                    </a>
                    <a href="{{ route('admin.reports.export.pdf', ['type' => 'sales', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                        class="px-3 py-1.5 rounded border border-gray-700 text-gray-300 text-[10px] uppercase tracking-wider hover:bg-white/5 transition-colors">
                        Export PDF
                    </a>
                </div>
            </div>
            <div class="h-72">
                <canvas id="dailyRevenueChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Weekly Revenue Chart -->
            <div class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6">
                <h2 class="text-xl font-serif text-white italic mb-6">Weekly Revenue</h2>
                <div class="h-64">
                    <canvas id="weeklyRevenueChart"></canvas>
                </div>
            </div>

            <!-- Monthly Revenue Chart -->
            <div class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6">
                <h2 class="text-xl font-serif text-white italic mb-6">Monthly Revenue</h2>
                <div class="h-64">
                    <canvas id="monthlyRevenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab: Peak Hours -->
    <div x-show="activeTab === 'peak_hours'" x-cloak class="space-y-6">
        <div class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-serif text-white italic">Orders by Hour</h2>
                <div class="flex gap-2">
                    <a href="{{ route('admin.reports.export.csv', ['type' => 'peak_hours', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                        class="px-3 py-1.5 rounded border border-gray-700 text-gray-300 text-[10px] uppercase tracking-wider hover:bg-white/5 transition-colors">
                        Export CSV
                    </a>
                    <a href="{{ route('admin.reports.export.pdf', ['type' => 'peak_hours', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                        class="px-3 py-1.5 rounded border border-gray-700 text-gray-300 text-[10px] uppercase tracking-wider hover:bg-white/5 transition-colors">
                        Export PDF
                    </a>
                </div>
            </div>
            <div class="h-72">
                <canvas id="peakHoursChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tab: Top Items -->
    <div x-show="activeTab === 'top_items'" x-cloak class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Items by Quantity -->
            <div class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-serif text-white italic">Top 10 by Quantity</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.reports.export.csv', ['type' => 'top_items_quantity', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                            class="px-3 py-1.5 rounded border border-gray-700 text-gray-300 text-[10px] uppercase tracking-wider hover:bg-white/5 transition-colors">
                            Export CSV
                        </a>
                        <a href="{{ route('admin.reports.export.pdf', ['type' => 'top_items_quantity', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                            class="px-3 py-1.5 rounded border border-gray-700 text-gray-300 text-[10px] uppercase tracking-wider hover:bg-white/5 transition-colors">
                            Export PDF
                        </a>
                    </div>
                </div>
                <div class="space-y-3">
                    @forelse($topItemsByQuantity as $index => $item)
                    <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded bg-amber-500 text-black text-xs font-bold flex items-center justify-center">{{ $index + 1 }}</span>
                            <span class="text-white text-sm">{{ $item->name }}</span>
                        </div>
                        <div class="text-right">
                            <div class="text-white text-sm font-medium">{{ $item->total_quantity }} sold</div>
                            <div class="text-gray-500 text-xs">${{ number_format($item->total_revenue, 2) }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-gray-500 text-center py-8 italic">No data available for selected range</div>
                    @endforelse
                </div>
            </div>

            <!-- Top Items by Revenue -->
            <div class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-serif text-white italic">Top 10 by Revenue</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.reports.export.csv', ['type' => 'top_items_revenue', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                            class="px-3 py-1.5 rounded border border-gray-700 text-gray-300 text-[10px] uppercase tracking-wider hover:bg-white/5 transition-colors">
                            Export CSV
                        </a>
                        <a href="{{ route('admin.reports.export.pdf', ['type' => 'top_items_revenue', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                            class="px-3 py-1.5 rounded border border-gray-700 text-gray-300 text-[10px] uppercase tracking-wider hover:bg-white/5 transition-colors">
                            Export PDF
                        </a>
                    </div>
                </div>
                <div class="space-y-3">
                    @forelse($topItemsByRevenue as $index => $item)
                    <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded bg-amber-500 text-black text-xs font-bold flex items-center justify-center">{{ $index + 1 }}</span>
                            <span class="text-white text-sm">{{ $item->name }}</span>
                        </div>
                        <div class="text-right">
                            <div class="text-white text-sm font-medium">${{ number_format($item->total_revenue, 2) }}</div>
                            <div class="text-gray-500 text-xs">{{ $item->total_quantity }} sold</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-gray-500 text-center py-8 italic">No data available for selected range</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Tab: Customers -->
    <div x-show="activeTab === 'customers'" x-cloak class="space-y-6">
        <div class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-serif text-white italic">Most Frequent Customers</h2>
                <div class="flex gap-2">
                    <a href="{{ route('admin.reports.export.csv', ['type' => 'customers', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                        class="px-3 py-1.5 rounded border border-gray-700 text-gray-300 text-[10px] uppercase tracking-wider hover:bg-white/5 transition-colors">
                        Export CSV
                    </a>
                    <a href="{{ route('admin.reports.export.pdf', ['type' => 'customers', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                        class="px-3 py-1.5 rounded border border-gray-700 text-gray-300 text-[10px] uppercase tracking-wider hover:bg-white/5 transition-colors">
                        Export PDF
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-500 text-[10px] uppercase tracking-[0.2em]">
                            <th class="pb-3">Rank</th>
                            <th class="pb-3">Customer</th>
                            <th class="pb-3">Phone</th>
                            <th class="pb-3 text-right">Orders</th>
                            <th class="pb-3 text-right">Total Spent</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($loyalCustomers as $index => $customer)
                        <tr class="border-b border-gray-800/50 hover:bg-white/5 transition-colors">
                            <td class="py-3">
                                <span class="w-6 h-6 rounded bg-amber-500 text-black text-xs font-bold inline-flex items-center justify-center">{{ $index + 1 }}</span>
                            </td>
                            <td class="py-3 text-white">{{ $customer->customer_name ?? 'N/A' }}</td>
                            <td class="py-3 text-gray-400">{{ $customer->customer_phone }}</td>
                            <td class="py-3 text-right text-white font-medium">{{ $customer->order_count }}</td>
                            <td class="py-3 text-right text-amber-500">${{ number_format($customer->total_spent, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500 italic">No customer data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab: Categories -->
    <div x-show="activeTab === 'categories'" x-cloak class="space-y-6">
        <div class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-serif text-white italic">Category Performance</h2>
                <div class="flex gap-2">
                    <a href="{{ route('admin.reports.export.csv', ['type' => 'categories', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                        class="px-3 py-1.5 rounded border border-gray-700 text-gray-300 text-[10px] uppercase tracking-wider hover:bg-white/5 transition-colors">
                        Export CSV
                    </a>
                    <a href="{{ route('admin.reports.export.pdf', ['type' => 'categories', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                        class="px-3 py-1.5 rounded border border-gray-700 text-gray-300 text-[10px] uppercase tracking-wider hover:bg-white/5 transition-colors">
                        Export PDF
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="h-64">
                    <canvas id="categoryRevenueChart"></canvas>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-800 text-gray-500 text-[10px] uppercase tracking-[0.2em]">
                                <th class="pb-3">Category</th>
                                <th class="pb-3">Station</th>
                                <th class="pb-3 text-right">Qty Sold</th>
                                <th class="pb-3 text-right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($categoryPerformance as $category)
                            <tr class="border-b border-gray-800/50 hover:bg-white/5 transition-colors">
                                <td class="py-3 text-white font-medium">{{ $category->name }}</td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] uppercase tracking-wider
                                        {{ $category->station === 'kitchen' ? 'bg-orange-500/20 text-orange-400' : ($category->station === 'bar' ? 'bg-blue-500/20 text-blue-400' : ($category->station === 'shisha' ? 'bg-purple-500/20 text-purple-400' : 'bg-gray-500/20 text-gray-400')) }}">
                                        {{ $category->station ?? 'general' }}
                                    </span>
                                </td>
                                <td class="py-3 text-right text-gray-300">{{ $category->total_quantity }}</td>
                                <td class="py-3 text-right text-amber-500 font-medium">${{ number_format($category->total_revenue, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-500 italic">No category data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const chartColors = {
        amber: 'rgba(245, 158, 11, 0.8)',
        amberLight: 'rgba(245, 158, 11, 0.2)',
        gray: 'rgba(107, 114, 128, 0.5)',
        white: 'rgba(255, 255, 255, 0.8)',
    };

    Chart.defaults.color = '#9ca3af';
    Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';

    // Daily Revenue
    const dailyCtx = document.getElementById('dailyRevenueChart');
    if (dailyCtx) {
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyRevenue->pluck('date')) !!},
                datasets: [{
                    label: 'Revenue ($)',
                    data: {!! json_encode($dailyRevenue->pluck('revenue')) !!},
                    borderColor: chartColors.amber,
                    backgroundColor: chartColors.amberLight,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => '$' + v } }
                }
            }
        });
    }

    // Weekly Revenue
    const weeklyCtx = document.getElementById('weeklyRevenueChart');
    if (weeklyCtx) {
        new Chart(weeklyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($weeklyRevenue->pluck('week_label')) !!},
                datasets: [{
                    label: 'Revenue ($)',
                    data: {!! json_encode($weeklyRevenue->pluck('revenue')) !!},
                    backgroundColor: chartColors.amber,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => '$' + v } }
                }
            }
        });
    }

    // Monthly Revenue
    const monthlyCtx = document.getElementById('monthlyRevenueChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyRevenue->pluck('month_label')) !!},
                datasets: [{
                    label: 'Revenue ($)',
                    data: {!! json_encode($monthlyRevenue->pluck('revenue')) !!},
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => '$' + v } }
                }
            }
        });
    }

    // Peak Hours
    const peakCtx = document.getElementById('peakHoursChart');
    if (peakCtx) {
        new Chart(peakCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($peakHoursData->pluck('label')) !!},
                datasets: [{
                    label: 'Orders',
                    data: {!! json_encode($peakHoursData->pluck('orders_count')) !!},
                    backgroundColor: (ctx) => {
                        const val = ctx.raw;
                        const max = Math.max(...{!! json_encode($peakHoursData->pluck('orders_count')) !!});
                        const alpha = max > 0 ? 0.3 + (val / max) * 0.7 : 0.3;
                        return `rgba(245, 158, 11, ${alpha})`;
                    },
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Category Revenue
    const catCtx = document.getElementById('categoryRevenueChart');
    if (catCtx) {
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryPerformance->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($categoryPerformance->pluck('total_revenue')) !!},
                    backgroundColor: [
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                        'rgba(99, 102, 241, 0.8)',
                    ],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { padding: 20 } }
                }
            }
        });
    }
</script>
@endpush
