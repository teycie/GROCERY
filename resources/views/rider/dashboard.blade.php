@extends('layouts.app')

@section('title', 'Rider Dashboard - FreshMart')

@section('content')
<div class="mb-8 flex justify-between items-start">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-slate-100">Welcome, Rider {{ auth()->user()->first_name ?? auth()->user()->name }}!</h1>
        <p class="mt-2 text-gray-600 dark:text-slate-300">Here is your delivery summary and active assignments.</p>
    </div>
    <div class="flex items-center bg-white p-3 rounded-xl shadow-sm border border-gray-100 dark:bg-slate-900 dark:border-slate-700">
        <span class="mr-3 text-sm font-medium text-gray-700 dark:text-slate-300">Status: <span class="{{ auth()->user()->is_rider_available ? 'text-green-500' : 'text-red-500' }} font-bold">{{ auth()->user()->is_rider_available ? 'Available' : 'Busy' }}</span></span>
        <form action="{{ route('rider.availability.toggle') }}" method="POST" class="m-0 flex items-center">
            @csrf
            <button type="submit" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 {{ auth()->user()->is_rider_available ? 'bg-green-500' : 'bg-gray-200 dark:bg-slate-700' }}" role="switch" aria-checked="{{ auth()->user()->is_rider_available ? 'true' : 'false' }}">
                <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ auth()->user()->is_rider_available ? 'translate-x-5' : 'translate-x-0' }}"></span>
            </button>
        </form>
    </div>
</div>

<div class="grid gap-6 md:grid-cols-4 mb-8">
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <p class="text-sm font-medium text-gray-500 dark:text-slate-300">Total Assigned</p>
        <p class="mt-2 text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ $totalAssigned }}</p>
    </div>
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <p class="text-sm font-medium text-gray-500 dark:text-slate-300">Picked Up</p>
        <p class="mt-2 text-3xl font-extrabold text-cyan-600 dark:text-cyan-400">{{ $pickedUpCount }}</p>
    </div>
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <p class="text-sm font-medium text-gray-500 dark:text-slate-300">On The Way</p>
        <p class="mt-2 text-3xl font-extrabold text-purple-600 dark:text-purple-400">{{ $onDeliveryCount }}</p>
    </div>
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <p class="text-sm font-medium text-gray-500 dark:text-slate-300">Delivered</p>
        <p class="mt-2 text-3xl font-extrabold text-green-600 dark:text-green-400">{{ $totalDelivered }}</p>
    </div>
</div>

<div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
    <div class="border-b border-gray-100 px-6 py-4 flex justify-between items-center dark:border-slate-700">
        <h2 class="text-xl font-bold text-gray-800 dark:text-slate-100">Active Deliveries</h2>
        <a href="{{ route('rider.deliveries') }}" class="text-sm font-semibold text-green-600 hover:text-green-500 dark:text-green-400">View All</a>
    </div>

    <div class="p-6">
        @if($activeDeliveries->isEmpty())
            <div class="py-12 text-center text-gray-500 dark:text-slate-300">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-slate-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <p class="text-lg font-semibold text-gray-800 dark:text-slate-100">No active deliveries</p>
                <p class="mt-1">You are all caught up! Wait for sellers to assign new deliveries.</p>
            </div>
        @else
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach($activeDeliveries as $delivery)
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
                        }
                    @endphp
                    <a href="{{ route('rider.deliveries.show', $delivery) }}" class="block rounded-xl border border-gray-100 bg-gray-50 p-5 transition hover:border-green-300 hover:shadow-md dark:border-slate-700 dark:bg-slate-800/60 dark:hover:border-green-600/60">
                        <div class="flex justify-between items-start mb-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">
                                {{ str_replace('_', ' ', ucfirst($delivery->tracking_status)) }}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-slate-400">{{ $delivery->rider_assigned_at ? $delivery->rider_assigned_at->diffForHumans() : '' }}</span>
                        </div>
                        
                        <h3 class="font-bold text-gray-900 dark:text-slate-100 text-lg mb-1 truncate">{{ optional($delivery->product)->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-slate-300 mb-4">Order: {{ $delivery->order_id }}</p>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 mr-2 text-gray-400 dark:text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                <span class="text-gray-700 dark:text-slate-300 line-clamp-1">Payment: <span class="font-semibold uppercase">{{ $delivery->payment_mode ?? '-' }}</span></span>
                            </div>
                            @if(strtolower($delivery->payment_mode) === 'cod' || strtolower($delivery->payment_mode) === 'cash on delivery')
                            <div class="flex items-start">
                                <svg class="h-5 w-5 mr-2 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-red-600 dark:text-red-400 font-bold line-clamp-1">Collect: ₱{{ number_format(optional($delivery->product)->price * $delivery->quantity, 2) }}</span>
                            </div>
                            @endif
                            <div class="flex items-start">
                                <svg class="h-5 w-5 mr-2 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-gray-700 dark:text-slate-300 line-clamp-2">To: <span class="font-semibold">{{ $delivery->address }}</span></span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
