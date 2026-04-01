@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="card small-card">
    <h1>Edit Profile</h1>

    <form action="{{ route('profile.update') }}" method="POST" class="form-grid">
        @csrf
        @method('PUT')

        <label>Name</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        @error('name')
            <span class="error">{{ $message }}</span>
        @enderror

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        @error('email')
            <span class="error">{{ $message }}</span>
        @enderror

        <label>Password (leave blank to keep current)</label>
        <input type="password" name="password" placeholder="Enter new password">
        @error('password')
            <span class="error">{{ $message }}</span>
        @enderror

        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" placeholder="Confirm new password">

        <div class="button-group" style="grid-column: 1 / -1; margin-top: 1rem;">
            <button type="submit" class="btn">Save Changes</button>
            <a href="{{ route('profile.show') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
