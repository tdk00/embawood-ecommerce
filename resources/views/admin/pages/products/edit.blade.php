@extends('admin.metronic')

@section('title', 'Məhsul redaktə')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Məhsul redaktə</h1>
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
                            <!--end:::Tab item-->
                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_add_product_reviews">Rənglər</a>
                            </li>
                            <!--end:::Tab item-->
                            <li class="nav-item">
                                <a  target="_blank" class="nav-link text-active-primary pb-4" href="{{ route('admin.related-products.index', $product->id) }}">Bənzər məhsullar</a>
                            </li>
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
                                                        {{ number_format(($product->discount ?? 0), 0) }}
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
                                </div>
                            </div>
                            <!--end::Tab pane-->
                            <!--begin::Tab pane-->
                            <div class="tab-pane fade" id="kt_ecommerce_add_product_reviews" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10">
                                    <!--begin::Reviews-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Table-->
                                            <table class="table table-row-dashed fs-6 gy-5 my-0" id="kt_ecommerce_add_product_reviews">
                                                <thead>
                                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                    <th class="w-10px pe-2">
                                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                            <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ecommerce_products_table .form-check-input" value="1" />
                                                        </div>
                                                    </th>
                                                    <th class="min-w-200px">Adı</th>
                                                    <th class="text-end min-w-100px">SKU</th>
                                                    <th class="text-end min-w-70px">Stok</th>
                                                    <th class="text-end min-w-100px">Qiymət</th>
                                                    <th class="text-end min-w-100px">Rating</th>
                                                    <th class="text-end min-w-70px">Əməliyyatlar</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($product->colorVariations as $variation)
                                                    <tr>
                                                        <td>
                                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                <input class="form-check-input" type="checkbox" value="1" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <!--begin::Thumbnail-->
                                                                <a href="{{route('admin.products.edit', $variation->id)}}" class="symbol symbol-50px">
                                                                    <span class="symbol-label" style="background-image:url('{{$product->image}}');"></span>
                                                                </a>
                                                                <!--end::Thumbnail-->
                                                                <div class="ms-5">
                                                                    <!--begin::Title-->
                                                                    <a href="{{route('admin.products.edit', $variation->id)}}" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-kt-ecommerce-product-filter="product_name">{{$product->name}}</a>
                                                                    <!--end::Title-->
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-end pe-0">
                                                            <span class="fw-bold">{{$variation->sku}}</span>
                                                        </td>
                                                        <td class="text-end pe-0" data-order="0">
                                                            <span class="fw-bold text-primary ms-3">{{$variation->stock}}</span>
                                                        </td>
                                                        <td class="text-end pe-0">{{$variation->price}}</td>
                                                        <td class="text-end pe-0" data-order="rating-{{floor($variation->average_rating)}}">
                                                            <div class="rating justify-content-end">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <div class="rating-label {{ $i <= $variation->average_rating ? 'checked' : '' }}">
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
                                                                    <a href="{{route('admin.products.edit', $variation->id)}}" class="menu-link px-3">Edit</a>
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
                                    <!--end::Reviews-->
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
                var price = $('input[name="price"]').val();
                var sku = $('input[name="sku"]').val();
                var stock = $('input[name="warehouse"]').val();
                var avatarFile = $('input[name="avatar"]')[0].files[0];
                var discountOption = $('input[name="discount_option"]:checked').val();
                var discountPercentage = $('#kt_ecommerce_add_product_discount_label').text();
                var unlimitedDiscount = $('#unlimited_discount_checkbox').is(':checked');
                var discountEndDatetime = $('#discount_ends_datetimepicker').val();

                var selectedSubcategoryId= $('#subcategorySelect').val();

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
                            formData.append("existing_images[]", file.name);
                            formData.append("existing_images_ids[]", file.id);// This will be the string path
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
