@extends('layouts.app')

@section('title', 'Add Product - FreshMart')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('seller.products.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium mb-1 inline-block">&larr; Back to Products</a>
            <h1 class="text-3xl font-extrabold text-gray-900">Add New Product</h1>
        </div>
    </div>

    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-3 px-4 transition">
            </div>

            <div>
                <label for="category" class="block text-sm font-bold text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                <select id="category" name="category" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-3 px-4 bg-white transition">
                    <option value="" disabled selected>Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="price" class="block text-sm font-bold text-gray-700 mb-1">Price (?) <span class="text-red-500">*</span></label>
                    <input type="number" id="price" step="0.01" name="price" value="{{ old('price') }}" min="0" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-3 px-4 transition" placeholder="0.00">
                </div>
                <div>
                    <label for="stock" class="block text-sm font-bold text-gray-700 mb-1">Stock Quantity <span class="text-red-500">*</span></label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-3 px-4 transition">
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-bold text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea id="description" name="description" rows="5" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-3 px-4 transition">{{ old('description') }}</textarea>
            </div>

            <div class="border-t border-gray-100 pt-6">
                <label class="block text-sm font-bold text-gray-700 mb-1">Product Images</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-400 transition bg-gray-50 group">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-green-500 transition" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500 px-1 py-0.5">
                                <span>Upload files</span>
                                <input id="file-upload" name="images[]" type="file" accept="image/*" multiple class="sr-only">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">PNG, JPG up to 2MB (Max 5 images)</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('seller.products.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg font-bold hover:bg-gray-50 transition shadow-sm">Cancel</a>
                <button type="submit" class="px-6 py-3 border border-transparent bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition shadow-md shadow-green-600/30">Save Product</button>
            </div>
        </form>
    </div>
</div>
@endsection
