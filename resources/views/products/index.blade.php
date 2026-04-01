@extends('layouts.app')

@section('title', 'All Products - FreshMart')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Our Products</h1>
        <p class="text-gray-600">Fresh groceries delivered straight to your door.</p>
    </div>
</div>

<div class="flex flex-col lg:flex-row gap-8">
    <!-- Filters Sidebar -->
    <div class="w-full lg:w-1/4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Categories</h2>
            <div class="space-y-2">
                <a href="{{ route('products.index') }}" class="block px-3 py-2 rounded-md transition {{ !request('category') ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="mr-2">??</span> All Products
                </a>
                @php
                    $categoryIcons = [
                        'Frozen' => '??', 'Beverage' => '?', 'Snacks' => '??', 
                        'Fruits & Vegetables' => '??', 'Pet Care' => '??', 'Household Cleaning & Essentials' => '??'
                    ];
                @endphp
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category]) }}" class="block px-3 py-2 rounded-md transition {{ request('category') === $category ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                        <span class="mr-2">{!! $categoryIcons[$category] ?? '??' !!}</span> {{ $category }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="w-full lg:w-3/4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($products as $product)
                <div class="bg-white border rounded-xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col">
                    <a href="{{ route('products.show', $product) }}" class="relative h-48 bg-gray-100 group block overflow-hidden">
                        @if($product->images->count() > 0)
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 group-hover:scale-105 transition duration-300">
                                <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        <span class="absolute top-2 right-2 bg-white/90 backdrop-blur text-xs font-bold px-2 py-1 rounded text-gray-700 shadow-sm">
                            {!! $categoryIcons[$product->category] ?? '' !!} {{ $product->category }}
                        </span>
                    </a>
                    
                    <div class="p-4 flex-grow flex flex-col justify-between">
                        <div class="mb-3">
                            <a href="{{ route('products.show', $product) }}" class="block">
                                <h3 class="text-lg font-bold text-gray-900 mb-1 hover:text-green-600 transition truncate">{{ $product->name }}</h3>
                            </a>
                            <span class="text-xl font-extrabold text-green-600">?{{ number_format($product->price, 2) }}</span>
                        </div>
                        
                        <div class="flex gap-2 mt-auto">
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full bg-green-50 text-green-700 hover:bg-green-100 py-2 px-3 rounded-md text-sm font-semibold transition flex justify-center items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Add
                                </button>
                            </form>
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="buy_now" value="1">
                                <button type="submit" class="w-full bg-green-600 text-white hover:bg-green-700 py-2 px-3 rounded-md text-sm font-semibold transition">
                                    Buy Now
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-gray-50 rounded-xl border border-gray-100 p-8">
                    <span class="text-4xl block mb-4">??</span>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">No Products Found</h3>
                    <p class="text-gray-500">We couldn't find any products matching your current filters.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-8">
            {{ $products->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
