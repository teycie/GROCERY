@extends('layouts.app')

@section('title', 'Delivery Details - FreshMart')

@section('content')
<div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
    <div>
        <p class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-slate-400">Order {{ $delivery->order_id }}</p>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-slate-100">Delivery Details</h1>
    </div>
    <a href="{{ route('rider.deliveries') }}" class="inline-flex items-center justify-center rounded-lg bg-gray-100 px-4 py-2 font-semibold text-gray-700 transition hover:bg-gray-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
        Back to Deliveries
    </a>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    <!-- Main Delivery Information -->
    <div class="lg:col-span-2 space-y-6">
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <h2 class="text-xl font-bold text-gray-800 dark:text-slate-100 mb-4">Product Information</h2>
            <div class="flex items-start gap-4">
                <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-lg border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800">
                    @php
                        $productImage = $delivery->product && $delivery->product->images->first() ? $delivery->product->images->first()->image_path : null;
                    @endphp
                    @if($productImage)
                        <img src="{{ asset('storage/' . $productImage) }}" alt="{{ optional($delivery->product)->name }}" class="h-full w-full object-cover object-center">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-gray-400">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100">{{ optional($delivery->product)->name }}</h3>
                    <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">Quantity: <span class="font-semibold text-gray-800 dark:text-slate-200">{{ $delivery->quantity }}</span></p>
                    <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">Payment: <span class="font-semibold uppercase text-gray-800 dark:text-slate-200">{{ $delivery->payment_mode ?? '-' }}</span></p>
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <h2 class="text-lg font-bold text-gray-800 dark:text-slate-100 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Pickup (Seller)
                </h2>
                <div class="space-y-3">
                    <p class="text-sm"><span class="font-semibold text-gray-600 dark:text-slate-400">Name:</span> <span class="text-gray-900 dark:text-slate-100">{{ optional($delivery->seller)->name }}</span></p>
                    <p class="text-sm"><span class="font-semibold text-gray-600 dark:text-slate-400">Phone:</span> <span class="text-gray-900 dark:text-slate-100">{{ optional($delivery->seller)->phone ?? 'Not provided' }}</span></p>
                    <p class="text-sm"><span class="font-semibold text-gray-600 dark:text-slate-400">Email:</span> <span class="text-gray-900 dark:text-slate-100">{{ optional($delivery->seller)->email }}</span></p>
                </div>
            </div>

            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <h2 class="text-lg font-bold text-gray-800 dark:text-slate-100 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Drop-off (Buyer)
                </h2>
                <div class="space-y-3">
                    <p class="text-sm"><span class="font-semibold text-gray-600 dark:text-slate-400">Name:</span> <span class="text-gray-900 dark:text-slate-100">{{ optional($delivery->user)->name }}</span></p>
                    <p class="text-sm"><span class="font-semibold text-gray-600 dark:text-slate-400">Phone:</span> <span class="text-gray-900 dark:text-slate-100">{{ optional($delivery->user)->phone ?? 'Not provided' }}</span></p>
                    <p class="text-sm"><span class="font-semibold text-gray-600 dark:text-slate-400">Address:</span> <span class="text-gray-900 dark:text-slate-100 block mt-1 p-2 bg-gray-50 dark:bg-slate-800 rounded">{{ $delivery->address }}</span></p>
                    @if($delivery->notes)
                        <p class="text-sm mt-3 pt-3 border-t border-gray-100 dark:border-slate-700"><span class="font-semibold text-gray-600 dark:text-slate-400">Notes:</span> <span class="text-gray-900 dark:text-slate-100 block mt-1 italic">{{ $delivery->notes }}</span></p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Actions and Status -->
    <div class="space-y-6">
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 sticky top-24">
            <h2 class="text-xl font-bold text-gray-800 dark:text-slate-100 mb-6">Update Status</h2>
            
            <div class="space-y-4 relative before:absolute before:inset-y-0 before:left-[19px] before:w-[2px] before:bg-gray-200 dark:before:bg-slate-700">
                <!-- Status 1: Assigned -->
                <div class="relative flex items-center gap-4">
                    <div class="h-10 w-10 shrink-0 rounded-full flex items-center justify-center relative z-10 {{ in_array($delivery->tracking_status, ['rider_assigned', 'picked_up', 'on_delivery', 'delivered']) ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500 dark:bg-slate-700 dark:text-slate-400' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 dark:text-slate-100">Assigned</p>
                        <p class="text-xs text-gray-500 dark:text-slate-400">{{ $delivery->rider_assigned_at ? $delivery->rider_assigned_at->format('M d, h:i A') : '-' }}</p>
                    </div>
                </div>

                <!-- Status 2: Picked Up -->
                <div class="relative flex items-center gap-4">
                    <div class="h-10 w-10 shrink-0 rounded-full flex items-center justify-center relative z-10 {{ in_array($delivery->tracking_status, ['picked_up', 'on_delivery', 'delivered']) ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500 dark:bg-slate-700 dark:text-slate-400' }}">
                        @if(in_array($delivery->tracking_status, ['picked_up', 'on_delivery', 'delivered']))
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <span class="font-bold">2</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="font-bold {{ in_array($delivery->tracking_status, ['picked_up', 'on_delivery', 'delivered']) ? 'text-gray-900 dark:text-slate-100' : 'text-gray-500 dark:text-slate-400' }}">Picked Up</p>
                        @if($delivery->tracking_status === 'rider_assigned')
                            <form action="{{ route('rider.deliveries.pickup', $delivery) }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="w-full rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-cyan-700 transition">Mark as Picked Up</button>
                            </form>
                        @elseif(in_array($delivery->tracking_status, ['picked_up', 'on_delivery', 'delivered']))
                            <p class="text-xs text-gray-500 dark:text-slate-400">{{ $delivery->picked_up_at ? $delivery->picked_up_at->format('M d, h:i A') : '-' }}</p>
                        @endif
                    </div>
                </div>

                <!-- Status 3: On The Way -->
                <div class="relative flex items-center gap-4">
                    <div class="h-10 w-10 shrink-0 rounded-full flex items-center justify-center relative z-10 {{ in_array($delivery->tracking_status, ['on_delivery', 'delivered']) ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500 dark:bg-slate-700 dark:text-slate-400' }}">
                        @if(in_array($delivery->tracking_status, ['on_delivery', 'delivered']))
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <span class="font-bold">3</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="font-bold {{ in_array($delivery->tracking_status, ['on_delivery', 'delivered']) ? 'text-gray-900 dark:text-slate-100' : 'text-gray-500 dark:text-slate-400' }}">On The Way</p>
                        @if($delivery->tracking_status === 'picked_up')
                            <form action="{{ route('rider.deliveries.on-the-way', $delivery) }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="w-full rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-700 transition">Mark as On The Way</button>
                            </form>
                        @elseif(in_array($delivery->tracking_status, ['on_delivery', 'delivered']))
                            <p class="text-xs text-gray-500 dark:text-slate-400">{{ $delivery->on_delivery_at ? $delivery->on_delivery_at->format('M d, h:i A') : '-' }}</p>
                        @endif
                    </div>
                </div>

                <!-- Status 4: Delivered -->
                <div class="relative flex items-center gap-4">
                    <div class="h-10 w-10 shrink-0 rounded-full flex items-center justify-center relative z-10 {{ $delivery->tracking_status === 'delivered' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500 dark:bg-slate-700 dark:text-slate-400' }}">
                        @if($delivery->tracking_status === 'delivered')
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <span class="font-bold">4</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="font-bold {{ $delivery->tracking_status === 'delivered' ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-slate-400' }}">Delivered</p>
                        @if($delivery->tracking_status === 'on_delivery')
                            <form action="{{ route('rider.deliveries.delivered', $delivery) }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="w-full rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-700 transition btn-animate">Complete Delivery</button>
                            </form>
                        @elseif($delivery->tracking_status === 'delivered')
                            <p class="text-xs text-green-600 dark:text-green-400 font-semibold">{{ $delivery->delivered_date ? $delivery->delivered_date->format('M d, Y') : '-' }}</p>
                        @endif
                    </div>
                </div>
                
                @if($delivery->tracking_status === 'cancelled')
                    <div class="relative flex items-center gap-4 mt-6">
                        <div class="h-10 w-10 shrink-0 rounded-full bg-red-500 text-white flex items-center justify-center relative z-10">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-red-600 dark:text-red-400">Cancelled</p>
                            <p class="text-xs text-red-500">This order has been cancelled.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
