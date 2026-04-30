@extends('layouts.app')

@section('title', 'My Profile - FreshMart')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-slate-100">User Profile</h1>
        <p class="text-gray-600 dark:text-slate-300 mt-2">Manage your account information and preferences.</p>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="h-48 bg-gradient-to-r from-green-500 to-emerald-500 dark:from-emerald-600 dark:to-green-500"></div>

        <div class="px-6 md:px-10 pb-8 -mt-16">
            <div class="grid grid-cols-1 lg:grid-cols-[300px_minmax(0,1fr)] gap-8">
                <aside>
                    <div class="h-36 w-36 rounded-full bg-gray-100 dark:bg-slate-800 border-8 border-white dark:border-slate-900 shadow-md flex items-center justify-center text-5xl text-green-600 dark:text-green-400 font-bold overflow-hidden">
                        {{ substr($user->name, 0, 1) }}
                    </div>

                    <h2 class="mt-6 text-4xl font-bold text-gray-900 dark:text-slate-100">{{ $user->name }}</h2>
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold capitalize mt-3
                        {{ $user->role === 'seller' ? 'bg-blue-100 text-blue-800 dark:bg-blue-500/20 dark:text-blue-300' : 'bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-300' }}">
                        {{ $user->role }} Account
                    </span>

                    <div class="mt-8 space-y-3">
                        <a href="{{ route('profile.edit') }}" class="w-full flex items-center justify-between rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 px-5 py-3 text-left text-sm font-semibold text-gray-800 dark:text-slate-100 hover:border-green-500 hover:text-green-700 dark:hover:text-green-300 transition">
                            <span>Edit Profile</span>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        </a>

                        <a href="{{ route('profile.settings') }}" class="w-full flex items-center justify-between rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-5 py-3 text-left text-sm font-semibold text-gray-700 dark:text-slate-200 hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                            <span>Account Settings</span>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>

                        <a href="{{ route('profile.system-settings') }}" class="w-full flex items-center justify-between rounded-xl border border-green-200 dark:border-green-700 bg-green-50 dark:bg-green-900/20 px-5 py-3 text-left text-sm font-semibold text-green-800 dark:text-green-300 hover:border-green-500 hover:text-green-700 dark:hover:text-green-400 transition">
                            <span>System Settings</span>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                </aside>

                <div class="pt-32 lg:pt-24">
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-slate-100">Profile Details</h3>
                    </div>

                    <div class="space-y-6 max-w-xl">
                        <!-- Profile details list continues here -->

                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 flex items-center justify-center text-gray-400 dark:text-slate-500">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wide">Username</p>
                                <p class="text-base text-gray-900 dark:text-slate-100">{{ $user->username }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 flex items-center justify-center text-gray-400 dark:text-slate-500">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wide">Email</p>
                                <p class="text-base text-gray-900 dark:text-slate-100">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 flex items-center justify-center text-gray-400 dark:text-slate-500">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wide">Member Since</p>
                                <p class="text-base text-gray-900 dark:text-slate-100">{{ $user->created_at->format('F d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
