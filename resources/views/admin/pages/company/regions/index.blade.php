@extends('admin.metronic')

@section('title', 'Regions')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Regions</h1>

                <a href="{{ route('admin.regions.create') }}" class="btn btn-primary mb-3">Create Region</a>

                @if ($regions->count())
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($regions as $region)
                            <tr>
                                <td>{{ $region->name }}</td>
                                <td>
                                    <a href="{{ route('admin.regions.edit', $region->id) }}" class="btn btn-info">Edit</a>
                                    <form action="{{ route('admin.regions.destroy', $region->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{ $regions->links() }}
                @else
                    <p>No regions available.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
