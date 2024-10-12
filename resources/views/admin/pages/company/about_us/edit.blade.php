@extends('admin.metronic')

@section('title', 'Edit About Us')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Edit About Us</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.about-us.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Azerbaijani (AZ) -->
                    <div class="mb-10">
                        <label class="form-label">Title (AZ)</label>
                        <input type="text" name="title_az" class="form-control mb-2"
                               value="{{ old('title_az', $aboutUs->translations->where('locale', 'az')->first()->title ?? '') }}">
                    </div>
                    <div class="mb-10">
                        <label class="form-label">Description (AZ)</label>
                        <textarea name="description_az" class="form-control mb-2">{{ old('description_az', $aboutUs->translations->where('locale', 'az')->first()->description ?? '') }}</textarea>
                    </div>

                    <!-- English (EN) -->
                    <div class="mb-10">
                        <label class="form-label">Title (EN)</label>
                        <input type="text" name="title_en" class="form-control mb-2"
                               value="{{ old('title_en', $aboutUs->translations->where('locale', 'en')->first()->title ?? '') }}">
                    </div>
                    <div class="mb-10">
                        <label class="form-label">Description (EN)</label>
                        <textarea name="description_en" class="form-control mb-2">{{ old('description_en', $aboutUs->translations->where('locale', 'en')->first()->description ?? '') }}</textarea>
                    </div>

                    <!-- Russian (RU) -->
                    <div class="mb-10">
                        <label class="form-label">Title (RU)</label>
                        <input type="text" name="title_ru" class="form-control mb-2"
                               value="{{ old('title_ru', $aboutUs->translations->where('locale', 'ru')->first()->title ?? '') }}">
                    </div>
                    <div class="mb-10">
                        <label class="form-label">Description (RU)</label>
                        <textarea name="description_ru" class="form-control mb-2">{{ old('description_ru', $aboutUs->translations->where('locale', 'ru')->first()->description ?? '') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Update About Us</button>
                </form>
            </div>
        </div>
    </div>
@endsection
