@extends('admin.metronic')

@section('title', 'Xəbər redaktə')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0"> Xəbər</h1>
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
                <form action="{{ route('admin.sliders-news.update', $slider->id) }}" method="POST" enctype="multipart/form-data" id="newsEditForm">
                @csrf
                @method('PUT')

                <!-- Title for Azerbaijani (AZ) -->
                    <div class="form-group">
                        <label for="title_az">Xəbər başlığı (AZ)</label>
                        <input type="text" name="title_az" id="title_az" value="{{ old('title_az', $slider->news->translations->where('locale', 'az')->first()->title ?? '') }}" class="form-control" required>
                        @error('title_az')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Title for English (EN) -->
                    <div class="form-group">
                        <label for="title_en">News Title (EN)</label>
                        <input type="text" name="title_en" id="title_en" value="{{ old('title_en', $slider->news->translations->where('locale', 'en')->first()->title ?? '') }}" class="form-control" required>
                        @error('title_en')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Title for Russian (RU) -->
                    <div class="form-group">
                        <label for="title_ru">Заголовок новости (RU)</label>
                        <input type="text" name="title_ru" id="title_ru" value="{{ old('title_ru', $slider->news->translations->where('locale', 'ru')->first()->title ?? '') }}" class="form-control" required>
                        @error('title_ru')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Content for Azerbaijani (AZ) -->
                    <div class="form-group">
                        <label for="content_az" class="required form-label">Xəbər Content (AZ)</label>
                        <textarea name="content_az" class="form-control" placeholder="Təsviri Daxil Edin (AZ)">{{ $slider->news->translations->where('locale', 'az')->first()->content ?? '' }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="content_en" class="required form-label">Xəbər Content (EN)</label>
                        <textarea name="content_en" class="form-control" placeholder="Enter Description (EN)">{{ $slider->news->translations->where('locale', 'en')->first()->content ?? '' }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="content_ru" class="required form-label">Xəbər Content (RU)</label>
                        <textarea name="content_ru" class="form-control" placeholder="Введите Описание (RU)">{{ $slider->news->translations->where('locale', 'ru')->first()->content ?? '' }}</textarea>
                    </div>

                    <!-- Banner Image -->
                    <div class="form-group">
                        <label for="banner_image">Xəbər Banner Şəkil</label>
                        <input type="file" name="banner_image" id="banner_image" class="form-control">
                        @if ($slider->news->banner_image)
                            <img src="{{ url('storage/images/news/'.$slider->news->banner_image) }}" width="100" alt="Current Banner">
                        @endif
                        @error('banner_image')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Slider Image -->
                    <div class="form-group">
                        <label for="slider_image">Slider şəkli</label>
                        <input type="file" name="slider_image" id="slider_image" class="form-control">
                        <img src="{{ url('storage/images/home_screen/sliders/'.$slider->slider_image) }}" width="100" alt="Current Slider">
                        @error('slider_image')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Is Active Field -->
                    <div class="form-group">
                        <label for="is_active">Aktivdir?</label>
                        <select name="is_active" id="is_active" class="form-control">
                            <option value="1" {{ old('is_active', $slider->is_active) == 1 ? 'selected' : '' }}>Bəli</option>
                            <option value="0" {{ old('is_active', $slider->is_active) == 0 ? 'selected' : '' }}>Xeyr</option>
                        </select>
                        @error('is_active')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Yenilə</button>
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
@endpush
