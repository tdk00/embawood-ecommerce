@extends('admin.metronic')

@section('title', 'Video Call Requests')
@push('style')
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            padding: 1em 0;
        }

        .pagination .page-item {
            margin: 0 0.25em;
        }

        .pagination .page-item .page-link {
            color: #007bff; /* Change to your link color */
            border: 1px solid #ddd;
            padding: 0.5em 0.75em;
            text-decoration: none;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff; /* Active page color */
            color: #fff;
            border-color: #007bff;
        }

        .pagination .page-item.disabled .page-link {
            color: #999;
            pointer-events: none;
            background-color: #f8f9fa;
        }
    </style>
@endpush
@section('content')
    <div class="container">
        <h1>Video Call Requests</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Name</th>
                <th>WhatsApp Number</th>
                <th>Subject</th>
                <th>Address</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($videoCallRequests as $request)
                <tr>
                    <td>{{ $request->name }}</td>
                    <td>{{ $request->whatsapp_number }}</td>
                    <td>{{ $request->subject }}</td>
                    <td>{{ $request->address }}</td>
                    <td class="text-end pe-0" data-order="{{ $request->status }}">
                        <select class="form-select form-select-sm change-request-status" data-request-id="{{ $request->id }}">
                            @foreach(['pending' => 'Pending', 'rejected' => 'Rejected', 'completed' => 'Completed'] as $statusKey => $statusLabel)
                                <option value="{{ $statusKey }}" {{ $request->status === $statusKey ? 'selected' : '' }}>
                                    {{ $statusLabel }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <form action="{{ route('admin.video_call_requests.destroy', $request->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Əminsiniz?')">Sil</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="my-pagination" style="display: flex; justify-content: center; padding-top: 1em;">
            @if ($videoCallRequests->onFirstPage())
                <span style="padding: 0.5em; margin: 0 0.25em; color: #999; cursor: not-allowed;">&laquo;</span>
            @else
                <a href="{{ $videoCallRequests->previousPageUrl() }}" style="padding: 0.5em; margin: 0 0.25em; color: #007bff; text-decoration: none;">&laquo;</a>
            @endif

            {{-- Show the first page link --}}
            @if ($videoCallRequests->currentPage() > 3)
                <a href="{{ $videoCallRequests->url(1) }}" style="padding: 0.5em; margin: 0 0.25em; color: #007bff; text-decoration: none;">1</a>
                @if ($videoCallRequests->currentPage() > 4)
                    <span style="padding: 0.5em; margin: 0 0.25em;">...</span>
                @endif
            @endif

            {{-- Display pages around the current page --}}
            @for ($page = max(1, $videoCallRequests->currentPage() - 2); $page <= min($videoCallRequests->lastPage(), $videoCallRequests->currentPage() + 2); $page++)
                @if ($page == $videoCallRequests->currentPage())
                    <span style="padding: 0.5em; margin: 0 0.25em; background-color: #007bff; color: #fff; border-radius: 3px;">{{ $page }}</span>
                @else
                    <a href="{{ $videoCallRequests->url($page) }}" style="padding: 0.5em; margin: 0 0.25em; color: #007bff; text-decoration: none;">{{ $page }}</a>
                @endif
            @endfor

            {{-- Show the last page link --}}
            @if ($videoCallRequests->currentPage() < $videoCallRequests->lastPage() - 2)
                @if ($videoCallRequests->currentPage() < $videoCallRequests->lastPage() - 3)
                    <span style="padding: 0.5em; margin: 0 0.25em;">...</span>
                @endif
                <a href="{{ $videoCallRequests->url($videoCallRequests->lastPage()) }}" style="padding: 0.5em; margin: 0 0.25em; color: #007bff; text-decoration: none;">{{ $videoCallRequests->lastPage() }}</a>
            @endif

            @if ($videoCallRequests->hasMorePages())
                <a href="{{ $videoCallRequests->nextPageUrl() }}" style="padding: 0.5em; margin: 0 0.25em; color: #007bff; text-decoration: none;">&raquo;</a>
            @else
                <span style="padding: 0.5em; margin: 0 0.25em; color: #999; cursor: not-allowed;">&raquo;</span>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Local Scripts -->
    <script src="{{ asset('assets/admin/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>

    <script src="{{ asset('assets/admin/js/custom/widgets.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="{{ asset('assets/admin/js/custom/apps/ecommerce/customers/listing/listing.js') }}"></script>
    <script>
        $(document).on('change', '.change-request-status', function () {
            var requestId = $(this).data('request-id');
            var newStatus = $(this).val();
            var statusText = $(this).find('option:selected').text();

            Swal.fire({
                title: 'Əminsiniz?',
                text: `Video zəng tələbinin statusunu ${statusText} olaraq dəyişmək istədiyinizdən əminsiniz?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Bəli, dəyişdir!',
                cancelButtonText: 'Xeyr, olduğu kimi saxla',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.video_call_requests.update", ":id") }}'.replace(':id', requestId),
                        type: 'POST', // Ensure this is PATCH
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: newStatus
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Uğurlu!',
                                text: 'Status uğurla yeniləndi!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Xəta!',
                                text: 'Statusu yeniləmək mümkün olmadı. Bir daha cəhd edin.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: 'Ləğv edildi',
                        text: 'Status dəyişdirilmədi.',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                    location.reload();
                }
            });
        });
    </script>
@endpush
