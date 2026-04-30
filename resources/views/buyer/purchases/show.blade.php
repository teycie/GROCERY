@extends('layouts.app')

@section('title', 'Track Your Purchase - FreshMart')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('buyer.purchases.index') }}" class="inline-flex items-center text-sm font-semibold text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 transition mb-2">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Purchases
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-slate-100">Track Your Purchase</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-slate-400">Order ID: <span class="font-bold text-gray-900 dark:text-slate-200">{{ $purchase->order_id }}</span></p>
        </div>
    </div>

    @php
        $product = $purchase->product;
        $productImage = $product && $product->images->first() ? $product->images->first()->image_path : null;
        $status = $purchase->tracking_status;
        $isPickup = $purchase->fulfillment_type === 'pickup';
        $totalPrice = $product ? $product->price * $purchase->quantity : 0;

        $statusClass = 'bg-gray-100 text-gray-800 dark:bg-slate-800 dark:text-slate-200';
        if (in_array($status, ['approved'], true)) {
            $statusClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300';
        } elseif (in_array($status, ['processing', 'preparing'], true)) {
            $statusClass = 'bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300';
        } elseif (in_array($status, ['shipped', 'ready', 'rider_assigned'], true)) {
            $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-500/20 dark:text-blue-300';
        } elseif (in_array($status, ['out_for_delivery', 'ready_to_pickup', 'on_delivery'], true)) {
            $statusClass = 'bg-violet-100 text-violet-800 dark:bg-violet-500/20 dark:text-violet-300';
        } elseif (in_array($status, ['delivered', 'picked_up'], true)) {
            $statusClass = 'bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-300';
        } elseif ($status === 'cancelled') {
            $statusClass = 'bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-300';
        }

        $timeline = $isPickup
            ? [
                'pending' => 'Order Placed',
                'approved' => 'Order Approved',
                'preparing' => 'Preparing',
                'ready' => 'Ready',
                'ready_to_pickup' => 'Ready for Pick-up',
                'picked_up' => 'Completed'
            ]
            : [
                'pending' => 'Order Placed',
                'approved' => 'Order Approved',
                'preparing' => 'Preparing',
                'rider_assigned' => 'Rider Assigned',
                'picked_up' => 'Picked Up by Rider',
                'on_delivery' => 'On Delivery',
                'delivered' => 'Delivered'
            ];

        $currentStepIndex = array_search($status, array_keys($timeline));
        if ($currentStepIndex === false && in_array($status, ['processing'])) {
             $currentStepIndex = 2; // fallback
        } elseif ($currentStepIndex === false && in_array($status, ['shipped'])) {
             $currentStepIndex = 3;
        } elseif ($currentStepIndex === false && in_array($status, ['out_for_delivery'])) {
             $currentStepIndex = 4;
        }

        if ($status === 'cancelled') {
             $currentStepIndex = -1; // hide timeline or mark cancelled
        }
    @endphp

    <div class="space-y-6">
        <!-- Status & Timeline Card -->
        <div class="rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-gray-50 dark:bg-slate-800/50">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-slate-100">Order Status</h2>
                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">Placed on {{ $purchase->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <span class="inline-flex items-center rounded-full px-4 py-1.5 text-sm font-bold {{ $statusClass }}">
                    {{ str_replace('_', ' ', ucfirst($status)) }}
                </span>
            </div>

            @if($status !== 'cancelled')
            <div class="p-6 overflow-x-auto">
                <div class="min-w-[600px] py-4">
                    <div class="relative flex justify-between">
                        <!-- Progress Bar Background -->
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-200 dark:bg-slate-700 rounded-full"></div>
                        <!-- Progress Bar Fill -->
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-green-500 rounded-full transition-all duration-500" style="width: {{ $purchase->progress_percentage }}%"></div>

                        @php $i = 0; @endphp
                        @foreach($timeline as $key => $label)
                            @php
                                $isCompleted = $i <= $currentStepIndex;
                                $isCurrent = $i === $currentStepIndex;
                            @endphp
                            <div class="relative flex flex-col items-center justify-center w-10 z-10 group">
                                <div class="w-8 h-8 rounded-full border-4 flex items-center justify-center transition-colors duration-300 {{ $isCompleted ? 'border-green-500 bg-green-500 text-white' : 'border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-transparent' }} {{ $isCurrent ? 'ring-4 ring-green-100 dark:ring-green-900/30' : '' }}">
                                    @if($isCompleted)
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                    @endif
                                </div>
                                <div class="absolute top-10 w-32 text-center">
                                    <span class="text-xs font-bold {{ $isCurrent ? 'text-green-600 dark:text-green-400' : ($isCompleted ? 'text-gray-900 dark:text-slate-300' : 'text-gray-400 dark:text-slate-500') }}">{{ $label }}</span>
                                </div>
                            </div>
                            @php $i++; @endphp
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <div class="p-6 flex items-center justify-center text-red-500">
                <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-bold">This order has been cancelled.</span>
            </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Purchase Details -->
            <div class="lg:col-span-2 rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-slate-800">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-slate-100">Purchase Details</h2>
                </div>
                
                <div class="p-6 flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-32 h-32 flex-shrink-0 overflow-hidden rounded-xl border border-gray-100 dark:border-slate-800 bg-gray-50 dark:bg-slate-800/50">
                        @if($productImage)
                            <img src="{{ asset('storage/' . $productImage) }}" alt="{{ optional($product)->name ?? 'Product image' }}" class="h-full w-full object-cover object-center">
                        @else
                            <div class="h-full w-full flex items-center justify-center text-gray-400 dark:text-slate-500">
                                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex-1 space-y-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-slate-100">{{ optional($product)->name ?? 'Unknown Product' }}</h3>
                            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">Sold by {{ optional($purchase->seller)->name ?? 'Unknown Seller' }}</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-slate-400">Unit Price</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-slate-100">₱{{ number_format(optional($product)->price ?? 0, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-slate-400">Quantity</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-slate-100">{{ $purchase->quantity }}x</p>
                            </div>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-100 dark:border-slate-800">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-gray-700 dark:text-slate-300">Total Order Amount</span>
                                <span class="text-xl font-extrabold text-green-600 dark:text-green-400">₱{{ number_format($totalPrice, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery & Payment Info -->
            <div class="rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden flex flex-col">
                <div class="p-6 border-b border-gray-100 dark:border-slate-800">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-slate-100">Order Information</h2>
                </div>
                
                <div class="p-6 flex-1 space-y-5">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-slate-400 mb-1">Fulfillment & Payment</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-slate-100">{{ ucfirst($purchase->fulfillment_type ?? 'delivery') }} — {{ strtoupper($purchase->payment_mode ?? 'cod') }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-slate-400 mb-1">Estimated Date</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-slate-100">{{ optional($purchase->estimated_date)->format('M d, Y') ?? 'TBA' }}</p>
                    </div>

                    @if($purchase->delivered_date)
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-slate-400 mb-1">Delivered On</p>
                        <p class="text-sm font-bold text-green-600 dark:text-green-400">{{ $purchase->delivered_date->format('M d, Y') }}</p>
                    </div>
                    @endif

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-slate-400 mb-1">Delivery Address</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-slate-100">{{ $purchase->address ?? 'No address provided' }}</p>
                    </div>

                    @if($purchase->rider_id)
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-slate-800/50 border border-gray-100 dark:border-slate-800">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-slate-400 mb-1">Assigned Rider</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-slate-100 flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            {{ optional($purchase->rider)->name }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Notes Section -->
            @if($purchase->notes)
            <div class="lg:col-span-3 rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-slate-800">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-slate-100">Order Notes</h2>
                </div>
                <div class="p-6">
                    <div class="prose dark:prose-invert prose-sm max-w-none text-gray-700 dark:text-slate-300">
                        {!! nl2br(e($purchase->notes)) !!}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
