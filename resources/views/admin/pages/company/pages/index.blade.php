@extends('admin.metronic')

@section('title', 'Pages')

@section('content')
    <div class="container">
        <h1>Pages</h1>
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary mb-3">Create New Page</a>

        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title (AZ)</th>
                <th>Show in App</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($pages as $page)
                <tr>
                    <td>{{ $page->id }}</td>
                    <td>{{ $page->translations->where('locale', 'az')->first()->title ?? '' }}</td>
                    <td>{{ $page->show_in_footer ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-info">Edit</a>
                        <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
