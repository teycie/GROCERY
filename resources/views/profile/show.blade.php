@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="card">
    <h1>My Profile</h1>

    <div class="profile-info">
        <div class="info-row">
            <label>Name:</label>
            <span>{{ $user->name }}</span>
        </div>

        <div class="info-row">
            <label>Email:</label>
            <span>{{ $user->email }}</span>
        </div>

        <div class="info-row">
            <label>Role:</label>
            <span>{{ ucfirst($user->role) }}</span>
        </div>

        <div class="info-row">
            <label>Member Since:</label>
            <span>{{ $user->created_at->format('M d, Y') }}</span>
        </div>
    </div>

    <div class="button-group">
        <a href="{{ route('profile.edit') }}" class="btn">Edit Profile</a>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>
@endsection
