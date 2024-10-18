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
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Kateqoriyalar</h1>
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
                                <a href="{{route('admin.categories.create')}}" class="menu-link px-3">Yeni Kateqoriya</a>
                            </div>
                            <!--end::Menu item-->
                            <div class="menu-item px-3">
                                <a id="bulkDeactivate" class="menu-link px-3">
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
                                <th class="min-w-250px">Kateqoriya</th>
                                <th class="min-w-70px">Sıra</th>
                                <th class="text-end min-w-70px">Əməliyyatlar</th>
                            </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                            @foreach($categories as $category)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value="{{ $category->id }}" />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div class="ms-5">
                                                <a href="{{route('admin.categories.edit', $category->id)}}" class="text-gray-800 text-hover-primary fs-5 fw-bold mb-1" data-kt-ecommerce-category-filter="category_name">{{$category->name}}</a>
                                                <div class="text-muted fs-7 fw-bold">{{$category->description}}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control form-control-sm category-order" data-id="{{ $category->id }}" value="{{ $category->order }}" />
                                            <button class="btn btn-sm btn-primary save-order-btn" data-id="{{ $category->id }}" type="button">Save</button>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary btn-flex btn-center" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a href="{{route('admin.categories.edit', $category->id)}}" class="menu-link px-3">Edit</a>
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
        $(document).on('click', '.save-order-btn', function () {
            var categoryId = $(this).data('id');
            var orderValue = $(this).closest('.input-group').find('.category-order').val();

            $.ajax({
                url: "{{ route('admin.categories.updateOrder') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: categoryId,
                    order: orderValue
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Sıra yadda saxlandı!',
                            timer: 1000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Səhv yarandı.',
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Səhv yarandı.',
                    });
                }
            });
        });
    </script>
    <script>
        $("#bulkDeactivate").on('click', function () {
            let selectedCategoryIds = new Set();
            document.querySelectorAll('#kt_ecommerce_category_table .form-check-input:checked').forEach(function (checkbox) {
                selectedCategoryIds.add(checkbox.value);
            });

            // Set-i yenidən array-ə çevirin
            selectedCategoryIds = Array.from(selectedCategoryIds);

            // Ən azı bir kateqoriyanın seçildiyinə əmin olun
            if (selectedCategoryIds.length === 0) {
                alert('Zəhmət olmasa, ən azı bir kateqoriya seçin.');
                return;
            }

            // SweetAlert2 istifadə edərək təsdiq dialoqunu göstərin
            Swal.fire({
                title: 'Əminsiniz ?',
                html: "<b>Kateqoriya deaktiv ediləcək və siyahılarda görünməyəcək</b> </span>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Bəli!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // İstifadəçi təsdiqləyirsə, AJAX sorğusu ilə davam edin

                    let data = {
                        category_ids: selectedCategoryIds,
                        _token: '{{ csrf_token() }}'
                    };

                    // Kateqoriyaları deaktiv etmək üçün AJAX sorğusu göndərin
                    fetch('{{ route('admin.categories.bulk-deactivate') }}', {
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
                                Swal.fire(
                                    'Deaktiv edildi!',
                                    'Seçilmiş kateqoriyalar deaktiv edilib',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else if (data.undeletable_categories && data.undeletable_categories.length > 0) {
                                let undeletableCategories = data.undeletable_categories.join(', ');

                                Swal.fire({
                                    title: 'Bəzi Kateqoriyalar Deaktiv Edilə Bilmədi',
                                    html: `Aşağıdakı kateqoriyalar aktiv alt kateqoriyalar olduğu üçün deaktiv edilə bilmədi: <br><br> ${undeletableCategories}`,
                                    icon: 'warning'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Xəta!', 'Xəta baş verdi: ' + data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Xəta!', 'Gözlənilməyən xəta baş verdi. Zəhmət olmasa yenidən cəhd edin.', 'error');
                        });
                }
            });
        });

    </script>
@endpush
