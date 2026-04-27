@extends('layouts.app')

@section('title', 'Buyer Dashboard - FreshMart')

@section('content')
<div class="relative overflow-hidden rounded-2xl bg-white dark:bg-[#0b1020] p-2">
    <div class="pointer-events-none absolute inset-0 hidden dark:block">
        <div class="absolute -top-32 left-1/4 h-96 w-80 bg-green-500/5 blur-3xl"></div>
        <div class="absolute top-0 right-1/4 h-96 w-72 bg-emerald-400/5 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/2 h-72 w-80 -translate-x-1/2 bg-slate-500/5 blur-3xl"></div>
    </div>

    <div class="relative">
        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-green-600 dark:text-[#69da58]">Welcome back, {{ auth()->user()->name }}!</h1>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="min-h-[130px] rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-[#0f1726] shadow-sm dark:shadow-none p-5 flex flex-col justify-center">
                <span class="text-sm font-medium text-gray-500 dark:text-slate-300 mb-2">Store Products Available</span>
                <span class="text-3xl sm:text-4xl font-extrabold leading-none text-gray-900 dark:text-slate-100">{{ $totalProductsAvailable }}</span>
            </div>
            <div class="min-h-[130px] rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-[#0f1726] shadow-sm dark:shadow-none p-5 flex flex-col justify-center">
                <span class="text-sm font-medium text-gray-500 dark:text-slate-300 mb-2">Items in Your Cart</span>
                <span class="text-3xl sm:text-4xl font-extrabold leading-none text-gray-900 dark:text-slate-100">{{ $cartItemsCount }}</span>
            </div>

            <div class="min-h-[130px] rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-[#0f1726] shadow-sm dark:shadow-none p-5 flex flex-col justify-center">
                <span class="text-sm font-medium text-gray-500 dark:text-slate-300 mb-2">My Purchases</span>
                <span class="text-3xl sm:text-4xl font-extrabold leading-none text-gray-900 dark:text-slate-100">{{ $totalPurchases ?? 0 }}</span>
            </div>

            <div class="min-h-[130px] rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-[#0f1726] shadow-sm dark:shadow-none p-5 flex flex-col justify-center">
                <span class="text-sm font-medium text-gray-600 dark:text-[#d6e79a] mb-2">Track Orders</span>
                <a href="{{ route('buyer.purchases.index') }}" class="mt-1 inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-[#39b94b] to-[#228a32] px-4 py-3 text-sm font-semibold text-white shadow-[0_0_24px_rgba(57,185,75,0.35)] transition hover:brightness-110">
                    Go to Tracking Section
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-[#0f1726] shadow-sm dark:shadow-none p-6">
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 dark:border-slate-800 pb-3">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-slate-100">Latest Products</h2>
                    <a href="{{ route('products.index') }}" class="text-sm font-semibold text-green-600 dark:text-[#69da58] hover:text-green-700 dark:hover:text-[#8ef07f]">View All &rarr;</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                    @forelse($products as $product)
                        <a href="{{ route('products.show', $product) }}" class="group rounded-xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-[#111a2b] overflow-hidden hover:border-green-300 dark:hover:border-slate-600 transition flex flex-col shadow-sm dark:shadow-none">
                            <div class="relative h-44 bg-gray-100 dark:bg-[#0d1525]">
                                @if($product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-slate-400">
                                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <span class="absolute top-2 right-2 rounded-full bg-[#45b950] px-2.5 py-0.5 text-xs font-bold uppercase text-white">New</span>
                            </div>
                            <div class="p-4 flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="text-base font-bold text-gray-900 dark:text-slate-100 truncate group-hover:text-green-600 dark:group-hover:text-green-400 transition">{{ $product->name }}</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-slate-400 capitalize">{{ $product->category }}</p>
                                </div>
                                <span class="mt-0.5 text-sm font-semibold text-green-600 dark:text-green-400 whitespace-nowrap">View</span>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full rounded-lg border border-dashed border-gray-300 dark:border-[#2f456f] p-8 text-center text-gray-500 dark:text-slate-300">
                            No products available right now.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <div class="rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-[#0f1726] shadow-sm dark:shadow-none p-6">
                    <div class="flex items-center mb-5">
                        <svg class="h-6 w-6 text-green-600 dark:text-[#69da58] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-slate-100">Store Announcements</h2>
                    </div>

                    <div class="space-y-3">
                        @forelse($announcements as $announcement)
                            <div class="rounded-lg border border-green-100 dark:border-slate-800 bg-green-50 dark:bg-[#111a2b] p-4">
                                <h3 class="font-bold text-gray-900 dark:text-slate-100">{{ $announcement->title }}</h3>
                                <p class="mt-2 text-sm text-gray-600 dark:text-slate-300">{{ $announcement->message }}</p>
                            </div>
                        @empty
                            <div class="rounded-lg border border-dashed border-gray-300 dark:border-[#2f456f] p-4 text-center text-gray-500 dark:text-slate-300">
                                No announcements right now.
                            </div>
                        @endforelse
                    </div>

                    @if(count($announcements) > 0)
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-[#1c2b4b] text-right">
                            <a href="{{ route('announcements.index') }}" class="text-sm font-medium text-green-600 dark:text-[#69da58] hover:text-green-700 dark:hover:text-[#8ef07f]">Read all announcements</a>
                        </div>
                    @endif
                </div>

                <div class="rounded-2xl bg-gradient-to-r from-[#2da93f] to-[#1f8f31] p-8 text-white shadow-[0_0_18px_rgba(45,169,63,0.22)]">
                    <h3 class="text-2xl sm:text-3xl font-bold leading-tight">Ready to checkout?</h3>
                </div>
            </div>
        </div>

        <div class="mt-8 rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-[#0f1726] shadow-sm dark:shadow-none p-6">
            <div class="flex justify-between items-center mb-6 border-b border-gray-100 dark:border-slate-800 pb-3">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-slate-100">Recent Purchases</h2>
                <a href="{{ route('buyer.purchases.index') }}" class="text-sm font-semibold text-green-600 dark:text-[#69da58] hover:text-green-700 dark:hover:text-[#8ef07f]">Open tracking &rarr;</a>
            </div>

            <div class="space-y-4">
                @forelse($recentPurchases as $purchase)
                    @php
                        $status = $purchase->tracking_status;
                        $statusClass = 'bg-gray-100 text-gray-700 dark:bg-slate-700/40 dark:text-slate-200';

                        if ($status === 'approved') {
                            $statusClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300';
                        } elseif (in_array($status, ['processing', 'preparing'], true)) {
                            $statusClass = 'bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300';
                        } elseif (in_array($status, ['shipped', 'ready'], true)) {
                            $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-500/20 dark:text-blue-300';
                        } elseif (in_array($status, ['out_for_delivery', 'ready_to_pickup'], true)) {
                            $statusClass = 'bg-violet-100 text-violet-800 dark:bg-violet-500/20 dark:text-violet-300';
                        } elseif (in_array($status, ['delivered', 'picked_up'], true)) {
                            $statusClass = 'bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-300';
                        }
                    @endphp

                    <a href="{{ route('buyer.purchases.show', $purchase) }}" class="block rounded-xl border border-gray-100 dark:border-slate-800 bg-gray-50 dark:bg-[#111a2b] p-4 hover:border-green-300 dark:hover:border-slate-600 transition">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-slate-100">{{ $purchase->product->name ?? 'Unknown Product' }}</h3>
                                <p class="text-sm text-gray-500 dark:text-slate-300 mt-1">Order {{ $purchase->order_id }}</p>
                                <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">{{ ucfirst($purchase->fulfillment_type ?? 'delivery') }} • Qty {{ $purchase->quantity }}</p>
                            </div>
                            <div class="text-left md:text-right">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ str_replace('_', ' ', ucfirst($status)) }}</span>
                                <p class="text-xs text-gray-400 dark:text-slate-500 mt-2">Click to view details</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="rounded-lg border border-dashed border-gray-300 dark:border-slate-700 p-6 text-center text-gray-500 dark:text-slate-300">
                        No purchases yet. When you checkout, your latest orders will appear here.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
