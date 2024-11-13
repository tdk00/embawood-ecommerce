@extends('admin.metronic')

@section('title', 'Yeni xəbər')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Yeni Xəbər / Slider</h1>
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
                <form action="{{ route('admin.sliders-news.store') }}" method="POST" enctype="multipart/form-data" id="newsForm">
                @csrf

                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $news->slug ?? '') }}" placeholder="Enter slug" required>
                        @error('slug')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                <!-- Title for each language -->
                    <div class="form-group">
                        <label for="title_az">Xəbər başlığı (AZ)</label>
                        <input type="text" name="title_az" id="title_az" class="form-control" value="{{ old('title_az') }}" required>
                        @error('title_az')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="title_en">News Title (EN)</label>
                        <input type="text" name="title_en" id="title_en" class="form-control" value="{{ old('title_en') }}" required>
                        @error('title_en')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="title_ru">Заголовок новости (RU)</label>
                        <input type="text" name="title_ru" id="title_ru" class="form-control" value="{{ old('title_ru') }}" required>
                        @error('title_ru')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Content for each language -->

                    <div class="form-group">
                        <label for="content_az" class="required form-label">Xəbər Content (AZ)</label>
                        <textarea name="content_az" class="form-control mb-2" placeholder="Təsviri Daxil Edin (AZ)">{{ old('content_az') }}</textarea>
                        @error('content_az')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content_en" class="required form-label">Xəbər Content (EN)</label>
                        <textarea name="content_en" class="form-control mb-2" placeholder="Enter Description (EN)">{{ old('content_en') }}</textarea>
                        @error('content_en')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content_ru" class="required form-label">Xəbər Content (RU)</label>
                        <textarea name="content_ru" class="form-control mb-2" placeholder="Введите Описание (RU)">{{ old('content_ru') }}</textarea>
                        @error('content_ru')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Meta Title for each language -->
                    <div class="form-group">
                        <label for="meta_title_az">Meta Başlıq (AZ)</label>
                        <input type="text" name="meta_title_az" value="{{ old('meta_title_az') }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="meta_title_en">Meta Title (EN)</label>
                        <input type="text" name="meta_title_en" value="{{ old('meta_title_en') }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="meta_title_ru">Meta Заголовок (RU)</label>
                        <input type="text" name="meta_title_ru" value="{{ old('meta_title_ru') }}" class="form-control">
                    </div>

                    <!-- Meta Description for each language -->
                    <div class="form-group">
                        <label for="meta_description_az">Meta Təsvir (AZ)</label>
                        <textarea name="meta_description_az" class="form-control">{{ old('meta_description_az') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="meta_description_en">Meta Description (EN)</label>
                        <textarea name="meta_description_en" class="form-control">{{ old('meta_description_en') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="meta_description_ru">Meta Описание (RU)</label>
                        <textarea name="meta_description_ru" class="form-control">{{ old('meta_description_ru') }}</textarea>
                    </div>

                    <!-- Content Web with Quill Editor for each language -->
                    <div class="form-group">
                        <label for="content_web_az" class="form-label">Web Content (AZ)</label>
                        <div id="content_web_az_quill" style="height: 200px;">{!! old('content_web_az') !!}</div>
                        <input type="hidden" name="content_web_az" id="content_web_az">
                        @error('content_web_az')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content_web_en" class="form-label">Web Content (EN)</label>
                        <div id="content_web_en_quill" style="height: 200px;">{!! old('content_web_en') !!}</div>
                        <input type="hidden" name="content_web_en" id="content_web_en">
                        @error('content_web_en')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content_web_ru" class="form-label">Веб-контент (RU)</label>
                        <div id="content_web_ru_quill" style="height: 200px;">{!! old('content_web_ru') !!}</div>
                        <input type="hidden" name="content_web_ru" id="content_web_ru">
                        @error('content_web_ru')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Banner Image -->
                    <div class="form-group">
                        <label for="banner_image">Xəbər Banner Şəkil</label>
                        <input type="file" name="banner_image" id="banner_image" class="form-control" required>
                        @error('banner_image')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Slider Image -->
                    <div class="form-group">
                        <label for="slider_image">Slider şəkli</label>
                        <input type="file" name="slider_image" id="slider_image" class="form-control" required>
                        @error('slider_image')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Is Active -->
                    <div class="form-group">
                        <label for="is_active">Aktivdir?</label>
                        <select name="is_active" id="is_active" class="form-control" required>
                            <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>Bəli</option>
                            <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Xeyr</option>
                        </select>
                        @error('is_active')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Yadda saxla</button>
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
        var quillAz = new Quill('#content_web_az_quill', {
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

        var quillEn = new Quill('#content_web_en_quill', {
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

        var quillRu = new Quill('#content_web_ru_quill', {
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

        // Capture Quill content into hidden input fields on form submission
        document.getElementById('newsForm').onsubmit = function() {
            document.getElementById('content_web_az').value = quillAz.root.innerHTML;
            document.getElementById('content_web_en').value = quillEn.root.innerHTML;
            document.getElementById('content_web_ru').value = quillRu.root.innerHTML;
        };
    </script>
@endpush
