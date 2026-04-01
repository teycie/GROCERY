@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="hero card">
    <p class="hero-kicker">Fresh Picks Delivered</p>
    <h1>Shop smarter with your neighborhood grocery hub.</h1>
    <p class="subtitle">Discover seasonal products, daily essentials, and trusted sellers. Sign in to add items to your cart and place orders.</p>

    <div class="action-row">
        <a href="{{ route('login') }}" class="btn">Login to Start Shopping</a>
        <a href="{{ route('register') }}" class="btn btn-light">Create an Account</a>
    </div>
</div>

<div class="card">
    <div class="title-row">
        <h2>Featured Products</h2>
        <a href="{{ route('login') }}" class="small-link">Login to view full catalog</a>
    </div>

    <div class="grid">
        @forelse($products as $product)
            <div class="product-box">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                @endif
                <h3>{{ $product->name }}</h3>
                <p class="muted">{{ $product->category }}</p>
                <p>₱{{ number_format($product->price, 2) }}</p>
                <a href="{{ route('login') }}" class="btn btn-light">Login to Buy</a>
            </div>
        @empty
            <p>No products available yet.</p>
        @endforelse
    </div>
</div>

<div class="card-row">
    <div class="card">
        <h2>Why shoppers choose us</h2>
        <div class="feature-list">
            <p><strong>Fresh inventory</strong><br><span class="small-text">New products are added regularly by verified sellers.</span></p>
            <p><strong>Category-based browsing</strong><br><span class="small-text">Find groceries, pet care, household essentials, and more.</span></p>
            <p><strong>Local-first marketplace</strong><br><span class="small-text">Support nearby sellers with convenient online ordering.</span></p>
        </div>
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
@endsection
