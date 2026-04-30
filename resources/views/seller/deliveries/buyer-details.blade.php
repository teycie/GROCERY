@extends('layouts.app')

@section('title', 'Buyer Checkout Details - FreshMart')

@section('content')
<div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
    <div>
        <p class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-slate-400">Buyer Checkout Details</p>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-slate-100">
            {{ $buyer->username ?? $buyer->name }}
        </h1>
        <p class="mt-2 text-gray-600 dark:text-slate-300">
            {{ $buyer->name ?? 'No full name' }}
        </p>
    </div>
    <a href="{{ route('seller.deliveries.index') }}" class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-4 py-2 font-semibold text-white transition hover:bg-gray-800 dark:bg-slate-700 dark:hover:bg-slate-600">
        Back to Deliveries
    </a>
</div>

<div class="mb-8 grid gap-6 md:grid-cols-2">
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <p class="text-sm font-medium text-gray-500 dark:text-slate-300">Total Checkouts</p>
        <p class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-slate-100">{{ $totalCheckouts }}</p>
    </div>
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <p class="text-sm font-medium text-gray-500 dark:text-slate-300">Total Products Checked Out</p>
        <p class="mt-2 text-3xl font-extrabold text-green-600 dark:text-green-400">{{ $totalProducts }}</p>
    </div>
</div>

