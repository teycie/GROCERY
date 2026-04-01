@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="card">
    <h1>Add Product</h1>

    <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" class="form-grid">
        @csrf

        <label>Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required>

        <label>Description</label>
        <textarea name="description" rows="4">{{ old('description') }}</textarea>

        <label>Price</label>
        <input type="number" step="0.01" name="price" value="{{ old('price') }}" required>

        <label>Stock</label>
        <input type="number" name="stock" value="{{ old('stock', 0) }}" required>

        <label>Category</label>
        <select name="category" required>
            @foreach($categories as $category)
                <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
            @endforeach
        </select>

        <label>Images (up to 5 files)</label>
        <input type="file" name="images[]" accept="image/*" multiple>

        <button type="submit" class="btn">Save Product</button>
    </form>
</div>
@endsection
