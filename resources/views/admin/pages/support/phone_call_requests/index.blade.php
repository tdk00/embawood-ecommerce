@extends('admin.metronic')

@section('title', 'Phone Call Requests')

@section('content')
    <div class="container">
        <h1>Phone Call Requests</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($phoneCallRequests as $request)
                <tr>
                    <td>{{ $request->user->name }}</td>
                    <td>{{ $request->user->phone }}</td>
                    <td>
                        <select class="form-select form-select-sm change-request-status" data-request-id="{{ $request->id }}">
                            @foreach(['pending', 'rejected', 'completed'] as $status)
                                <option value="{{ $status }}" {{ $request->status === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>{{ $request->created_at ? $request->created_at->format('Y-m-d H:i') : '-' }}</td>
                    <td>
                        <form action="{{ route('admin.phone_call_requests.destroy', $request->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="my-pagination">
            {{ $phoneCallRequests->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('change', '.change-request-status', function () {
            var requestId = $(this).data('request-id');
            var newStatus = $(this).val();

            $.ajax({
                url: '{{ route("admin.phone_call_requests.update", ":id") }}'.replace(':id', requestId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: newStatus,
                },
                success: function () {
                    alert('Status updated successfully.');
                    location.reload();
                },
                error: function () {
                    alert('Failed to update status. Please try again.');
                }
            });
        });
    </script>
@endpush
