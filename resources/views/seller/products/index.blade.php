@extends('layouts.app')

@section('title', 'Manage Products - FreshMart')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-slate-100 mb-2">Manage Products</h1>
        <p class="text-gray-600 dark:text-slate-300">View, edit, or delete your current store inventory.</p>
    </div>
    <a href="{{ route('seller.products.create') }}" class="mt-4 md:mt-0 flex items-center bg-green-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-green-700 transition shadow-sm dark:shadow-none">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Add New Product
    </a>
</div>

<div class="mb-1 rounded-xl bg-white dark:bg-slate-900 shadow-sm border border-gray-100 dark:border-slate-800 p-4">
    <form action="{{ route('seller.products.index') }}" method="GET" class="flex flex-col lg:flex-row gap-3 lg:items-center">
        @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
        @endif
        <div class="flex-1">
            <label for="search" class="block text-sm font-semibold text-gray-700 dark:text-slate-200 mb-2">Search Products</label>
            <input
                id="search"
                name="search"
                type="search"
                value="{{ $search ?? '' }}"
                placeholder="Search by product name or category"
                class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-[#18243a] text-gray-900 dark:text-slate-100 px-4 py-3 text-sm shadow-sm focus:border-green-500 focus:ring-green-500"
            >
        </div>
        <div class="flex gap-3 pt-1 lg:pt-7">
            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-green-700">
                Search
            </button>
            @if(!empty($search) || request('category'))
                <a href="{{ route('seller.products.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200 transition hover:bg-gray-50 dark:hover:bg-[#111a2b]">
                    Clear
                </a>
            @endif
        </div>
    </form>
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
                <a href="{{ route('seller.products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ !request('category') ? 'bg-green-50 dark:bg-green-500/15 text-green-700 dark:text-green-300 font-medium' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                    <span>{!! $renderCategoryIcon('all') !!}</span>
                    <span>All Products</span>
                </a>

                @foreach($categories as $category)
                    <a href="{{ route('seller.products.index', ['category' => $category]) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ ($selectedCategory ?? '') === $category ? 'bg-green-50 dark:bg-green-500/15 text-green-700 dark:text-green-300 font-medium' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                        <span>{!! $renderCategoryIcon($categoryIcons[$category] ?? null) !!}</span>
                        <span>{{ $category }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="w-full lg:w-[78%]">
        <div class="bg-white dark:bg-[#0f1726] rounded-xl shadow-sm dark:shadow-none border border-gray-100 dark:border-slate-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-800">
                    <thead class="bg-gray-50 dark:bg-[#111a2b]">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-slate-300 uppercase tracking-wider">Product</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-slate-300 uppercase tracking-wider">Category</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-slate-300 uppercase tracking-wider">Price</th>
                            <th scope="col" class="px-6 py-4 pr-2 text-left text-xs font-semibold text-gray-500 dark:text-slate-300 uppercase tracking-wider">Stock</th>
                            <th scope="col" class="px-6 py-4 pl-2 pr-4 text-right text-xs font-semibold text-gray-500 dark:text-slate-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-[#0f1726] divide-y divide-gray-200 dark:divide-slate-800">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-[#111a2b] transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 bg-gray-100 dark:bg-[#18243a] rounded-md overflow-hidden">
                                            @if($product->images->count() > 0)
                                                <img class="h-10 w-10 object-cover" src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="">
                                            @else
                                                <svg class="h-10 w-10 text-gray-400 dark:text-slate-500 p-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-slate-100">{{ $product->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-slate-300">
                                    {{ $product->category }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600 dark:text-green-400">
                                    ₱{{ number_format($product->price, 2) }}
                                </td>
                                <td class="px-6 py-4 pr-2 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->stock > 10 ? 'bg-green-100 text-green-800' : ($product->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $product->stock }} in stock
                                    </span>
                                </td>
                                <td class="px-6 py-4 pl-2 pr-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('seller.products.edit', $product) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded transition">Edit</a>
                                        <form action="{{ route('seller.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded transition">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-slate-300 bg-white dark:bg-[#0f1726]">
                                    <span class="block text-4xl mb-3">??</span>
                                    <p class="text-lg font-medium text-gray-800 dark:text-slate-100">Your store has no products</p>
                                    <p class="mb-4">Start by adding your first product inventory.</p>
                                    <a href="{{ route('seller.products.create') }}" class="inline-flex items-center text-green-600 hover:text-green-700 font-semibold font-medium">Add Product &rarr;</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800 bg-white dark:bg-[#0f1726]">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
