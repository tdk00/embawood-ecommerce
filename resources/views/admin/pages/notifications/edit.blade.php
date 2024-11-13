@extends('admin.metronic')

@section('title', 'Edit Notification')

@section('content')
    <div class="container">
        <h1>Edit Notification</h1>

        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('admin.notifications.update', $notification->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $notification->title) }}" required>
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="message" id="message" class="form-control" required>{{ old('message', $notification->message) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
        </form>

        <form action="{{ route('admin.notifications.send', $notification->id) }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-success">Send Notification to All Users</button>
        </form>
    </div>
@endsection
