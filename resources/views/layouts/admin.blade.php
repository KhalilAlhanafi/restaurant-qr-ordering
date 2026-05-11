<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'L\'ELITE Administration')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,600&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Montserrat', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .font-serif {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
        }

        /* CustomScrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        ::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #444;
        }

        /* Waiter Calls Sidebar */
        .waiter-sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .waiter-sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .waiter-sidebar {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: 380px;
            max-width: 100vw;
            background: #111;
            border-left: 1px solid rgba(255, 255, 255, 0.08);
            z-index: 999;
            transform: translateX(100%);
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .waiter-sidebar.active {
            transform: translateX(0);
        }

        .waiter-sidebar-header {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .waiter-sidebar-header h2 {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .waiter-sidebar-header h2 .ws-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 22px;
            height: 22px;
            padding: 0 6px;
            background: #ef4444;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            border-radius: 50px;
        }

        .waiter-sidebar-close {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: transparent;
            color: #999;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .waiter-sidebar-close:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .waiter-sidebar-list {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
        }

        .waiter-call-card {
            background: #1a1a1a;
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 12px;
            animation: wc-slide-in 0.4s ease both;
            transition: all 0.3s ease;
        }

        .waiter-call-card:hover {
            border-color: rgba(239, 68, 68, 0.3);
        }

        .waiter-call-card.removing {
            opacity: 0;
            transform: translateX(30px) scale(0.95);
            margin-bottom: 0;
            padding-top: 0;
            padding-bottom: 0;
            max-height: 0;
            overflow: hidden;
        }

        @keyframes wc-slide-in {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .wc-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .wc-table-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .wc-table-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(239, 68, 68, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .wc-table-icon svg {
            width: 20px;
            height: 20px;
            color: #ef4444;
        }

        .wc-table-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 2px;
        }

        .wc-table-number {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
        }

        .wc-time {
            font-size: 11px;
            color: #666;
            white-space: nowrap;
        }

        .wc-resolve-btn {
            width: 100%;
            padding: 10px;
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.25);
            border-radius: 10px;
            color: #22c55e;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .wc-resolve-btn:hover {
            background: rgba(34, 197, 94, 0.2);
            border-color: rgba(34, 197, 94, 0.5);
            color: #4ade80;
        }

        .waiter-sidebar-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            text-align: center;
        }

        .waiter-sidebar-empty svg {
            width: 48px;
            height: 48px;
            color: #333;
            margin-bottom: 16px;
        }

        .waiter-sidebar-empty p {
            color: #555;
            font-size: 13px;
        }

        /* Header bell button */
        .waiter-bell-btn {
            position: relative;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: #999;
        }

        .waiter-bell-btn:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .waiter-bell-btn svg {
            width: 18px;
            height: 18px;
        }

        .waiter-bell-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 18px;
            height: 18px;
            padding: 0 4px;
            background: #ef4444;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            border-radius: 50px;
            display: none;
            align-items: center;
            justify-content: center;
            animation: bell-pop 0.3s ease;
        }

        @keyframes bell-pop {
            0% {
                transform: scale(0.5);
            }

            70% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }

        .waiter-bell-btn.has-calls {
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.3);
            animation: bell-shake 0.6s ease;
        }

        @keyframes bell-shake {

            0%,
            100% {
                transform: rotate(0);
            }

            20% {
                transform: rotate(12deg);
            }

            40% {
                transform: rotate(-10deg);
            }

            60% {
                transform: rotate(6deg);
            }

            80% {
                transform: rotate(-3deg);
            }
        }
    </style>
    @stack('styles')
</head>

