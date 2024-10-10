@extends('admin.metronic')

@section('title', 'SubIdea List')

@section('content')
    <div class="container">
        <h1>SubIdea List</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('admin.sub-ideas.create') }}" class="btn btn-primary mb-4">Add New SubIdea</a>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Parent Idea</th>
                <th>Title (AZ)</th>
                <th>Title (EN)</th>
                <th>Title (RU)</th>
                <th>Is Active</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($subIdeas as $subIdea)
                <tr>
                    <td>{{ $subIdea->id }}</td>
                    <td>{{ $subIdea->idea->title_category_view }}</td>
                    <td>{{ $subIdea->translations->where('locale', 'az')->first()->title ?? '' }}</td>
                    <td>{{ $subIdea->translations->where('locale', 'en')->first()->title ?? '' }}</td>
                    <td>{{ $subIdea->translations->where('locale', 'ru')->first()->title ?? '' }}</td>
                    <td>{{ $subIdea->is_active ? 'Yes' : 'No' }}</td>
                    <td> <!-- Button to manage SubIdeaItems for this SubIdea -->
                        <a href="{{ route('admin.sub-idea-items.listBySubIdea', $subIdea->id) }}" class="btn btn-secondary btn-sm">Manage SubIdeaItems</a>

                        <a href="{{ route('admin.sub-ideas.edit', $subIdea->id) }}" class="btn btn-info btn-sm">Edit</a>
                        <form action="{{ route('admin.sub-ideas.destroy', $subIdea->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
