@extends('layouts.app')

@section('title', 'Deliveries - FreshMart')

@section('content')
<div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-slate-100">Deliveries</h1>
        <p class="mt-2 text-gray-600 dark:text-slate-300">Monitor delivery progress for every order assigned to your store.</p>
    </div>
    <a href="{{ route('seller.products.index') }}" class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-4 py-2 font-semibold text-white transition hover:bg-gray-800 dark:bg-slate-700 dark:hover:bg-slate-600">
        View Products
    </a>
</div>

<div class="grid gap-6 md:grid-cols-4 mb-8">
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition-colors duration-200 dark:border-slate-700 dark:bg-slate-900">
        <p class="text-sm font-medium text-gray-500 dark:text-slate-300">Pending</p>
        <p class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-slate-100">{{ $pendingDeliveries }}</p>
    </div>
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition-colors duration-200 dark:border-slate-700 dark:bg-slate-900">
        <p class="text-sm font-medium text-gray-500 dark:text-slate-300">Processing</p>
        <p class="mt-2 text-3xl font-extrabold text-amber-600 dark:text-amber-400">{{ $processingDeliveries }}</p>
    </div>
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition-colors duration-200 dark:border-slate-700 dark:bg-slate-900">
        <p class="text-sm font-medium text-gray-500 dark:text-slate-300">Shipped</p>
        <p class="mt-2 text-3xl font-extrabold text-blue-600 dark:text-blue-400">{{ $shippedDeliveries }}</p>
    </div>
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition-colors duration-200 dark:border-slate-700 dark:bg-slate-900">
        <p class="text-sm font-medium text-gray-500 dark:text-slate-300">Delivered</p>
        <p class="mt-2 text-3xl font-extrabold text-green-600 dark:text-green-400">{{ $deliveredDeliveries }}</p>
    </div>
</div>

<div class="mb-8 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
    <div class="border-b border-gray-100 px-6 py-4 dark:border-slate-700">
        <h2 class="text-xl font-bold text-gray-800 dark:text-slate-100">Buyers Checkout Summary</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-slate-300">Click a buyer to view complete checkout details.</p>
    </div>

    <div class="p-6">
        @forelse($buyersSummary as $summary)
            <a href="{{ route('seller.deliveries.buyer-details', $summary->user_id) }}" class="mb-3 block rounded-xl border border-gray-100 bg-gray-50 p-4 transition hover:border-green-300 hover:bg-green-50/60 dark:border-slate-700 dark:bg-slate-800/60 dark:hover:border-green-700 dark:hover:bg-green-900/10">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-base font-bold text-gray-900 dark:text-slate-100">{{ $summary->user->username ?? ($summary->user->name ?? 'Unknown Buyer') }}</p>
                        <p class="text-sm text-gray-600 dark:text-slate-300">{{ $summary->user->name ?? 'No full name' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-800 dark:text-slate-100">{{ $summary->checkout_count }} checkout{{ $summary->checkout_count > 1 ? 's' : '' }}</p>
                        <p class="text-sm text-gray-600 dark:text-slate-300">{{ $summary->total_products }} product{{ $summary->total_products > 1 ? 's' : '' }}</p>
                    </div>
                </div>
            </a>
        @empty
            <div class="rounded-xl bg-gray-50 px-4 py-5 text-sm text-gray-600 dark:bg-slate-800/60 dark:text-slate-300">
                No buyer checkout data yet.
            </div>
        @endforelse
    </div>
</div>

<div class="mt-8 grid gap-6 md:grid-cols-2">
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100">Status Breakdown</h3>
        <div class="mt-4 space-y-3">
            @foreach(['pending', 'approved', 'processing', 'shipped', 'out_for_delivery', 'preparing', 'ready', 'ready_to_pickup', 'delivered', 'picked_up', 'cancelled'] as $status)
                <div class="flex items-center justify-between rounded-lg bg-gray-50 px-4 py-3 dark:bg-slate-800/70">
                    <span class="text-sm font-medium text-gray-700 dark:text-slate-200">{{ str_replace('_', ' ', ucfirst($status)) }}</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-slate-100">{{ $statusBreakdown[$status] ?? 0 }}</span>
                </div>
            @endforeach
        </div>
    </div>

   
</div>
@endsection