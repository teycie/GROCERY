@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="card small-card">
    <h1>Login</h1>

    <form action="{{ route('login.submit') }}" method="POST" class="form-grid">
        @csrf

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn">Login</button>
    </form>

    <p class="small-text">No account? <a href="{{ route('register') }}">Register here</a>.</p>
</div>
@endsection
