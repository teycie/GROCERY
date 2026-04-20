@extends('layouts.app')

@section('title', 'Manage Products - FreshMart')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Manage Products</h1>
        <p class="text-gray-600">View, edit, or delete your current store inventory.</p>
    </div>
    <a href="{{ route('seller.products.create') }}" class="mt-4 md:mt-0 flex items-center bg-green-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-green-700 transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Add New Product
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/70">
        <form method="GET" action="{{ route('seller.products.index') }}" class="flex flex-col md:flex-row md:items-center gap-3">
            <div class="flex-1">
                <label for="category" class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">Filter by Category</label>
                <select id="category" name="category" onchange="this.form.submit()" class="w-full md:w-80 rounded-md border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">All Categories ({{ array_sum($categoryCounts ?? []) }})</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ ($selectedCategory ?? '') === $category ? 'selected' : '' }}>
                            {{ $category }} ({{ $categoryCounts[$category] ?? 0 }})
                        </option>
                    @endforeach
                </select>
            </div>

            @if(!empty($selectedCategory))
                <a href="{{ route('seller.products.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition">
                    Clear Filter
                </a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Posted By</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 bg-gray-100 rounded-md overflow-hidden">
                                    @if($product->images->count() > 0)
                                        <img class="h-10 w-10 object-cover" src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="">
                                    @else
                                        <svg class="h-10 w-10 text-gray-400 p-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $product->user->name ?? 'Unknown Seller' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $product->category }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                            ₱{{ number_format($product->price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->stock > 10 ? 'bg-green-100 text-green-800' : ($product->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $product->stock }} in stock
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
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
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <span class="block text-4xl mb-3">??</span>
                            <p class="text-lg font-medium text-gray-800">Your store has no products</p>
                            <p class="mb-4">Start by adding your first product inventory.</p>
                            <a href="{{ route('seller.products.create') }}" class="inline-flex items-center text-green-600 hover:text-green-700 font-semibold font-medium">Add Product &rarr;</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
