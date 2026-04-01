@extends('layouts.app')

@section('title', 'Checkout - FreshMart')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('cart.index') }}" class="text-green-600 font-semibold hover:text-green-700 flex items-center transition mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Cart
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900">Checkout</h1>
        <p class="text-gray-500 mt-2">Confirm your items and select quantities.</p>
    </div>

    <!-- Main Checkout Form -->
    <form action="{{ route('cart.checkout') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @csrf
        <div class="p-6 bg-gray-50/50 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-800">Order Summary</h2>
        </div>

        <div class="p-6">
            <ul class="divide-y divide-gray-100 mb-8">
                @foreach($items as $item)
                    <li class="py-6 flex flex-col sm:flex-row items-start sm:items-center">
                        <div class="flex items-center flex-1 w-full">
                            <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 bg-white">
                                @if(!empty($item['image_path']))
                                    <img src="{{ asset('storage/' . $item['image_path']) }}" alt="{{ $item['product']->name }}" class="h-full w-full object-cover object-center">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-gray-300">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-bold text-gray-900">{{ $item['product']->name }}</h3>
                                <p class="text-gray-500 text-sm mt-1">In Cart: {{ $item['quantity'] }}</p>
                                <input type="hidden" name="selected_items[]" value="{{ $item['product_id'] }}">
                            </div>
                        </div>

                        <!-- Quantity Control -->
                        <div class="mt-4 sm:mt-0 sm:ml-6 flex items-center sm:block w-full sm:w-auto justify-between bg-gray-50 sm:bg-transparent p-4 sm:p-0 rounded-lg">
                            <label for="qty_{{ $item['product_id'] }}" class="block text-sm font-bold text-gray-700 sm:mb-2">Order Quantity</label>
                            <input
                                type="number"
                                id="qty_{{ $item['product_id'] }}"
                                name="quantities[{{ $item['product_id'] }}]"
                                min="1"
                                max="{{ $item['quantity'] }}"
                                value="{{ old('quantities.' . $item['product_id'], $item['quantity']) }}"
                                class="mt-1 block sm:w-32 rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 font-bold py-2 px-3 text-center sm:text-left"
                            >
                            @error('quantities.' . $item['product_id'])
                                <span class="text-red-500 text-xs font-bold block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="border-t border-gray-100 pt-6 flex flex-col md:flex-row items-center justify-between">
                <div class="text-gray-600 mb-4 md:mb-0 text-center md:text-left">
                    <p>You have <strong>{{ count($items) }}</strong> unique items in your order.</p>
                </div>
                
                <button type="submit" class="w-full md:w-auto px-8 py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition flex justify-center items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Confirm Order
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
