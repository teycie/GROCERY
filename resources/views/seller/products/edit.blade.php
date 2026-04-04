@extends('layouts.app')

@section('title', 'Edit Product - FreshMart')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('seller.products.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium mb-1 inline-block">&larr; Back to Products</a>
            <h1 class="text-3xl font-extrabold text-gray-900">Edit Product</h1>
        </div>
    </div>

    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('seller.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-3 px-4 transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                <select name="category" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-3 px-4 bg-white transition">
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ old('category', $product->category) === $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Price (₱) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" min="0" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-3 px-4 transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Stock Quantity <span class="text-red-500">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-3 px-4 transition">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-3 px-4 transition">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="border-t border-gray-100 pt-6">
                <label class="block text-sm font-bold text-gray-700 mb-1">Upload More Images</label>
                <input type="file" name="images[]" accept="image/*" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 mb-4 transition">
                
                @if($product->images->count() > 0)
                    <div class="bg-gray-50 p-4 rounded-lg mt-4">
                        <p class="text-sm font-bold text-gray-700 mb-3">Current Images ({{ $product->images->count() }})</p>
                        <div class="flex gap-4 overflow-x-auto pb-2">
                            @foreach($product->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product image" class="h-24 w-24 object-cover rounded-md border border-gray-200 shadow-sm flex-shrink-0">
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Uploading new images will add them alongside these existing images.</p>
                    </div>
                @endif
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('seller.products.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg font-bold hover:bg-gray-50 transition shadow-sm">Cancel</a>
                <button type="submit" class="px-6 py-3 border border-transparent bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition shadow-md shadow-blue-600/30">Update Product</button>
            </div>
        </form>
    </div>
</div>
@endsection
