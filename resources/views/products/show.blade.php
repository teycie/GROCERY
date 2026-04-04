@extends('layouts.app')

@section('title', $product->name . ' - FreshMart')

@section('content')
<div class="mb-6">
    <a href="{{ route('products.index') }}" class="text-green-600 hover:text-green-800 flex items-center mb-4 transition font-medium">
        <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Back to Products
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="flex flex-col md:flex-row">
        <!-- Product Images -->
        <div class="w-full md:w-1/2 bg-gray-50 border-b md:border-b-0 md:border-r border-gray-100 p-8 flex items-center justify-center min-h-[400px] relative">
            @if($product->images->count() > 0)
                <img id="mainImage" src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="w-full max-h-[500px] object-contain rounded-lg shadow-sm transition duration-500">
                @if($product->images->count() > 1)
                    <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 px-4">
                        @foreach($product->images as $index => $image)
                            <button onclick="document.getElementById('mainImage').src='{{ asset('storage/' . $image->image_path) }}'" class="h-16 w-16 rounded-md overflow-hidden border-2 border-gray-200 hover:border-green-500 focus:border-green-500 transition opacity-80 hover:opacity-100 shadow-sm">
                                <img src="{{ asset('storage/' . $image->image_path) }}" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="w-full h-[400px] flex items-center justify-center text-gray-400">
                    <svg class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
            @endif
        </div>

        <!-- Product Details -->
        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col">
            <span class="text-sm font-bold text-green-600 uppercase tracking-wide mb-2 inline-block">{{ $product->category }}</span>
            <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 mb-4">{{ $product->name }}</h1>
            
            <div class="text-4xl font-extrabold text-gray-900 mb-6">₱{{ number_format($product->price, 2) }}</div>
            
            <p class="text-gray-600 mb-8 leading-relaxed">{{ $product->description }}</p>

            <div class="mt-auto border-t border-gray-100 pt-6">
                <div class="flex items-center mb-6">
                    <span class="mr-2 font-medium {{ $product->stock > 0 ? 'text-green-600' : 'text-red-500' }}">
                        @if($product->stock > 0)
                            <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> {{ $product->stock }} items left
                        @else
                            <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Out of stock
                        @endif
                    </span>
                </div>

                <form action="{{ route('cart.add', $product) }}" method="POST" class="flex flex-col sm:flex-row gap-4">
                    @csrf
                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden h-12 max-w-[150px]">
                        <button type="button" class="w-12 h-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition" onclick="document.getElementById('qty').stepDown()">-</button>
                        <input type="number" id="qty" name="quantity" value="1" min="1" max="{{ $product->stock > 0 ? $product->stock : 1 }}" class="w-full h-full text-center border-0 focus:ring-0 text-gray-900 font-bold" {{ $product->stock == 0 ? 'disabled' : '' }}>
                        <button type="button" class="w-12 h-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition" onclick="document.getElementById('qty').stepUp()">+</button>
                    </div>
                    
                    <div class="flex-1 flex gap-2">
                        <button type="submit" class="flex-1 bg-green-50 text-green-700 hover:bg-green-100 h-12 rounded-lg font-bold transition flex items-center justify-center" {{ $product->stock == 0 ? 'disabled' : '' }}>
                            Add to Cart
                        </button>
                        <button type="submit" name="buy_now" value="1" class="flex-1 bg-green-600 text-white hover:bg-green-700 shadow-md h-12 rounded-lg font-bold transition flex items-center justify-center" {{ $product->stock == 0 ? 'disabled' : '' }}>
                            Buy Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
