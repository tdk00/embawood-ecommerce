@extends('admin.metronic')

@section('title', 'Sifarişlərin siyahısı')

@section('content')


    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Sifarişlərin siyahısı</h1>
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
                <!--begin::Products-->
                <div class="card card-flush">
                    <!--begin::Card header-->
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <input type="text" data-kt-ecommerce-order-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Axtarış" />
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            <!--begin::Flatpickr-->
                            <div class="input-group w-250px">
                                <input class="form-control form-control-solid rounded rounded-end-0" placeholder="Vaxt aralığı seçin" id="kt_ecommerce_sales_flatpickr" />
                                <button class="btn btn-icon btn-light" id="kt_ecommerce_sales_flatpickr_clear">
                                    <i class="ki-duotone ki-cross fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </button>
                            </div>
                            <!--end::Flatpickr-->
                            <div class="w-100 mw-150px">
                                <!--begin::Select2-->
                                <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Status" data-kt-ecommerce-order-filter="status">
                                    <option value="all">Hamsı</option>
                                    @foreach($statusMapping as $dbStatus => $friendlyStatus)
                                        <option value="{{ $dbStatus }}">{{ $friendlyStatus }}</option>
                                    @endforeach
                                </select>
                                <!--end::Select2-->
                            </div>
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table">
                            <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ecommerce_sales_table .form-check-input" value="1" />
                                    </div>
                                </th>
                                <th class="min-w-100px">Sifariş ID</th>
                                <th class="min-w-175px">Müştəri</th>
                                <th class="text-end min-w-70px">Status</th>
                                <th class="text-end min-w-100px">Total</th>
                                <th class="text-end min-w-100px">Tarix</th>
                                <th class="text-end min-w-100px">Əməliyyatlar</th>
                            </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value="1" />
                                        </div>
                                    </td>
                                    <td data-kt-ecommerce-order-filter="order_id">
                                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="text-gray-800 text-hover-primary fw-bold">{{ $order->id }}</a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-5">
                                                <a href="{{ route('admin.customers.edit', $order->user->id) }}" class="text-gray-800 text-hover-primary fs-5 fw-bold">{{ $order->user->name }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end pe-0" data-order="{{ $order->status }}">
                                        <!--begin::Status Changer-->
                                        <select class="form-select form-select-sm change-order-status" data-order-id="{{ $order->id }}">
                                            @foreach($statusMapping as $statusKey => $statusLabel)
                                                <option value="{{ $statusKey }}" {{ $order->status === $statusKey ? 'selected' : '' }}>
                                                    {{ $statusLabel }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <!--end::Status Changer-->
                                    </td>
                                    <td class="text-end pe-0">
                                        <span class="fw-bold">{{ ($order->total - $order->coupon_discount - $order->item_discounts_total) }} AZN</span>
                                    </td>
                                    <td class="text-end" data-order="{{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}">
                                        <span class="fw-bold">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                        </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a href="{{ route('admin.orders.edit', $order->id) }}" class="menu-link px-3">View</a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-kt-ecommerce-order-filter="delete_row">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Products-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->

@endsection

@push('scripts')
    <!-- Local Scripts -->
    <script src="{{ asset('assets/admin/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>

    <script src="{{ asset('assets/admin/js/custom/widgets.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="{{ asset('assets/admin/js/custom/apps/ecommerce/sales/listing.js') }}"></script>

    <script>
        $(document).on('change', '.change-order-status', function () {
            var orderId = $(this).data('order-id');
            var newStatus = $(this).val();
            var statusText = $(this).find('option:selected').text(); // Get the selected status text

            // Show confirmation alert before making the status change
            Swal.fire({
                title: 'Əminsiniz?',
                text: `Sifariş statusunu ${statusText} olaraq dəyişmək istədiyinizdən əminsiniz?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Bəli, dəyişdir!',
                cancelButtonText: 'Xeyr, olduğu kimi saxla',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with the status change via AJAX
                    $.ajax({
                        url: '{{ route("admin.orders.changeStatus", ":id") }}'.replace(':id', orderId),
                        type: 'PATCH',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: newStatus
                        },
                        success: function (response) {
                            // Show success alert
                            Swal.fire({
                                title: 'Uğurlu!',
                                text: 'Sifariş statusu uğurla yeniləndi!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        },
                        error: function (xhr) {
                            // Show error alert
                            Swal.fire({
                                title: 'Xəta!',
                                text: 'Sifariş statusunu yeniləmək mümkün olmadı. Zəhmət olmasa, bir daha cəhd edin.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // If the user cancels the confirmation, reset the select dropdown to the original status
                    Swal.fire({
                        title: 'Ləğv edildi',
                        text: 'Sifariş statusu dəyişdirilmədi.',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });

                    // Reset the dropdown to the previous value (using AJAX to get the current value or just reload the page)
                    location.reload();  // This will reset the dropdown to the current status
                }
            });
        });

    </script>

@endpush
