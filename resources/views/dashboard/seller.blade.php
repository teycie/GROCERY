@extends('layouts.app')

@section('title', 'Seller Dashboard')

@section('content')
<h1>Seller Dashboard</h1>
<p class="subtitle">Welcome, {{ auth()->user()->name }}. Manage products and post announcements.</p>

<div class="card-row">
    <div class="card">
        <h2>Your Summary</h2>
        <p>Total Products: <strong>{{ $productsCount }}</strong></p>
        <p><a href="{{ route('seller.products.index') }}" class="btn">Manage Products</a></p>
        <p><a href="{{ route('seller.announcements.create') }}" class="btn btn-light">Post Announcement</a></p>
    </div>

    <div class="card">
        <h2>Recent Announcements</h2>
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
