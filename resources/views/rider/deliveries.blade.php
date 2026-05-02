@extends('layouts.app')

@section('title', 'My Deliveries - FreshMart')

@section('content')
<div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-slate-100">My Deliveries</h1>
        <p class="mt-2 text-gray-600 dark:text-slate-300">Manage your assigned deliveries and view history.</p>
    </div>
</div>

<div class="mb-6 flex space-x-2">
    <a href="{{ route('rider.deliveries', ['filter' => 'active']) }}" class="rounded-lg px-4 py-2 text-sm font-semibold transition {{ $filter === 'active' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700' }}">
        Active Deliveries
    </a>
    <a href="{{ route('rider.deliveries', ['filter' => 'history']) }}" class="rounded-lg px-4 py-2 text-sm font-semibold transition {{ $filter === 'history' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700' }}">
        Delivery History
    </a>
</div>

<div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
    @if($deliveries->isEmpty())
        <div class="py-16 text-center text-gray-500 dark:text-slate-300">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-slate-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p class="text-lg font-semibold text-gray-800 dark:text-slate-100">No {{ $filter }} deliveries found</p>
        </div>
    @else
        <ul class="divide-y divide-gray-100 dark:divide-slate-700">
            @foreach($deliveries as $delivery)
                @php
                    $statusClass = 'bg-gray-100 text-gray-800 dark:bg-slate-800 dark:text-slate-200';
                    switch ($delivery->tracking_status) {
                        case 'rider_assigned':
                            $statusClass = 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300';
                            break;
                        case 'picked_up':
                            $statusClass = 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/40 dark:text-cyan-300';
                            break;
                        case 'on_delivery':
                            $statusClass = 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300';
                            break;
                        case 'delivered':
                            $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300';
                            break;
                        case 'cancelled':
                            $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300';
                            break;
                    }
                @endphp
                <li class="p-6 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ str_replace('_', ' ', ucfirst($delivery->tracking_status)) }}
                                </span>
                                <span class="text-sm text-gray-500 dark:text-slate-400">Order {{ $delivery->order_id }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100 mb-1">{{ optional($delivery->product)->name }}</h3>
                            <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-600 dark:text-slate-300 mt-3">
                                <div>
                                    <p><span class="font-semibold text-gray-800 dark:text-slate-200">Payment:</span> <span class="uppercase">{{ $delivery->payment_mode ?? '-' }}</span></p>
                                    @if(strtolower($delivery->payment_mode) === 'cod' || strtolower($delivery->payment_mode) === 'cash on delivery')
                                        <p class="mt-1"><span class="font-bold text-red-600 dark:text-red-400">Collect: ₱{{ number_format(optional($delivery->product)->price * $delivery->quantity, 2) }}</span></p>
                                    @endif
                                    <p class="mt-1"><span class="font-semibold text-gray-800 dark:text-slate-200">Buyer:</span> {{ optional($delivery->user)->name }}</p>
                                </div>
                                <div>
                                    <p><span class="font-semibold text-gray-800 dark:text-slate-200">Address:</span> {{ $delivery->address }}</p>
                                    <p class="mt-1"><span class="font-semibold text-gray-800 dark:text-slate-200">Assigned:</span> {{ $delivery->rider_assigned_at ? $delivery->rider_assigned_at->format('M d, Y h:i A') : '-' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 md:flex-col md:items-end">
                            <a href="{{ route('rider.deliveries.show', $delivery) }}" class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-700 shadow-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        @if($deliveries->hasPages())
            <div class="border-t border-gray-100 px-6 py-4 dark:border-slate-700">
                {{ $deliveries->appends(['filter' => $filter])->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
