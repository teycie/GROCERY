@extends('layouts.app')

@section('title', 'Account Settings - FreshMart')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('profile.show') }}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 text-sm font-medium mb-1 inline-block">&larr; Back to Profile</a>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-slate-100">Account Settings</h1>
            <p class="text-gray-600 dark:text-slate-300 mt-2">Change your email address and account password.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 p-6 md:p-8">
        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 text-green-800 px-4 py-3 text-sm dark:border-green-500/30 dark:bg-green-500/10 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 text-red-800 px-4 py-3 text-sm dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-300">
                Please fix the highlighted fields below.
            </div>
        @endif

        <form action="{{ route('profile.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-200 mb-1">Email Address <span class="text-red-500">*</span></label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:border-green-500 focus:ring focus:ring-green-200 dark:focus:ring-green-500/20 focus:ring-opacity-50 py-3 px-4 transition"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t border-gray-100 dark:border-slate-800 pt-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100 mb-2">Change Password</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-200 mb-1">Current Password</label>
                        <input
                            type="password"
                            name="current_password"
                            placeholder="Enter your current password"
                            class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:border-green-500 focus:ring focus:ring-green-200 dark:focus:ring-green-500/20 focus:ring-opacity-50 py-3 px-4 transition"
                        >
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-200 mb-1">New Password</label>
                        <input
                            type="password"
                            name="password"
                            placeholder="Enter new password"
                            class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:border-green-500 focus:ring focus:ring-green-200 dark:focus:ring-green-500/20 focus:ring-opacity-50 py-3 px-4 transition"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-200 mb-1">Confirm New Password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            placeholder="Confirm new password"
                            class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:border-green-500 focus:ring focus:ring-green-200 dark:focus:ring-green-500/20 focus:ring-opacity-50 py-3 px-4 transition"
                        >
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('profile.show') }}" class="px-6 py-3 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-200 bg-white dark:bg-slate-900 rounded-lg font-bold hover:bg-gray-50 dark:hover:bg-slate-800 transition shadow-sm">Cancel</a>
                <button type="submit" class="px-6 py-3 border border-transparent bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition shadow-md shadow-green-600/30">
                    Save Account Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
