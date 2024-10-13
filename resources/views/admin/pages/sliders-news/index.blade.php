@extends('admin.metronic')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Yeni Məhsul</h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-xxl">
                <!--begin::Form-->
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Slider Şəkil</th>
                        <th>Xəbər Başlıq</th>
                        <th>Xəbər Banner</th>
                        <th>Aktivlik</th>
                        <th>Sıra</th> <!-- New column for the order input -->
                        <th>Əməliyyat</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($sliders as $slider)
                        <tr>
                            <td>{{ $slider->id }}</td>
                            <td><img src="{{ url('storage/images/home_screen/sliders/'.$slider->slider_image) }}" width="100" alt="Slider Image"></td>
                            <td>{{ $slider->news->title }}</td>
                            <td>
                                @if ($slider->news->banner_image)
                                    <img src="{{ url('storage/images/news/'.$slider->news->banner_image) }}" width="100" alt="News Banner">
                                @else
                                    <p>No Image Available</p>
                                @endif
                            </td>
                            <td>{{ $slider->is_active ? 'Bəli' : 'Xeyr' }}</td>
                            <td>
                                <div class="input-group">
                                    <input type="number" class="form-control form-control-sm slider-order" data-id="{{ $slider->id }}" value="{{ $slider->order }}" />
                                    <button class="btn btn-sm btn-primary save-slider-order-btn" data-id="{{ $slider->id }}" type="button">Save</button>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.sliders-news.edit', $slider->id) }}" class="btn btn-sm btn-primary">Redaktə</a>

                                <form action="{{ route('admin.sliders-news.destroy', $slider->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bu slideri və əlaqəli xəbəri silmək istədiyinizə əminsinizmi?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <!--end::Form-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
@endsection

@push('scripts')
    <!-- Local Scripts -->
    <script src="{{ asset('assets/admin/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>

    <script src="{{ asset('assets/admin/js/custom/apps/ecommerce/catalog/save-product.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/widgets.js') }}"></script>



    <script src="{{ asset('assets/admin/js/custom/apps/ecommerce/sales/save-order.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).on('click', '.save-slider-order-btn', function () {
            var sliderId = $(this).data('id');
            var orderValue = $(this).closest('.input-group').find('.slider-order').val();

            $.ajax({
                url: "{{ route('admin.sliders-news.updateOrder') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: sliderId,
                    order: orderValue
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Order updated successfully!',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error updating order.',
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while updating the order.',
                    });
                }
            });
        });
    </script>
@endpush
