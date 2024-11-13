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
                                <select class="form-select mb-2" id="subcategorySelect" name="category_id" data-control="select2" data-placeholder="Select a category" data-allow-clear="true">
                                    <option></option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @if (old('category_id', $subcategory->category_id ?? '') == $category->id) selected @endif>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Meta Title for each language -->
                            @foreach(['az' => 'AZ', 'en' => 'EN', 'ru' => 'RU'] as $locale => $label)
                                <div class="mb-10 fv-row">
                                    <label class="form-label">Meta Title ({{ $label }})</label>
                                    <input type="text" name="meta_title_{{ $locale }}" class="form-control mb-2" placeholder="Meta Title ({{ $label }})"
                                           value="{{ old('meta_title_' . $locale, isset($subcategory) ? $subcategory->translations->where('locale', $locale)->first()->meta_title ?? '' : '') }}" />
                                    @error("meta_title_{$locale}")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-10 fv-row">
                                    <label class="form-label">Meta Description ({{ $label }})</label>
                                    <textarea name="meta_description_{{ $locale }}" class="form-control mb-2" placeholder="Meta Description ({{ $label }})">{{ old('meta_description_' . $locale, isset($subcategory) ? $subcategory->translations->where('locale', $locale)->first()->meta_description ?? '' : '') }}</textarea>
                                    @error("meta_description_{$locale}")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach

                        <!-- Name for each language -->
                            @foreach(['az' => 'AZ', 'en' => 'EN', 'ru' => 'RU'] as $locale => $label)
                                <div class="mb-10 fv-row">
                                    <label class="required form-label">Subcategory Name ({{ $label }})</label>
                                    <input type="text" name="name_{{ $locale }}" class="form-control mb-2" placeholder="Subcategory Name ({{ $label }})"
                                           value="{{ old('name_' . $locale, isset($subcategory) ? $subcategory->translations->where('locale', $locale)->first()->name ?? '' : '') }}" />
                                    @error("name_{$locale}")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach

                        <!-- Slug -->
                            <div class="mb-10 fv-row">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" class="form-control mb-2" placeholder="Slug"
                                       value="{{ old('slug', isset($subcategory) ? $subcategory->slug : '') }}" />
                                @error('slug')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description for each language -->
                            @foreach(['az' => 'AZ', 'en' => 'EN', 'ru' => 'RU'] as $locale => $label)
                                <div class="mb-10 fv-row">
                                    <label class="form-label">Description ({{ $label }})</label>
                                    <textarea name="description_{{ $locale }}" class="min-h-200px mb-2 form-control" placeholder="Enter description ({{ $label }})">{{ old('description_' . $locale, isset($subcategory) ? $subcategory->translations->where('locale', $locale)->first()->description ?? '' : '') }}</textarea>
                                    @error("description_{$locale}")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach

                        <!-- Description Web for each language (with Quill) -->
                            @foreach(['az' => 'AZ', 'en' => 'EN', 'ru' => 'RU'] as $locale => $label)
                                <div class="form-group">
                                    <label for="description_web_{{ $locale }}" class="form-label">Web Description ({{ $label }})</label>
                                    <div id="description_web_{{ $locale }}_quill" style="height: 200px;">{!! old('description_web_' . $locale, isset($subcategory) ? $subcategory->translations->where('locale', $locale)->first()->description_web ?? '' : '') !!}</div>
                                    <input type="hidden" name="description_web_{{ $locale }}" id="description_web_{{ $locale }}">
                                    @error("description_web_{$locale}")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                        @endforeach

                        <!-- Input group for image -->
                            <div class="mb-10 fv-row">
                                <label class="form-label">Thumbnail Image</label>
                                <input type="file" name="image" class="form-control mb-2" />
                                @if (isset($subcategory) && $subcategory->image)
                                    <img src="{{ asset('storage/images/subcategories/small/' . $subcategory->image) }}" alt="{{ $subcategory->name }}" width="100">
                                @endif
                                @error('image')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

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
    <script>
        var quillInstances = {};
        @foreach(['az', 'en', 'ru'] as $locale)
            quillInstances['{{ $locale }}'] = new Quill('#description_web_{{ $locale }}_quill', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline'],
                    ['image', 'link'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }]
                ]
            },
            placeholder: 'Enter Web Description ({{ strtoupper($locale) }})...'
        });
        @endforeach

        // On form submit, save Quill contents to hidden inputs
        document.querySelector('form').onsubmit = function() {
            @foreach(['az', 'en', 'ru'] as $locale)
            document.getElementById('description_web_{{ $locale }}').value = quillInstances['{{ $locale }}'].root.innerHTML;
            @endforeach
        };
    </script>
@endpush
