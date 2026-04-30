@extends('layouts.app')

@section('title', 'System Settings - FreshMart')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('profile.show') }}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 text-sm font-medium mb-1 inline-block">&larr; Back to Profile</a>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-slate-100">System Settings</h1>
            <p class="text-gray-600 dark:text-slate-300 mt-2">Manage your app preferences and theme.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 p-6 md:p-8">
        <div class="space-y-6">
            <div class="p-4 border border-gray-100 dark:border-slate-700 rounded-lg bg-gray-50 dark:bg-slate-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-base font-bold text-gray-900 dark:text-slate-100">Appearance</p>
                        <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">Toggle between light and dark mode</p>
                    </div>
                    <button id="theme-toggle" type="button" class="group inline-flex items-center gap-3 rounded-full border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-700 px-4 py-3 shadow-sm hover:border-green-500 dark:hover:border-green-500 transition-all" aria-pressed="false">
                        <span id="theme-toggle-label" class="text-sm font-bold uppercase tracking-wide text-gray-700 dark:text-slate-200">Light Mode</span>
                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-300 dark:bg-green-600 transition-colors">
                            <span id="theme-toggle-knob" class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform translate-x-1"></span>
                        </span>
                    </button>
                </div>
            </div>

            <!-- Other system settings can go here in the future -->
        </div>
    </div>
</div>
@endsection
