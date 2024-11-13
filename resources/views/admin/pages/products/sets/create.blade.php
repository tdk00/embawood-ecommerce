@extends('admin.metronic')

@section('title', 'Yeni Dəst')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Yeni Dəst</h1>
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
                            <select class="form-select mb-2" id="subcategorySelect" data-control="select2" data-placeholder="Seçin" data-allow-clear="true">
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
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_add_product_modules">Modullar</a>
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
                                        <!--end::Input group-->
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
                            </div>
                        </div>
                        <!--end::Tab pane-->
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade" id="kt_ecommerce_add_product_modules" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <!--begin::Reviews-->

                                <!--begin::Order details-->
                                <div class="card card-flush py-4">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Modul seç</h2>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pt-0">
                                        <div class="d-flex flex-column gap-10">
                                            <!--begin::Input group-->
                                            <div>
                                                <!--begin::Label-->
                                                <label class="form-label">Dəst üçün modulları seçin</label>
                                                <!--end::Label-->
                                                <!--begin::Selected products-->
                                                <div class="row row-cols-1 row-cols-xl-3 row-cols-md-2 border border-dashed rounded pt-3 pb-1 px-2 mb-5 mh-300px overflow-scroll" id="kt_ecommerce_edit_order_selected_products">
                                                    <!--begin::Empty message-->
                                                    <input type="hidden"  value="" id="product_ids"/>
                                                </div>
                                                <!--begin::Selected products-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Separator-->
                                            <div class="separator"></div>
                                            <!--end::Separator-->
                                            <!--begin::Search products-->
                                            <div class="d-flex align-items-center position-relative mb-n7">
                                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <input type="text" data-kt-ecommerce-edit-order-filter="search" class="form-control form-control-solid w-100 w-lg-50 ps-12" placeholder="Axtarış" />
                                            </div>
                                            <!--end::Search products-->
                                            <!--begin::Table-->
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_edit_order_product_table">
                                                <thead>
                                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                    <th class="w-25px pe-2"></th>
                                                    <th class="min-w-200px">Məhsul</th>
                                                    <th class="min-w-100px text-end pe-5">Say</th>
                                                </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                @foreach($individualProducts as $individualProduct)
                                                    <tr data-list-product-id="product_{{ $individualProduct->id }}" data-list-product-real-id="{{ $individualProduct->id }}">
                                                        <td>
                                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                <input class="form-check-input module-check-input" type="checkbox" value="1" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center" data-kt-ecommerce-edit-order-filter="product">
                                                                <!--begin::Thumbnail-->
                                                                <a class="symbol symbol-50px">
                                                                    <span class="symbol-label list-image" data-image="{{$individualProduct->image}}" style="background-image:url('{{$individualProduct->image}}');"></span>
                                                                </a>
                                                                <!--end::Thumbnail-->
                                                                <div class="ms-5">
                                                                    <!--begin::Title-->
                                                                    <a class="text-gray-800 text-hover-primary fs-5 fw-bold">{{$individualProduct->name}}</a>
                                                                    <!--end::Title-->
                                                                    <!--begin::Price-->
                                                                    <div class="fw-semibold fs-7">Qiymət:
                                                                        <span class="list-product-price" data-kt-ecommerce-edit-order-filter="price" data-price="{{$individualProduct->price}}">{{$individualProduct->price}}</span></div>
                                                                    <!--end::Price-->
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-end pe-5" data-order="17">
                                                            <div class="d-flex justify-content-end align-items-center">
                                                                <input type="number" class="form-control form-control-sm w-75 list-quantity-input" min="1" value="1" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                </tbody>
                                            </table>
                                            <!--end::Table-->
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                </div>
                                <!--end::Order details-->
                                <!--end::Reviews-->
                            </div>
                        </div>
                        <!--end::Tab pane-->
                    </div>
                    <!--end::Tab content-->
                    <div class="d-flex justify-content-end">
                        <!--begin::Button-->
                        <a href="" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">Ləğv Et</a>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="button" id="add_product_submit" class="btn btn-primary">
                            <span class="indicator-label">Yadda Saxla</span>
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
        $(document).ready(function () {
            // Handle product selection/deselection
            $('.module-check-input').change(function () {
                let $productRow = $(this).closest('tr');
                let productId = $productRow.data('list-product-id');
                let productRealId = $productRow.data('list-product-real-id');
                let quantityInput = $productRow.find('input[type="number"]').val();
                let productName = $productRow.find('.fs-5.fw-bold').text();
                let productPrice = $productRow.find('.list-product-price').data('price');
                let productThumbnail = $productRow.find('.list-image').data('image');

                if ($(this).is(':checked')) {
                    addProductToSelectedList(productId, productName, productPrice, productThumbnail, quantityInput, productRealId);
                } else {
                    removeProductFromSelectedList(productId, productRealId);
                }
            });

            $('.list-quantity-input').keyup(function () {
                let $productRow = $(this).closest('tr');
                let productId = $productRow.data('list-product-id');
                let quantityInput = $(this).val();

                // Update the quantity in the selected product list if it exists
                let $existingProduct = $(`[data-column-product-id="${productId}"]`);
                if ($existingProduct.length > 0) {
                    $existingProduct.attr('data-product-quantity', quantityInput);
                    $existingProduct.find('.column-quantity span').text(quantityInput); // Update the displayed quantity
                }
            });

            // Function to add product to the selected list
            function addProductToSelectedList(productId, productName, productPrice, productThumbnail, quantity, productRealId) {
                let $existingProduct = $(`[data-column-product-id="${productId}"]`);

                // If product already exists, just update the quantity
                if ($existingProduct.length > 0) {
                    $existingProduct.attr('data-product-quantity', quantity);
                    $existingProduct.find('.fw-semibold span').text(quantity); // Update the displayed quantity
                } else {
                    // If product does not exist, create new entry
                    let productHTML = `
            <div class="col my-2" data-column-product-id="${productId}" data-product-real-id="${productRealId}" data-product-quantity="${quantity}">
                <div class="d-flex align-items-center border border-dashed p-3 rounded bg-white">
                    <!--begin::Thumbnail-->
                    <a href="#" class="symbol symbol-50px">
                        <span class="symbol-label" style="background-image: url('${productThumbnail}')"></span>
                    </a>
                    <!--end::Thumbnail-->
                    <div class="ms-5">
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fs-5 fw-bold">${productName}</a>
                        <!--end::Title-->
                        <!--begin::Price-->
                        <div class="fw-semibold fs-7">Qiymət: $<span>${productPrice}</span></div>
                        <!--end::Price-->
                        <!--begin::Quantity-->
                        <div class="fw-semibold fs-7 column-quantity">Say: <span>${quantity}</span></div>
                        <!--end::Quantity-->
                    </div>
                </div>
            </div>`;

                    $('#kt_ecommerce_edit_order_selected_products').append(productHTML);
                }
            }

            // Function to remove product from the selected list
            function removeProductFromSelectedList(productId, productRealId) {
                $(`[data-column-product-id="${productId}"]`).remove();
            }


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
                var avatarFile = $('input[name="avatar"]')[0].files[0];
                var selectedSubcategoryId = $('#subcategorySelect').val();

                // Collect form data
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
                formData.append('selected_sub_category_id', selectedSubcategoryId);


                // Append the avatar file if it exists
                if (avatarFile) {
                    formData.append('main_image', avatarFile);
                }


                // Prepare the selected products to send in the request
                $('#kt_ecommerce_edit_order_selected_products > .col').each(function () {
                    var productRealId = $(this).data('product-real-id');
                    var productQuantity = $(this).data('product-quantity');
                    formData.append('selected_products[' + productRealId + ']', productQuantity);
                });

                // Send AJAX request
                $.ajax({
                    url: '{{route("admin.set_products.store")}}',
                    type: 'POST',
                    data: formData,
                    contentType: false, // Needed for file uploads
                    processData: false, // Needed for file uploads
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Uğurlu!',
                            text: 'Məhsul uğurla yaradıldı!',
                            confirmButtonText: 'Bağla'
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Xəta!',
                            text: 'Xəta baş verdi: ' + xhr.responseJSON?.message || error,
                            confirmButtonText: 'Bağla'
                        });
                    }
                });
            });
        });
    </script>

@endpush
