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
                        <h3 class="card-title">Edit Category</h3>
                    </div>

                    <div class="mb-10">
                        <a href="{{ route('admin.category.top-list.index', $category->id) }}" class="btn btn-primary">Top List</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <!-- Name for each language -->
                            <div class="form-group">
                                <label for="name_az" class="required form-label">Category Name (AZ)</label>
                                <input type="text" name="name_az" class="form-control" value="{{ $category->translations->where('locale', 'az')->first()->name ?? '' }}" placeholder="Enter Category Name (AZ)">
                            </div>

                            <div class="form-group">
                                <label for="name_en" class="required form-label">Category Name (EN)</label>
                                <input type="text" name="name_en" class="form-control" value="{{ $category->translations->where('locale', 'en')->first()->name ?? '' }}" placeholder="Enter Category Name (EN)">
                            </div>

                            <div class="form-group">
                                <label for="name_ru" class="required form-label">Название Категории (RU)</label>
                                <input type="text" name="name_ru" class="form-control" value="{{ $category->translations->where('locale', 'ru')->first()->name ?? '' }}" placeholder="Введите Название Категории (RU)">
                            </div>

                            <!-- Banner Image -->
                            <div class="form-group">
                                <label for="banner_image">Banner Image</label>
                                <input type="file" name="banner_image" class="form-control">
                                <div style="padding: 50px">
                                    @if ($category->banner_image)
                                        <img src="{{ $category->banner_image }}" alt="{{ $category->name }}" width="100">
                                    @endif
                                </div>
                            </div>

                            <!-- Widget View Image -->
                            <div class="form-group">
                                <label for="widget_view_image">Ana Səhifə "Widget" Şəkli</label>
                                <input type="file" name="widget_view_image" class="form-control">
                                <div style="padding: 50px">
                                    @if ($category->widget_view_image)
                                        <img src="{{ $category->widget_view_image }}" alt="{{ $category->name }}" width="100">
                                    @endif
                                </div>
                            </div>

                            <!-- Description for each language -->
                            <div class="form-group">
                                <label for="description_az" class="required form-label">Təsvir (AZ)</label>
                                <textarea name="description_az" class="form-control" placeholder="Təsviri Daxil Edin (AZ)">{{ $category->translations->where('locale', 'az')->first()->description ?? '' }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="description_en" class="required form-label">Description (EN)</label>
                                <textarea name="description_en" class="form-control" placeholder="Enter Description (EN)">{{ $category->translations->where('locale', 'en')->first()->description ?? '' }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="description_ru" class="required form-label">Описание (RU)</label>
                                <textarea name="description_ru" class="form-control" placeholder="Введите Описание (RU)">{{ $category->translations->where('locale', 'ru')->first()->description ?? '' }}</textarea>
                            </div>

                            <!-- Is Header Menu Category -->
                            <div class="form-group">
                                <label for="homescreen_widget">is HomeScreen widget ?</label>
                                <input type="checkbox" name="homescreen_widget" value="1" {{ $category->homescreen_widget ? 'checked' : '' }}>
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
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
