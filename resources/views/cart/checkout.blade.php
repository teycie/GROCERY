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
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-800">Customer Details</h2>
            <p class="text-sm text-gray-500 mt-1">Please provide your delivery/contact information.</p>

            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-2">First Name</label>
                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        value="{{ old('first_name', auth()->user()->first_name) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                        required
                    >
                    @error('first_name')
                        <span class="text-red-500 text-xs font-bold block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-2">Last Name</label>
                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        value="{{ old('last_name', auth()->user()->last_name) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                        required
                    >
                    @error('last_name')
                        <span class="text-red-500 text-xs font-bold block mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                <textarea
                    id="address"
                    name="address"
                    rows="3"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                    required
                >{{ old('address', auth()->user()->address) }}</textarea>
                @error('address')
                    <span class="text-red-500 text-xs font-bold block mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mt-6 border-t border-gray-100 pt-6">
                <h3 class="text-lg font-bold text-gray-800">Fulfillment & Payment</h3>
                <p class="text-sm text-gray-500 mt-1">Choose how you want to receive your order and pay.</p>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="fulfillment_type" class="block text-sm font-semibold text-gray-700 mb-2">Fulfillment Type</label>
                        <select
                            id="fulfillment_type"
                            name="fulfillment_type"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            required
                        >
                            <option value="">Select option</option>
                            <option value="delivery" {{ old('fulfillment_type') === 'delivery' ? 'selected' : '' }}>Delivery</option>
                            <option value="pickup" {{ old('fulfillment_type') === 'pickup' ? 'selected' : '' }}>Pick-up</option>
                        </select>
                        @error('fulfillment_type')
                            <span class="text-red-500 text-xs font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_mode" class="block text-sm font-semibold text-gray-700 mb-2">Payment Method</label>
                        <select
                            id="payment_mode"
                            name="payment_mode"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            required
                        >
                            <option value="">Select payment method</option>
                            <option value="cod" {{ old('payment_mode') === 'cod' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
                            <option value="cash" {{ old('payment_mode') === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="ewallet" {{ old('payment_mode') === 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                        </select>
                        @error('payment_mode')
                            <span class="text-red-500 text-xs font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

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

<script>
document.addEventListener('DOMContentLoaded', () => {
    const fulfillmentSelect = document.getElementById('fulfillment_type');
    const paymentSelect = document.getElementById('payment_mode');

    if (!fulfillmentSelect || !paymentSelect) {
        return;
    }

    const codOption = paymentSelect.querySelector('option[value="cod"]');

    function syncPaymentOptions() {
        if (!codOption) {
            return;
        }

        const isPickup = fulfillmentSelect.value === 'pickup';
        codOption.disabled = isPickup;

        if (isPickup && paymentSelect.value === 'cod') {
            paymentSelect.value = '';
        }
    }

    fulfillmentSelect.addEventListener('change', syncPaymentOptions);
    syncPaymentOptions();
});
</script>
@endsection
