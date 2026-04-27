<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                            <a href="{{ route('buyer.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition">Home</a>
                            <a href="{{ route('products.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition">Shop</a>
                            <a href="{{ route('cart.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition relative">
                                Cart
                            </a>
                        @elseif(auth()->user()->role === 'seller' || auth()->user()->role === 'admin')
                            <a href="{{ route('seller.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition">Dashboard</a>
                            <a href="{{ route('seller.products.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition">Products</a>
                            <a href="{{ route('seller.inventory.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition">Inventory</a>
                            <a href="{{ route('seller.deliveries.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition">Deliveries</a>
                            <a href="{{ route('seller.announcements.create') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition">Post Announcement</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition">Dashboard</a>
                        @endif

                        <a href="{{ route('profile.show') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition">Profile</a>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-semibold transition ml-4">Logout</button>
                        </form>
                    @else
                        <a href="{{ url('/') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition">Home</a>
                        <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-semibold transition">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md font-semibold transition">Register</a>
                    @endauth

                    <!-- Dark Mode Toggle Button -->
                    <button id="theme-toggle" type="button" class="text-gray-500 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-green-900/40 focus:outline-none focus:ring-4 focus:ring-green-100 dark:focus:ring-green-900/60 rounded-lg text-sm p-2.5 border border-transparent dark:border-green-900/60">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8 {{ request()->routeIs('dashboard', 'buyer.dashboard', 'seller.dashboard') ? 'dark:bg-[#0b1020]' : '' }}">
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

    <!-- Dark Mode Toggle Script -->
    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside the button based on previous settings
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function() {
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });
    </script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
