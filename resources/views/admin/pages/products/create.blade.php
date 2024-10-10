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
            <form id="kt_ecommerce_add_product_form" class="form d-flex flex-column flex-lg-row" data-kt-redirect="../../demo1/dist/apps/ecommerce/catalog/products.html">
                <!--begin::Aside column-->
                <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                    <!--begin::Thumbnail settings-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>Əsas Şəkil</h2>
                            </div>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body text-center pt-0">
                            <!--begin::Image input-->
                            <!--begin::Image input placeholder-->
                            <style>.image-input-placeholder { background-image: url('assets/media/svg/files/blank-image.svg'); } [data-bs-theme="dark"] .image-input-placeholder { background-image: url('assets/media/svg/files/blank-image-dark.svg'); }</style>
                            <!--end::Image input placeholder-->
                            <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                                <!--begin::Preview existing avatar-->
                                <div class="image-input-wrapper w-150px h-150px"></div>
                                <!--end::Preview existing avatar-->
                                <!--begin::Label-->
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                    <i class="ki-duotone ki-pencil fs-7">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <!--begin::Inputs-->
                                    <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                                    <input type="hidden" name="avatar_remove" />
                                    <!--end::Inputs-->
                                </label>
                                <!--end::Label-->
                                <!--begin::Cancel-->
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
															<i class="ki-duotone ki-cross fs-2">
																<span class="path1"></span>
																<span class="path2"></span>
															</i>
														</span>
                                <!--end::Cancel-->
                                <!--begin::Remove-->
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
															<i class="ki-duotone ki-cross fs-2">
																<span class="path1"></span>
																<span class="path2"></span>
															</i>
														</span>
                                <!--end::Remove-->
                            </div>
                            <!--end::Image input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7"></div>
                            <!--end::Description-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Thumbnail settings-->
                    <!--begin::Category & tags-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>Kateqoriya seçimi</h2>
                            </div>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Input group-->
                            <!--begin::Label-->
                            <label class="form-label">Kateqoriyalar</label>
                            <!--end::Label-->
                            <!--begin::Select2-->
                            <select class="form-select mb-2" id="subcategorySelect" data-control="select2" data-placeholder="Select an option" data-allow-clear="true">
                                <option></option>
                                @foreach($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                @endforeach
                            </select>
                            <!--end::Select2-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7 mb-7">Məhsulun aid olduğu kateqoriyanı seçin.</div>
                            <!--end::Description-->
                            <!--end::Input group-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Category & tags-->
                </div>
                <!--end::Aside column-->
                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <!--begin:::Tabs-->
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_add_product_general">Ümumi</a>
                        </li>
                        <!--end:::Tab item-->
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_add_product_advanced">SKU/STOK</a>
                        </li>
                        <!--end:::Tab item-->
                    </ul>
                    <!--end:::Tabs-->
                    <!--begin::Tab content-->
                    <div class="tab-content">
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade show active" id="kt_ecommerce_add_product_general" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <!--begin::General options-->
                                <div class="card card-flush py-4">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pt-0">
                                        <!--begin::Input group-->
                                        <!-- Product Name for Azerbaijani (AZ) -->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">Məhsul adı (AZ)</label>
                                            <input type="text" name="product_name_az" class="form-control mb-2" placeholder="Məhsul adı (AZ)" value="" />
                                        </div>

                                        <!-- Product Name for English (EN) -->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">Product Name (EN)</label>
                                            <input type="text" name="product_name_en" class="form-control mb-2" placeholder="Product Name (EN)" value="" />
                                        </div>

                                        <!-- Product Name for Russian (RU) -->
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">Название продукта (RU)</label>
                                            <input type="text" name="product_name_ru" class="form-control mb-2" placeholder="Название продукта (RU)" value="" />
                                        </div>

                                        <!-- Short Description for each language -->
                                        <div>
                                            <label class="form-label">Məhsul haqqında (qısa məlumat AZ)</label>
                                            <textarea name="product_description_az" class="min-h-200px mb-2 form-control"></textarea>
                                        </div>

                                        <div>
                                            <label class="form-label">Product Short Description (EN)</label>
                                            <textarea name="product_description_en" class="min-h-200px mb-2 form-control"></textarea>
                                        </div>

                                        <div>
                                            <label class="form-label">Краткое описание продукта (RU)</label>
                                            <textarea name="product_description_ru" class="min-h-200px mb-2 form-control"></textarea>
                                        </div>
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <!--begin::Label-->
                                            <label class="form-label">Məhsul Rənginin kodu</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="product_color" class="form-control mb-2" placeholder="#FFFFFF" value="" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->

                                    </div>
                                    <!--end::Card header-->
                                </div>
                                <!--end::General options-->
                                <!--begin::Media-->
                                <div class="card card-flush py-4">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Məhsul şəkilləri</h2>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pt-0">
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-2">
                                            <!--begin::Dropzone-->
                                            <div class="dropzone" id="kt_ecommerce_add_product_media_custom">
                                                <!--begin::Message-->
                                                <div class="dz-message needsclick">
                                                    <!--begin::Icon-->
                                                    <i class="ki-duotone ki-file-up text-primary fs-3x">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    <!--end::Icon-->
                                                    <!--begin::Info-->
                                                    <div class="ms-4">
                                                        <h3 class="fs-5 fw-bold text-gray-900 mb-1">Şəkil yükləmək üçün şəkilləri bura sürüşdürün və ya klikləyin</h3>
                                                    </div>
                                                    <!--end::Info-->
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Card header-->
                                </div>
                                <!--end::Media-->
                                <!--begin::Pricing-->
                                <div class="card card-flush py-4">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Qiymət</h2>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pt-0">
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <!--begin::Label-->
                                            <label class="required form-label">Əsas Qiymət</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="price" class="form-control mb-2" placeholder="Məhsulun qiyməti" value="" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-10">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold mb-2">Endirim
                                                <span class="ms-1" data-bs-toggle="tooltip" title="Select a discount type that will be applied to this product">
																		<i class="ki-duotone ki-information-5 text-gray-500 fs-6">
																			<span class="path1"></span>
																			<span class="path2"></span>
																			<span class="path3"></span>
																		</i>
																	</span></label>
                                            <!--End::Label-->
                                            <!--begin::Row-->
                                            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-1 row-cols-xl-3 g-9" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button='true']">
                                                <!--begin::Col-->
                                                <div class="col">
                                                    <!--begin::Option-->
                                                    <label class="btn btn-outline btn-outline-dashed btn-active-light-primary active d-flex text-start p-6" data-kt-button="true">
                                                        <!--begin::Radio-->
                                                        <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
																					<input class="form-check-input" type="radio" name="discount_option" value="1" checked="checked" />
																				</span>
                                                        <!--end::Radio-->
                                                        <!--begin::Info-->
                                                        <span class="ms-5">
																					<span class="fs-4 fw-bold text-gray-800 d-block">Endirimsiz</span>
																				</span>
                                                        <!--end::Info-->
                                                    </label>
                                                    <!--end::Option-->
                                                </div>
                                                <!--end::Col-->
                                                <!--begin::Col-->
                                                <div class="col">
                                                    <!--begin::Option-->
                                                    <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6" data-kt-button="true">
                                                        <!--begin::Radio-->
                                                        <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
																					<input class="form-check-input" type="radio" name="discount_option" value="2" />
																				</span>
                                                        <!--end::Radio-->
                                                        <!--begin::Info-->
                                                        <span class="ms-5">
																					<span class="fs-4 fw-bold text-gray-800 d-block">Faiz endirimi %</span>
																				</span>
                                                        <!--end::Info-->
                                                    </label>
                                                    <!--end::Option-->
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Row-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="d-none mb-10 fv-row" id="kt_ecommerce_add_product_discount_percentage">
                                            <!--begin::Label-->
                                            <label class="form-label">Endirimi təyin edin</label>
                                            <!--end::Label-->

                                            <!--begin::Slider-->
                                            <div class="d-flex flex-column text-center mb-5">
                                                <div class="d-flex align-items-start justify-content-center mb-7">
                                                    <span class="fw-bold fs-3x" id="kt_ecommerce_add_product_discount_label">0</span>
                                                    <span class="fw-bold fs-4 mt-1 ms-2">%</span>
                                                </div>
                                                <div id="kt_ecommerce_add_product_discount_slider" class="noUi-sm"></div>
                                            </div>
                                            <!--end::Slider-->

                                            <!--begin::Checkbox for Unlimited Discount-->
                                            <div class="mb-50 fv-row">
                                                <div class="form-check form-check-solid form-check-custom form-check-inline">
                                                    <input class="form-check-input" type="checkbox" id="unlimited_discount_checkbox" name="unlimited_discount">
                                                    <label class="form-check-label" for="unlimited_discount_checkbox">
                                                        Vaxt Limitsiz endirim
                                                    </label>
                                                </div>
                                            </div>
                                            <!--end::Checkbox-->
                                            <div class="mb-50 fv-row">

                                                <!--begin::Datetime Picker-->
                                                <input id="discount_ends_datetimepicker" name="discount_ends_datetimepicker" placeholder="Select a date and time" class="form-control mb-2" value="{{ \Carbon\Carbon::now() }}" />
                                                <!--end::Datetime Picker-->

                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Endirimin bitmə vaxtı.</div>
                                                <!--end::Description-->
                                            </div>
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Card header-->
                                </div>
                                <!--end::Pricing-->
                            </div>
                        </div>
                        <!--end::Tab pane-->
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade" id="kt_ecommerce_add_product_advanced" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <!--begin::Inventory-->
                                <div class="card card-flush py-4">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Stok Sayı</h2>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pt-0">
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <!--begin::Label-->
                                            <label class="required form-label">SKU</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="sku" class="form-control mb-2" placeholder="SKU Number" value="" />
                                            <!--end::Input-->
                                            <!--begin::Description-->
                                            <div class="text-muted fs-7"> SKU daxil edin</div>
                                            <!--end::Description-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <!--begin::Label-->
                                            <label class="required form-label">Stok sayı</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <div class="d-flex gap-3">
                                                <input type="number" name="warehouse" class="form-control mb-2" placeholder="In warehouse" />
                                            </div>
                                            <!--end::Input-->
                                            <!--begin::Description-->
                                            <div class="text-muted fs-7">Stok sayı daxil edin.</div>
                                            <!--end::Description-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Card header-->
                                </div>
                                <!--end::Inventory-->
                            </div>
                        </div>
                        <!--end::Tab pane-->
                    </div>
                    <!--end::Tab content-->
                    <div class="d-flex justify-content-end">
                        <!--begin::Button-->
                        <a href="../../demo1/dist/apps/ecommerce/catalog/products.html" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">Cancel</a>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="button" id="add_product_submit" class="btn btn-primary">
                            <span class="indicator-label">Save Changes</span>
                        </button>
                        <!--end::Button-->
                    </div>
                </div>
                <!--end::Main column-->
            </form>
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

        document.addEventListener('DOMContentLoaded', function () {

            var toolbarElement = document.querySelector('.ql-image');
            if (toolbarElement) {
                toolbarElement.parentNode.removeChild(toolbarElement); // This removes the image button
            }


            $("#discount_ends_datetimepicker").flatpickr({enableTime:!0,dateFormat:"Y-m-d H:i"})
            const unlimitedDiscountCheckbox = document.getElementById('unlimited_discount_checkbox');
            const discountEndsDatetimePicker = document.getElementById('discount_ends_datetimepicker');

            // Disable datetime picker if the checkbox is checked
            unlimitedDiscountCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    discountEndsDatetimePicker.disabled = true;
                    discountEndsDatetimePicker.value = ''; // Clear value when disabled
                } else {
                    discountEndsDatetimePicker.disabled = false;
                    discountEndsDatetimePicker.value = '{{ \Carbon\Carbon::now() }}'; // Set to default if unchecked
                }
            });
        });
    </script>

    <script>
        var myDropzone = new Dropzone("#kt_ecommerce_add_product_media_custom", {
            url: "{{ route('admin.products.uploadMedia') }}",
            autoProcessQueue: false,
            paramName: "file",
            maxFiles: 10,
            maxFilesize: 10,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function (file, response) {
                // Append the uploaded file path to the hidden input
                var uploadedFilesInput = document.getElementById('uploaded_files');
                var currentFiles = uploadedFilesInput.value ? JSON.parse(uploadedFilesInput.value) : [];
                currentFiles.push(response.filepath); // Add new uploaded file path
                uploadedFilesInput.value = JSON.stringify(currentFiles);

                // Attach the file's path to the file object (for future reference when removing)
                file.uploadedPath = response.filepath;
            },
            removedfile: function (file) {
                // Handle file removal from backend
                var uploadedFilesInput = document.getElementById('uploaded_files');
                var currentFiles = JSON.parse(uploadedFilesInput.value);
                var index = currentFiles.indexOf(file.uploadedPath);
                if (index !== -1) currentFiles.splice(index, 1);
                uploadedFilesInput.value = JSON.stringify(currentFiles);

                // Send AJAX request to delete file from server
                $.ajax({
                    url: "{{ route('admin.products.deleteMedia') }}", // Define the route for deletion
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        filepath: file.uploadedPath // Pass the file path to delete
                    },
                    success: function (response) {
                        console.log(response.message);
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                    }
                });

                // Remove file from Dropzone preview
                file.previewElement.remove();
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#add_product_submit").on('click', function () {

            var productNameAz = $('input[name="product_name_az"]').val();
            var productNameEn = $('input[name="product_name_en"]').val();
            var productNameRu = $('input[name="product_name_ru"]').val();


            var productDescriptionAz = $('textarea[name="product_description_az"]').val();
            var productDescriptionEn = $('textarea[name="product_description_en"]').val();
            var productDescriptionRu = $('textarea[name="product_description_ru"]').val();

            var color = $('input[name="product_color"]').val();
            var price = $('input[name="price"]').val();
            var sku = $('input[name="sku"]').val();
            var stock = $('input[name="warehouse"]').val();
            var avatarFile = $('input[name="avatar"]')[0].files[0];
            var discountOption = $('input[name="discount_option"]:checked').val();
            var discountPercentage = $('#kt_ecommerce_add_product_discount_label').text();
            var unlimitedDiscount = $('#unlimited_discount_checkbox').is(':checked');
            var discountEndDatetime = $('#discount_ends_datetimepicker').val();

            var selectedSubcategoryId= $('#subcategorySelect').val();

            // Collect other form data
            var formData = new FormData();
            formData.append('name_az', productNameAz);
            formData.append('name_en', productNameEn);
            formData.append('name_ru', productNameRu);

            formData.append('description_az', productDescriptionAz);
            formData.append('description_en', productDescriptionEn);
            formData.append('description_ru', productDescriptionRu);

            formData.append('color', color);
            formData.append('price', price);
            formData.append('sku', sku);
            formData.append('stock', stock);
            formData.append('discount_option', discountOption);
            formData.append('selected_sub_category_id', selectedSubcategoryId);

            if (discountOption == '2') {
                formData.append('discount', discountPercentage);
                formData.append('unlimited_discount', unlimitedDiscount ? 1 : 0); // Convert boolean to 1 or 0
                formData.append('discount_ends_at', discountEndDatetime);
            }


            if (avatarFile) {
                formData.append('main_image', avatarFile);
            }


            if (myDropzone.files && myDropzone.files.length > 0) {
                myDropzone.files.forEach(function (file) {
                    if (file.upload) {
                        // Append new uploaded files (binary)
                        formData.append("images[]", file); // This will be the binary data
                    } else if (file.storagePath) {
                        // Append existing file paths (string)
                        formData.append("existing_images[]", file.name); // This will be the string path
                    }
                });
            } else {
                formData.append("images[]", ""); // Append an empty field for images if no files are selected
            }

            // Send AJAX request
            $.ajax({
                url: '{{route("admin.products.store")}}',
                type: 'POST',
                data: formData,
                contentType: false, // Needed for file uploads
                processData: false, // Needed for file uploads
                success: function(response) {
                    // SweetAlert success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Uğurlu!',
                        text: 'Məhsul uğurla yaradıldı!',
                        confirmButtonText: 'Bağla'
                    });
                },
                error: function(xhr, status, error) {
                    // SweetAlert error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Xəta!',
                        text: 'Xəta baş verdi: ' + xhr.responseJSON?.message || error,
                        confirmButtonText: 'Bağla'
                    });
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var productDiscount = {{ $product->discount ?? 0 }};  // Dynamically set the product discount
            var a = document.querySelector("#kt_ecommerce_add_product_discount_label");
            var o = document.querySelector("#kt_ecommerce_add_product_discount_slider");

            noUiSlider.create(o, {
                start: [productDiscount],  // Set the start value to the product discount
                connect: true,
                range: {
                    min: 1,
                    max: 100
                }
            });

            o.noUiSlider.on("update", function(e, t) {
                a.innerHTML = Math.round(e[t]);
            });
        });

    </script>
@endpush
