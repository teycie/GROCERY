@extends('layouts.app')

@section('title', 'Products')

@section('content')
@php
    $categoryIcons = [
        'Frozen' => '&#10052;',
        'Beverage' => '&#9749;',
        'Snacks' => '&#127839;',
        'Fruits & Vegetables' => '&#127814;',
        'Pet Care' => '&#128054;',
        'Household Cleaning & Essentials' => '&#129529;',
    ];
@endphp

<h1>All Products</h1>

<div class="card">
    <p class="small-text">Browse by category</p>
    <div class="category-filter-bar">
        <a href="{{ route('products.index') }}" class="category-filter-chip {{ request('category') ? '' : 'is-active' }}">
            <span class="category-chip-icon">&#128722;</span>
            <span>All</span>
        </a>

        @foreach($categories as $category)
            <a
                href="{{ route('products.index', ['category' => $category]) }}"
                class="category-filter-chip {{ request('category') === $category ? 'is-active' : '' }}"
            >
                <span class="category-chip-icon">{!! $categoryIcons[$category] ?? '&#128722;' !!}</span>
                <span>{{ $category }}</span>
            </a>
        @endforeach
    </div>
</div>

<div class="grid">
    @forelse($products as $product)
        <div class="product-box">
            <a href="{{ route('products.show', $product) }}" class="product-box-link">
                @if($product->images->count() > 0)
                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="product-image">
                @endif
                <h3>{{ $product->name }}</h3>
                <p class="category-badge">
                    <span class="category-icon">{!! $categoryIcons[$product->category] ?? '&#128722;' !!}</span>
                    <span>{{ $product->category }}</span>
                </p>
                <p>₱{{ number_format($product->price, 2) }}</p>
            </a>

            <div class="product-actions">
                <form action="{{ route('cart.add', $product) }}" method="POST" class="inline-form">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn">Add to Cart</button>
                </form>

                <form action="{{ route('cart.add', $product) }}" method="POST" class="inline-form">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="buy_now" value="1">
                    <button type="submit" class="btn btn-light">Buy Now</button>
                </form>
            </div>
        </div>
    @empty
        <p>No products found.</p>
    @endforelse
</div>

<div class="pagination-wrap">
    {{ $products->withQueryString()->links() }}
</div>
@endsection
