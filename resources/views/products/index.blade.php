@extends('layouts.app')

@section('title', 'All Products - FreshMart')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-slate-100 mb-2">Our Products</h1>
        <p class="text-gray-600 dark:text-slate-300">Fresh groceries delivered straight to your door.</p>
    </div>
</div>

<div class="flex flex-col lg:flex-row gap-8">
    <!-- Filters Sidebar -->
    <div class="w-full lg:w-1/4">
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6 sticky top-24">
            <h2 class="text-lg font-bold text-gray-800 dark:text-slate-100 mb-4 border-b border-gray-200 dark:border-slate-800 pb-2">Categories</h2>
            @php
                $categoryIcons = [
                    'Frozen' => 'frozen',
                    'Beverage' => 'beverage',
                    'Snacks' => 'snacks',
                    'Fruits & Vegetables' => 'produce',
                    'Pet Care' => 'pet',
                    'Household Cleaning & Essentials' => 'household',
                ];

                $renderCategoryIcon = function ($icon) {
                    switch ($icon) {
                        case 'all':
                            return '<svg class="w-4 h-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>';
                        case 'frozen':
                            return '<svg class="w-4 h-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20m0-20l3 3m-3-3L9 5m3 17l3-3m-3 3l-3-3M2 12h20m-20 0l3-3m-3 3l3 3m17-3l-3-3m3 3l-3 3" /></svg>';
                        case 'beverage':
                            return '<svg class="w-4 h-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4h8l-1 14a2 2 0 01-2 2h-2a2 2 0 01-2-2L8 4zm0 0V2h8v2" /></svg>';
                        case 'snacks':
                            return '<svg class="w-4 h-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4h10l2 5-2 11H7L5 9l2-5z" /></svg>';
                        case 'produce':
                            return '<svg class="w-4 h-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21c4.418 0 8-3.582 8-8 0-5-4-9-8-9s-8 4-8 9c0 4.418 3.582 8 8 8zm0-17c.5-1.5 1.5-2.5 3-3" /></svg>';
                        case 'pet':
                            return '<svg class="w-4 h-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11a2 2 0 100-4 2 2 0 000 4zm10 0a2 2 0 100-4 2 2 0 000 4zM5 9a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm14 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM12 20c3 0 6-1.5 6-4 0-2-1.5-3.5-3.5-3.5-.9 0-1.8.3-2.5.9a3.7 3.7 0 00-2.5-.9C7.5 12.5 6 14 6 16c0 2.5 3 4 6 4z" /></svg>';
                        case 'household':
                            return '<svg class="w-4 h-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 11l9-8 9 8M5 10v10h14V10" /></svg>';
                        default:
                            return '<svg class="w-4 h-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8h.01M12 12v4m9-4a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                    }
                };
            @endphp
            <div class="space-y-2">
                <a href="{{ route('products.index') }}" class="block px-3 py-2 rounded-md transition {{ !request('category') ? 'bg-green-50 dark:bg-green-500/15 text-green-700 dark:text-green-300 font-medium' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                    <span class="mr-2">{!! $renderCategoryIcon('all') !!}</span> All Products
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category]) }}" class="block px-3 py-2 rounded-md transition {{ request('category') === $category ? 'bg-green-50 dark:bg-green-500/15 text-green-700 dark:text-green-300 font-medium' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                        <span class="mr-2">{!! $renderCategoryIcon($categoryIcons[$category] ?? null) !!}</span> {{ $category }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="w-full lg:w-3/4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($products as $product)
                <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col">
                    <a href="{{ route('products.show', $product) }}" class="relative h-48 bg-gray-100 dark:bg-slate-800 group block overflow-hidden">
                        @if($product->images->count() > 0)
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-slate-500 group-hover:scale-105 transition duration-300">
                                <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        <span class="absolute top-2 right-2 bg-white/90 dark:bg-slate-900/90 backdrop-blur text-xs font-bold px-2 py-1 rounded text-gray-700 dark:text-slate-200 shadow-sm">
                            {!! $categoryIcons[$product->category] ?? '' !!} {{ $product->category }}
                        </span>
                    </a>
                    
                    <div class="p-4 flex-grow flex flex-col justify-between">
                        <div class="mb-3">
                            <a href="{{ route('products.show', $product) }}" class="block">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100 mb-1 hover:text-green-600 dark:hover:text-green-400 transition truncate">{{ $product->name }}</h3>
                            </a>
                            <span class="text-xl font-extrabold text-green-600 dark:text-green-400">&#8369;{{ number_format($product->price, 2) }}</span>
                        </div>
                        
                        <div class="flex gap-2 mt-auto">
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1 add-to-cart-form" data-product-name="{{ $product->name }}">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="button" class="w-full bg-green-50 dark:bg-green-500/15 text-green-700 dark:text-green-300 hover:bg-green-100 dark:hover:bg-green-500/25 py-2 px-3 rounded-md text-sm font-semibold transition flex justify-center items-center add-to-cart-btn">
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
                <div class="col-span-full py-12 text-center bg-gray-50 dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 p-8">
                    <span class="text-4xl block mb-4">??</span>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-slate-100 mb-2">No Products Found</h3>
                    <p class="text-gray-500 dark:text-slate-300">We couldn't find any products matching your current filters.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-8">
            {{ $products->withQueryString()->links() }}
        </div>
    </div>
</div>

<div id="add-to-cart-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-sm rounded-xl bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 p-6 shadow-xl">
        <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100">Choose quantity</h3>
        <p id="add-to-cart-product-name" class="mt-1 text-sm text-gray-600 dark:text-slate-300"></p>

        <label for="modal-quantity" class="mt-4 block text-sm font-medium text-gray-700 dark:text-slate-200">How many items?</label>
        <input id="modal-quantity" type="number" min="1" value="1" class="mt-2 w-full rounded-md border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 px-3 py-2 focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500">

        <div class="mt-5 flex justify-end gap-2">
            <button id="add-to-cart-cancel" type="button" class="rounded-md border border-gray-300 dark:border-slate-600 px-4 py-2 text-sm font-medium text-gray-700 dark:text-slate-200 hover:bg-gray-50 dark:hover:bg-slate-800">Cancel</button>
            <button id="add-to-cart-confirm" type="button" class="rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">Add to Cart</button>
        </div>
    </div>
</div>

<script>
    (function () {
        var modal = document.getElementById('add-to-cart-modal');
        var productNameEl = document.getElementById('add-to-cart-product-name');
        var quantityInput = document.getElementById('modal-quantity');
        var confirmBtn = document.getElementById('add-to-cart-confirm');
        var cancelBtn = document.getElementById('add-to-cart-cancel');
        var currentForm = null;

        if (!modal || !quantityInput || !confirmBtn || !cancelBtn) {
            return;
        }

        function openModal(form) {
            currentForm = form;
            var productName = form.getAttribute('data-product-name') || 'this product';
            productNameEl.textContent = 'Product: ' + productName;
            quantityInput.value = '1';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            quantityInput.focus();
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            currentForm = null;
        }

        document.querySelectorAll('.add-to-cart-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var form = btn.closest('form');
                if (form) {
                    openModal(form);
                }
            });
        });

        cancelBtn.addEventListener('click', closeModal);

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
            }
        });

        confirmBtn.addEventListener('click', function () {
            if (!currentForm) {
                return;
            }

            var quantity = parseInt(quantityInput.value, 10);
            if (Number.isNaN(quantity) || quantity < 1) {
                quantity = 1;
            }

            currentForm.querySelector('input[name="quantity"]').value = quantity;
            currentForm.submit();
        });
    })();
</script>
@endsection
