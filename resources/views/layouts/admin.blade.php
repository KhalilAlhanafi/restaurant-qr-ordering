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
                    class="flex items-center px-4 py-3 rounded-lg group transition-all duration-200 {{ request()->routeIs('admin.orders.*') ? 'bg-[#1a1a1a] text-amber-500 border-l-2 border-amber-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                    onclick="resetNotifications();">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.orders.*') ? 'text-amber-500' : 'text-gray-500 group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M7 12l3-3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                    <span class="text-sm font-medium">Orders</span>
                    <!-- Notification Badge -->
                    <span id="orders-notification-badge" class="hidden ml-2 px-2 py-0.5 text-xs font-bold bg-red-500 text-white rounded-full animate-pulse transition-all duration-300">
                        0
                    </span>
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
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 pt-16 lg:pt-0 bg-[#0f0f0f]">
            <!-- Top Header (Desktop only - simplified) -->
        <header class="bg-[#1a1a1a] border-b border-gray-800 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center">
                <h1 class="text-xl font-semibold text-white">Admin Dashboard</h1>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="text-sm">Logout</span>
                </button>
            </form>
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
            <div class="bg-[#1a1a1a]/80 backdrop-blur-sm border border-gray-700/50 rounded-lg px-3 py-2 text-xs text-gray-400">
                Powered By <span class="text-amber-500 font-semibold">Code De Luxe</span>
            </div>
        </div>
    </div>

    @stack('scripts')
    
    <!-- Order Notifications Script -->
    @if(!request()->routeIs('admin.orders.*'))
    <script>
        let lastOrderCount = 0;
        let notificationSound = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVohDbt2cBhAFl1gI');
        
        // Get seen orders from localStorage or create new Set
        let seenOrderIds = new Set(JSON.parse(localStorage.getItem('seenOrderIds') || '[]'));
        
        async function checkForNewOrders() {
            try {
                const response = await fetch('{{ route("admin.orders.recent") }}', {
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
