@extends('layouts.app')

@section('title', 'Enter Shop - FreshMart')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white dark:bg-slate-900 p-10 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
        <div>
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-500/15">
                <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-slate-100">
                Create Account
            </h2>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
            @csrf

            <!-- Form Error Block -->
            @if ($errors->any())
                <div class="rounded-md bg-red-50 dark:bg-red-900/20 p-4 mb-4 border border-red-200 dark:border-red-800/50">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-300">
                                There were errors with your submission
                            </h3>
                            <div class="mt-2 text-sm text-red-700 dark:text-red-200">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-slate-300">First Name</label>
                    <input id="first_name" name="first_name" type="text" autocomplete="given-name" required value="{{ old('first_name') }}" class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm mt-1">
                    @error('first_name')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Last Name</label>
                    <input id="last_name" name="last_name" type="text" autocomplete="family-name" required value="{{ old('last_name') }}" class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm mt-1">
                    @error('last_name')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm mt-1">
                    @error('email')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Username</label>
                    <input id="username" name="username" type="text" autocomplete="username" required value="{{ old('username') }}" class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm mt-1">
                    @error('username')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm mt-1">
                    @error('password')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm mt-1">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition btn-animate shadow-md shadow-green-600/20">
                    Create Account
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-slate-300">
                    Already have an Account?
                    <a href="{{ route('login') }}" class="font-medium text-green-600 hover:text-green-500 transition">
                        Log In
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
