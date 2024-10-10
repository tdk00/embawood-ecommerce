@extends('admin.metronic')

@section('title', 'Ideas List')

@section('content')
    <div class="container">
        <h1>Ideas List</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('admin.ideas.create') }}" class="btn btn-primary mb-4">Add New Idea</a>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title (Category View)</th>
                <th>Title (Homepage Tab View)</th>
                <th>Is Active</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($ideas as $idea)
                <tr>
                    <td>{{ $idea->id }}</td>
                    <td>{{ $idea->title_category_view }}</td>
                    <td>{{ $idea->title_homepage_tab_view }}</td>
                    <td>{{ $idea->is_active ? 'Yes' : 'No' }}</td>
                    <td>
                        <!-- Button to edit the Idea -->
                        <a href="{{ route('admin.ideas.edit', $idea->id) }}" class="btn btn-info btn-sm">Edit</a>

                        <!-- Form to delete the Idea -->
                        <form action="{{ route('admin.ideas.destroy', $idea->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>

                        <!-- Link to manage SubIdeas for this Idea -->
                        <a href="{{ route('admin.sub-ideas.listByIdea', $idea->id) }}" class="btn btn-secondary btn-sm">Manage SubIdeas</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
