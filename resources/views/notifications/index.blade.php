@extends('layouts.app')

@section('title', 'Notifications - FreshMart')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-100 dark:border-slate-800 pb-5">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-slate-100">Your Notifications</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-slate-400">Stay updated on your orders and activities.</p>
        </div>
        
        @if($notifications->count() > 0)
        <form method="POST" action="{{ route('notifications.readAll') }}">
            @csrf
            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-gray-100 dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-slate-300 transition hover:bg-gray-200 dark:hover:bg-slate-700 border border-transparent dark:border-slate-700">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Mark all as read
            </button>
        </form>
        @endif
    </div>

    <div class="space-y-4">
        @forelse($notifications as $notification)
            @php
                $isRead = !is_null($notification->read_at);
                $statusType = data_get($notification->data, 'status', '');
                
                $iconClass = 'bg-gray-100 text-gray-500 dark:bg-slate-800 dark:text-slate-400';
                $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
                
                if (in_array($statusType, ['approved', 'processing', 'preparing'])) {
                    $iconClass = 'bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400';
                    $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />';
                } elseif (in_array($statusType, ['shipped', 'rider_assigned', 'picked_up'])) {
                    $iconClass = 'bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400';
                    $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />';
                } elseif (in_array($statusType, ['out_for_delivery', 'on_delivery', 'ready_to_pickup', 'ready'])) {
                    $iconClass = 'bg-violet-100 text-violet-600 dark:bg-violet-500/20 dark:text-violet-400';
                    $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />';
                } elseif (in_array($statusType, ['delivered'])) {
                    $iconClass = 'bg-green-100 text-green-600 dark:bg-green-500/20 dark:text-green-400';
                    $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
                } elseif ($statusType === 'cancelled') {
                    $iconClass = 'bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400';
                    $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
                }
            @endphp

            <div class="group relative rounded-2xl border transition-all duration-200 
                {{ $isRead ? 'bg-white dark:bg-slate-900 border-gray-100 dark:border-slate-800' : 'bg-green-50/50 dark:bg-slate-800 border-green-200 dark:border-green-900/50 shadow-sm' }}
            ">
                @if(!$isRead)
                    <div class="absolute -left-1 top-1/2 -translate-y-1/2 w-2 h-12 bg-green-500 rounded-r-full hidden sm:block"></div>
                @endif
                
                <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="w-full h-full p-0 m-0">
                    @csrf
                    <button type="submit" class="w-full text-left p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 focus:outline-none">
                        
                        <!-- Notification Icon -->
                        <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center {{ $iconClass }}">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                {!! $iconSvg !!}
                            </svg>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1">
                                <h3 class="text-base font-bold {{ $isRead ? 'text-gray-800 dark:text-slate-200' : 'text-gray-900 dark:text-slate-100' }}">
                                    Order {{ data_get($notification->data, 'order_id') ? '#' . data_get($notification->data, 'order_id') : 'Update' }}
                                </h3>
                                <span class="text-xs font-semibold whitespace-nowrap {{ $isRead ? 'text-gray-400 dark:text-slate-500' : 'text-green-600 dark:text-green-400' }}">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="mt-1 text-sm {{ $isRead ? 'text-gray-500 dark:text-slate-400' : 'text-gray-700 dark:text-slate-300 font-medium' }}">
                                {{ data_get($notification->data, 'message') }}
                            </p>
                            @if(data_get($notification->data, 'product_name'))
                                <p class="mt-2 text-xs font-semibold text-gray-400 dark:text-slate-500 bg-gray-100 dark:bg-slate-800 inline-block px-2 py-1 rounded">
                                    {{ data_get($notification->data, 'product_name') }}
                                </p>
                            @endif
                        </div>
                        
                        <!-- Actions / View Link -->
                        <div class="hidden sm:flex items-center text-gray-400 group-hover:text-green-500 dark:text-slate-500 dark:group-hover:text-green-400 transition-colors">
                            <span class="text-sm font-semibold mr-1">View</span>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </button>
                </form>
            </div>
        @empty
            <div class="py-16 text-center rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="mx-auto w-16 h-16 rounded-full bg-gray-50 dark:bg-slate-800 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100">No notifications yet</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-slate-400">When you have updates about your orders, they will appear here.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $notifications->links() }}
    </div>
</div>

@push('scripts')
<script>
    (function(){
        @auth
            if (window.Echo) {
                window.Echo.private('App.Models.User.{{ auth()->id() }}')
                    .notification(function (notification) {
                        location.reload();
                    });
            }
        @endauth
    })();
</script>
@endpush

@endsection
