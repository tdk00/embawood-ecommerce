@extends('admin.metronic')

@section('title', 'Stores')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Stores</h1>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <a href="{{ route('admin.stores.create') }}" class="btn btn-primary mb-4">Add New Store</a>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Region</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Phone Numbers</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stores as $store)
                        <tr>
                            <td>{{ $store->name }}</td>
                            <td>{{ $store->address }}</td>
                            <td>{{ $store->city }}</td>
                            <td>{{ $store->region->name ?? 'N/A' }}</td>
                            <td>{{ $store->latitude }}</td>
                            <td>{{ $store->longitude }}</td>
                            <td>
                                @foreach($store->phoneNumbers as $phoneNumber)
                                    {{ $phoneNumber->phone_number }}<br>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('admin.stores.edit', $store->id) }}" class="btn btn-info">Edit</a>
                                <form action="{{ route('admin.stores.destroy', $store->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            {{ $stores->links() }} <!-- Pagination -->
            </div>
        </div>
    </div>
@endsection
