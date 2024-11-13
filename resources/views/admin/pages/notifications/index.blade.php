<!-- resources/views/admin/notifications/index.blade.php -->

@extends('admin.metronic')

@section('title', 'Notifications')

@section('content')
    <div class="container">
        <h1>Notifications</h1>
        <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary mb-3">Create Notification</a>

        <table class="table">
            <thead>
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Sent At</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($notifications as $notification)
                <tr>
                    <td>{{ $notification->title }}</td>
                    <td>{{ ucfirst($notification->status) }}</td>
                    <td>{{ $notification->sent_at ?? 'Not Sent' }}</td>
                    <td>
                        <a href="{{ route('admin.notifications.edit', $notification->id) }}" class="btn btn-secondary">Edit</a>
                        <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                        <form action="{{ route('admin.notifications.send', $notification->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-success">Send</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
