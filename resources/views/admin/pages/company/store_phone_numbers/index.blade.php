@extends('admin.metronic')

@section('title', 'Store Phone Numbers')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Store Phone Numbers</h1>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <a href="{{ route('admin.store-phone-numbers.create') }}" class="btn btn-primary mb-4">Add Phone Number</a>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Store</th>
                        <th>Phone Number</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($phoneNumbers as $phoneNumber)
                        <tr>
                            <td>{{ $phoneNumber->store->name }}</td>
                            <td>{{ $phoneNumber->phone_number }}</td>
                            <td>
                                <a href="{{ route('admin.store-phone-numbers.edit', $phoneNumber->id) }}" class="btn btn-info">Edit</a>
                                <form action="{{ route('admin.store-phone-numbers.destroy', $phoneNumber->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            {{ $phoneNumbers->links() }} <!-- Pagination -->
            </div>
        </div>
    </div>
@endsection
