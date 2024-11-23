@extends('admin.metronic')

@section('title', 'Edit About Us')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.about-us.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                    <div class="card card-flush h-md-100">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Edit About Us</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <!-- Azerbaijani (AZ) -->
                            <div class="mb-10">
                                <label class="form-label">Title (AZ)</label>
                                <input type="text" name="title_az" class="form-control mb-2"
                                       value="{{ old('title_az', $aboutUs->translations->where('locale', 'az')->first()->title ?? '') }}">
                            </div>
                            <!-- English (EN) -->
                            <div class="mb-10">
                                <label class="form-label">Title (EN)</label>
                                <input type="text" name="title_en" class="form-control mb-2"
                                       value="{{ old('title_en', $aboutUs->translations->where('locale', 'en')->first()->title ?? '') }}">
                            </div>
                            <!-- Russian (RU) -->
                            <div class="mb-10">
                                <label class="form-label">Title (RU)</label>
                                <input type="text" name="title_ru" class="form-control mb-2"
                                       value="{{ old('title_ru', $aboutUs->translations->where('locale', 'ru')->first()->title ?? '') }}">
                            </div>
                            <div class="mb-10">
                                <label class="form-label">Description (AZ)</label>
                                <textarea name="description_az" class="form-control mb-2">{{ old('description_az', $aboutUs->translations->where('locale', 'az')->first()->description ?? '') }}</textarea>
                            </div>

                            <div class="mb-10">
                                <label class="form-label">Description (EN)</label>
                                <textarea name="description_en" class="form-control mb-2">{{ old('description_en', $aboutUs->translations->where('locale', 'en')->first()->description ?? '') }}</textarea>
                            </div>

                            <div class="mb-10">
                                <label class="form-label">Description (RU)</label>
                                <textarea name="description_ru" class="form-control mb-2">{{ old('description_ru', $aboutUs->translations->where('locale', 'ru')->first()->description ?? '') }}</textarea>
                            </div>

                            <!-- Banner Image Upload -->
                            <div class="mb-10">
                                <label class="form-label">Banner Image</label>
                                <input type="file" name="banner_image" class="form-control mb-2">
                                @if($aboutUs->banner_image)
                                    <img src="{{ asset('storage/images/category/banner/' . $aboutUs->banner_image) }}" alt="Current Banner Image" style="width: 200px; margin-top: 10px;">
                                @endif
                            </div>

                            <!-- Web Description with Quill -->
                            <div class="form-group mb-10">
                                <label for="description_web_az" class="form-label">Veb Təsviri (AZ)</label>
                                <div id="description_web_az_quill" style="height: 200px;">{!! old('description_web_az', $aboutUs->translations->where('locale', 'az')->first()->description_web ?? '') !!}</div>
                                <input type="hidden" name="description_web_az" id="description_web_az">
                            </div>

                            <!-- Quill Editor for Description Web in English (EN) -->
                            <div class="form-group mb-10">
                                <label for="description_web_en" class="form-label">Web Description (EN)</label>
                                <div id="description_web_en_quill" style="height: 200px;">{!! old('description_web_en', $aboutUs->translations->where('locale', 'en')->first()->description_web ?? '') !!}</div>
                                <input type="hidden" name="description_web_en" id="description_web_en">
                            </div>

                            <!-- Quill Editor for Description Web in Russian (RU) -->
                            <div class="form-group mb-10">
                                <label for="description_web_ru" class="form-label">Веб Описание (RU)</label>
                                <div id="description_web_ru_quill" style="height: 200px;">{!! old('description_web_ru', $aboutUs->translations->where('locale', 'ru')->first()->description_web ?? '') !!}</div>
                                <input type="hidden" name="description_web_ru" id="description_web_ru">
                            </div>
                        </div>
                        <div class="card-footer flex-wrap pt-0">
                            <button type="submit" class="btn btn-primary">Update About Us</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Include Quill library -->
    <script src="{{ asset('assets/admin/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>

    <script src="{{ asset('assets/admin/js/custom/apps/ecommerce/catalog/save-product.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/widgets.js') }}"></script>



    <script>
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

        document.querySelector('form').onsubmit = function() {
            document.getElementById('description_web_az').value = quillAz.root.innerHTML;
            document.getElementById('description_web_en').value = quillEn.root.innerHTML;
            document.getElementById('description_web_ru').value = quillRu.root.innerHTML;
        };
    </script>
@endpush
