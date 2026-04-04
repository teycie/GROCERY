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
            background-image:
                radial-gradient(circle at 12% 8%, rgba(79, 198, 122, 0.08), transparent 32%),
                radial-gradient(circle at 85% 4%, rgba(134, 221, 165, 0.1), transparent 30%);
        }

        .dark body {
            background-image:
                radial-gradient(circle at 10% 10%, rgba(79, 198, 122, 0.14), transparent 34%),
                radial-gradient(circle at 90% 0%, rgba(31, 143, 79, 0.18), transparent 33%);
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
<body class="antialiased bg-gray-50 text-gray-800 dark:bg-emerald-950 dark:text-slate-200 min-h-screen flex flex-col transition-colors duration-200">
    <!-- Main Navigation -->
    <nav class="bg-white/95 dark:bg-emerald-950/90 shadow relative z-50 sticky top-0 transition-colors duration-200 border-b border-green-100 dark:border-green-900/60 backdrop-blur">
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
    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
    <footer class="bg-green-700 dark:bg-emerald-950 text-white py-8 mt-auto border-t border-green-800 dark:border-green-900/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <span class="text-2xl font-bold text-green-100 dark:text-green-500">FreshMart</span>
                    <p class="text-green-100/90 dark:text-gray-400 mt-2 text-sm">Delivering freshness to your doorstep.</p>
                </div>
                <div class="text-green-100/90 dark:text-gray-400 text-sm">
                    &copy; {{ date('Y') }} FreshMart Online Grocery. All rights reserved.
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
