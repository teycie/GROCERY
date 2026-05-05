@extends('layouts.app')

@section('title', 'Inventory - FreshMart')

@section('content')
<div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-slate-100">Inventory</h1>
        <p class="mt-2 text-gray-600 dark:text-slate-300">Track stock levels, low inventory items, and product value in one place.</p>
    </div>
    <a href="{{ route('seller.products.create') }}" class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2 font-semibold text-white transition hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600">
        Add Product
    </a>
</div>

<div class="grid gap-6 md:grid-cols-4 mb-8">
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition-colors duration-200 dark:border-slate-700 dark:bg-slate-900">
        <p class="text-sm font-medium text-gray-500 dark:text-slate-300">Total Products</p>
        <p class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-slate-100">{{ $totalProducts }}</p>
    </div>
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition-colors duration-200 dark:border-slate-700 dark:bg-slate-900">
        <p class="text-sm font-medium text-gray-500 dark:text-slate-300">Low Stock</p>
        <p class="mt-2 text-3xl font-extrabold text-amber-600 dark:text-amber-400">{{ $lowStockProducts }}</p>
    </div>
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition-colors duration-200 dark:border-slate-700 dark:bg-slate-900">
        <p class="text-sm font-medium text-gray-500 dark:text-slate-300">Out of Stock</p>
        <p class="mt-2 text-3xl font-extrabold text-red-600 dark:text-red-400">{{ $outOfStockProducts }}</p>
    </div>
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition-colors duration-200 dark:border-slate-700 dark:bg-slate-900">
        <p class="text-sm font-medium text-gray-500 dark:text-slate-300">Inventory Value</p>
        <p class="mt-2 text-3xl font-extrabold text-green-600 dark:text-green-400">₱{{ number_format($totalInventoryValue, 2) }}</p>
    </div>
</div>

<div class="flex flex-col lg:flex-row gap-4">
    <div class="w-full lg:w-[22%]">
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-5 sticky top-24">
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
                <a href="{{ route('seller.inventory.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ !request('category') ? 'bg-green-50 dark:bg-green-500/15 text-green-700 dark:text-green-300 font-medium' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                    <span>{!! $renderCategoryIcon('all') !!}</span>
                    <span>All Products</span>
                </a>

                @foreach($categories as $category)
                    <a href="{{ route('seller.inventory.index', ['category' => $category]) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ ($selectedCategory ?? '') === $category ? 'bg-green-50 dark:bg-green-500/15 text-green-700 dark:text-green-300 font-medium' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                        <span>{!! $renderCategoryIcon($categoryIcons[$category] ?? null) !!}</span>
                        <span>{{ $category }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="w-full lg:w-[78%]">
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
    <div class="border-b border-gray-100 px-6 py-4 dark:border-slate-700">
        <h2 class="text-xl font-bold text-gray-800 dark:text-slate-100">Stock Overview</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
            <thead class="bg-gray-50 dark:bg-slate-800/70">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-300 w-2/5">Product</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-300 w-1/5">Category</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-300 w-1/5">Stock</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-300 w-1/5">Value</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white dark:divide-slate-800 dark:bg-slate-900">
                @forelse($products as $product)
                    @php
                        $stockPercent = min(100, max(0, ($product->stock / 20) * 100));
                        $stockBarClass = $product->stock <= 0 ? 'bg-red-500' : ($product->stock < 5 ? 'bg-amber-500' : 'bg-green-500');
                        $stockBadgeClass = $product->stock <= 0 ? 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300' : ($product->stock < 5 ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' : 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300');
                    @endphp
                    <tr class="transition hover:bg-gray-50 dark:hover:bg-slate-800/60">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gray-100 dark:bg-slate-800">
                                    @if($product->images->count() > 0)
                                        <img class="h-12 w-12 object-cover" src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}">
                                    @else
                                        <svg class="h-6 w-6 text-gray-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-slate-100">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-slate-400">₱{{ number_format($product->price, 2) }} each</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-600 dark:text-slate-300">{{ $product->category }}</td>
                        <td class="px-6 py-5">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $stockBadgeClass }}">{{ $product->stock }} in stock</span>
                                    <span class="text-sm font-semibold text-gray-700 dark:text-slate-200">{{ round($stockPercent) }}%</span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-gray-100 dark:bg-slate-700">
                                    <div class="h-2 rounded-full {{ $stockBarClass }}" style="width: {{ $stockPercent }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-sm font-semibold text-green-600 dark:text-green-400">₱{{ number_format($product->stock * $product->price, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center text-gray-500 dark:text-slate-300">
                            <p class="text-lg font-semibold text-gray-800 dark:text-slate-100">No products found</p>
                            <p class="mt-1">Add products first so inventory can be tracked here.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div class="border-t border-gray-100 px-6 py-4 dark:border-slate-700">
            {{ $products->links() }}
        </div>
    @endif
    </div>
</div>
@endsection