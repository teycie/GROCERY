@extends('layouts.app')

@section('title', 'My Profile - FreshMart')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-slate-100">User Profile</h1>
        <p class="text-gray-600 dark:text-slate-300 mt-2">Manage your account information and preferences.</p>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden mb-8">
        <div class="h-32 bg-gradient-to-r from-green-500 to-emerald-500 dark:from-emerald-600 dark:to-green-500"></div>
        <div class="px-8 pb-8">
            <div class="relative flex justify-between items-end -mt-12 mb-6">
                <div class="h-24 w-24 rounded-full bg-white dark:bg-slate-800 border-4 border-white dark:border-slate-800 shadow-md flex items-center justify-center text-4xl bg-gray-100 dark:bg-slate-800 text-green-600 dark:text-green-400 font-bold overflow-hidden shadow">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <a href="{{ route('profile.edit') }}" class="bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-slate-200 hover:bg-gray-50 dark:hover:bg-slate-800 px-4 py-2 rounded-lg font-semibold transition shadow-sm text-sm flex items-center">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        Edit Profile
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-slate-100 mb-1">{{ $user->name }}</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold capitalize
                        {{ $user->role === 'seller' ? 'bg-blue-100 text-blue-800 dark:bg-blue-500/20 dark:text-blue-300' : 'bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-300' }}">
                        {{ $user->role }} Account
                    </span>
                </div>
                
                <div class="space-y-4 pt-2">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-6 w-6 flex items-center justify-center text-gray-400 dark:text-slate-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wide">Username or Email</p>
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
@endsection
