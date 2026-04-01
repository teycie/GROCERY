@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
<h1>Announcements</h1>

<div class="card">
    @forelse($announcements as $announcement)
        <div class="list-item">
            <h3>{{ $announcement->title }}</h3>
            <p>{{ $announcement->message }}</p>
            <p class="muted">{{ $announcement->created_at->format('M d, Y h:i A') }}</p>
        </div>
    @empty
        <p>No announcements yet.</p>
    @endforelse

    <div class="pagination-wrap">
        {{ $announcements->links() }}
    </div>
</div>
@endsection
