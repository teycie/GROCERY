@extends('layouts.app')

@section('title', 'Seller Dashboard - FreshMart')

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-slate-100 transition-colors duration-200">Seller Dashboard</h1>
        <p class="mt-2 text-gray-600 dark:text-slate-300 transition-colors duration-200">Welcome back, {{ auth()->user()->name }}. Manage your inventory and store updates.</p>
    </div>
    <div class="space-x-2">
        <a href="{{ route('seller.products.create') }}" class="btn-primary inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
            <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add Product
        </a>
    </div>
</div>

<!-- Analytics Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-[#0f1726] rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6 flex flex-col justify-center transition-colors duration-200">
        <span class="text-sm font-medium text-gray-500 dark:text-slate-300 mb-1">Total Active Products</span>
        <span class="text-3xl font-extrabold text-gray-900 dark:text-slate-100">{{ $productsCount }}</span>
    </div>
    <div class="bg-white dark:bg-[#0f1726] rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6 flex flex-col justify-center transition-colors duration-200">
        <span class="text-sm font-medium text-gray-500 dark:text-slate-300 mb-1">Items Added to Carts</span>
        <span class="text-3xl font-extrabold text-blue-600 dark:text-blue-400">{{ $totalCartItems ?? 0 }}</span>
    </div>
    <div class="bg-white dark:bg-[#0f1726] rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6 flex flex-col justify-center transition-colors duration-200">
        <span class="text-sm font-medium text-gray-500 dark:text-slate-300 mb-1">Total Store Buyers</span>
        <span class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ $totalBuyers }}</span>
    </div>
    <div class="bg-white dark:bg-[#0f1726] rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6 flex flex-col justify-center transition-colors duration-200">
        <span class="text-sm font-medium text-gray-500 dark:text-slate-300 mb-1">Total Buyer Checkouts</span>
        <span class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $totalCheckoutOrders ?? 0 }}</span>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 flex flex-col justify-center transition-colors duration-200 md:col-span-1">
        <span class="text-sm font-medium text-gray-500 dark:text-slate-300 mb-1">Pending Checkout Orders</span>
        <span class="text-3xl font-extrabold text-amber-600 dark:text-amber-400">{{ $pendingCheckoutOrders ?? 0 }}</span>
    </div>
</div>

