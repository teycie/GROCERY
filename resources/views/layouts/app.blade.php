<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FreshMart - Online Grocery')</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        green: {
                            50: '#f2fbf4',
                            100: '#dcf5e3',
                            200: '#b9ebc9',
                            300: '#86dda5',
                            400: '#4fc67a',
                            500: '#28a95e',
                            600: '#1f8f4f',
                            700: '#1b7241',
                            800: '#175b35',
                            900: '#144a2d',
                            950: '#0a2b19',
                        }
                    },
                    fontFamily: {
                        sans: ['Nunito', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            color-scheme: light;
        }

        .dark {
            color-scheme: dark;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-image: none;
        }

        .dark body {
            background-color: #0b1020;
        }

        .dark nav[aria-label="Pagination Navigation"] a,
        .dark nav[aria-label="Pagination Navigation"] span {
            background-color: #111a2b !important;
            border-color: #25324a !important;
            color: #d1d5db !important;
        }

        .dark nav[aria-label="Pagination Navigation"] a:hover {
            background-color: #162235 !important;
        }

        .dark nav[aria-label="Pagination Navigation"] span[aria-current="page"] span {
            background-color: #1f7f3a !important;
            border-color: #1f7f3a !important;
            color: #ffffff !important;
        }

        /* Notification dropdown animation */
        .notif-dropdown {
            transform: translateY(-8px);
            opacity: 0;
            pointer-events: none;
            transition: all 0.2s ease;
        }
        .notif-dropdown.show {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        /* Pulse animation for notification badge */
        @keyframes notif-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        .notif-badge-pulse {
            animation: notif-pulse 2s ease-in-out infinite;
        }
    </style>
    <!-- Dark Mode Initializer -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="antialiased bg-white text-gray-800 dark:bg-[#0b1020] dark:text-slate-200 min-h-screen flex flex-col transition-colors duration-200">
    <!-- Main Navigation -->
    <nav class="bg-white/95 dark:bg-[#0d1528]/95 shadow relative z-50 sticky top-0 transition-colors duration-200 border-b border-green-100 dark:border-slate-800 backdrop-blur">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="flex items-center">
                        <svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="ml-2 text-2xl font-bold text-green-600 dark:text-green-400 hidden sm:block">FreshMart</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-6">
                    @auth
                        @if(auth()->user()->role === 'buyer')
                            <a href="{{ route('buyer.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition {{ request()->routeIs('buyer.dashboard') ? 'text-green-600 dark:text-green-400' : '' }}" {{ request()->routeIs('buyer.dashboard') ? 'aria-current=\"page\"' : '' }}>Home</a>
                            <a href="{{ route('products.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition {{ request()->routeIs('products.*') ? 'text-green-600 dark:text-green-400' : '' }}" {{ request()->routeIs('products.*') ? 'aria-current=\"page\"' : '' }}>Shop</a>
                            <a href="{{ route('cart.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition relative {{ request()->routeIs('cart.*') ? 'text-green-600 dark:text-green-400' : '' }}" title="Cart" {{ request()->routeIs('cart.*') ? 'aria-current=\"page\"' : '' }}>
                                <svg class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </a>
                        @elseif(auth()->user()->role === 'seller' || auth()->user()->role === 'admin')
                            <a href="{{ route('seller.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition {{ request()->routeIs('seller.dashboard') ? 'text-green-600 dark:text-green-400' : '' }}" {{ request()->routeIs('seller.dashboard') ? 'aria-current=\"page\"' : '' }}>Dashboard</a>
                            <a href="{{ route('seller.products.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition {{ request()->routeIs('seller.products.*') ? 'text-green-600 dark:text-green-400' : '' }}" {{ request()->routeIs('seller.products.*') ? 'aria-current=\"page\"' : '' }}>Products</a>
                            <a href="{{ route('seller.inventory.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition {{ request()->routeIs('seller.inventory.*') ? 'text-green-600 dark:text-green-400' : '' }}" {{ request()->routeIs('seller.inventory.*') ? 'aria-current=\"page\"' : '' }}>Inventory</a>
                            <a href="{{ route('seller.deliveries.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition {{ request()->routeIs('seller.deliveries.*') ? 'text-green-600 dark:text-green-400' : '' }}" {{ request()->routeIs('seller.deliveries.*') ? 'aria-current=\"page\"' : '' }}>Order</a>
                            <a href="{{ route('seller.announcements.create') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition {{ request()->routeIs('seller.announcements.*') ? 'text-green-600 dark:text-green-400' : '' }}" title="Post Announcement" {{ request()->routeIs('seller.announcements.*') ? 'aria-current=\"page\"' : '' }}>
                                <svg class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                            </a>
                        @elseif(auth()->user()->role === 'rider')
                            <a href="{{ route('rider.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition {{ request()->routeIs('rider.dashboard') ? 'text-green-600 dark:text-green-400' : '' }}" {{ request()->routeIs('rider.dashboard') ? 'aria-current=\"page\"' : '' }}>Dashboard</a>
                            <a href="{{ route('rider.deliveries', ['filter' => 'active']) }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition {{ request()->routeIs('rider.deliveries') && request()->input('filter') !== 'history' ? 'text-green-600 dark:text-green-400' : '' }}" {{ request()->routeIs('rider.deliveries') && request()->input('filter') !== 'history' ? 'aria-current=\"page\"' : '' }}>My Deliveries</a>
                            <a href="{{ route('rider.deliveries', ['filter' => 'history']) }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition {{ request()->routeIs('rider.deliveries') && request()->input('filter') === 'history' ? 'text-green-600 dark:text-green-400' : '' }}" {{ request()->routeIs('rider.deliveries') && request()->input('filter') === 'history' ? 'aria-current=\"page\"' : '' }}>History</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition">Dashboard</a>
                        @endif

                        {{-- Notification Bell --}}
                        <div class="relative" id="notif-wrapper">
                            <button onclick="toggleNotifDropdown()" class="relative text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 transition p-1" title="Notifications">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span id="notif-count-badge" class="hidden absolute -top-1 -right-1 h-5 w-5 rounded-full bg-red-500 text-white text-xs font-bold flex items-center justify-center notif-badge-pulse">0</span>
                            </button>
                            <div id="notif-dropdown" class="notif-dropdown absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-gray-200 dark:border-slate-700 overflow-hidden z-50">
                                <div class="px-4 py-3 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
                                    <h3 class="font-bold text-sm text-gray-800 dark:text-slate-100">Notifications</h3>
                                    <a href="{{ route('notifications.index') }}" class="text-xs font-semibold text-green-600 hover:text-green-500">View All</a>
                                </div>
                                <div id="notif-list" class="max-h-72 overflow-y-auto divide-y divide-gray-50 dark:divide-slate-700">
                                    <div class="px-4 py-6 text-center text-sm text-gray-400 dark:text-slate-500">No new notifications</div>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('profile.show') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition {{ request()->routeIs('profile.*') ? 'text-green-600 dark:text-green-400' : '' }}" title="Profile" {{ request()->routeIs('profile.*') ? 'aria-current="page"' : '' }}>
                            <svg class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </a>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-semibold transition ml-4">Logout</button>
                        </form>
                    @else
                        <a href="{{ url('/') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition">Home</a>
                        <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md font-semibold transition">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8 {{ request()->routeIs('dashboard', 'buyer.dashboard', 'seller.dashboard', 'rider.dashboard') ? 'dark:bg-[#0b1020]' : '' }}">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-400 px-4 py-3 rounded relative mb-6" role="alert">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-green-700 dark:bg-[#0d1528] text-white dark:text-slate-300 py-6 mt-auto border-t border-green-800 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <span class="text-2xl font-bold text-green-100 dark:text-green-300">FreshMart</span>
                    <p class="text-green-100/90 dark:text-slate-400 mt-2 text-sm">Delivering freshness to your doorstep.</p>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-green-100 dark:text-slate-200 mb-4">Contact Us</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 mr-3 text-green-200 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <a href="mailto:support@freshmart.com" class="text-green-100/90 dark:text-slate-300 hover:text-white transition">support@freshmart.com</a>
                        </div>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 mr-3 text-green-200 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <a href="tel:+1234567890" class="text-green-100/90 dark:text-slate-300 hover:text-white transition">+1 (234) 567-890</a>
                        </div>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 mr-3 text-green-200 dark:text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.6915026,13.4744748 L17.6915026,13.4744748 C17.2182985,13.0152755 16.4625722,13.0152755 15.9893681,13.4744748 L15.2347474,14.2620114 C14.7625433,14.7220026 13.5750545,14.0151496 12.6563168,13.0851688 C11.7375792,12.1555634 11.4286619,11.0426181 11.9008661,10.5826269 L12.6554868,9.79509034 C13.1286909,9.33589105 13.1286909,8.5923409 12.6554868,8.13314161 L8.77369614,4.40535571 C8.30049206,3.94615641 7.54460567,3.94615641 7.07140159,4.40535571 L6.10772495,5.33131833 C5.25037849,6.16347978 5.25037849,7.62286122 6.10772495,8.45502267 C6.10772495,8.45502267 6.65513854,9.88908995 8.77369614,11.9266224 C10.8922537,14.021022 12.4087905,14.5348575 12.4087905,14.5348575 C13.2653169,15.3647509 14.7275381,15.3647509 15.5840646,14.5348575 L16.532739,13.6061271 C17.0059431,13.1462266 17.7625722,13.1462266 18.2355763,13.6061271 L19.8126178,15.1451467 C20.2858219,15.6043461 20.2858219,16.3479962 19.8126178,16.8071955 L19.8126178,16.8071955 C17.6291383,18.9285762 15.0385034,18.9285762 12.559108,16.5157149 C10.0797127,14.1028536 9.35873896,11.8354197 9.35873896,11.8354197 C8.83606567,10.9803468 9.35873896,8.5925096 11.1271293,6.8900255 C12.8619532,5.2181121 15.2123003,4.65289428 16.0704275,5.49323467 L18.0701067,7.44039408 C18.0701067,7.44039408 18.5938169,7.97213117 18.5938169,8.61234796 C18.5938169,9.25256475 18.0701067,9.79509034 17.6915026,10.1719756 L17.6915026,13.4744748 Z" />
                            </svg>
                            <a href="https://wa.me/1234567890" target="_blank" rel="noopener noreferrer" class="text-green-100/90 dark:text-slate-300 hover:text-white transition">Message via WhatsApp</a>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-green-100 dark:text-slate-200 mb-4">Follow Us</h3>
                    <div class="flex space-x-4 mb-4">
                        <a href="https://facebook.com/freshmart" target="_blank" rel="noopener noreferrer" class="text-green-100/90 dark:text-slate-300 hover:text-white transition transform hover:scale-110">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="https://instagram.com/freshmart" target="_blank" rel="noopener noreferrer" class="text-green-100/90 dark:text-slate-300 hover:text-white transition transform hover:scale-110">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0m5.894 17.417c-1.594 2.782-4.639 4.583-8.129 4.583-5.012 0-9.142-4.129-9.142-9.142 0-3.49 1.801-6.535 4.583-8.129v2.274c0 .857.695 1.552 1.552 1.552.857 0 1.552-.695 1.552-1.552v-3.104c0-.857.695-1.552 1.552-1.552.857 0 1.552.695 1.552 1.552v3.104c0 .857.695 1.552 1.552 1.552.857 0 1.552-.695 1.552-1.552v-2.274zm-9.142-3.104c-1.714 0-3.104 1.39-3.104 3.104 0 1.714 1.39 3.104 3.104 3.104 1.714 0 3.104-1.39 3.104-3.104 0-1.714-1.39-3.104-3.104-3.104z"/>
                            </svg>
                        </a>
                    </div>
                    <p class="text-green-100/90 dark:text-slate-400 text-xs">
                        &copy; 2026 FreshMart Online Grocery. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/script.js') }}"></script>

    {{-- Real-time Notification Polling --}}
    @auth
    <script>
        // Notification dropdown toggle
        function toggleNotifDropdown() {
            const dropdown = document.getElementById('notif-dropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('notif-wrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                document.getElementById('notif-dropdown').classList.remove('show');
            }
        });

        // Poll for notifications every 10 seconds
        function pollNotifications() {
            fetch('{{ route("notifications.poll") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notif-count-badge');
                const list = document.getElementById('notif-list');

                if (data.unread_count > 0) {
                    badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                    badge.classList.remove('hidden');
                    badge.classList.add('flex');
                } else {
                    badge.classList.add('hidden');
                    badge.classList.remove('flex');
                }

                if (data.notifications.length > 0) {
                    let html = '';
                    data.notifications.forEach(function(n) {
                        const statusColors = {
                            'approved': 'bg-emerald-500',
                            'preparing': 'bg-amber-500',
                            'rider_assigned': 'bg-indigo-500',
                            'picked_up': 'bg-cyan-500',
                            'on_delivery': 'bg-purple-500',
                            'delivered': 'bg-green-500',
                            'cancelled': 'bg-red-500',
                        };
                        const dotColor = statusColors[n.status] || 'bg-gray-400';

                        html += '<form method="POST" action="/notifications/' + n.id + '/read" class="m-0 p-0 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">' +
                            '<input type="hidden" name="_token" value="' + document.querySelector('meta[name="csrf-token"]').content + '">' +
                            '<button type="submit" class="w-full text-left px-4 py-3">' +
                            '<div class="flex items-start gap-3">' +
                            '<div class="mt-1.5 h-2.5 w-2.5 rounded-full flex-shrink-0 ' + dotColor + '"></div>' +
                            '<div class="flex-1 min-w-0">' +
                            '<p class="text-sm text-gray-800 dark:text-slate-200 leading-snug">' + n.message + '</p>' +
                            '<p class="text-xs text-gray-400 dark:text-slate-500 mt-1">' + n.time + '</p>' +
                            '</div></div></button></form>';
                    });
                    list.innerHTML = html;
                } else {
                    list.innerHTML = '<div class="px-4 py-6 text-center text-sm text-gray-400 dark:text-slate-500">No new notifications</div>';
                }
            })
            .catch(function() {});
        }

        // Initial poll + interval
        pollNotifications();
        setInterval(pollNotifications, 10000);
    </script>
    @endauth
</body>
</html>
