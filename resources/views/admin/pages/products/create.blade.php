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
                                    <input type="file" name="avatar" accept=".png, .jpg, .jpeg, .webp" />
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
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">Slug</label>
                                            <input type="text" required name="slug" class="form-control mb-2" placeholder="Slug" value="" />
                                        </div>
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
                                        <!-- Meta Title and Description for each language -->
                                        <div class="mb-10 fv-row">
                                            <label class="form-label">Meta Title (AZ)</label>
                                            <input type="text" name="meta_title_az" class="form-control mb-2" placeholder="Meta Title (AZ)" value="" />
                                        </div>

                                        <div class="mb-10 fv-row">
                                            <label class="form-label">Meta Description (AZ)</label>
                                            <textarea name="meta_description_az" class="form-control mb-2" placeholder="Meta Description (AZ)"></textarea>
                                        </div>

                                        <div class="mb-10 fv-row">
                                            <label class="form-label">Meta Title (EN)</label>
                                            <input type="text" name="meta_title_en" class="form-control mb-2" placeholder="Meta Title (EN)" value="" />
                                        </div>

                                        <div class="mb-10 fv-row">
                                            <label class="form-label">Meta Description (EN)</label>
                                            <textarea name="meta_description_en" class="form-control mb-2" placeholder="Meta Description (EN)"></textarea>
                                        </div>

                                        <div class="mb-10 fv-row">
                                            <label class="form-label">Meta Title (RU)</label>
                                            <input type="text" name="meta_title_ru" class="form-control mb-2" placeholder="Meta Title (RU)" value="" />
                                        </div>

                                        <div class="mb-10 fv-row">
                                            <label class="form-label">Meta Description (RU)</label>
                                            <textarea name="meta_description_ru" class="form-control mb-2" placeholder="Meta Description (RU)"></textarea>
                                        </div>

                                        <!-- Quill Editor for Description Web in Azerbaijani (AZ) -->
                                        <div class="form-group">
                                            <label for="description_web_az" class="form-label">Veb Təsviri (AZ)</label>
                                            <div id="description_web_az_quill" style="height: 200px;"></div>
                                            <input type="hidden" name="description_web_az" id="description_web_az">
                                        </div>

                                        <!-- Quill Editor for Description Web in English (EN) -->
                                        <div class="form-group">
                                            <label for="description_web_en" class="form-label">Web Description (EN)</label>
                                            <div id="description_web_en_quill" style="height: 200px;"></div>
                                            <input type="hidden" name="description_web_en" id="description_web_en">
                                        </div>

                                        <!-- Quill Editor for Description Web in Russian (RU) -->
                                        <div class="form-group">
                                            <label for="description_web_ru" class="form-label">Веб Описание (RU)</label>
                                            <div id="description_web_ru_quill" style="height: 200px;"></div>
                                            <input type="hidden" name="description_web_ru" id="description_web_ru">
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
                    </div>
                    <!--end::Tab content-->
                    <div class="d-flex justify-content-end">
                        <!--begin::Button-->
                        <a href="" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">Cancel</a>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var quillAz = new Quill('#description_web_az_quill', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline'],
                    ['image', 'link'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }]
                ]
            },
            placeholder: 'Məhsul haqqında (AZ)...'
        });

        var quillEn = new Quill('#description_web_en_quill', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline'],
                    ['image', 'link'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }]
                ]
            },
            placeholder: 'Product Description (EN)...'
        });

        var quillRu = new Quill('#description_web_ru_quill', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline'],
                    ['image', 'link'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }]
                ]
            },
            placeholder: 'Описание продукта (RU)...'
        });

        $("#add_product_submit").on('click', function () {

            var slug = $('input[name="slug"]').val();
            var productNameAz = $('input[name="product_name_az"]').val();
            var productNameEn = $('input[name="product_name_en"]').val();
            var productNameRu = $('input[name="product_name_ru"]').val();


            var productDescriptionAz = $('textarea[name="product_description_az"]').val();
            var productDescriptionEn = $('textarea[name="product_description_en"]').val();
            var productDescriptionRu = $('textarea[name="product_description_ru"]').val();

            var metaTitleAz = $('input[name="meta_title_az"]').val();
            var metaDescriptionAz = $('textarea[name="meta_description_az"]').val();
            var metaTitleEn = $('input[name="meta_title_en"]').val();
            var metaDescriptionEn = $('textarea[name="meta_description_en"]').val();
            var metaTitleRu = $('input[name="meta_title_ru"]').val();
            var metaDescriptionRu = $('textarea[name="meta_description_ru"]').val();

            // Get content from Quill editors
            var descriptionWebAz = quillAz.root.innerHTML;
            var descriptionWebEn = quillEn.root.innerHTML;
            var descriptionWebRu = quillRu.root.innerHTML;

            var color = $('input[name="product_color"]').val();
            var price = $('input[name="price"]').val();
            var avatarFile = $('input[name="avatar"]')[0].files[0];
            var discountOption = $('input[name="discount_option"]:checked').val();
            var discountPercentage = $('#kt_ecommerce_add_product_discount_label').text();
            var unlimitedDiscount = $('#unlimited_discount_checkbox').is(':checked');
            var discountEndDatetime = $('#discount_ends_datetimepicker').val();

            var selectedSubcategoryId= $('#subcategorySelect').val();

            // Collect other form data
            var formData = new FormData();

            formData.append('slug', slug);

            formData.append('name_az', productNameAz);
            formData.append('name_en', productNameEn);
            formData.append('name_ru', productNameRu);

            formData.append('description_az', productDescriptionAz);
            formData.append('description_en', productDescriptionEn);
            formData.append('description_ru', productDescriptionRu);
            formData.append('meta_title_az', metaTitleAz);
            formData.append('meta_description_az', metaDescriptionAz);
            formData.append('meta_title_en', metaTitleEn);
            formData.append('meta_description_en', metaDescriptionEn);
            formData.append('meta_title_ru', metaTitleRu);
            formData.append('meta_description_ru', metaDescriptionRu);
            formData.append('description_web_az', descriptionWebAz);
            formData.append('description_web_en', descriptionWebEn);
            formData.append('description_web_ru', descriptionWebRu);
            formData.append('color', color);
            formData.append('price', price);
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
