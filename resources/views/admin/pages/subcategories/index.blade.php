@extends('admin.metronic')

@section('title', 'Kateqoriyalar')

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
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Sub Kateqoriyalar</h1>
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
                <!--begin::Category-->
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
                                <input type="text" data-kt-ecommerce-category-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Axtarış" />
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--end::Card title-->
                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                            <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="{{route('admin.subcategories.create')}}" class="menu-link px-3">Yeni Sub Kateqoriya</a>
                            </div>
                            <!--end::Menu item-->
                            <div class="menu-item px-3">
                                <a id="bulkDeactivate" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#subcategoryMigrationModal">
                                    Toplu deaktiv etmə
                                </a>
                            </div>
                            <!--end::Menu item-->
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
                            <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ecommerce_category_table .form-check-input" value="1" />
                                    </div>
                                </th>
                                <th class="min-w-250px">Sub Kateqoriya</th>
                                <th class="min-w-250px">Kateqoriya</th>
                                <th class="min-w-250px">Sıra</th>
                                <th class="min-w-250px">Discount</th>
                                <th class="text-end min-w-70px">Əməliyyatlar</th>
                            </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                            @foreach($subcategories as $subcategory)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value="{{$subcategory->id}}" />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div class="ms-5">
                                                <!--begin::Title-->
                                                <a href="{{route('admin.subcategories.edit', $subcategory->id)}}" class="text-gray-800 text-hover-primary fs-5 fw-bold mb-1" data-kt-ecommerce-category-filter="sub_category_name">{{$subcategory->name}}</a>
                                                <!--end::Title-->
                                                <div class="text-muted fs-7 fw-bold">{{$subcategory->description}}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div class="ms-5">
                                                <!--begin::Title-->
                                                <a href="{{route('admin.categories.edit', $subcategory->category->id)}}" class="text-gray-800 text-hover-primary fs-5 fw-bold mb-1" data-kt-ecommerce-category-filter="category_name">{{$subcategory->category->name}}</a>
                                                <!--end::Title-->
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control form-control-sm subcategory-order" data-id="{{ $subcategory->id }}" value="{{ $subcategory->order }}" />
                                            <button class="btn btn-sm btn-primary save-subcategory-order-btn" data-id="{{ $subcategory->id }}" type="button">Save</button>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#applyDiscountModal"
                                                data-subcategory-id="{{ $subcategory->id }}">Apply Discount</button>
                                    </td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary btn-flex btn-center" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a href="{{route('admin.subcategories.edit', $subcategory->id)}}" class="menu-link px-3">Edit</a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-kt-ecommerce-category-filter="delete_row">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Category-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <div class="modal fade" id="applyDiscountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alt Kateqoriya Məhsullarına Endirim Tətbiq Edin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bağla"></button>
                </div>
                <div class="modal-body">
                    <form id="applyDiscountForm" action="" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="discount" class="form-label">Endirim (%)</label>
                            <input type="number" class="form-control" name="discount" id="discount" required>
                        </div>
                        <div class="mb-3">
                            <label for="discount_ends_at" class="form-label">Endirim Bitmə Tarixi (İstəyə bağlı)</label>
                            <input type="date" class="form-control" name="discount_ends_at" id="discount_ends_at">
                        </div>
                        <input type="hidden" name="subcategory_id" id="subcategory_id">
                        <button type="submit" class="btn btn-primary">Tətbiq Et</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--end::Content wrapper-->
    <div class="modal fade" id="subcategoryMigrationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Məhsulları Başqa Alt Kateqoriyaya Köçürün</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bağla"></button>
                </div>
                <div class="modal-body">
                    <form id="subcategoryMigrationForm">
                        <div class="form-group">
                            <label for="migrateToSubcategory">Məhsulları Köçürmək üçün Alt Kateqoriya Seçin</label>
                            <select id="migrateToSubcategory" class="form-control" style="width:100%;" data-dropdown-parent="#subcategoryMigrationModal">
                                <!-- Options populated here -->
                                @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" id="selectedSubcategories" name="selected_subcategories">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Bağla">İmtina</button>
                    <button type="button" id="migrateAndDeactivate" class="btn btn-primary">Köçür və Deaktiv Et</button>
                </div>
            </div>
        </div>
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


    <script src="{{ asset('assets/admin/js/custom/apps/ecommerce/catalog/categories.js') }}"></script>

    <script>
        $(document).on('click', '.save-subcategory-order-btn', function () {
            var subcategoryId = $(this).data('id');
            var orderValue = $(this).closest('.input-group').find('.subcategory-order').val();

            $.ajax({
                url: "{{ route('admin.subcategories.updateOrder') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: subcategoryId,
                    order: orderValue
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Subcategory order updated successfully!',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error updating subcategory order.',
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while updating the subcategory order.',
                    });
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var applyDiscountModal = document.getElementById('applyDiscountModal');
            applyDiscountModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var subcategoryId = button.getAttribute('data-subcategory-id');

                var form = document.getElementById('applyDiscountForm');
                form.action = "/admin/subcategories/" + subcategoryId + "/apply-discount";

                // Set the hidden subcategory_id field value
                document.getElementById('subcategory_id').value = subcategoryId;
            });
        });
    </script>

    <script>
        // Initialize Select2 without AJAX
        $('#migrateToSubcategory').select2({
            placeholder: 'Select a subcategory',
            allowClear: true
        });
    </script>

    <script>
        $("#bulkDeactivate").on('click', function () {
            let selectedSubcategoryIds = new Set();
            document.querySelectorAll('#kt_ecommerce_category_table .form-check-input:checked').forEach(function (checkbox) {
                selectedSubcategoryIds.add(checkbox.value);
            });

            // Convert Set to Array
            selectedSubcategoryIds = Array.from(selectedSubcategoryIds);

            // Check if at least one subcategory is selected
            if (selectedSubcategoryIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Diqqət!',
                    text: 'Zəhmət olmasa, ən azı bir alt kateqoriya seçin.',
                    confirmButtonText: 'Bağla'
                });
                return;
            }

            // Store the selected subcategories in a hidden input
            $('#selectedSubcategories').val(JSON.stringify(selectedSubcategoryIds));

            // Open the migration modal
            $('#subcategoryMigrationModal').modal('show');
        });

        // Migrate products and deactivate subcategories
        $("#migrateAndDeactivate").on('click', function () {
            let migrateToSubcategoryId = $('#migrateToSubcategory').val();
            let selectedSubcategories = $('#selectedSubcategories').val();

            if (!migrateToSubcategoryId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Alt Kateqoriya Seçilməyib!',
                    text: 'Məhsulları köçürmək üçün alt kateqoriya seçin.',
                    confirmButtonText: 'Bağla'
                });
                return;
            }

            // Parse the selected subcategories from the hidden field
            selectedSubcategories = JSON.parse(selectedSubcategories);

            // Check if the migrateToSubcategoryId is in the selectedSubcategories list
            if (selectedSubcategories.includes(migrateToSubcategoryId)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Yanlış Seçim!',
                    text: 'Məhsulları köçürmək üçün seçdiyiniz alt kateqoriya deaktiv edilən alt kateqoriyalardan biri ola bilməz.',
                    confirmButtonText: 'Bağla'
                });
                return;
            }

            Swal.fire({
                title: 'Əminsiniz?',
                text: "Məhsulları köçürməyə və seçilmiş alt kateqoriyaları deaktiv etməyə hazırsınız. Bu əməliyyat geri alına bilməz.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Bəli, Köçür və Deaktiv Et!',
                cancelButtonText: 'İmtina'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send the form data to the backend via POST request
                    let data = {
                        migrate_to_subcategory_id: migrateToSubcategoryId,
                        selected_subcategories: selectedSubcategories,
                        _token: '{{ csrf_token() }}'
                    };

                    fetch('{{ route('admin.subcategories.bulk-deactivate') }}', {
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
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deaktiv Edildi!',
                                    text: 'Seçilmiş alt kateqoriyalar deaktiv edildi və məhsullar köçürüldü.',
                                    confirmButtonText: 'Bağla'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Xəta!',
                                    text: data.message || 'Sorğunu yerinə yetirərkən xəta baş verdi.',
                                    confirmButtonText: 'Bağla'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gözlənilməyən Xəta!',
                                text: 'Gözlənilməyən bir xəta baş verdi. Zəhmət olmasa yenidən cəhd edin.',
                                confirmButtonText: 'Bağla'
                            });
                        });
                }
            });
        });
    </script>

@endpush
