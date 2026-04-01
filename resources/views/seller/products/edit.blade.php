@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="card">
    <h1>Edit Product</h1>

    <form action="{{ route('seller.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="form-grid">
        @csrf
        @method('PUT')

        <label>Name</label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}" required>

        <label>Description</label>
        <textarea name="description" rows="4">{{ old('description', $product->description) }}</textarea>

        <label>Price</label>
        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required>

        <label>Stock</label>
        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required>

        <label>Category</label>
        <select name="category" required>
            @foreach($categories as $category)
                <option value="{{ $category }}" {{ old('category', $product->category) === $category ? 'selected' : '' }}>{{ $category }}</option>
            @endforeach
        </select>

        <label>Images (up to 5 files, will be added to existing images)</label>
        <input type="file" name="images[]" accept="image/*" multiple>
        
        @if($product->images->count() > 0)
            <div class="current-images">
                <p style="font-weight: 600; margin-bottom: 8px;">Current Images ({{ $product->images->count() }}):</p>
                <div class="image-grid">
                    @foreach($product->images as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product image" class="thumbnail-image">
                    @endforeach
                </div>
                <p class="small-text">Uploading new images will add them to your existing images.</p>
            </div>
        @endif

        <button type="submit" class="btn">Update Product</button>
    </form>
</div>
@endsection
