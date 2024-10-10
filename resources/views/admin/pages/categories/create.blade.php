@extends('admin.metronic')

@section('title', 'Kateqoriya')

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
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Kateqoriya</h1>
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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Kateqoriya Əlavə Et</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Name for each language -->
                            <div class="form-group">
                                <label for="name_az" class="required form-label">Kateqoriya Adı (AZ)</label>
                                <input type="text" name="name_az" class="form-control mb-2" placeholder="Kateqoriya Adını Daxil Edin (AZ)" value="{{ old('name_az') }}">
                                @error('name_az')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="name_en" class="required form-label">Category Name (EN)</label>
                                <input type="text" name="name_en" class="form-control mb-2" placeholder="Enter Category Name (EN)" value="{{ old('name_en') }}">
                                @error('name_en')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="name_ru" class="required form-label">Название категории (RU)</label>
                                <input type="text" name="name_ru" class="form-control mb-2" placeholder="Введите Название Категории (RU)" value="{{ old('name_ru') }}">
                                @error('name_ru')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Banner Image -->
                            <div class="form-group">
                                <label for="banner_image" class="required form-label">Banner Şəkli</label>
                                <input type="file" required name="banner_image" class="form-control mb-2">
                                @error('banner_image')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Widget View Image -->
                            <div class="form-group">
                                <label for="widget_view_image" class="required form-label">Home Screen Widget Şəkli</label>
                                <input type="file" required name="widget_view_image" class="form-control mb-2">
                                @error('widget_view_image')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description for each language -->
                            <div class="form-group">
                                <label for="description_az" class="required form-label">Təsvir (AZ)</label>
                                <textarea name="description_az" class="form-control mb-2" placeholder="Təsviri Daxil Edin (AZ)">{{ old('description_az') }}</textarea>
                                @error('description_az')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description_en" class="required form-label">Description (EN)</label>
                                <textarea name="description_en" class="form-control mb-2" placeholder="Enter Description (EN)">{{ old('description_en') }}</textarea>
                                @error('description_en')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description_ru" class="required form-label">Описание (RU)</label>
                                <textarea name="description_ru" class="form-control mb-2" placeholder="Введите Описание (RU)">{{ old('description_ru') }}</textarea>
                                @error('description_ru')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Is Room Category -->
                            <div class="form-group">
                                <label for="homescreen_widget" class="form-label">is HomeScreen widget ?</label>
                                <input class="form-check" type="checkbox" name="homescreen_widget" value="1" {{ old('homescreen_widget') ? 'checked' : '' }}>
                            </div>

                            <button type="submit" class="btn btn-primary">Yadda Saxla</button>
                        </form>
                    </div>
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
