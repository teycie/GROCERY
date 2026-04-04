@extends('layouts.app')

@section('title', 'My Cart - FreshMart')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex flex-col md:flex-row items-center justify-between">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-slate-100">Your Shopping Cart</h1>
        <a href="{{ route('products.index') }}" class="mt-4 md:mt-0 text-green-600 dark:text-green-400 font-semibold hover:text-green-700 dark:hover:text-green-300 flex items-center transition">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Continue Shopping
        </a>
    </div>

    @php
        $selectedItemIds = old('selected_items', collect($items)->pluck('product_id')->all());
    @endphp

    @if(empty($items))
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-12 text-center">
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-gray-50 dark:bg-slate-800 mb-6 text-gray-400 dark:text-slate-500">
                <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-slate-100 mb-2">Cart is empty</h2>
            <p class="text-gray-500 dark:text-slate-300 mb-6 text-lg">Looks like you haven't added anything yet.</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-base font-bold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                Start Shopping Now
            </a>
        </div>
    @else
        <form action="{{ route('cart.checkout.page') }}" method="GET" id="checkoutForm" class="flex flex-col lg:flex-row gap-8">

            <!-- Cart Items -->
            <div class="w-full lg:w-2/3">
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between bg-gray-50/50 dark:bg-slate-800/70">
                        <label class="flex items-center space-x-3 cursor-pointer group">
                            <input type="checkbox" id="selectAll" class="form-checkbox h-5 w-5 text-green-600 rounded border-gray-300 focus:ring-green-500 transition" checked>
                            <span class="text-sm font-bold text-gray-700 dark:text-slate-200 group-hover:text-gray-900 dark:group-hover:text-white transition">Select All/None</span>
                        </label>
                    </div>
                    
                    <ul class="divide-y divide-gray-100 dark:divide-slate-800">
                        @foreach($items as $item)
                            @php
                                $isChecked = in_array($item['product_id'], $selectedItemIds);
                                $unitPrice = $item['quantity'] > 0 ? $item['line_total'] / $item['quantity'] : 0;
                            @endphp
                            <li class="p-6 hover:bg-gray-50/50 dark:hover:bg-slate-800/60 transition duration-150">
                                <div class="flex items-center">
                                    <!-- Checkbox -->
                                    <div class="mr-4">
                                        <input type="checkbox" class="js-item-checkbox form-checkbox h-5 w-5 text-green-600 rounded border-gray-300 focus:ring-green-500 transition" name="selected_items[]" value="{{ $item['product_id'] }}" {{ $isChecked ? 'checked' : '' }} data-price="{{ $item['line_total'] }}">
                                    </div>
                                    <!-- Image -->
                                    <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900">
                                        @if($item['image_path'])
                                            <img src="{{ asset('storage/' . $item['image_path']) }}" alt="{{ $item['product']->name }}" class="h-full w-full object-cover object-center">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-gray-300 dark:text-slate-500">
                                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-6 flex flex-1 flex-col">
                                        <!-- Product Top/Name -->
                                        <div>
                                            <div class="flex justify-between text-base font-bold text-gray-900 dark:text-slate-100">
                                                <h3><a href="{{ route('products.show', $item['product']) }}" class="hover:text-green-600 dark:hover:text-green-400 transition">{{ $item['product']->name }}</a></h3>
                                                <p class="ml-4 tabular-nums">{{ number_format($item['line_total'], 2) }}</p>
                                            </div>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-slate-300">{{ number_format($unitPrice, 2) }} each</p>
                                        </div>
                                        <!-- Quantity Form Actions -->
                                        <div class="flex flex-1 items-end justify-between text-sm mt-4">
                                            <p class="text-gray-500 dark:text-slate-300 bg-gray-100 dark:bg-slate-800 px-3 py-1 rounded-full font-medium">Qty: {{ $item['quantity'] }}</p>

                                            <div class="flex">
                                                <button type="button" onclick="document.getElementById('remove-form-{{ $item['product_id'] }}').submit()" class="font-bold text-red-500 dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-500/10 px-3 py-1 rounded-md transition flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Sticky Summary Sidebar -->
            <div class="w-full lg:w-1/3">
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6 sticky top-24">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-slate-100 flex items-center border-b border-gray-200 dark:border-slate-800 pb-4 mb-4">
                        <svg class="h-5 w-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Order Summary
                    </h2>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center justify-between text-gray-600 dark:text-slate-300">
                            <p>Items checked</p>
                            <p class="font-medium" id="checkedCount">0</p>
                        </div>
                        <div class="flex justify-between font-bold text-lg border-t border-gray-200 dark:border-slate-800 pt-4">
                            <p>Total Estimated</p>
                            <p class="tabular-nums text-green-600 dark:text-green-400" id="totalPreview">₱0.00</p>
                        </div>
                    </div>

                    <button type="submit" id="mainCheckoutBtn" class="w-full bg-green-600 border border-transparent rounded-lg shadow-sm py-4 px-4 text-base font-bold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition mb-3 flex justify-center items-center">
                        Proceed to Checkout
                    </button>

                    <button type="button" onclick="document.getElementById('remove-selected-form').submit()" class="w-full bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-300 border border-red-100 dark:border-red-500/20 rounded-lg py-3 px-4 text-sm font-bold hover:bg-red-100 dark:hover:bg-red-500/20 transition focus:outline-none focus:ring-2 focus:ring-red-500 flex justify-center items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Remove Selected Items
                    </button>
                    <p class="text-xs text-gray-400 dark:text-slate-400 mt-4 text-center">Shipping & taxes calculated at checkout.</p>
                </div>
            </div>
        </form>

        <!-- Hidden Forms -->
        @foreach($items as $item)
            <form action="{{ route('cart.remove', $item['product_id']) }}" method="POST" id="remove-form-{{ $item['product_id'] }}" class="hidden">
                @csrf
            </form>
        @endforeach

        <form action="{{ route('cart.remove-selected') }}" method="POST" id="remove-selected-form" class="hidden">
            @csrf
            @foreach($items as $item)
                <input type="checkbox" name="selected_items[]" value="{{ $item['product_id'] }}" class="hidden replica-checkbox js-replica-checkbox-{{ $item['product_id'] }}">
            @endforeach
        </form>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const masterSelectAll = document.getElementById('selectAll');
    const masterCheckboxes = document.querySelectorAll('.js-item-checkbox');
    const replicaCheckboxes = document.querySelectorAll('.replica-checkbox');
    
    const checkedCountEl = document.getElementById('checkedCount');
    const totalPreviewEl = document.getElementById('totalPreview');
    const checkoutBtn = document.getElementById('mainCheckoutBtn');

    function calculateTotal() {
        let currentTotal = 0;
        let count = 0;
        masterCheckboxes.forEach(cb => {
            if(cb.checked) {
                currentTotal += parseFloat(cb.dataset.price || 0);
                count++;
            }
            // Sync replicas
            const rep = document.querySelector('.js-replica-checkbox-' + cb.value);
            if(rep) rep.checked = cb.checked;
        });

        checkedCountEl.innerText = count;
        totalPreviewEl.innerText = '₱' + currentTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        
        checkoutBtn.disabled = count === 0;
        if(count === 0) {
            checkoutBtn.classList.replace('bg-green-600', 'bg-gray-300');
            checkoutBtn.classList.replace('hover:bg-green-700', 'hover:bg-gray-400');
        } else {
            checkoutBtn.classList.replace('bg-gray-300', 'bg-green-600');
            checkoutBtn.classList.replace('hover:bg-gray-400', 'hover:bg-green-700');
        }
    }

    if(masterSelectAll) {
        masterSelectAll.addEventListener('change', (e) => {
            let isChecked = e.target.checked;
            masterCheckboxes.forEach(cb => cb.checked = isChecked);
            calculateTotal();
        });
    }

    masterCheckboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            calculateTotal();
            if(masterSelectAll) {
                masterSelectAll.checked = Array.from(masterCheckboxes).every(curr => curr.checked);
            }
        });
    });

    // Sub form prevention if no items checked
    const checkoutForm = document.getElementById('checkoutForm');
    if(checkoutForm) {
        checkoutForm.addEventListener('submit', (e) => {
            const anyChecked = Array.from(masterCheckboxes).some(cb => cb.checked);
            if(!anyChecked) {
                e.preventDefault();
                alert('Please select at least one item to checkout.');
            }
        });
    }

    calculateTotal();
});
</script>
@endsection
