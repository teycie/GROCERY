@extends('layouts.app')

@section('title', 'Edit Profile - FreshMart')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('profile.show') }}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 text-sm font-medium mb-1 inline-block">&larr; Back to Profile</a>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-slate-100">Edit Profile</h1>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 p-8 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800">
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-200 mb-1">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:border-green-500 focus:ring focus:ring-green-200 dark:focus:ring-green-500/20 focus:ring-opacity-50 py-3 px-4 transition">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-200 mb-1">Address</label>
                <textarea name="address" rows="3" class="w-full rounded-lg border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:border-green-500 focus:ring focus:ring-green-200 dark:focus:ring-green-500/20 focus:ring-opacity-50 py-3 px-4 transition">{{ old('address', $user->address) }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 dark:border-slate-800 mt-6">
                <a href="{{ route('profile.show') }}" class="px-6 py-3 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-200 bg-white dark:bg-slate-900 rounded-lg font-bold hover:bg-gray-50 dark:hover:bg-slate-800 transition shadow-sm">Cancel</a>
                <button type="submit" class="px-6 py-3 border border-transparent bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition shadow-md shadow-green-600/30">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
