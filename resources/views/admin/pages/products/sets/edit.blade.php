@extends('admin.metronic')

@section('title', 'Dəst redaktə')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Dəst redaktə</h1>
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
                <form id="kt_ecommerce_add_product_form" class="form d-flex flex-column flex-lg-row">
                @csrf
                <!--begin::Aside column-->
                    <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                        <!--begin::Thumbnail settings-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Əsas şəkil</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body text-center pt-0">
                                <!--begin::Image input-->
                                <!--begin::Image input placeholder-->
                                <style>
                                    .image-input-placeholder { background-image: url('{{ asset('assets/media/svg/files/blank-image.svg') }}'); }
                                    [data-bs-theme="dark"] .image-input-placeholder { background-image: url('{{ asset('assets/media/svg/files/blank-image-dark.svg') }}'); }
                                </style>
                                <!--end::Image input placeholder-->
                                <div class="image-input image-input-outline
                                        @if($product->main_image) image-input-filled @endif
                                    image-input-placeholder mb-3"
                                     data-kt-image-input="true">
                                    <!--begin::Preview existing avatar-->
                                    <div class="image-input-wrapper w-150px h-150px"
                                         @if($product->main_image)
                                         style="background-image: url('{{ asset('storage/images/products/' . $product->main_image) }}')"
                                        @endif>
                                    </div>
                                    <!--end::Preview existing avatar-->
                                    <!--begin::Label-->
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                           data-kt-image-input-action="change"
                                           data-bs-toggle="tooltip"
                                           title="Change avatar">
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
                                    @if($product->main_image)
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                              data-kt-image-input-action="cancel"
                                              data-bs-toggle="tooltip"
                                              title="Cancel avatar">
                                        <i class="ki-duotone ki-cross fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                        <!--end::Cancel-->
                                        <!--begin::Remove-->
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                              data-kt-image-input-action="remove"
                                              data-bs-toggle="tooltip"
                                              title="Remove avatar">
                                        <i class="ki-duotone ki-cross fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                        <!--end::Remove-->
                                    @endif
                                </div>
                                <!--end::Image input-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Yükləmək üçün şəkili seçin.</div>
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
                                <select class="form-select mb-2" id="subcategorySelect"
                                        name="subcategory_id"
                                        data-control="select2"
                                        data-placeholder="Select an option"
                                        data-allow-clear="true">
                                    <option></option>
                                    @foreach($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}"
                                                @if($product->subcategories?->first()?->id == $subcategory->id) selected @endif>
                                            {{ $subcategory->name }}
                                        </option>
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
                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a target="_blank" class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_add_product_modules">Modullar</a>
                            </li>
                            <!--end:::Tab item-->
                            <!--end:::Tab item-->
                            <li class="nav-item">
                                <a target="_blank" class="nav-link text-active-primary pb-4" href="{{ route('admin.related-products.index', $product->id) }}">Bənzər məhsullar</a>
                            </li>
                            <!--end:::Tab item-->
                            <!--end:::Tab item-->
                            <li class="nav-item">
                                <a target="_blank" class="nav-link text-active-primary pb-4" href="{{ route('admin.purchased-together-products.index', $product->id) }}">Birlikdə alınan məhsullar</a>
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
                                            <div class="card-title">
                                                <h2>Ümumi</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <div class="mb-10 fv-row">
                                                <label class="required form-label">Məhsul adı (AZ)</label>
                                                <input type="text" name="product_name_az" class="form-control mb-2"
                                                       placeholder="Məhsul adı (AZ)" value="{{ old('product_name_az', $product->translations->where('locale', 'az')->first()->name ?? '') }}" />
                                            </div>

                                            <!-- Product Name for English (EN) -->
                                            <div class="mb-10 fv-row">
                                                <label class="required form-label">Product Name (EN)</label>
                                                <input type="text" name="product_name_en" class="form-control mb-2"
                                                       placeholder="Product Name (EN)" value="{{ old('product_name_en', $product->translations->where('locale', 'en')->first()->name ?? '') }}" />
                                            </div>

                                            <!-- Product Name for Russian (RU) -->
                                            <div class="mb-10 fv-row">
                                                <label class="required form-label">Название продукта (RU)</label>
                                                <input type="text" name="product_name_ru" class="form-control mb-2"
                                                       placeholder="Название продукта (RU)" value="{{ old('product_name_ru', $product->translations->where('locale', 'ru')->first()->name ?? '') }}" />
                                            </div>

                                            <!-- Product Short Description for Azerbaijani (AZ) -->
                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Məhsul haqqında (qısa məlumat AZ)</label>
                                                <textarea name="product_description_az" class="min-h-200px mb-2 form-control">{{ old('product_description_az', $product->translations->where('locale', 'az')->first()->description ?? '') }}</textarea>
                                            </div>

                                            <!-- Product Short Description for English (EN) -->
                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Product Short Description (EN)</label>
                                                <textarea name="product_description_en" class="min-h-200px mb-2 form-control">{{ old('product_description_en', $product->translations->where('locale', 'en')->first()->description ?? '') }}</textarea>
                                            </div>

                                            <!-- Product Short Description for Russian (RU) -->
                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Краткое описание продукта (RU)</label>
                                                <textarea name="product_description_ru" class="min-h-200px mb-2 form-control">{{ old('product_description_ru', $product->translations->where('locale', 'ru')->first()->description ?? '') }}</textarea>
                                            </div>
                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="form-label">Məhsul Rənginin kodu</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="text" name="product_color" class="form-control mb-2" placeholder="#FFFFFF"
                                                       value="{{ old('product_color', $product->color) }}" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--end::Card body-->
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
                                                <!--end::Dropzone-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Media-->
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
                                                <input type="text" name="sku" class="form-control mb-2"
                                                       placeholder="SKU Number"
                                                       value="{{ old('sku', $product->sku) }}" />
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
                                                    <input type="number" name="warehouse" class="form-control mb-2"
                                                           placeholder="In warehouse"
                                                           value="{{ old('warehouse', $product->stock) }}" />
                                                </div>
                                                <!--end::Input-->
                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Stok sayı daxil edin.</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Inventory-->
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
                                                        @foreach($product->products as $module)
                                                            <div class="col my-2" data-column-product-id="product_{{$module->id}}" data-product-real-id="{{$module->id}}" data-product-quantity="{{$module->pivot->quantity}}">
                                                                <div class="d-flex align-items-center border border-dashed p-3 rounded bg-white">
                                                                    <!--begin::Thumbnail-->
                                                                    <a href="#" class="symbol symbol-50px">
                                                                        <span class="symbol-label" style="background-image: url('{{ asset('storage/images/products/' . $module->main_image) }}')"></span>
                                                                    </a>
                                                                    <!--end::Thumbnail-->
                                                                    <div class="ms-5">
                                                                        <!--begin::Title-->
                                                                        <a href="#" class="text-gray-800 text-hover-primary fs-5 fw-bold">{{$module->name}}</a>
                                                                        <!--end::Title-->
                                                                        <!--begin::Price-->
                                                                        <div class="fw-semibold fs-7">Qiymət: {{$module->price}}</span></div>
                                                                        <!--end::Price-->
                                                                        <!--begin::Quantity-->
                                                                        <div class="fw-semibold fs-7 column-quantity">Say: <span>{{$module->pivot->quantity}}</span></div>
                                                                        <!--end::Quantity-->
                                                                        <!--begin::SKU-->
                                                                        <div class="text-muted fs-7">SKU: {{$module->sku}}</div>
                                                                        <!--end::SKU-->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
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
                                                    @php
                                                        $sortedProducts = $individualProducts->sortBy(function ($individualProduct) use ($product) {
                                                            return $product->products->contains($individualProduct->id) ? 0 : 1;
                                                        });
                                                    @endphp

                                                    @foreach($sortedProducts as $individualProduct)
                                                        @php
                                                            $isProductInModules = $product->products->contains($individualProduct->id);
                                                            $productModule = $product->products->firstWhere('id', $individualProduct->id);
                                                            $quantity = $isProductInModules ? $productModule->pivot->quantity : 1;
                                                        @endphp
                                                        <tr data-list-product-id="product_{{ $individualProduct->id }}" data-list-product-real-id="{{ $individualProduct->id }}">
                                                            <td>
                                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                    <input class="form-check-input module-check-input" type="checkbox" value="1"
                                                                           @if($isProductInModules) checked @endif />
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
                                                                        <!--begin::SKU-->
                                                                        <div class="text-muted fs-7">SKU: {{$individualProduct->sku}}</div>
                                                                        <!--end::SKU-->
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="text-end pe-5" data-order="17">
                                                                <div class="d-flex justify-content-end align-items-center">
                                                                    <input type="number" class="form-control form-control-sm w-75 list-quantity-input" min="1" value="{{$quantity}}" />
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
                            <a href="" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">Ləğv et</a>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="button" id="edit_product_submit" class="btn btn-primary">
                                <span class="indicator-label">Yadda Saxla</span>
                            </button>
                            <!--end::Button-->
                        </div>
                    </div>
                    <!--end::Main column-->
                </form>f
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>

        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $('.module-check-input').change(function () {
                let $productRow = $(this).closest('tr');
                let productId = $productRow.data('list-product-id');
                let productRealId = $productRow.data('list-product-real-id');
                let quantityInput = $productRow.find('input[type="number"]').val();
                let productName = $productRow.find('.fs-5.fw-bold').text();
                let productPrice = $productRow.find('.list-product-price').data('price');
                let productSku = $productRow.find('.text-muted').text();
                let productThumbnail = $productRow.find('.list-image').data('image');

                if ($(this).is(':checked')) {
                    addProductToSelectedList(productId, productName, productPrice, productSku, productThumbnail, quantityInput, productRealId);
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
            function addProductToSelectedList(productId, productName, productPrice, productSku, productThumbnail, quantity, productRealId) {
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
                        <div class="fw-semibold fs-7">Qiymət: <span>${productPrice}</span></div>
                        <!--end::Price-->
                        <!--begin::Quantity-->
                        <div class="fw-semibold fs-7 column-quantity">Say: <span>${quantity}</span></div>
                        <!--end::Quantity-->
                        <!--begin::SKU-->
                        <div class="text-muted fs-7">${productSku}</div>
                        <!--end::SKU-->
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

            var myDropzone = new Dropzone("#kt_ecommerce_add_product_media_custom", {
                url: "{{ route('admin.products.uploadMedia') }}",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }, // The URL to handle the file uploads on your backend
                addRemoveLinks: true, // Enable remove links
                init: function () {
                    var dropzoneInstance = this;

                    // Static path for the images
                    var basePath = "/public/images/products/";

                    // Fetch the existing file names from the Laravel Blade template
                    var existingFiles = @json($existingFiles);

                    existingFiles.forEach(function (file) {
                        let imagePath = basePath + file.image_path; // Construct full image path
                        let mockFile = {
                            name: file.image_path,
                            imagePath: imagePath,
                            storagePath: '/storage/images/products/' + file.image_path,
                            id: file.id // Include image ID here
                        };

                        // Display the image preview in Dropzone
                        dropzoneInstance.emit("addedfile", mockFile);
                        dropzoneInstance.emit("thumbnail", mockFile, '/storage/images/products/' + file.image_path);
                        dropzoneInstance.emit("complete", mockFile);

                        // Mark file as added so Dropzone doesn't re-upload it
                        dropzoneInstance.files.push(mockFile);
                    });

                    // Handle file removal
                    dropzoneInstance.on("removedfile", function (file) {
                        if (file.name) {
                            // Send an AJAX request to remove the file from the backend
                            $.ajax({
                                url: "{{ route('admin.products.deleteMedia') }}",
                                type: 'POST',
                                data: {
                                    filepath: file.imagePath,  // Send the file name for removal
                                    _token: "{{ csrf_token() }}" // Include CSRF token for security
                                },
                                success: function (response) {
                                    console.log("File removed successfully");
                                },
                                error: function (error) {
                                    console.error("Error removing file", error);
                                }
                            });
                        }
                    });
                }
            });


            $("#edit_product_submit").on('click', function () {

                var productId = {{$product->id}}; // Get the product ID
                var productNameAz = $('input[name="product_name_az"]').val();
                var productNameEn = $('input[name="product_name_en"]').val();
                var productNameRu = $('input[name="product_name_ru"]').val();


                var productDescriptionAz = $('textarea[name="product_description_az"]').val();
                var productDescriptionEn = $('textarea[name="product_description_en"]').val();
                var productDescriptionRu = $('textarea[name="product_description_ru"]').val();

                var color = $('input[name="product_color"]').val();
                var sku = $('input[name="sku"]').val();
                var stock = $('input[name="warehouse"]').val();
                var avatarFile = $('input[name="avatar"]')[0].files[0];
                var selectedSubcategoryId = $('#subcategorySelect').val();

                // Collect color options
                var formData = new FormData();
                formData.append('_method', 'PUT');

                formData.append('name_az', productNameAz);
                formData.append('name_en', productNameEn);
                formData.append('name_ru', productNameRu);

                formData.append('description_az', productDescriptionAz);
                formData.append('description_en', productDescriptionEn);
                formData.append('description_ru', productDescriptionRu);

                formData.append('color', color);
                formData.append('sku', sku);
                formData.append('stock', stock);
                formData.append('selected_sub_category_id', selectedSubcategoryId);


                // Append the avatar file if it exists
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
                            formData.append("existing_images[]", file.name);
                            formData.append("existing_images_ids[]", file.id); // This will be the string path
                        }
                    });
                } else {
                    formData.append("images[]", ""); // Append an empty field for images if no files are selected
                }

                $('#kt_ecommerce_edit_order_selected_products > .col').each(function () {
                    var productRealId = $(this).data('product-real-id');
                    var productQuantity = $(this).data('product-quantity');
                    formData.append('selected_products[' + productRealId + ']', productQuantity);
                });

                // Send AJAX request
                $.ajax({
                    url: '/admin/set-products/update/' + productId, // Ensure productId is added to the URL
                    type: 'POST', // Laravel allows overriding with _method for PUT
                    data: formData,
                    contentType: false, // Needed for file uploads
                    processData: false, // Needed for file uploads
                    success: function(response) {
                        // SweetAlert success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Uğurlu!',
                            text: 'Məhsul uğurla yeniləndi!',
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
        });
    </script>

@endpush
