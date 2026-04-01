@extends('layouts.app')

@section('title', 'Manage Products')

@section('content')
<h1>Manage Products</h1>
<p><a href="{{ route('seller.products.create') }}" class="btn">Add New Product</a></p>

<div class="card">
    @forelse($products as $product)
        <div class="cart-item">
            <div>
                <strong>{{ $product->name }}</strong>
                <p>{{ $product->category }} | ₱{{ number_format($product->price, 2) }} | Stock: {{ $product->stock }}</p>
            </div>
            <div class="action-row">
                <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-light">Edit</a>
                <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="inline-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    @empty
        <p>No products added yet.</p>
    @endforelse

    <div class="pagination-wrap">
        {{ $products->links() }}
    </div>
</div>
@endsection
