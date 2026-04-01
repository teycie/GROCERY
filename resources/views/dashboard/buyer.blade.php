@extends('layouts.app')

@section('title', 'Buyer Dashboard')

@section('content')
<h1>Hello, {{ auth()->user()->name }}</h1>
<p class="subtitle">Browse products, manage your cart, and read announcements.</p>

<div class="card-row">
    <div class="card">
        <h2>Quick Links</h2>
        <p><a href="{{ route('products.index') }}" class="btn">View Products</a></p>
        <p><a href="{{ route('cart.index') }}" class="btn btn-light">View Cart</a></p>
    </div>

    <div class="card">
        <h2>Latest Announcements</h2>
        @forelse($announcements as $announcement)
            <div class="list-item">
                <strong>{{ $announcement->title }}</strong>
                <p>{{ $announcement->message }}</p>
            </div>
        @empty
            <p>No announcements yet.</p>
        @endforelse
    </div>
</div>

<div class="card">
    <h2>Latest Products</h2>
    <div class="grid">
        @forelse($products as $product)
            <div class="product-box">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                @endif
                <h3>{{ $product->name }}</h3>
                <p class="muted">{{ $product->category }}</p>
                <p>₱{{ number_format($product->price, 2) }}</p>
                <a href="{{ route('products.show', $product) }}" class="btn btn-light">View</a>
            </div>
        @empty
            <p>No products available yet.</p>
        @endforelse
    </div>
</div>
@endsection
