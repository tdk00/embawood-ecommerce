@extends('admin.metronic')

@section('title', 'Badges')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Badges</h1>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <a href="{{ route('admin.badges.create') }}" class="btn btn-primary mb-4">Add New Badge</a>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Badge Image</th>
                        <th>Is Active</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($badges as $badge)
                        <tr>
                            <td><img src="{{ asset('storage/images/badge/'.$badge->badge_image) }}" alt="Badge Image" width="50"></td>
                            <td>{{ $badge->is_active ? 'Active' : 'Inactive' }}</td>
                            <td>
                                <a href="{{ route('admin.badges.edit', $badge->id) }}" class="btn btn-info">Edit</a>
                                <form action="{{ route('admin.badges.destroy', $badge->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            {{ $badges->links() }} <!-- Pagination -->
            </div>
        </div>
    </div>
@endsection
