<!DOCTYPE html>
<html lang=""{{ str_replace('_', '-', app()->getLocale()) }}"">
<head>
    <meta charset=""utf-8"">
    <meta name=""viewport"" content=""width=device-width, initial-scale=1"">
    <title>FreshMart - Online Grocery</title>
    <link href=""https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap"" rel=""stylesheet"">
    <link href=""https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"" rel=""stylesheet"">    <link href="{{ asset('css/style.css') }}" rel="stylesheet">    <style>
        body { font-family: 'Nunito', sans-serif; }
        .hero-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1542838132-92c53300491e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class=""antialiased bg-gray-50 text-gray-800"">
    
    <!-- Navbar -->
    <nav class=""bg-white shadow relative z-50"">
        <div class=""max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"">
            <div class=""flex justify-between h-16"">
                <div class=""flex items-center"">
                    <svg class=""h-8 w-8 text-green-500"" fill=""none"" viewBox=""0 0 24 24"" stroke=""currentColor"">
                        <path stroke-linecap=""round"" stroke-linejoin=""round"" stroke-width=""2"" d=""M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"" />
                    </svg>
                    <span class=""ml-2 text-2xl font-bold text-green-600"">FreshMart</span>
                </div>
                <div class=""flex items-center space-x-4"">
                    @if (Route::has('login'))
                        @auth
                            <a href=""{{ url('/home') }}"" class=""text-md font-semibold text-gray-700 hover:text-green-600"">Dashboard</a>
                        @else
                            <a href=""{{ route('login') }}"" class=""text-md font-semibold text-gray-700 hover:text-green-600"">Log in</a>
                            @if (Route::has('register'))
                                <a href=""{{ route('register') }}"" class=""px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md font-semibold transition"">Register</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class=""hero-bg h-[500px] flex items-center justify-center py-32"">
        <div class=""text-center px-4 relative z-10"">
            <h1 class=""text-4xl md:text-6xl font-extrabold text-white mb-6"">Fresh Groceries Delivered Fast</h1>
            <p class=""text-lg md:text-xl text-gray-200 mb-8 max-w-2xl mx-auto"">Shop for farm-fresh vegetables, fruits, dairy, and daily essentials from the comfort of your home.</p>
            <a href=""{{ route('login') }}"" class=""px-8 py-4 bg-green-500 hover:bg-green-600 text-white text-lg font-bold rounded-full shadow-lg transition transform hover:scale-105 inline-block"">Start Shopping</a>
        </div>
    </div>

    <!-- Features Section -->
    <div class=""max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16"">
        <h2 class=""text-3xl font-bold text-center mb-12"">Why Choose Us?</h2>
        <div class=""grid grid-cols-1 md:grid-cols-3 gap-8 text-center"">
            <div class=""p-6 bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition"">
                <div class=""w-16 h-16 mx-auto bg-green-100 text-green-500 rounded-full flex items-center justify-center mb-4"">
                    <svg class=""h-8 w-8"" fill=""none"" viewBox=""0 0 24 24"" stroke=""currentColor"">
                        <path stroke-linecap=""round"" stroke-linejoin=""round"" stroke-width=""2"" d=""M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"" />
                    </svg>
                </div>
                <h3 class=""text-xl font-bold mb-2"">Fast Delivery</h3>
                <p class=""text-gray-600"">Get your daily staples and fresh food delivered to your doorstep within hours.</p>
            </div>
            <div class=""p-6 bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition"">
                <div class=""w-16 h-16 mx-auto bg-green-100 text-green-500 rounded-full flex items-center justify-center mb-4"">
                    <svg class=""h-8 w-8"" fill=""none"" viewBox=""0 0 24 24"" stroke=""currentColor"">
                        <path stroke-linecap=""round"" stroke-linejoin=""round"" stroke-width=""2"" d=""M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"" />
                    </svg>
                </div>
                <h3 class=""text-xl font-bold mb-2"">Farm Fresh</h3>
                <p class=""text-gray-600"">We source locally from verified farmers to ensure the highest quality produce.</p>
            </div>
            <div class=""p-6 bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition"">
                <div class=""w-16 h-16 mx-auto bg-green-100 text-green-500 rounded-full flex items-center justify-center mb-4"">
                    <svg class=""h-8 w-8"" fill=""none"" viewBox=""0 0 24 24"" stroke=""currentColor"">
                        <path stroke-linecap=""round"" stroke-linejoin=""round"" stroke-width=""2"" d=""M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"" />
                    </svg>
                </div>
                <h3 class=""text-xl font-bold mb-2"">Secure Payments</h3>
                <p class=""text-gray-600"">Your payments are highly secure with multiple hassle-free checkout options.</p>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <div class=""bg-white py-16 border-t border-gray-100"">
        <div class=""max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"">
            <h2 class=""text-3xl font-bold text-center mb-12"">Featured Categories</h2>
            <div class=""grid grid-cols-2 md:grid-cols-4 gap-6"">
                <div class=""relative rounded-xl overflow-hidden group cursor-pointer shadow-sm group"">
                    <img src=""https://images.unsplash.com/photo-1610832958506-aa56368176cf?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"" alt=""Fruits"" class=""w-full h-48 object-cover transform group-hover:scale-110 transition duration-500"">
                    <div class=""absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center transition duration-500 group-hover:bg-opacity-50"">
                        <h3 class=""text-white text-xl font-bold"">Fresh Fruits</h3>
                    </div>
                </div>
                <div class=""relative rounded-xl overflow-hidden group cursor-pointer shadow-sm group"">
                    <img src=""https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"" alt=""Vegetables"" class=""w-full h-48 object-cover transform group-hover:scale-110 transition duration-500"">
                    <div class=""absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center transition duration-500 group-hover:bg-opacity-50"">
                        <h3 class=""text-white text-xl font-bold"">Vegetables</h3>
                    </div>
                </div>
                <div class=""relative rounded-xl overflow-hidden group cursor-pointer shadow-sm"">
                    <img src=""https://images.unsplash.com/photo-1628088062854-d1870b4553da?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"" alt=""Meat"" class=""w-full h-48 object-cover transform group-hover:scale-110 transition duration-500"">
                    <div class=""absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center transition duration-500 group-hover:bg-opacity-50"">
                        <h3 class=""text-white text-xl font-bold"">Meat & Seafood</h3>
                    </div>
                </div>
                <div class=""relative rounded-xl overflow-hidden group cursor-pointer shadow-sm"">
                    <img src=""https://images.unsplash.com/photo-1550583724-b2692b85b150?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"" alt=""Dairy"" class=""w-full h-48 object-cover transform group-hover:scale-110 transition duration-500"">
                    <div class=""absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center transition duration-500 group-hover:bg-opacity-50"">
                        <h3 class=""text-white text-xl font-bold"">Dairy & Eggs</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class=""bg-gray-900 text-white py-8"">
        <div class=""max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center"">
            <p class=""text-gray-400"">&copy; {{ date('Y') }} FreshMart Online Grocery. All rights reserved.</p>
        </div>
    </footer>
    <script src="{{ asset('js/script.js') }}"></script></body>
</html>
