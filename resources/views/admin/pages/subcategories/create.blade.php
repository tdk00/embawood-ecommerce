@extends('admin.metronic')

@section('title', 'SubKateqoriya')

@section('content')

    <!--begin::Content wrapper-->
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">SubKateqoriya</h1>
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
                <!--begin::Layout-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>{{ isset($subcategory) ? 'Edit Subcategory' : 'Add Subcategory' }}</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!-- Show Validation Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ isset($subcategory) ? route('admin.subcategories.update', $subcategory) : route('admin.subcategories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($subcategory))
                            @method('PUT')
                        @endif

                        <!-- Parent Category -->
                            <div class="mb-10 fv-row">
                                <label class="required form-label">Parent Category</label>
                                <select class="form-select mb-2" id="subcategorySelect"
                                        name="category_id"
                                        data-control="select2"
                                        data-placeholder="Select a category"
                                        data-allow-clear="true">
                                    <option></option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                                @if (old('category_id', $subcategory->category_id ?? '') == $category->id) selected @endif>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Name for each language -->
                            <div class="mb-10 fv-row">
                                <label class="required form-label">Subcategory Name (AZ)</label>
                                <input type="text" name="name_az" class="form-control mb-2"
                                       placeholder="Subcategory Name (AZ)"
                                       value="{{ old('name_az', isset($subcategory) ? $subcategory->translations->where('locale', 'az')->first()->name ?? '' : '') }}" />
                            </div>

                            <div class="mb-10 fv-row">
                                <label class="required form-label">Subcategory Name (EN)</label>
                                <input type="text" name="name_en" class="form-control mb-2"
                                       placeholder="Subcategory Name (EN)"
                                       value="{{ old('name_en', isset($subcategory) ? $subcategory->translations->where('locale', 'en')->first()->name ?? '' : '') }}" />
                            </div>

                            <div class="mb-10 fv-row">
                                <label class="required form-label">Название Подкатегории (RU)</label>
                                <input type="text" name="name_ru" class="form-control mb-2"
                                       placeholder="Subcategory Name (RU)"
                                       value="{{ old('name_ru', isset($subcategory) ? $subcategory->translations->where('locale', 'ru')->first()->name ?? '' : '') }}" />
                            </div>

                            <!-- Description for each language -->
                            <div class="mb-10 fv-row">
                                <label class="form-label">Description (AZ)</label>
                                <textarea name="description_az" class="min-h-200px mb-2 form-control"
                                          placeholder="Enter description (AZ)">{{ old('description_az', isset($subcategory) ? $subcategory->translations->where('locale', 'az')->first()->description ?? '' : '') }}</textarea>
                            </div>

                            <div class="mb-10 fv-row">
                                <label class="form-label">Description (EN)</label>
                                <textarea name="description_en" class="min-h-200px mb-2 form-control"
                                          placeholder="Enter description (EN)">{{ old('description_en', isset($subcategory) ? $subcategory->translations->where('locale', 'en')->first()->description ?? '' : '') }}</textarea>
                            </div>

                            <div class="mb-10 fv-row">
                                <label class="form-label">Описание (RU)</label>
                                <textarea name="description_ru" class="min-h-200px mb-2 form-control"
                                          placeholder="Введите Описание">{{ old('description_ru', isset($subcategory) ? $subcategory->translations->where('locale', 'ru')->first()->description ?? '' : '') }}</textarea>
                            </div>

                            <!--begin::Input group for image-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="form-label">Kiçik Şəkil ( menu )</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="file" name="image" class="form-control mb-2" />
                                @if (isset($subcategory) && $subcategory->image)
                                    <img src="{{ asset('storage/images/subcategories/small/' . $subcategory->image) }}" alt="{{ $subcategory->name }}" width="100">
                            @endif
                            <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group for banner image-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="form-label">Banner Image</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="file" name="banner_image" class="form-control mb-2" />
                                @if (isset($subcategory) && $subcategory->banner_image)
                                    <img src="{{ asset('storage/images/subcategories/banner/' . $subcategory->banner_image) }}" alt="{{ $subcategory->name }}" width="100">
                            @endif
                            <!--end::Input-->
                                <!--begin::Input group for banner image-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">Əsas Səhifə Widget Şəkli</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="file" name="widget_view_image" class="form-control mb-2" />
                                    @if (isset($subcategory) && $subcategory->widget_view_image)
                                        <img src="{{ asset('storage/images/subcategories/homescreen/' . $subcategory->widget_view_image) }}" alt="{{ $subcategory->name }}" width="100">
                                @endif
                                <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group for is_popular-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">is Homescreen widget ?</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="checkbox" name="homescreen_widget" value="1" {{ isset($subcategory) && $subcategory->homescreen_widget ? 'checked' : '' }} />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                            <!-- Other fields like image, banner_image, widget_view_image -->

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">{{ isset($subcategory) ? 'Update Subcategory' : 'Create Subcategory' }}</button>
                        </form>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Layout-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
    <!--end::Content wrapper-->

@endsection

@push('scripts')
    <!-- Local Scripts -->
    <script src="{{ asset('assets/admin/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>

    <script src="{{ asset('assets/admin/js/custom/widgets.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="{{ asset('assets/admin/js/custom/apps/ecommerce/customers/listing/listing.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/apps/ecommerce/customers/listing/add.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/apps/ecommerce/customers/listing/export.js') }}"></script>
@endpush
