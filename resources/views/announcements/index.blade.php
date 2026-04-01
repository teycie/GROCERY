@extends('layouts.app')

@section('title', 'Announcements - FreshMart')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 border-b border-gray-200 pb-6 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Latest Announcements</h1>
            <p class="text-gray-500 mt-2">Stay updated with the newest deals and information from our sellers.</p>
        </div>
        @if(auth()->check() && (auth()->user()->role === 'seller' || auth()->user()->role === 'admin'))
            <a href="{{ route('seller.announcements.create') }}" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-green-600 hover:bg-green-700 transition">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Post New Announcement
            </a>
        @endif
    </div>

    <div class="space-y-6">
        @forelse($announcements as $announcement)
            <div class="bg-white rounded-xl shadow-sm border border-blue-50 overflow-hidden relative group hover:shadow-md transition">
                <!-- Decorative element -->
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500"></div>
                
                <div class="p-6 pl-8">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-3">
                        <h2 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition">{{ $announcement->title }}</h2>
                        <span class="inline-flex items-center text-xs font-medium text-gray-500 mt-1 sm:mt-0 bg-gray-100 px-2.5 py-0.5 rounded-full">
                            <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $announcement->created_at->format('M d, Y') }}
                        </span>
                    </div>
                    
                    <div class="prose prose-sm max-w-none text-gray-600">
                        <p class="whitespace-pre-line">{{ $announcement->message }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-gray-50 rounded-xl border border-gray-100 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900">No announcements</h3>
                <p class="mt-1 text-gray-500">There are no announcements currently published by the sellers.</p>
            </div>
        @endforelse
    </div>

    @if($announcements->hasPages())
        <div class="mt-8">
            {{ $announcements->links() }}
        </div>
    @endif
</div>
@endsection
