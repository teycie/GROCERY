@extends('layouts.app')

@section('title', 'Checkout - FreshMart')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-8">
        <a href="{{ route('cart.index') }}" class="text-green-600 dark:text-green-400 font-semibold hover:text-green-700 dark:hover:text-green-300 flex items-center transition mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Cart
        </a>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-slate-100">Checkout</h1>
        <p class="text-sm sm:text-base text-gray-500 dark:text-slate-400 mt-2 max-w-2xl">Complete the form below to confirm your delivery details, choose how you want to receive the order, and review everything before submitting.</p>
    </div>

    <!-- Main Checkout Form -->
    <form action="{{ route('cart.checkout') }}" method="POST" class="bg-white dark:bg-[#0f1726] rounded-2xl shadow-[0_12px_40px_rgba(15,23,42,0.08)] dark:shadow-none border border-gray-100 dark:border-slate-800 overflow-hidden ring-1 ring-black/5 dark:ring-white/5">
        @csrf
        <div class="p-5 sm:p-6 border-b border-gray-100 dark:border-slate-800 bg-gradient-to-r from-green-50 to-white dark:from-[#111a2b] dark:to-[#0f1726]">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-slate-100">Customer Details</h2>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-slate-400 mt-1">Please provide your delivery/contact information.</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-500/15 px-3 py-1 text-xs font-semibold text-green-700 dark:text-green-300">Required fields</span>
            </div>

            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                <div>
                    <label for="first_name" class="block text-xs sm:text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-slate-200 mb-2">First Name</label>
                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        value="{{ old('first_name', auth()->user()->first_name) }}"
                        placeholder="Enter first name"
                        class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-[#18243a] text-gray-900 dark:text-slate-100 shadow-sm focus:border-green-500 focus:ring-green-500 py-3 px-4 text-sm"
                        required
                    >
                    @error('first_name')
                        <span class="text-red-500 text-xs font-bold block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-xs sm:text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-slate-200 mb-2">Last Name</label>
                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        value="{{ old('last_name', auth()->user()->last_name) }}"
                        placeholder="Enter last name"
                        class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-[#18243a] text-gray-900 dark:text-slate-100 shadow-sm focus:border-green-500 focus:ring-green-500 py-3 px-4 text-sm"
                        required
                    >
                    @error('last_name')
                        <span class="text-red-500 text-xs font-bold block mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <label for="address" class="block text-xs sm:text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-slate-200 mb-2">Address</label>
                <textarea
                    id="address"
                    name="address"
                    rows="3"
                    placeholder="House number, street, barangay, city, landmark, etc."
                    class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-[#18243a] text-gray-900 dark:text-slate-100 shadow-sm focus:border-green-500 focus:ring-green-500 py-3 px-4 text-sm"
                    required
                >{{ old('address', auth()->user()->address) }}</textarea>
                @error('address')
                    <span class="text-red-500 text-xs font-bold block mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mt-6 border-t border-gray-100 dark:border-slate-800 pt-5">
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-slate-100">Fulfillment & Payment</h3>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-slate-400 mt-1">Choose how you want to receive your order and pay.</p>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                    <div>
                        <label for="fulfillment_type" class="block text-xs sm:text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-slate-200 mb-2">Fulfillment Type</label>
                        <select
                            id="fulfillment_type"
                            name="fulfillment_type"
                            class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-[#18243a] text-gray-900 dark:text-slate-100 shadow-sm focus:border-green-500 focus:ring-green-500 py-3 px-4 text-sm"
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
                        <label for="payment_mode" class="block text-xs sm:text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-slate-200 mb-2">Payment Method</label>
                        <select
                            id="payment_mode"
                            name="payment_mode"
                            class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-[#18243a] text-gray-900 dark:text-slate-100 shadow-sm focus:border-green-500 focus:ring-green-500 py-3 px-4 text-sm"
                            required
                        >
                            <option value="">Select payment method</option>
                            <option value="cod" {{ old('payment_mode') === 'cod' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
                            <option value="cash" {{ old('payment_mode') === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="ewallet" {{ old('payment_mode') === 'ewallet' ? 'selected' : '' }}>E-Wallet (on site)</option>
                        </select>
                        @error('payment_mode')
                            <span class="text-red-500 text-xs font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-4">
                    <label for="buyer_notes" class="block text-xs sm:text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-slate-200 mb-2">Order Notes (Optional)</label>
                    <textarea
                        id="buyer_notes"
                        name="buyer_notes"
                        rows="2"
                        placeholder="e.g. Please handle with care. / Call me before delivery. / Leave at the gate."
                        class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-[#18243a] text-gray-900 dark:text-slate-100 shadow-sm focus:border-green-500 focus:ring-green-500 py-3 px-4 text-sm"
                    >{{ old('buyer_notes') }}</textarea>
                    @error('buyer_notes')
                        <span class="text-red-500 text-xs font-bold block mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="p-5 sm:p-6 bg-gray-50/80 dark:bg-[#111a2b] border-b border-gray-100 dark:border-slate-800">
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-slate-100">Order Summary</h2>
            </div>
        </div>

        <div class="p-5 sm:p-6">
            <ul class="divide-y divide-gray-100 dark:divide-slate-800 mb-8 rounded-xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-[#0f1726] overflow-hidden">
                @foreach($items as $item)
                    <li class="py-4 sm:py-5 px-4 sm:px-5 flex flex-col sm:flex-row items-start sm:items-center">
                        <div class="flex items-center flex-1 w-full">
                            <div class="h-16 w-16 sm:h-20 sm:w-20 flex-shrink-0 overflow-hidden rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-[#18243a] shadow-sm">
                                @if(!empty($item['image_path']))
                                    <img src="{{ asset('storage/' . $item['image_path']) }}" alt="{{ $item['product']->name }}" class="h-full w-full object-cover object-center">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-gray-300 dark:text-slate-500">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-slate-100">{{ $item['product']->name }}</h3>
                                <p class="text-gray-500 dark:text-slate-400 text-xs sm:text-sm mt-1">In Cart: {{ $item['quantity'] }}</p>
                                <input type="hidden" name="selected_items[]" value="{{ $item['product_id'] }}">
                            </div>
                        </div>

                        <!-- Quantity Control -->
                        <div class="mt-3 sm:mt-0 sm:ml-6 flex items-center sm:block w-full sm:w-auto justify-between bg-gray-50 dark:bg-[#111a2b] sm:bg-transparent p-3 sm:p-0 rounded-xl border border-gray-100 dark:border-slate-800 sm:border-0">
                            <label for="qty_{{ $item['product_id'] }}" class="block text-xs sm:text-sm font-bold text-gray-700 dark:text-slate-200 sm:mb-2">Order Quantity</label>
                            <input
                                type="number"
                                id="qty_{{ $item['product_id'] }}"
                                name="quantities[{{ $item['product_id'] }}]"
                                min="1"
                                max="{{ $item['quantity'] }}"
                                value="{{ old('quantities.' . $item['product_id'], $item['quantity']) }}"
                                class="mt-1 block w-full sm:w-28 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-[#18243a] text-gray-900 dark:text-slate-100 shadow-sm focus:border-green-500 focus:ring-green-500 font-bold py-3 px-3 text-center sm:text-left text-sm"
                            >
                            @error('quantities.' . $item['product_id'])
                                <span class="text-red-500 text-xs font-bold block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="border-t border-gray-100 dark:border-slate-800 pt-5 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-gray-600 dark:text-slate-400 text-center md:text-left">
                    <p class="text-xs sm:text-sm mt-1">Double-check the details above before clicking confirm.</p>
                </div>
                
                <button type="submit" class="w-full md:w-auto px-7 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition flex justify-center items-center text-sm">
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
    const paymentHelp = document.getElementById('payment-help');

    if (!fulfillmentSelect || !paymentSelect) {
        return;
    }

    const codOption = paymentSelect.querySelector('option[value="cod"]');
    const cashOption = paymentSelect.querySelector('option[value="cash"]');
    const ewalletOption = paymentSelect.querySelector('option[value="ewallet"]');

    function syncPaymentOptions() {
        if (!codOption || !cashOption || !ewalletOption) {
            return;
        }

        const isDelivery = fulfillmentSelect.value === 'delivery';
        const isPickup = fulfillmentSelect.value === 'pickup';

        codOption.hidden = isPickup;
        codOption.disabled = isPickup;

        cashOption.hidden = isDelivery;
        cashOption.disabled = isDelivery;

        ewalletOption.hidden = isDelivery;
        ewalletOption.disabled = isDelivery;

        if (paymentHelp) {
            paymentHelp.textContent = isDelivery
                ? 'Delivery shows COD only.'
        }

        if (isDelivery) {
            paymentSelect.value = 'cod';
            return;
        }

        if (isPickup && paymentSelect.value === 'cod') {
            paymentSelect.value = 'cash';
        }
    }

    fulfillmentSelect.addEventListener('change', syncPaymentOptions);
    syncPaymentOptions();
});
</script>
@endsection