<body class="font-sans antialiased bg-[#0f0f0f] text-gray-300" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Mobile Header -->
        <div
            class="lg:hidden bg-[#1a1a1a] border-b border-gray-800 text-white h-16 flex items-center justify-between px-4 fixed top-0 left-0 right-0 z-50">
            <span class="text-lg font-serif italic text-amber-500">L'ELITE</span>
            <button @click="sidebarOpen = !sidebarOpen"
                class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5">
                <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
                <svg x-show="sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="lg:hidden fixed inset-0 bg-black/80 z-40"
            x-transition.opacity></div>

        <!-- Sidebar -->
        <aside :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
            class="fixed lg:static inset-y-0 left-0 w-64 bg-[#0a0a0a] border-r border-gray-800 transform transition-transform duration-300 ease-in-out z-50 lg:transform-none lg:shrink-0 pt-16 lg:pt-0">

            <div class="h-24 flex flex-col justify-center px-8 border-b border-gray-800/50 hidden lg:flex">
                <h1 class="text-2xl font-serif italic text-amber-500 tracking-wider">L'ELITE</h1>
                <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 mt-1">Administration</p>
            </div>

            <nav class="mt-8 px-4 space-y-2 overflow-y-auto h-[calc(100vh-4rem)] lg:h-auto pb-20">
                @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-4 py-3 rounded-lg group transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-[#1a1a1a] text-amber-500 border-l-2 border-amber-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                    onclick="resetNotifications()">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-amber-500' : 'text-gray-500 group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg group transition-all duration-200 {{ request()->routeIs('admin.items.*', 'admin.categories.*') ? 'bg-[#1a1a1a] text-amber-500 border-l-2 border-amber-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                    onclick="resetNotifications()">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.items.*', 'admin.categories.*') ? 'text-amber-500' : 'text-gray-500 group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    <span class="text-sm font-medium">Categories & Items</span>
                </a>

                <a href="{{ route('admin.tables.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg group transition-all duration-200 {{ request()->routeIs('admin.tables.*') ? 'bg-[#1a1a1a] text-amber-500 border-l-2 border-amber-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                    onclick="resetNotifications()">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.tables.*') ? 'text-amber-500' : 'text-gray-500 group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3M2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    <span class="text-sm font-medium">Tables</span>
                </a>

                <a href="{{ route('admin.reservations.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg group transition-all duration-200 {{ request()->routeIs('admin.reservations.*') ? 'bg-[#1a1a1a] text-amber-500 border-l-2 border-amber-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                    onclick="resetNotifications()">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.reservations.*') ? 'text-amber-500' : 'text-gray-500 group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium">Reservations</span>
                </a>

                <a href="{{ route('admin.orders.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg group transition-all duration-200 {{ request()->is('admin/orders*') && !request()->is('admin/orders/create') ? 'bg-[#1a1a1a] text-amber-500 border-l-2 border-amber-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                    onclick="resetNotifications();">
                    <svg class="w-5 h-5 mr-3 {{ request()->is('admin/orders*') && !request()->is('admin/orders/create') ? 'text-amber-500' : 'text-gray-500 group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M7 12l3-3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                    <span class="text-sm font-medium">Orders</span>
                    <!-- Notification Badge -->
                    <span id="orders-notification-badge"
                        class="hidden ml-2 px-2 py-0.5 text-xs font-bold bg-red-500 text-white rounded-full animate-pulse transition-all duration-300">
                        0
                    </span>
                </a>
                @endif

                <div class="pt-4 pb-2 px-4 shadow-sm">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-600 font-bold">Stations</p>
                </div>

                @php
                    $user = auth()->check() ? auth()->user() : null;
                    $stations = ['kitchen', 'bar', 'shisha', 'cash'];
                    $allowedStations = [];
                    if ($user) {
                        if ($user->role === 'admin') {
                            $allowedStations = $stations;
                        } elseif ($user->role === 'station' && $user->station) {
                            $allowedStations = [$user->station];
                        }
                    }
                @endphp

                @if(in_array('kitchen', $allowedStations))
                <a href="{{ route('admin.stations.index', 'kitchen') }}"
                    class="flex items-center px-4 py-3 rounded-lg group transition-all duration-200 {{ request()->is('admin/stations/kitchen*') ? 'bg-[#1a1a1a] text-amber-500 border-l-2 border-amber-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->is('admin/stations/kitchen*') ? 'text-amber-500' : 'text-gray-500 group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.673.337a4 4 0 01-2.587.344l-2.031-.406a1 1 0 01-.8-.98V4.702a1 1 0 01.8-.98l2.03-.406a4 4 0 11.233 7.828l-2.03-.406c-.544-.109-.942-.585-.942-1.14V7" />
                    </svg>
                    <span class="text-sm font-medium">Kitchen</span>
                </a>
                @endif

                @if(in_array('bar', $allowedStations))
                <a href="{{ route('admin.stations.index', 'bar') }}"
                    class="flex items-center px-4 py-3 rounded-lg group transition-all duration-200 {{ request()->is('admin/stations/bar*') ? 'bg-[#1a1a1a] text-amber-500 border-l-2 border-amber-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->is('admin/stations/bar*') ? 'text-amber-500' : 'text-gray-500 group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium">Bar</span>
                </a>
                @endif

                @if(in_array('shisha', $allowedStations))
                <a href="{{ route('admin.stations.index', 'shisha') }}"
                    class="flex items-center px-4 py-3 rounded-lg group transition-all duration-200 {{ request()->is('admin/stations/shisha*') ? 'bg-[#1a1a1a] text-amber-500 border-l-2 border-amber-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->is('admin/stations/shisha*') ? 'text-amber-500' : 'text-gray-500 group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343c1.566 1.566 1.566 4.101 0 5.657z" />
                    </svg>
                    <span class="text-sm font-medium">Shisha</span>
                </a>
                @endif

                @if(in_array('cash', $allowedStations))
                <a href="{{ route('admin.stations.index', 'cash') }}"
                    class="flex items-center px-4 py-3 rounded-lg group transition-all duration-200 {{ request()->is('admin/stations/cash*') ? 'bg-[#1a1a1a] text-amber-500 border-l-2 border-amber-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->is('admin/stations/cash*') ? 'text-amber-500' : 'text-gray-500 group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-medium">Cash</span>
                </a>
                @endif

                @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('admin.reports.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg group transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-[#1a1a1a] text-amber-500 border-l-2 border-amber-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.reports.*') ? 'text-amber-500' : 'text-gray-500 group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium">Reports</span>
                </a>

                <a href="{{ route('admin.ratings.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg group transition-all duration-200 {{ request()->routeIs('admin.ratings.*') ? 'bg-[#1a1a1a] text-amber-500 border-l-2 border-amber-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.ratings.*') ? 'text-amber-500' : 'text-gray-500 group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium">Ratings</span>
                </a>

                <a href="{{ route('admin.taxes.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg group transition-all duration-200 {{ request()->routeIs('admin.taxes.*') ? 'bg-[#1a1a1a] text-amber-500 border-l-2 border-amber-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                    onclick="resetNotifications()">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.taxes.*') ? 'text-amber-500' : 'text-gray-500 group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium">Taxes</span>
                </a>

                <a href="{{ route('admin.qr-codes') }}"
                    class="flex items-center px-4 py-3 rounded-lg group transition-all duration-200 {{ request()->routeIs('admin.qr-codes') ? 'bg-[#1a1a1a] text-amber-500 border-l-2 border-amber-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                    onclick="resetNotifications()">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.qr-codes') ? 'text-amber-500' : 'text-gray-500 group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium">QR Codes</span>
                </a>
                @endif
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 pt-16 lg:pt-0 bg-[#0f0f0f]">
            <!-- Top Header (Desktop only - simplified) -->
            <header class="bg-[#1a1a1a] border-b border-gray-800 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-white">
                        @if(auth()->check() && auth()->user()->role === 'station' && auth()->user()->station)
                            {{ ucfirst(auth()->user()->station) }} Station
                        @else
                            Admin Dashboard
                        @endif
                    </h1>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Waiter Calls Bell -->
                    <button class="waiter-bell-btn" id="waiter-bell-btn" onclick="toggleWaiterSidebar()"
                        title="Active Waiter Requests">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        <span class="waiter-bell-badge" id="waiter-bell-badge">0</span>
                    </button>
                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="text-gray-400 hover:text-white transition-colors flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            <span class="text-sm">Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 lg:p-8">
                @if (session('success'))
                    <div class="mb-6 bg-green-900/30 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg relative text-sm backdrop-blur-sm"
                        role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-900/30 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg relative text-sm backdrop-blur-sm"
                        role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

        <!-- Footer -->
        <div class="fixed bottom-4 right-4 z-50">
            <div
                class="bg-[#1a1a1a]/80 backdrop-blur-sm border border-gray-700/50 rounded-lg px-3 py-2 text-xs text-gray-400">
                Powered By <span class="text-amber-500 font-semibold">Code De Luxe</span>
            </div>
        </div>
    </div>

    @stack('scripts')

    <!-- Waiter Calls Sidebar -->
    <div class="waiter-sidebar-overlay" id="waiter-sidebar-overlay" onclick="toggleWaiterSidebar()"></div>
    <div class="waiter-sidebar" id="waiter-sidebar">
        <div class="waiter-sidebar-header">
            <h2>
                <svg style="width:18px;height:18px" fill="none" stroke="#ef4444" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                    </path>
                </svg>
                Active Requests
                <span class="ws-count" id="ws-count" style="display:none">0</span>
            </h2>
            <button class="waiter-sidebar-close" onclick="toggleWaiterSidebar()">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="waiter-sidebar-list" id="waiter-sidebar-list">
            <div class="waiter-sidebar-empty" id="waiter-sidebar-empty">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                    </path>
                </svg>
                <p>No active waiter requests</p>
            </div>
        </div>
    </div>

    <!-- Waiter Calls Polling Script -->
    <script>
        let waiterSidebarOpen = false;
        let knownWaiterCallIds = new Set();
        let waiterCallsInitialized = false;

        // Professional ding sound using Web Audio API
        function playDingSound() {
            try {
                const ctx = new(window.AudioContext || window.webkitAudioContext)();
                const now = ctx.currentTime;

                // Main bell tone
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(830, now);
                osc1.frequency.exponentialRampToValueAtTime(780, now + 0.15);
                gain1.gain.setValueAtTime(0.35, now);
                gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.8);
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start(now);
                osc1.stop(now + 0.8);

                // Harmonic overtone
                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(1660, now);
                osc2.frequency.exponentialRampToValueAtTime(1560, now + 0.1);
                gain2.gain.setValueAtTime(0.12, now);
                gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.5);
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(now);
                osc2.stop(now + 0.5);

                // Second ding (slightly delayed)
                const osc3 = ctx.createOscillator();
                const gain3 = ctx.createGain();
                osc3.type = 'sine';
                osc3.frequency.setValueAtTime(1050, now + 0.18);
                osc3.frequency.exponentialRampToValueAtTime(990, now + 0.35);
                gain3.gain.setValueAtTime(0, now);
                gain3.gain.setValueAtTime(0.25, now + 0.18);
                gain3.gain.exponentialRampToValueAtTime(0.001, now + 1.0);
                osc3.connect(gain3);
                gain3.connect(ctx.destination);
                osc3.start(now + 0.18);
                osc3.stop(now + 1.0);
            } catch (e) {
                console.log('Could not play ding sound:', e);
            }
        }

        function toggleWaiterSidebar() {
            waiterSidebarOpen = !waiterSidebarOpen;
            document.getElementById('waiter-sidebar').classList.toggle('active', waiterSidebarOpen);
            document.getElementById('waiter-sidebar-overlay').classList.toggle('active', waiterSidebarOpen);
        }

        function renderWaiterCalls(calls) {
            const list = document.getElementById('waiter-sidebar-list');
            const badge = document.getElementById('waiter-bell-badge');
            const bellBtn = document.getElementById('waiter-bell-btn');
            const wsCount = document.getElementById('ws-count');

            if (calls.length === 0) {
                list.innerHTML = `
                    <div class="waiter-sidebar-empty">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <p>No active waiter requests</p>
                    </div>
                `;
                badge.style.display = 'none';
                bellBtn.classList.remove('has-calls');
                wsCount.style.display = 'none';
                return;
            }

            badge.textContent = calls.length;
            badge.style.display = 'flex';
            bellBtn.classList.add('has-calls');
            wsCount.textContent = calls.length;
            wsCount.style.display = 'inline-flex';

            let html = '';
            calls.forEach(call => {
                html += `
                    <div class="waiter-call-card" id="wc-card-${call.id}">
                        <div class="wc-card-top">
                            <div class="wc-table-info">
                                <div class="wc-table-icon">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="wc-table-label">Table</div>
                                    <div class="wc-table-number">#${call.table_number}</div>
                                </div>
                            </div>
                            <div class="wc-time">${call.created_at}</div>
                        </div>
                        <button class="wc-resolve-btn" onclick="resolveWaiterCall(${call.id})">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Done / Complete
                        </button>
                    </div>
                `;
            });
            list.innerHTML = html;
        }


        async function resolveWaiterCall(id) {
            const card = document.getElementById(`wc-card-${id}`);
            if (card) {
                card.classList.add('removing');
            }

            try {
                const response = await fetch(`/admin/waiter-calls/${id}/resolve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to resolve call');
                }

                knownWaiterCallIds.delete(id);

                // Remove card after animation
                setTimeout(() => {
                    if (card) card.remove();

                    // Immediately poll for updated calls to refresh the list
                    pollWaiterCalls();
                }, 350);
            } catch (e) {
                console.error('Error resolving waiter call:', e);
                if (card) card.classList.remove('removing');

                // Show error feedback
                const badge = document.getElementById('waiter-bell-badge');
                if (badge) {
                    badge.style.background = '#ef4444';
                    setTimeout(() => {
                        badge.style.background = '';
                    }, 2000);
                }
            }
        }

        async function pollWaiterCalls() {
            try {
                const res = await fetch('{{ route('admin.waiter-calls.pending') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await res.json();
                const calls = data.calls || [];

                // Check for NEW calls (not previously known)
                let hasNewCalls = false;
                calls.forEach(call => {
                    if (!knownWaiterCallIds.has(call.id)) {
                        hasNewCalls = true;
                        knownWaiterCallIds.add(call.id);
                    }
                });

                // Clean up IDs of resolved calls
                const currentIds = new Set(calls.map(c => c.id));
                knownWaiterCallIds.forEach(id => {
                    if (!currentIds.has(id)) knownWaiterCallIds.delete(id);
                });

                // Play ding + animate bell if there are new calls (skip initial load)
                if (hasNewCalls && waiterCallsInitialized) {
                    playDingSound();

                    // Re-trigger bell shake animation
                    const bellBtn = document.getElementById('waiter-bell-btn');
                    bellBtn.classList.remove('has-calls');
                    void bellBtn.offsetWidth; // Force reflow
                    bellBtn.classList.add('has-calls');

                    // Browser notification
                    if ('Notification' in window && Notification.permission === 'granted') {
                        const tableNums = calls.filter(c => !knownWaiterCallIds.has(c.id) || hasNewCalls)
                            .map(c => c.table_number).join(', ');
                        new Notification('Waiter Requested', {
                            body: `Table ${tableNums} needs assistance`,
                            icon: '/favicon.ico'
                        });
                    }
                }

                waiterCallsInitialized = true;
                renderWaiterCalls(calls);

            } catch (e) {
                console.error('Error polling waiter calls:', e);
            }
        }

        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }

        // Poll every 5 seconds
        setInterval(pollWaiterCalls, 5000);
        // Initial poll
        pollWaiterCalls();
    </script>

    <!-- Order Notifications Script -->
    @if (!request()->routeIs('admin.orders.*'))
        <script>
            let lastOrderCount = 0;
            let notificationSound = new Audio(
                'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVohDbt2cBhAFl1gI'
            );

            // Get seen orders from localStorage or create new Set
            let seenOrderIds = new Set(JSON.parse(localStorage.getItem('seenOrderIds') || '[]'));

            async function checkForNewOrders() {
                try {
                    const response = await fetch('{{ route('admin.orders.recent') }}', {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();
                    const currentOrderCount = data.orders.length;
                    const currentOrderIds = data.orders.map(order => order.id);

                    // Find truly new orders (not seen before)
                    const newOrders = currentOrderIds.filter(id => !seenOrderIds.has(id));

                    // Check for new orders
                    if (newOrders.length > 0) {
                        // Show notification badge
                        const badge = document.getElementById('orders-notification-badge');
                        if (badge) {
                            badge.textContent = newOrders.length;
                            badge.classList.remove('hidden');

                            // Play notification sound
                            notificationSound.play().catch(e => console.log('Could not play sound:', e));

                            // Show browser notification
                            if ('Notification' in window && Notification.permission === 'granted') {
                                new Notification('New Order Received', {
                                    body: `${newOrders.length} new order(s) received`,
                                    icon: '/favicon.ico'
                                });
                            }
                        }
                    }

                    // Update seen orders and save to localStorage
                    currentOrderIds.forEach(id => seenOrderIds.add(id));
                    localStorage.setItem('seenOrderIds', JSON.stringify(Array.from(seenOrderIds)));

                    // Check for checkouts (orders with is_checked_out = true)
                    const checkedOutOrders = data.orders.filter(order => order.is_checked_out);
                    if (checkedOutOrders.length > 0) {
                        const badge = document.getElementById('orders-notification-badge');
                        if (badge && badge.classList.contains('hidden')) {
                            badge.textContent = 'Checkout';
                            badge.classList.remove('hidden');
                            badge.classList.add('bg-green-500', 'animate-pulse');

                            // Play different sound for checkout
                            notificationSound.play().catch(e => console.log('Could not play sound:', e));

                            // Show browser notification for checkout
                            if ('Notification' in window && Notification.permission === 'granted') {
                                new Notification('Order Checked Out', {
                                    body: `Table ${checkedOutOrders[0].table_number || 'Unknown'} - Order ready for payment`,
                                    icon: '/favicon.ico'
                                });
                            }
                        }
                    }

                    lastOrderCount = currentOrderCount;
                } catch (error) {
                    console.error('Error checking for new orders:', error);
                }
            }

            // Function to clear notifications
            function clearNotifications() {
                const badge = document.getElementById('orders-notification-badge');
                if (badge) {
                    badge.classList.add('hidden');
                    badge.classList.remove('bg-green-500');
                    badge.textContent = '0';
                }
            }

            // Function to reset seen orders (when navigating to other sections)
            function resetNotifications() {
                clearNotifications();
            }

            // Function to clear seen orders completely
            function clearSeenOrders() {
                seenOrderIds.clear();
                localStorage.removeItem('seenOrderIds');
            }

            // Request notification permission
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }

            // Check for new orders every 5 seconds
            setInterval(checkForNewOrders, 5000);

            // Initial check
            checkForNewOrders();

            // Only clear notification badge when visiting orders page, but keep seen orders
            if (window.location.pathname.includes('/admin/orders')) {
                resetNotifications();
            }
        </script>
    @endif
</body>

</html>
