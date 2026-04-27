@extends('layouts.app')

@section('title', 'Purchase Details - FreshMart')

@section('content')
<div class="min-h-screen bg-[#050914]">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#69da58]">Purchase Details</h1>
            <p class="mt-2 text-sm text-slate-300">Track full information for this purchase.</p>
        </div>
        <a href="{{ route('buyer.purchases.index') }}" class="inline-flex items-center text-sm font-semibold text-[#69da58] hover:text-[#8ef07f] transition">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Purchases
        </a>
    </div>

    @php
        $product = $purchase->product;
        $productImage = $product && $product->images->first() ? $product->images->first()->image_path : null;
        $status = $purchase->tracking_status;
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

    <div class="rounded-xl border border-[#1d2c4f] bg-[#0a1226]/95 shadow-[0_10px_30px_rgba(0,0,0,0.35)] overflow-hidden">
        <div class="p-6 border-b border-[#1b2845]">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <p class="text-sm text-slate-400">Order ID</p>
                    <p class="text-lg font-bold text-slate-100">{{ $purchase->order_id }}</p>
                </div>
                <span class="inline-flex w-fit items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ str_replace('_', ' ', ucfirst($status)) }}</span>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <p class="text-sm text-slate-400 mb-3">Product Image</p>
                <div class="h-48 w-full max-w-sm overflow-hidden rounded-lg border border-[#2a3c66] bg-[#081126]">
                    @if($productImage)
                        <img src="{{ asset('storage/' . $productImage) }}" alt="{{ optional($product)->name ?? 'Product image' }}" class="h-full w-full object-cover object-center">
                    @else
                        <div class="h-full w-full flex items-center justify-center text-slate-500">
                            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>
            </div>
            <div>
                <p class="text-sm text-slate-400">Product</p>
                <p class="text-base font-semibold text-slate-100">{{ optional($product)->name ?? 'Unknown Product' }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-400">Quantity</p>
                <p class="text-base font-semibold text-slate-100">{{ $purchase->quantity }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-400">Fulfillment Type</p>
                <p class="text-base font-semibold text-slate-100">{{ ucfirst($purchase->fulfillment_type ?? 'delivery') }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-400">Payment Method</p>
                <p class="text-base font-semibold text-slate-100">{{ strtoupper($purchase->payment_mode ?? 'cod') }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-slate-400">Delivery Address</p>
                <p class="text-base font-semibold text-slate-100">{{ $purchase->address }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-400">Placed On</p>
                <p class="text-base font-semibold text-slate-100">{{ $purchase->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-400">Estimated Date</p>
                <p class="text-base font-semibold text-slate-100">{{ optional($purchase->estimated_date)->format('M d, Y') ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-400">Delivered Date</p>
                <p class="text-base font-semibold text-slate-100">{{ optional($purchase->delivered_date)->format('M d, Y') ?? 'Not yet delivered' }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-400">Progress</p>
                <p class="text-base font-semibold text-slate-100">{{ $purchase->progress_percentage }}%</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-slate-400">Notes</p>
                <p class="text-base font-semibold text-slate-100">{{ $purchase->notes ?: 'No additional notes.' }}</p>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
