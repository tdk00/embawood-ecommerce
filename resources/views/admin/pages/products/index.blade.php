@extends('admin.metronic')

@section('title', 'Məhsullar')

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
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Məhsullar</h1>
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
                            <input type="text" data-kt-ecommerce-product-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Axtarış" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <!--begin::Add product-->
                        <a href="{{route('admin.products.create')}}" class="btn btn-primary">Yeni Məhsul</a>
                        <!--end::Add product-->
                        <!--end::Add product-->
                        <a class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#bulkDiscountModal">
                            Apply Bulk Discount
                        </a>
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                        <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ecommerce_products_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class="min-w-200px">Ad</th>
                            <th class="text-end min-w-100px">SKU</th>
                            <th class="text-end min-w-100px">Sub Kateqoriya</th>
                            <th class="text-end min-w-70px">Stok</th>
                            <th class="text-end min-w-100px">Qiymət</th>
                            <th class="text-end min-w-100px">Rating</th>
                            <th class="text-end min-w-70px">Əməliyyat</th>
                        </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                        @foreach($products as $product)

                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="{{$product->id}}" />
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <!--begin::Thumbnail-->
                                        <a href="{{route('admin.products.edit', $product->id)}}" class="symbol symbol-50px">
                                            <span class="symbol-label" style="background-image:url('{{$product->image}}');"></span>
                                        </a>
                                        <!--end::Thumbnail-->
                                        <div class="ms-5">
                                            <!--begin::Title-->
                                            <a href="{{route('admin.products.edit', $product->id)}}" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-kt-ecommerce-product-filter="product_name">{{$product->name}}</a>
                                            <!--end::Title-->
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end pe-0">
                                    <span class="fw-bold">{{$product->sku}}</span>
                                </td>
                                <td class="text-end pe-0">
                                    <span class="fw-bold">{{$product->subcategories?->first()?->name}}</span>
                                </td>
                                <td class="text-end pe-0" data-order="0">
                                    <span class="fw-bold text-primary ms-3">{{$product->stock}}</span>
                                </td>
                                <td class="text-end pe-0">{{$product->price}}</td>
                                <td class="text-end pe-0" data-order="rating-{{floor($product->average_rating)}}">
                                    <div class="rating justify-content-end">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <div class="rating-label {{ $i <= $product->average_rating ? 'checked' : '' }}">
                                                <i class="ki-duotone ki-star fs-6"></i>
                                            </div>
                                        @endfor
                                    </div>
                                </td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="{{route('admin.products.edit', $product->id)}}" class="menu-link px-3">Edit</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-ecommerce-product-filter="delete_row">Delete</a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
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
<!-- Bulk Discount Modal -->
<div class="modal fade" id="bulkDiscountModal" tabindex="-1" aria-labelledby="bulkDiscountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkDiscountModalLabel">Bulk Apply Discount</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bulkDiscountForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="discountPercentage" class="form-label">Discount Percentage</label>
                        <input type="number" class="form-control" id="discountPercentage" name="discount_percentage" required min="0" max="100">
                    </div>

                    <div class="mb-50 fv-row" style="margin-bottom: 20px">
                        <div class="form-check form-check-solid form-check-custom form-check-inline">
                            <input class="form-check-input" type="checkbox" id="unlimited_discount_checkbox" name="unlimited_discount">
                            <label class="form-check-label" for="unlimited_discount_checkbox">
                                Vaxt Limitsiz endirim
                            </label>
                        </div>
                    </div>

                    <!-- Datetime Picker Input -->
                    <div class="mb-50 fv-row">
                        <input id="discount_ends_datetimepicker" name="discount_ends_datetimepicker" placeholder="Select a date and time" class="form-control mb-2" value="{{ \Carbon\Carbon::now() }}" />
                        <div class="text-muted fs-7">Endirimin bitmə vaxtı.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Apply Discount</button>
                </div>
            </form>
        </div>
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

    <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <!--end::Vendors Javascript-->


    <script src="{{ asset('assets/admin/js/custom/apps/ecommerce/catalog/products.js') }}"></script>

    <script>

        $("#discount_ends_datetimepicker").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i"
        });

        document.getElementById('unlimited_discount_checkbox').addEventListener('change', function () {
            // Get the datetime picker input
            let dateTimePicker = document.getElementById('discount_ends_datetimepicker');

            // Check if the checkbox is checked
            if (this.checked) {
                // Disable the datetime picker and set its value to null
                dateTimePicker.value = '';
                dateTimePicker.disabled = true;
            } else {
                // Re-enable the datetime picker
                dateTimePicker.disabled = false;
            }
        });

        document.getElementById('bulkDiscountForm').addEventListener('submit', function (event) {
            event.preventDefault();


            let selectedProductIds = new Set();
            document.querySelectorAll('#kt_ecommerce_products_table .form-check-input:checked').forEach(function (checkbox) {
                selectedProductIds.add(checkbox.value);
            });

            // Convert the Set back to an array
            selectedProductIds = Array.from(selectedProductIds);

            // Ensure at least one product is selected
            if (selectedProductIds.length === 0) {
                alert('Please select at least one product.');
                return;
            }

            // Get the discount percentage
            let discountPercentage = document.getElementById('discountPercentage').value;
            let unlimitedDiscount = document.getElementById('unlimited_discount_checkbox').checked;
            let discountEndTime = unlimitedDiscount ? null : document.getElementById('discount_ends_datetimepicker').value;

            if (discountPercentage === "" || discountPercentage <= 0 || discountPercentage > 100) {
                alert('Please enter a valid discount percentage between 1 and 100.');
                return;
            }

            // Prepare data for AJAX request
            let data = {
                product_ids: selectedProductIds,
                discount_percentage: discountPercentage,
                unlimited_discount: unlimitedDiscount,  // Send the unlimited discount status
                discount_end_time: discountEndTime,     // This will be null if unlimited discount is checked
                _token: '{{ csrf_token() }}'
            };

            // Send AJAX request to apply bulk discount
            fetch('{{ route('admin.products.bulk-discount') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close the modal
                        $('#bulkDiscountModal').modal('hide');

                        // Reload the table or page to reflect the changes
                        location.reload(); // Optional: You can update the table dynamically instead
                    } else {
                        alert('An error occurred: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An unexpected error occurred. Please try again.');
                });
        });
    </script>
@endpush