<!-- Chart Section -->
<div class="bg-white dark:bg-[#0f1726] rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6 mb-8 transition-colors duration-200">
    <h2 class="text-xl font-bold text-gray-800 dark:text-slate-100 mb-6 border-b border-gray-100 dark:border-slate-800 pb-4">Products by Category</h2>
    <div class="relative h-72 w-full">
        <canvas id="categoryChart"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    
    <!-- Stats & Actions Widget -->
    <div class="md:col-span-1 space-y-8">
        <!-- Summary Card -->
        <div class="bg-white dark:bg-[#0f1726] rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6 transition-colors duration-200">
            <h2 class="text-xl font-bold text-gray-800 dark:text-slate-100 mb-4 flex items-center">
                <svg class="h-6 w-6 text-green-500 dark:text-green-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Quick Actions
            </h2>
            
            <div class="space-y-3">
                <a href="{{ route('seller.products.index') }}" class="w-full text-center block bg-gray-50 dark:bg-[#18243a] hover:bg-gray-100 dark:hover:bg-[#1d2b45] text-gray-800 dark:text-slate-100 font-semibold py-2 px-4 border border-gray-200 dark:border-slate-700 rounded transition duration-200">
                    Manage Products
                </a>
                <a href="{{ route('seller.inventory.index') }}" class="w-full text-center block bg-gray-50 dark:bg-[#18243a] hover:bg-gray-100 dark:hover:bg-[#1d2b45] text-gray-800 dark:text-slate-100 font-semibold py-2 px-4 border border-gray-200 dark:border-slate-700 rounded transition duration-200">
                    View Inventory
                </a>
                <a href="{{ route('seller.deliveries.index') }}" class="w-full text-center block bg-gray-50 dark:bg-[#18243a] hover:bg-gray-100 dark:hover:bg-[#1d2b45] text-gray-800 dark:text-slate-100 font-semibold py-2 px-4 border border-gray-200 dark:border-slate-700 rounded transition duration-200">
                    Track Deliveries
                </a>
                <a href="{{ route('seller.announcements.create') }}" class="w-full text-center block bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow-sm transition duration-200">
                    Post New Announcement
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Announcements -->
    <div class="md:col-span-2">
        <div class="bg-white dark:bg-[#0f1726] rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6 h-full transition-colors duration-200">
            <div class="flex justify-between items-center border-b border-gray-100 dark:border-slate-800 pb-4 mb-4">
                <h2 class="text-xl font-bold text-gray-800 dark:text-slate-100">Recent Announcements by You</h2>
                <span class="text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 px-3 py-1 rounded-full">Updates</span>
            </div>
            
            <div class="space-y-4">
                @forelse($announcements as $announcement)
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-[#18243a] border border-gray-200 dark:border-slate-700 hover:border-slate-500 transition duration-200">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-gray-900 dark:text-slate-100">{{ $announcement->title }}</h3>
                            <span class="text-xs text-gray-400 dark:text-slate-400">{{ $announcement->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-slate-300">{{ $announcement->message }}</p>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-slate-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p class="text-gray-500 dark:text-slate-300 font-medium">You haven't posted any announcements yet.</p>
                        <a href="{{ route('seller.announcements.create') }}" class="text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 text-sm font-semibold mt-2 inline-block">Create one now &rarr;</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="mt-8 bg-white dark:bg-[#0f1726] rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6 transition-colors duration-200">
    <div class="flex justify-between items-center border-b border-gray-100 dark:border-slate-800 pb-4 mb-4">
        <h2 class="text-xl font-bold text-gray-800 dark:text-slate-100">Recent Buyer Checkouts</h2>
        <a href="{{ route('seller.deliveries.index') }}" class="text-sm font-medium text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300">View deliveries</a>
    </div>

    <div class="space-y-3">
        @forelse($recentCheckouts as $checkout)
            <div class="rounded-lg border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-[#18243a] p-4">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-slate-100">{{ $checkout->product->name ?? 'Unknown Product' }}</p>
                        <p class="text-xs text-gray-500 dark:text-slate-400">Order {{ $checkout->order_id }} • Buyer {{ $checkout->user->name ?? 'Unknown Buyer' }}</p>
                    </div>
                    <div class="text-left md:text-right">
                        <p class="text-sm font-semibold text-green-600 dark:text-green-400">Qty: {{ $checkout->quantity }}</p>
                        <p class="text-xs text-gray-500 dark:text-slate-400">{{ $checkout->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-lg border border-dashed border-gray-300 dark:border-slate-600 p-6 text-center text-gray-500 dark:text-slate-300">
                No buyer checkouts yet.
            </div>
        @endforelse
    </div>
</div>

<!-- Chart.js include and script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('categoryChart').getContext('2d');
        
        // Data populated from controller
        const labels = {!! json_encode($chartLabels ?? []) !!};
        const data = {!! json_encode($chartValues ?? []) !!};
        
        // Match dark mode grid colors dynamically setup for chart label/grid
        const isDarkMode = document.documentElement.classList.contains('dark');
        const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
        const tickColor = isDarkMode ? '#9ca3af' : '#6b7280';
        
        if (labels.length === 0) {
            // If no data, hide chart or show message
            document.getElementById('categoryChart').parentElement.innerHTML = '<div class="absolute inset-0 flex items-center justify-center text-gray-400 dark:text-gray-500 font-medium">No products available to chart.</div>';
            return;
        }

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Number of Products',
                    data: data,
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgba(37, 99, 235, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: 'rgba(37, 99, 235, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: tickColor
                        },
                        grid: {
                            color: gridColor
                        }
                    },
                    x: {
                        ticks: {
                            color: tickColor
                        },
                        grid: {
                            color: gridColor
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Watch for Dark Mode Changes to naturally update the chart ticks and grid
        const observer = new MutationObserver(function() {
            const isDark = document.documentElement.classList.contains('dark');
            const newGridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
            const newTickColor = isDark ? '#9ca3af' : '#6b7280';
            
            chart.options.scales.x.grid.color = newGridColor;
            chart.options.scales.x.ticks.color = newTickColor;
            chart.options.scales.y.grid.color = newGridColor;
            chart.options.scales.y.ticks.color = newTickColor;
            chart.update();
        });
        
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    });
</script>
@endsection