<div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
    <div class="border-b border-gray-100 px-6 py-4 dark:border-slate-700">
        <h2 class="text-xl font-bold text-gray-800 dark:text-slate-100">Delivery Progress</h2>
    </div>

    <div class="space-y-4 p-6">
        @forelse($deliveries as $delivery)
            @php
                $currentStatus = $delivery->tracking_status;
                $statusClass = 'bg-gray-100 text-gray-800 dark:bg-slate-800 dark:text-slate-200';
                switch ($currentStatus) {
                    case 'approved':
                        $statusClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300';
                        break;
                    case 'processing':
                    case 'preparing':
                        $statusClass = 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300';
                        break;
                    case 'shipped':
                    case 'ready':
                        $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300';
                        break;
                    case 'out_for_delivery':
                    case 'ready_to_pickup':
                        $statusClass = 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300';
                        break;
                    case 'delivered':
                    case 'picked_up':
                        $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300';
                        break;
                    case 'cancelled':
                        $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300';
                        break;
                }
                $progress = $delivery->progress_percentage;
                $progressClass = $currentStatus === 'cancelled' ? 'bg-red-500' : 'bg-green-500';
                $isPickup = $delivery->fulfillment_type === 'pickup';
                $statusLabels = $isPickup
                    ? ['pending' => 'Pending', 'approved' => 'Approved', 'preparing' => 'Preparing', 'ready' => 'Ready', 'ready_to_pickup' => 'Ready to Pick-up', 'picked_up' => 'Picked Up']
                    : ['pending' => 'Pending', 'approved' => 'Approved', 'processing' => 'Processing', 'shipped' => 'Shipped', 'out_for_delivery' => 'Out for Delivery', 'delivered' => 'Delivered'];
                $statusOptions = $isPickup
                    ? ['pending' => 'Pending', 'approved' => 'Approve Order', 'preparing' => 'Preparing', 'ready' => 'Ready', 'ready_to_pickup' => 'Ready to Pick-up', 'picked_up' => 'Picked Up', 'cancelled' => 'Cancelled']
                    : ['pending' => 'Pending', 'approved' => 'Approve Order', 'processing' => 'Processing', 'shipped' => 'Shipped', 'out_for_delivery' => 'Out for Delivery', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'];
            @endphp

            <div class="rounded-xl border border-gray-100 bg-gray-50 p-5 transition hover:border-green-200 dark:border-slate-700 dark:bg-slate-800/60 dark:hover:border-green-700/60">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="space-y-3">
                        <div class="flex flex-wrap items-center gap-3">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100">{{ $delivery->product->name ?? 'Unknown Product' }}</h3>
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ str_replace('_', ' ', ucfirst($currentStatus)) }}</span>
                        </div>
                        <div class="grid gap-2 text-sm text-gray-600 dark:text-slate-300 sm:grid-cols-2 lg:grid-cols-4">
                            <p><span class="font-semibold text-gray-800 dark:text-slate-100">Order:</span> {{ $delivery->order_id }}</p>
                            <p><span class="font-semibold text-gray-800 dark:text-slate-100">Buyer:</span> {{ $delivery->user->name ?? 'Unknown' }}</p>
                            <p><span class="font-semibold text-gray-800 dark:text-slate-100">Quantity:</span> {{ $delivery->quantity }}</p>
                            <p><span class="font-semibold text-gray-800 dark:text-slate-100">ETA:</span> {{ $delivery->estimated_date ? $delivery->estimated_date->format('M d, Y') : 'Not set' }}</p>
                        </div>
                        <div class="grid gap-2 text-sm text-gray-600 dark:text-slate-300 sm:grid-cols-2">
                            <p><span class="font-semibold text-gray-800 dark:text-slate-100">Fulfillment:</span> {{ ucfirst($delivery->fulfillment_type ?? 'delivery') }}</p>
                            <p><span class="font-semibold text-gray-800 dark:text-slate-100">Payment:</span> {{ strtoupper($delivery->payment_mode ?? '-') }}</p>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-slate-300"><span class="font-semibold text-gray-800 dark:text-slate-100">Address:</span> {{ $delivery->address ?? 'No delivery address provided' }}</p>
                        @if($delivery->notes)
                            <div class="text-sm text-gray-600 dark:text-slate-300"><span class="font-semibold text-gray-800 dark:text-slate-100">Notes:</span> <div class="mt-1">{!! nl2br(e($delivery->notes)) !!}</div></div>
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
                        <div class="mt-4 grid grid-cols-3 md:grid-cols-6 gap-2 text-center text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-slate-400">
                            @foreach($statusLabels as $stageKey => $stageLabel)
                                <span class="rounded-lg px-2 py-2 {{ $stageKey === $currentStatus ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' : 'bg-gray-100 text-gray-500 dark:bg-slate-800 dark:text-slate-500' }}">{{ $stageLabel }}</span>
                            @endforeach
                        </div>

                        <!-- Status & Notes Form -->
                        <form action="{{ route('seller.deliveries.update-status', $delivery) }}" method="POST" class="mt-4 grid gap-3 md:grid-cols-3 border-b border-gray-100 dark:border-slate-700 pb-4">
                            @csrf
                            @method('PUT')
                            <div class="md:col-span-1">
                                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-slate-300 mb-1">Update Status</label>
                                <select name="status" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 text-sm">
                                    @foreach($statusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ $currentStatus === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-slate-300 mb-1">Seller Note</label>
                                <textarea name="notes" rows="1" maxlength="500" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 text-sm" placeholder="Optional note">{{ $delivery->notes }}</textarea>
                            </div>
                            <div class="md:col-span-1 flex items-end">
                                <button type="submit" class="w-full rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4 transition">Save Status</button>
                            </div>
                        </form>

                        <!-- Rider Assignment Form / Info -->
                        @if(!$isPickup && in_array($currentStatus, ['approved', 'preparing', 'rider_assigned', 'picked_up', 'on_delivery', 'delivered']))
                            <div class="mt-4 bg-gray-50 dark:bg-slate-800/50 p-3 rounded-lg border border-gray-100 dark:border-slate-700">
                                @if($delivery->rider_id)
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-slate-400">Assigned Rider</p>
                                            <p class="text-sm font-bold text-gray-900 dark:text-slate-100">{{ optional($delivery->rider)->name }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300">
                                                Assigned
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <form action="{{ route('seller.deliveries.assign-rider', $delivery) }}" method="POST" class="grid gap-3 md:grid-cols-3">
                                        @csrf
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-slate-300 mb-1">Assign Rider</label>
                                            <select name="rider_id" required class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 text-sm">
                                                <option value="">-- Select Rider --</option>
                                                @foreach($availableRiders as $availableRider)
                                                    <option value="{{ $availableRider->id }}">{{ $availableRider->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="md:col-span-1 flex items-end">
                                            <button type="submit" class="w-full rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-4 transition">Assign</button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="py-16 text-center text-gray-500 dark:text-slate-300">
                <p class="text-lg font-semibold text-gray-800 dark:text-slate-100">No deliveries yet</p>
                <p class="mt-1">Delivery progress will appear here once this buyer checks out from your store.</p>
            </div>
        @endforelse
    </div>

    @if($deliveries->hasPages())
        <div class="border-t border-gray-100 px-6 py-4 dark:border-slate-700">
            {{ $deliveries->links() }}
        </div>
    @endif
</div>
@endsection
