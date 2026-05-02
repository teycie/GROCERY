@extends('layouts.app')

@section('title', 'My Purchases - FreshMart')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-slate-100">Track Your Purchases</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-slate-300">Check your purchase statuses and open each item for full details.</p>
        </div>
        <a href="{{ route('cart.index') }}" class="inline-flex items-center text-sm font-semibold text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 transition">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Cart
        </a>
    </div>

    <div class="rounded-xl border border-gray-100 bg-white shadow-sm dark:border-slate-800 dark:bg-[#0f1726] overflow-hidden">
        @if($purchases->isEmpty())
            <div class="p-10 text-center text-gray-500 dark:text-slate-300">
                You do not have any purchases yet.
            </div>
        @else
            <ul class="divide-y divide-gray-100 dark:divide-slate-800">
                @foreach($purchases as $purchase)
                    @php
                        $status = $purchase->tracking_status;
                        $product = $purchase->product;
                        $productImage = $product && $product->images->first() ? $product->images->first()->image_path : null;
                        $statusClass = 'bg-slate-700/50 text-slate-100';

                        if (in_array($status, ['approved'], true)) {
                            $statusClass = 'bg-emerald-500/20 text-emerald-300';
                        } elseif (in_array($status, ['processing', 'preparing'], true)) {
                            $statusClass = 'bg-amber-500/20 text-amber-300';
                        } elseif (in_array($status, ['shipped', 'ready'], true)) {
                            $statusClass = 'bg-blue-500/20 text-blue-300';
                        } elseif (in_array($status, ['out_for_delivery', 'ready_to_pickup'], true)) {
                            $statusClass = 'bg-violet-500/20 text-violet-300';
                        } elseif (in_array($status, ['delivered', 'picked_up'], true)) {
                            $statusClass = 'bg-green-500/20 text-green-300';
                        } elseif ($status === 'cancelled') {
                            $statusClass = 'bg-red-500/20 text-red-300';
                        }
                    @endphp
                    <li class="p-6 hover:bg-gray-50 dark:hover:bg-[#18243a] transition">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="h-16 w-16 overflow-hidden rounded-md border border-gray-200 bg-gray-50 dark:border-slate-700 dark:bg-slate-800">
                                    @if($productImage)
                                        <img src="{{ asset('storage/' . $productImage) }}" alt="{{ optional($product)->name ?? 'Product image' }}" class="h-full w-full object-cover object-center">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-gray-400 dark:text-slate-500">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-base font-bold text-gray-900 dark:text-slate-100">{{ optional($product)->name ?? 'Unknown Product' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">Order {{ $purchase->order_id }}</p>
                                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">{{ ucfirst($purchase->fulfillment_type ?? 'delivery') }} • Qty {{ $purchase->quantity }}</p>
                                </div>
                            </div>
                            <div class="text-left md:text-right">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ str_replace('_', ' ', ucfirst($status)) }}</span>
                                <p class="text-xs text-gray-500 dark:text-slate-400 mt-2">{{ $purchase->created_at->format('M d, Y h:i A') }}</p>
                                <a href="{{ route('buyer.purchases.show', $purchase) }}" class="mt-2 inline-block text-sm font-semibold text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300">View details</a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="p-4 border-t border-gray-100 dark:border-slate-800 text-gray-700 dark:text-slate-300">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
