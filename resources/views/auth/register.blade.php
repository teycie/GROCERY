@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="card small-card">
    <h1>Create Account</h1>

    <form action="{{ route('register.submit') }}" method="POST" class="form-grid">
        @csrf

        <label>Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required>

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" required>

        <button type="submit" class="btn">Register</button>
    </form>

    <p class="small-text">Already have an account? <a href="{{ route('login') }}">Login here</a>.</p>
</div>
@endsection
