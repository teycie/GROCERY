@extends('layouts.app')

@section('title', 'Log In - FreshMart')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white dark:bg-slate-900 p-10 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
        <div>
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-500/15">
                <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-slate-100">
                Sign In
            </h2>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('login.submit') }}" method="POST">
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
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Username or Email</label>
                    <input id="email" name="email" type="text" autocomplete="username" required value="{{ old('email') }}" class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm mt-1">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-slate-100 focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm mt-1">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" class="ml-2 block text-sm text-gray-900 dark:text-slate-200">
                        Remember me
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-green-600 hover:text-green-500 transition">
                            Forgot your password?
                        </a>
                    </div>
                @endif
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition btn-animate shadow-md shadow-green-600/20">
                    Sign in
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-slate-300">
                    No Account?
                    <a href="{{ route('register') }}" class="font-medium text-green-600 hover:text-green-500 transition">
                        Register
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
