@extends('layouts.app')

@section('title', 'Edit Profile - FreshMart')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('profile.show') }}" class="text-green-600 hover:text-green-800 text-sm font-medium mb-1 inline-block">&larr; Back to Profile</a>
            <h1 class="text-3xl font-extrabold text-gray-900">Edit Profile</h1>
        </div>
    </div>

    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-3 px-4 transition">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Email address <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-3 px-4 transition">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t border-gray-100 pt-6 mt-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Change Password</h3>
                <p class="text-sm text-gray-500 mb-4">Leave these fields blank if you do not want to change your password.</p>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">New Password</label>
                        <input type="password" name="password" placeholder="Enter new password" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-3 px-4 transition">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" placeholder="Confirm new password" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-3 px-4 transition">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 mt-6">
                <a href="{{ route('profile.show') }}" class="px-6 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg font-bold hover:bg-gray-50 transition shadow-sm">Cancel</a>
                <button type="submit" class="px-6 py-3 border border-transparent bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition shadow-md shadow-green-600/30">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
