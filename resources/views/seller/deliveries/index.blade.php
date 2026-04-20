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

<div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
    <div class="border-b border-gray-100 px-6 py-4 dark:border-slate-700">
        <h2 class="text-xl font-bold text-gray-800 dark:text-slate-100">Delivery Progress</h2>
    </div>

    <div class="space-y-4 p-6">
        @forelse($deliveries as $delivery)
            @php
                $statusClass = 'bg-gray-100 text-gray-800 dark:bg-slate-800 dark:text-slate-200';
                switch ($delivery->status) {
                    case 'processing':
                        $statusClass = 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300';
                        break;
                    case 'shipped':
                        $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300';
                        break;
                    case 'out_for_delivery':
                        $statusClass = 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300';
                        break;
                    case 'delivered':
                        $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300';
                        break;
                    case 'cancelled':
                        $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300';
                        break;
                }
                $progress = $delivery->progress_percentage;
                $progressClass = $delivery->status === 'cancelled' ? 'bg-red-500' : 'bg-green-500';
                $statusLabels = ['pending' => 'Pending', 'processing' => 'Processing', 'shipped' => 'Shipped', 'out_for_delivery' => 'Out for Delivery', 'delivered' => 'Delivered'];
            @endphp

            <div class="rounded-xl border border-gray-100 bg-gray-50 p-5 transition hover:border-green-200 dark:border-slate-700 dark:bg-slate-800/60 dark:hover:border-green-700/60">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="space-y-3">
                        <div class="flex flex-wrap items-center gap-3">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100">{{ $delivery->product->name ?? 'Unknown Product' }}</h3>
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ str_replace('_', ' ', ucfirst($delivery->status)) }}</span>
                        </div>
                        <div class="grid gap-2 text-sm text-gray-600 dark:text-slate-300 sm:grid-cols-2 lg:grid-cols-4">
                            <p><span class="font-semibold text-gray-800 dark:text-slate-100">Order:</span> {{ $delivery->order_id }}</p>
                            <p><span class="font-semibold text-gray-800 dark:text-slate-100">Buyer:</span> {{ $delivery->user->name ?? 'Unknown' }}</p>
                            <p><span class="font-semibold text-gray-800 dark:text-slate-100">Quantity:</span> {{ $delivery->quantity }}</p>
                            <p><span class="font-semibold text-gray-800 dark:text-slate-100">ETA:</span> {{ $delivery->estimated_date ? $delivery->estimated_date->format('M d, Y') : 'Not set' }}</p>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-slate-300"><span class="font-semibold text-gray-800 dark:text-slate-100">Address:</span> {{ $delivery->address ?? 'No delivery address provided' }}</p>
                        @if($delivery->notes)
                            <p class="text-sm text-gray-600 dark:text-slate-300"><span class="font-semibold text-gray-800 dark:text-slate-100">Notes:</span> {{ $delivery->notes }}</p>
                        @endif
                    </div>

                    <div class="w-full max-w-xl rounded-xl bg-white p-4 shadow-sm dark:bg-slate-900">
                        <div class="mb-3 flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-700 dark:text-slate-200">Progress</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-slate-100">{{ $progress }}%</span>
                        </div>
                        <div class="h-3 rounded-full bg-gray-100 dark:bg-slate-700">
                            <div class="h-3 rounded-full {{ $progressClass }}" style="width: {{ $progress }}%"></div>
                        </div>
                        <div class="mt-4 grid grid-cols-5 gap-2 text-center text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-slate-400">
                            @foreach($statusLabels as $stageKey => $stageLabel)
                                <span class="rounded-lg px-2 py-2 {{ $stageKey === $delivery->status ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' : 'bg-gray-100 text-gray-500 dark:bg-slate-800 dark:text-slate-500' }}">{{ $stageLabel }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-16 text-center text-gray-500 dark:text-slate-300">
                <p class="text-lg font-semibold text-gray-800 dark:text-slate-100">No deliveries yet</p>
                <p class="mt-1">Delivery progress will appear here once orders are assigned to your store.</p>
            </div>
        @endforelse
    </div>

    @if($deliveries->hasPages())
        <div class="border-t border-gray-100 px-6 py-4 dark:border-slate-700">
            {{ $deliveries->links() }}
        </div>
    @endif
</div>

<div class="mt-8 grid gap-6 md:grid-cols-2">
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100">Status Breakdown</h3>
        <div class="mt-4 space-y-3">
            @foreach(['pending', 'processing', 'shipped', 'out_for_delivery', 'delivered', 'cancelled'] as $status)
                <div class="flex items-center justify-between rounded-lg bg-gray-50 px-4 py-3 dark:bg-slate-800/70">
                    <span class="text-sm font-medium text-gray-700 dark:text-slate-200">{{ str_replace('_', ' ', ucfirst($status)) }}</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-slate-100">{{ $statusBreakdown[$status] ?? 0 }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="rounded-xl border border-gray-100 bg-gradient-to-br from-green-50 to-emerald-100 p-6 shadow-sm dark:border-slate-700 dark:from-emerald-900/40 dark:to-slate-900">
        <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100">What this page shows</h3>
        <p class="mt-3 text-sm leading-6 text-gray-700 dark:text-slate-300">Each delivery card includes the buyer, product, expected date, and a visible progress bar based on the current order status. Use it to see where every shipment stands without leaving the seller dashboard.</p>
    </div>
</div>
@endsection