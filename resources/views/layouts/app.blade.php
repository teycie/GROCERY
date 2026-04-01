<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Online Grocery System')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header class="topbar">
        <div class="container nav-wrap">
            <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="brand">Online Grocery System</a>

            <nav class="nav-links">
                @auth
                    @if(auth()->user()->role === 'buyer')
                        <a href="{{ route('buyer.dashboard') }}">Home</a>
                        <a href="{{ route('products.index') }}">Products</a>
                        <a href="{{ route('cart.index') }}">Cart</a>
                    @else
                        <a href="{{ route('seller.dashboard') }}">Home</a>
                        <a href="{{ route('seller.products.index') }}">Manage Products</a>
                        <a href="{{ route('seller.announcements.create') }}">Post Announcement</a>
                    @endif

                    <a href="{{ route('announcements.index') }}">Announcements</a>
                    <a href="{{ route('profile.show') }}">Profile</a>

                    <form action="{{ route('logout') }}" method="POST" class="inline-form">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                @else
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="container page-content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
