@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Notifications</h1>

    <form method="POST" action="{{ route('notifications.readAll') }}">
        @csrf
        <button class="btn btn-sm btn-primary mb-3">Mark all as read</button>
    </form>

    @foreach($notifications as $notification)
        <div class="card mb-2 {{ $notification->read_at ? 'bg-light' : 'bg-white' }}">
            <div class="card-body">
                <p class="mb-1">{{ data_get($notification->data, 'message') }}</p>
                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                @if(!$notification->read_at)
                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}" style="display:inline">
                        @csrf
                        <button class="btn btn-sm btn-link">Mark as read</button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach

    {{ $notifications->links() }}
</div>

@push('scripts')
<script>
    (function(){
        @auth
            if (window.Echo) {
                window.Echo.private('App.Models.User.{{ auth()->id() }}')
                    .notification(function (notification) {
                        // simple reload to show new notification - adapt for UI push
                        location.reload();
                    });
            }
        @endauth
    })();
</script>
@endpush

@endsection
