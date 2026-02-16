@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-4xl md:text-5xl font-serif italic text-white mb-2 tracking-wide">Good Evening,
                    {{ auth()->user()->name ?? 'Admin' }}</h1>
                <div class="flex items-center text-gray-400 text-sm tracking-wide">
                    <span class="mr-4 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        {{ now()->format('F d, Y') }}
                    </span>
                    <span class="border-l border-gray-700 pl-4 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                        Service is active
                    </span>
                </div>
            </div>
            <div class="flex space-x-4">
                <button
                    class="px-6 py-2 rounded-full border border-gray-700 text-gray-300 text-xs font-medium hover:bg-white/5 uppercase tracking-wider transition-colors">
                    Report
                </button>
                <a href="{{ route('admin.orders.index') }}"
                    class="px-6 py-2 rounded-full bg-amber-400 text-black text-xs font-bold hover:bg-amber-300 uppercase tracking-wider transition-colors shadow-[0_0_15px_rgba(251,191,36,0.3)]">
                    Open Service
                </a>
            </div>
        </div>

        <!-- Top Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card 1: Active Orders (Styled as Satisfaction Index) -->
            <div
                class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="flex justify-between items-start mb-6">
                    <span class="text-[10px] uppercase tracking-[0.2em] text-gray-500">Active Orders</span>
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="flex items-baseline gap-2">
                        <span class="text-5xl font-serif text-white">{{ $stats['active_orders'] }}</span>
                        <span class="text-sm text-gray-600">/ 50</span>
                    </div>
                    <p class="text-amber-500 text-xs font-medium mt-2">+{{ rand(1, 10) }}% from prev. hour</p>
                </div>
                <!-- Background decoration -->
                <div
                    class="absolute right-0 bottom-0 opacity-5 pointer-events-none transform translate-x-1/4 translate-y-1/4">
                    <svg class="w-40 h-40 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </div>
            </div>

            <!-- Card 2: Upcoming VIPs (Upcoming Reservations) -->
            <div
                class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6 relative group hover:border-white/10 transition-colors">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-[10px] uppercase tracking-[0.2em] text-gray-500">Upcoming Reservations</span>
                    <div class="flex -space-x-2">
                        @foreach ($upcomingReservations->take(2) as $res)
                            <div
                                class="w-6 h-6 rounded-full bg-gray-700 flex items-center justify-center text-[10px] text-white border border-[#1a1a1a]">
                                {{ substr($res->customer_name, 0, 1) }}</div>
                        @endforeach
                        @if ($upcomingReservations->count() > 2)
                            <div
                                class="w-6 h-6 rounded-full bg-gray-800 flex items-center justify-center text-[8px] text-gray-400 border border-[#1a1a1a]">
                                +{{ $upcomingReservations->count() - 2 }}</div>
                        @endif
                    </div>
                </div>

                <div class="space-y-3">
                    @forelse($upcomingReservations->take(2) as $index => $reservation)
                        <div
                            class="bg-white/5 rounded-lg p-3 flex items-center gap-4 hover:bg-white/10 transition-colors cursor-pointer">
                            <div
                                class="w-8 h-8 rounded bg-gradient-to-br {{ $index == 0 ? 'from-amber-600 to-amber-800' : 'from-gray-600 to-gray-800' }} flex items-center justify-center text-white font-serif italic text-lg">
                                {{ substr($reservation->customer_name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="text-white text-sm font-medium">{{ $reservation->customer_name }}</h4>
                                <p class="text-[10px] text-gray-500 tracking-wider">TABLE
                                    {{ $reservation->table->table_number ?? 'N/A' }} •
                                    {{ $reservation->start_time->format('H:i') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-gray-500 text-sm text-center py-4 italic">No upcoming reservations</div>
                    @endforelse
                </div>
            </div>

            <!-- Card 3: Revenue (Service Goal) -->
            <div
                class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6 relative group hover:border-white/10 transition-colors">
                <div class="flex justify-between items-start mb-6">
                    <span class="text-[10px] uppercase tracking-[0.2em] text-gray-500">Service Goal</span>
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <div class="mb-4">
                        <span
                            class="text-4xl font-serif text-white">${{ number_format($stats['total_revenue'] / 1000, 1) }}k</span>
                    </div>
                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-800 h-1 rounded-full mb-2 overflow-hidden">
                        <div class="bg-amber-500 h-1 rounded-full"
                            style="width: {{ min(($stats['total_revenue'] / 5000) * 100, 100) }}%"></div>
                    </div>
                    <div class="flex justify-between text-[10px] tracking-wider uppercase">
                        <span class="text-gray-500">Progress</span>
                        <span class="text-amber-500">{{ number_format(($stats['total_revenue'] / 5000) * 100, 0) }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Section -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Active Course Gallery (Recent Orders) -->
            <div class="lg:col-span-3">
                <div class="flex justify-between items-end mb-4">
                    <h2 class="text-2xl font-serif text-white italic">Active Course Gallery</h2>
                    <div class="flex gap-4 text-[10px] uppercase tracking-[0.2em] font-medium">
                        <span class="text-amber-500 border-b border-amber-500 pb-1 cursor-pointer">Recent Orders</span>
                        <span class="text-gray-600 hover:text-gray-400 cursor-pointer transition-colors">Completed</span>
                        <span class="text-gray-600 hover:text-gray-400 cursor-pointer transition-colors">Pending</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @forelse($recentOrders->take(3) as $order)
                        <div
                            class="bg-[#1a1a1a] rounded-xl border border-white/5 overflow-hidden group hover:border-white/10 transition-colors h-64 relative">
                            <!-- Background Image Overlay (Mockup using gradients since we might not have images) -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent z-10">
                            </div>
                            <div
                                class="absolute inset-0 bg-gray-800 z-0 opacity-30 group-hover:opacity-40 transition-opacity">
                                @if ($order->orderItems->first() && $order->orderItems->first()->item && $order->orderItems->first()->item->image)
                                    <img src="{{ asset('storage/' . $order->orderItems->first()->item->image) }}"
                                        class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-gray-800 to-black"></div>
                                @endif
                            </div>

                            <div class="absolute inset-0 z-20 p-5 flex flex-col justify-end">
                                <span
                                    class="bg-amber-500 text-black text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider w-max mb-2">
                                    Table {{ $order->table->table_number ?? 'N/A' }}
                                </span>
                                <h3 class="font-serif italic text-white text-xl mb-1 leading-tight">
                                    Order #{{ $order->id }}
                                </h3>
                                <p class="text-gray-400 text-xs tracking-wide">
                                    {{ $order->orderItems->first()->item->name ?? 'Unknown Item' }}
                                    @if ($order->orderItems->count() > 1)
                                        <span class="text-gray-600">+ {{ $order->orderItems->count() - 1 }} more</span>
                                    @endif
                                </p>
                                <div class="mt-3 flex justify-between items-center pt-3 border-t border-white/10">
                                    <span
                                        class="text-[10px] uppercase tracking-[0.2em] text-gray-500">{{ $order->status }}</span>
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-3 bg-[#1a1a1a] rounded-xl border border-white/5 p-8 text-center text-gray-500 italic">
                            No active orders in the gallery.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Manual Reserve / Actions -->
            <div class="lg:col-span-1">
                <div
                    class="h-full rounded-2xl border-2 border-dashed border-white/10 flex flex-col items-center justify-center p-6 group hover:border-amber-500/50 hover:bg-white/5 transition-all cursor-pointer mt-10 md:mt-0">
                    <a href="{{ route('admin.reservations.create') }}" class="flex flex-col items-center text-center">
                        <div
                            class="w-12 h-12 rounded-full border border-amber-500 flex items-center justify-center text-amber-500 mb-4 group-hover:bg-amber-500 group-hover:text-black transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-serif italic text-white text-lg mb-1">Add Manual<br>Reserve</h3>
                        <p
                            class="text-[10px] uppercase tracking-[0.2em] text-gray-500 group-hover:text-gray-400 transition-colors">
                            For Walk-in Patrons</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Terrace View (Tables) -->
            <div class="lg:col-span-2">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-serif text-white italic">Terrace View</h2>
                    <div class="flex items-center gap-4 text-[10px] uppercase tracking-wider">
                        <span class="flex items-center text-amber-500"><span
                                class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span>Occupied</span>
                        <span class="flex items-center text-gray-500"><span
                                class="w-2 h-2 rounded-full bg-gray-700 mr-2"></span>Available</span>
                    </div>
                </div>

                <div class="bg-[#1a1a1a] rounded-xl border border-white/5 p-8 min-h-[300px] relative">
                    <!-- Grid of Tables -->
                    <div class="flex flex-wrap gap-6 justify-center items-center h-full">
                        @foreach ($tables as $table)
                            <div
                                class="relative group cursor-pointer w-16 h-16 rounded-full border flex items-center justify-center transition-all duration-300 {{ $table->status === 'occupied' ? 'border-amber-500/50 bg-amber-500/10 text-amber-500' : 'border-gray-700 bg-transparent text-gray-600 hover:border-gray-500 hover:text-gray-400' }}">
                                <span
                                    class="font-serif italic {{ $table->status === 'occupied' ? 'text-amber-500' : 'text-gray-600' }}">T{{ $table->table_number }}</span>

                                {{-- Optional capacity indicator --}}
                                <span
                                    class="absolute -top-1 -right-1 text-[9px] w-4 h-4 rounded-full bg-[#111] border border-gray-800 flex items-center justify-center text-gray-500">{{ $table->capacity }}</span>

                                {{-- Tooltip --}}
                                <div
                                    class="absolute opacity-0 group-hover:opacity-100 bottom-full mb-2 bg-black border border-gray-800 text-white text-xs py-1 px-2 rounded whitespace-nowrap z-20 pointer-events-none transition-opacity">
                                    Table {{ $table->table_number }} - {{ ucfirst($table->status) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="absolute bottom-4 right-6 text-[10px] text-gray-600 italic font-serif">
                        Location: Paris, FR
                    </div>
                </div>
            </div>

            <!-- Critic Sentiment / Other Stats -->
            <div class="lg:col-span-1">
                <div class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6 h-full flex flex-col justify-between">
                    <div>
                        <h2 class="text-xl font-serif text-white italic mb-6">Critic Sentiment</h2>

                        <div class="space-y-6">
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <h4 class="text-white text-sm font-medium">Categories</h4>
                                    <div class="flex text-amber-500 text-xs">
                                        ★★★★★
                                    </div>
                                </div>
                                <p class="text-gray-500 text-xs italic leading-relaxed">
                                    "{{ $stats['categories'] }} categories currently active on the menu. A diverse
                                    selection."
                                </p>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <h4 class="text-white text-sm font-medium">Menu Items</h4>
                                    <div class="flex text-amber-500 text-xs">
                                        ★★★★☆
                                    </div>
                                </div>
                                <p class="text-gray-500 text-xs italic leading-relaxed">
                                    "{{ $stats['items'] }} exquisite dishes prepared for service tonight. The truffle
                                    selection is particularly notable."
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('admin.items.index') }}"
                            class="block w-full text-center py-3 border border-gray-700 rounded text-[10px] uppercase tracking-[0.2em] text-gray-400 hover:text-white hover:border-gray-500 transition-colors">
                            View Full Press Kit
                        </a>
                        <div class="flex justify-end mt-4">
                            <button
                                class="w-10 h-10 rounded-full bg-amber-400 flex items-center justify-center text-black hover:bg-amber-300 transition-colors shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
