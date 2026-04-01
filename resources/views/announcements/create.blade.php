@extends('layouts.app')

@section('title', 'Post Announcement')

@section('content')
<div class="card">
    <h1>Post Announcement</h1>

    <form action="{{ route('seller.announcements.store') }}" method="POST" class="form-grid">
        @csrf

        <label>Title</label>
        <input type="text" name="title" value="{{ old('title') }}" required>

        <label>Message</label>
        <textarea name="message" rows="6" required>{{ old('message') }}</textarea>

        <button type="submit" class="btn">Post</button>
    </form>
</div>
@endsection
