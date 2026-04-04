@extends('layouts.app')

@section('title', 'Buyer Dashboard - FreshMart')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-slate-100 transition-colors duration-200">Welcome back, {{ auth()->user()->name }}!</h1>
    <p class="mt-2 text-gray-600 dark:text-slate-300 transition-colors duration-200">Here is what's happening at FreshMart today.</p>
</div>

<!-- Buyer Analytics Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 flex flex-col justify-center transition-colors duration-200">
        <span class="text-sm font-medium text-gray-500 dark:text-slate-300 mb-1">Store Products Available</span>
        <span class="text-3xl font-extrabold text-gray-900 dark:text-slate-100">{{ $totalProductsAvailable }}</span>
    </div>
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 flex flex-col justify-center transition-colors duration-200">
        <span class="text-sm font-medium text-gray-500 dark:text-slate-300 mb-1">Items in Your Cart</span>
        <span class="text-3xl font-extrabold text-green-600 dark:text-green-400">{{ $cartItemsCount }}</span>
    </div>
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 flex flex-col justify-center transition-colors duration-200">
        <span class="text-sm font-medium text-gray-500 dark:text-slate-300 mb-1">Current Cart Total</span>
        <span class="text-3xl font-extrabold text-blue-600 dark:text-blue-400">&#8369;{{ number_format($cartTotalValue, 2) }}</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content: Products Panel -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 mb-8 transition-colors duration-200">
            <div class="flex justify-between items-center mb-6 border-b border-transparent dark:border-slate-700 pb-2">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-slate-100">Latest Products</h2>
                <a href="{{ route('products.index') }}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 font-medium">View All <span aria-hidden="true">&rarr;</span></a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse($products as $product)
                    <div class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition category-card flex flex-col duration-200">
                        <div class="relative h-48 bg-gray-100 dark:bg-slate-700 transition-colors duration-200">
                            @if($product->images->count() > 0)
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-slate-300">
                                    <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full uppercase">New</span>
                        </div>
                        <div class="p-4 flex-grow flex flex-col justify-between">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-slate-300 mb-1 capitalize">{{ $product->category }}</p>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100 mb-2 truncate">{{ $product->name }}</h3>
                            </div>
                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100 dark:border-slate-700">
                                <span class="text-xl font-extrabold text-green-600 dark:text-green-400">&#8369;{{ number_format($product->price, 2) }}</span>
                                <a href="{{ route('products.show', $product) }}" class="text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-900/50 p-2 rounded-full transition">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-8 text-center text-gray-500 dark:text-slate-300 bg-gray-50 dark:bg-slate-800 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                        <p>No products available right now.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar: Announcements & Quick Stats -->
    <div class="lg:col-span-1">
        <!-- Announcements Widget -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 mb-8 transition-colors duration-200">
            <div class="flex items-center mb-6">
                <svg class="h-6 w-6 text-blue-500 dark:text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <h2 class="text-xl font-bold text-gray-800 dark:text-slate-100">Store Announcements</h2>
            </div>
            
            <div class="space-y-4">
                @forelse($announcements as $announcement)
                    <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50 transition-colors duration-200">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700/50 pb-1">{{ $announcement->title }}</h3>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-slate-300">{{ $announcement->message }}</p>
                    </div>
                @empty
                    <div class="text-center p-4 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-lg text-gray-500 dark:text-slate-300">
                        <p>No announcements right now.</p>
                    </div>
                @endforelse
            </div>
            @if(count($announcements) > 0)
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-slate-700 text-center">
                    <a href="{{ route('announcements.index') }}" class="text-sm text-blue-600 dark:text-blue-400 font-medium hover:underline">Read all announcements</a>
                </div>
            @endif
        </div>
        
        <!-- Cart Teaser Widget -->
        <div class="bg-gradient-to-r from-green-400 to-green-600 dark:from-emerald-600 dark:to-emerald-800 rounded-xl shadow-sm text-white p-6 relative overflow-hidden group transition-colors duration-200">
            <div class="relative z-10">
                <h3 class="text-2xl font-bold mb-2">Ready to checkout?</h3>
                <p class="mb-6 text-green-50 dark:text-green-100">Don't forget the kitchen essentials!</p>
                <a href="{{ route('cart.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-full bg-white dark:bg-slate-900 text-green-600 dark:text-green-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition shadow">
                    Go to Cart
                </a>
            </div>
            <svg class="absolute -bottom-6 -right-6 h-32 w-32 text-white opacity-20 transform group-hover:scale-110 transition duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>

    </div>
</div>
@endsection
