@extends('admin.metronic')

@section('title', 'Edit Product')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Edit Product</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">eCommerce</li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Catalog</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
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
                                    <h2>Thumbnail</h2>
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
                                <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_add_product_general">General</a>
                            </li>
                            <!--end:::Tab item-->
                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_add_product_advanced">Advanced</a>
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
                                                <h2>General</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="required form-label">Məhsul adı</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="text" name="product_name" class="form-control mb-2"
                                                       placeholder="Məhsul adı"
                                                       value="{{ old('product_name', $product->name) }}" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <div>
                                                <!--begin::Label-->
                                                <label class="form-label">Məhsul haqqında</label>
                                                <!--end::Label-->
                                                <!--begin::Editor-->
                                                <textarea name="product_description" class="min-h-200px mb-2 form-control">{{ old('product_description', $product->description) }}</textarea>
                                                <!--end::Editor-->
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
                                                <input type="hidden" id="uploaded_files" name="uploaded_files"
                                                       value="{{ json_encode($product->images->pluck('image_path')->map(function($path) {
                                                            return ('public/images/products/' . $path);
                                                        })) }}">
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--end::Card body-->
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
                                                <input type="text" name="price" class="form-control mb-2"
                                                       placeholder="Məhsulun qiyməti"
                                                       value="{{ old('price', $product->price) }}" />
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
                                                </span>
                                                </label>
                                                <!--End::Label-->
                                                <!--begin::Row-->
                                                <div class="row row-cols-1 row-cols-md-3 row-cols-lg-1 row-cols-xl-3 g-9"
                                                     data-kt-buttons="true"
                                                     data-kt-buttons-target="[data-kt-button='true']">
                                                    <!--begin::Col-->
                                                    <div class="col">
                                                        <!--begin::Option-->
                                                        <label class="btn btn-outline btn-outline-dashed btn-active-light-primary
                                                                  @if(! $product->has_unlimited_discount && ! $product->has_limited_discount) active @endif
                                                            d-flex text-start p-6"
                                                               data-kt-button="true">
                                                            <!--begin::Radio-->
                                                            <span class="form-check form-check-custom form-check-solid form-check-sm
                                                                     align-items-start mt-1">
                                                            <input class="form-check-input" type="radio"
                                                                   name="discount_option" value="1"
                                                                   @if(! $product->has_unlimited_discount && ! $product->has_limited_discount) checked @endif />
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
                                                        <label class="btn btn-outline btn-outline-dashed btn-active-light-primary
                                                                  @if( $product->has_unlimited_discount ||  $product->has_limited_discount) active @endif
                                                            d-flex text-start p-6"
                                                               data-kt-button="true">
                                                            <!--begin::Radio-->
                                                            <span class="form-check form-check-custom form-check-solid form-check-sm
                                                                     align-items-start mt-1">
                                                            <input class="form-check-input" type="radio"
                                                                   name="discount_option" value="2"
                                                                   @if( $product->has_unlimited_discount ||  $product->has_limited_discount ) checked @endif />
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
                                            <div class="@if(! $product->has_unlimited_discount && ! $product->has_limited_discount) d-none @endif mb-10 fv-row"
                                                 id="kt_ecommerce_add_product_discount_percentage">
                                                <!--begin::Label-->
                                                <label class="form-label">Endirimi təyin edin</label>
                                                <!--end::Label-->

                                                <!--begin::Slider-->
                                                <div class="d-flex flex-column text-center mb-5">
                                                    <div class="d-flex align-items-start justify-content-center mb-7">
                                                    <span class="fw-bold fs-3x" id="kt_ecommerce_add_product_discount_label">
                                                        {{ $product->discount ?? 0 }}
                                                    </span>
                                                        <span class="fw-bold fs-4 mt-1 ms-2">%</span>
                                                    </div>
                                                    <div id="kt_ecommerce_add_product_discount_slider" class="noUi-sm"></div>
                                                </div>
                                                <!--end::Slider-->

                                                <!--begin::Checkbox for Unlimited Discount-->
                                                <div class="mb-50 fv-row">
                                                    <div class="form-check form-check-solid form-check-custom form-check-inline">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="unlimited_discount_checkbox"
                                                               name="unlimited_discount"
                                                               @if($product->has_unlimited_discount) checked @endif>
                                                        <label class="form-check-label" for="unlimited_discount_checkbox">
                                                            Vaxt Limitsiz endirim
                                                        </label>
                                                    </div>
                                                </div>
                                                <!--end::Checkbox-->
                                                <div class="mb-50 fv-row">

                                                    <!--begin::Datetime Picker-->
                                                    <input id="discount_ends_datetimepicker"
                                                           name="discount_ends_datetimepicker"
                                                           @if($product->has_unlimited_discount) disabled @endif
                                                           placeholder="Select a date and time"
                                                           class="form-control mb-2"
                                                           value="{{ $product->discount_ends_at ? \Carbon\Carbon::parse($product->discount_ends_at)->format('Y-m-d H:i') : '' }}" />
                                                    <!--end::Datetime Picker-->

                                                    <!--begin::Description-->
                                                    <div class="text-muted fs-7">Endirimin bitmə vaxtı.</div>
                                                    <!--end::Description-->
                                                </div>
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--end::Card body-->
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
                                    <!--begin::Variations-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Məhsulun rəng variasiyaları</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Input group-->
                                            <div class="" data-kt-ecommerce-catalog-add-product="auto-options">
                                                <!--begin::Label-->
                                                <label class="form-label">Rəngləri əlavə edin</label>
                                                <!--end::Label-->
                                                <!--begin::Repeater-->
                                                <div id="kt_ecommerce_add_product_options">
                                                    <!--begin::Form group-->
                                                    <div class="form-group">
                                                        <div data-repeater-list="kt_ecommerce_add_product_options" class="d-flex flex-column gap-3">
                                                            @foreach($product->colorVariations as $variation)
                                                                <div data-repeater-item="" class="form-group d-flex flex-wrap align-items-center gap-5">
                                                                    <!--begin::Select2-->
                                                                    <div class="w-100 w-md-200px">
                                                                        <select class="form-select" name="product_option"
                                                                                data-placeholder="Rəng"
                                                                                data-kt-ecommerce-catalog-add-product="product_option">
                                                                            <option selected value="color">Rəng</option>
                                                                            <!-- Add other options if necessary -->
                                                                        </select>
                                                                    </div>
                                                                    <!--end::Select2-->
                                                                    <!--begin::Input-->
                                                                    <input type="text" class="form-control mw-100 w-200px product_option_value"
                                                                           name="product_option_value"
                                                                           placeholder="Kod"
                                                                           value="{{ $variation->color }}" />
                                                                    <!--end::Input-->
                                                                    <button type="button" data-repeater-delete=""
                                                                            class="btn btn-sm btn-icon btn-light-danger">
                                                                        <i class="ki-duotone ki-cross fs-1">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                        </i>
                                                                    </button>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <!--end::Form group-->
                                                    <!--begin::Form group-->
                                                    <div class="form-group mt-5">
                                                        <button type="button" data-repeater-create=""
                                                                class="btn btn-sm btn-light-primary">
                                                            <i class="ki-duotone ki-plus fs-2"></i>Yeni əlavə et</button>
                                                    </div>
                                                    <!--end::Form group-->
                                                </div>
                                                <!--end::Repeater-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Variations-->
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
                            <button type="button" id="edit_product_submit" class="btn btn-primary">
                                <span class="indicator-label">Save Changes</span>
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
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Flatpickr
            $("#discount_ends_datetimepicker").flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i"
            });

            const unlimitedDiscountCheckbox = document.getElementById('unlimited_discount_checkbox');
            const discountEndsDatetimePicker = document.getElementById('discount_ends_datetimepicker');

            // Disable datetime picker if the checkbox is checked
            unlimitedDiscountCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    discountEndsDatetimePicker.disabled = true;
                    discountEndsDatetimePicker.value = ''; // Clear value when disabled
                } else {
                    discountEndsDatetimePicker.disabled = false;
                    @if($product->discount_ends_at)
                        discountEndsDatetimePicker.value = '{{ \Carbon\Carbon::parse($product->discount_ends_at)->format('Y-m-d H:i') }}';
                    @else
                        discountEndsDatetimePicker.value = '';
                    @endif
                }
            });

            // Initialize noUiSlider for discount
            var discountSlider = document.getElementById('kt_ecommerce_add_product_discount_slider');
            noUiSlider.create(discountSlider, {
                start: {{ $product->discount ?? 0 }},
                connect: [true, false],
                range: {
                    'min': 0,
                    'max': 100
                },
                step: 1,
                tooltips: true,
                format: {
                    to: function (value) {
                        return Math.round(value);
                    },
                    from: function (value) {
                        return Number(value);
                    }
                }
            });

            discountSlider.noUiSlider.on('update', function (values, handle) {
                document.getElementById('kt_ecommerce_add_product_discount_label').innerText = values[handle];
            });

            // Show or hide discount percentage based on selected option
            $('input[name="discount_option"]').on('change', function () {
                if ($(this).val() == '2') {
                    $('#kt_ecommerce_add_product_discount_percentage').removeClass('d-none');
                } else {
                    $('#kt_ecommerce_add_product_discount_percentage').addClass('d-none');
                }
            });
        });
    </script>

    <script>

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

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
                    var existingFiles = @json($product->images()->where('is_main', '!=', 1)->pluck('image_path')); // Only using file names

                    // Loop over each existing file name and display it in Dropzone
                    existingFiles.forEach(function (fileName) {
                        let imagePath = basePath + fileName; // Construct full image path
                        let mockFile = { name: fileName, imagePath: imagePath, storagePath: '/storage/images/products/' + fileName }; // Use file name as the mock name

                        // Display the image preview in Dropzone
                        dropzoneInstance.emit("addedfile", mockFile);
                        dropzoneInstance.emit( "thumbnail", mockFile, '/storage/images/products/' + fileName );
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
                var productName = $('input[name="product_name"]').val();
                var productDescription = $('textarea[name="product_description"]').val();
                var price = $('input[name="price"]').val();
                var sku = $('input[name="sku"]').val();
                var stock = $('input[name="warehouse"]').val();
                var avatarFile = $('input[name="avatar"]')[0].files[0];
                var discountOption = $('input[name="discount_option"]:checked').val();
                var discountPercentage = $('#kt_ecommerce_add_product_discount_label').text();
                var unlimitedDiscount = $('#unlimited_discount_checkbox').is(':checked');
                var discountEndDatetime = $('#discount_ends_datetimepicker').val();
                var uploadedFiles = $('#uploaded_files').val();
                var selectedSubcategoryId = $('#subcategorySelect').val();

                // Collect color options
                var formData = new FormData();
                formData.append('_method', 'PUT'); // Required for PUT request in FormData
                formData.append('name', productName);
                formData.append('description', productDescription);
                formData.append('uploaded_files', uploadedFiles);
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

                var colors = [];
                $('.product_option_value').each(function () {
                    colors.push($(this).val());
                });

                // Append the colors array to the FormData object
                colors.forEach(function(color, index) {
                    formData.append('colors[' + index + ']', color);
                });

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
                            formData.append("existing_images[]", file.name); // This will be the string path
                        }
                    });
                } else {
                    formData.append("images[]", ""); // Append an empty field for images if no files are selected
                }

                // Send AJAX request
                $.ajax({
                    url: '/admin/products/update/' + productId, // Ensure productId is added to the URL
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

    <script>



    </script>
@endpush
