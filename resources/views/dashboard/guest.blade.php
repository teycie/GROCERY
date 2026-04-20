@extends('layouts.app')

@section('title', 'Welcome to FreshMart - Online Grocery')

@section('content')
<!-- Hero Section -->
<div class="relative bg-white dark:bg-slate-900 overflow-hidden rounded-2xl shadow-sm mb-12 border border-gray-100 dark:border-slate-700">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-white dark:bg-slate-900 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 h-full flex flex-col justify-center">
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 dark:text-slate-100 sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">Fresh groceries</span>
                        <span class="block text-green-600 xl:inline">delivered fast</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-500 dark:text-slate-300 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        Shop for farm-fresh vegetables, fruits, dairy, and daily essentials from the comfort of your home. Quality guaranteed.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-md shadow">
                            <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 md:py-4 md:text-lg transition btn-animate">
                                Get Started
                            </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 dark:bg-slate-800 dark:text-green-300 dark:hover:bg-slate-700 md:py-4 md:text-lg transition">
                                Log In
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
        <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1542838132-92c53300491e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80" alt="Fresh Groceries">
    </div>
</div>

<!-- Featured Products Section -->
<div class="mb-12">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-slate-100">Featured Products</h2>
        <a href="{{ route('register') }}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 font-medium">Log in to buy &rarr;</a>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition category-card flex flex-col">
                <div class="relative h-48 bg-gray-100 dark:bg-slate-700 group">
                    @if($product->images->count() > 0)
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-slate-300">
                            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="p-4 flex-grow flex flex-col justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-slate-300 mb-1 capitalize">{{ $product->category }}</p>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100 mb-2 truncate">{{ $product->name }}</h3>
                        <p class="text-xs text-gray-500 dark:text-slate-400">Sold by {{ $product->user->name ?? 'FreshMart Seller' }}</p>
                    </div>
                    <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100 dark:border-slate-700">
                        <span class="text-xl font-extrabold text-green-600">&#8369;{{ number_format($product->price, 2) }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-10 bg-gray-50 dark:bg-slate-800 rounded-xl">
                <p class="text-gray-500 dark:text-slate-300">Products are currently being restocked. Check back soon!</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Store Location -->
<div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-700 p-8 mb-12">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-slate-100 mb-2">Visit Our Grocery</h2>
    <p class="text-gray-600 dark:text-slate-300 mb-5">Father Saturnino Urios University, San Francisco St, Butuan City, 8600 Agusan del Norte</p>
    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-slate-700">
        <iframe
            title="FreshMart Public Store Map"
            src="https://maps.google.com/maps?q=Father%20Saturnino%20Urios%20University,%20San%20Francisco%20St,%20Butuan%20City,%208600%20Agusan%20del%20Norte&z=16&output=embed"
            class="w-full h-72"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            allowfullscreen
        ></iframe>
    </div>
</div>

<!-- Features Section -->
<div class="mb-16">
    <h2 class="text-3xl font-bold text-center mb-10 text-gray-800 dark:text-slate-100">Why Choose FreshMart?</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
        <div class="p-6 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition">
            <div class="w-16 h-16 mx-auto bg-green-100 dark:bg-green-500/15 text-green-500 dark:text-green-300 rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-slate-100">Fast Delivery</h3>
            <p class="text-gray-600 dark:text-slate-300">Get your daily staples and fresh food delivered to your doorstep within hours.</p>
        </div>
        <div class="p-6 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition">
            <div class="w-16 h-16 mx-auto bg-green-100 dark:bg-green-500/15 text-green-500 dark:text-green-300 rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-slate-100">Farm Fresh</h3>
            <p class="text-gray-600 dark:text-slate-300">We source locally from verified farmers to ensure the highest quality produce.</p>
        </div>
        <div class="p-6 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition">
            <div class="w-16 h-16 mx-auto bg-green-100 dark:bg-green-500/15 text-green-500 dark:text-green-300 rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-slate-100">Secure Payments</h3>
            <p class="text-gray-600 dark:text-slate-300">Your payments are highly secure with multiple hassle-free checkout options.</p>
        </div>
    </div>
</div>

<!-- Announcements -->
@if($announcements->count() > 0)
    <div class="bg-blue-50 dark:bg-slate-900 rounded-xl border border-blue-100 dark:border-slate-700 p-8 mb-12">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-slate-100 mb-6 flex items-center">
            <svg class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            Latest Updates
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($announcements as $announcement)
                <div class="bg-white dark:bg-slate-800 p-4 rounded-lg shadow-sm">
                    <h3 class="font-bold text-gray-900 dark:text-slate-100 mb-2">{{ $announcement->title }}</h3>
                    <p class="text-sm text-gray-600 dark:text-slate-300 line-clamp-3">{{ $announcement->message }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endif
@endsection
