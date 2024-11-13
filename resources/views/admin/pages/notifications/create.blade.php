@extends('admin.metronic')

@section('title', 'Create Notification')

@section('content')
    <div class="container">
        <h1>Create Notification</h1>

        <form action="{{ route('admin.notifications.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="message" id="message" class="form-control" required>{{ old('message') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save Notification</button>
        </form>
    </div>
@endsection
