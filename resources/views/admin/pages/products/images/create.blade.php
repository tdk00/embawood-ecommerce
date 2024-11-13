@extends('admin.metronic')

@section('title', 'Yeni Məhsul Şəkli Əlavə Et')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Yeni Məhsul Şəkli Əlavə Et</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.products.images.store', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Şəkil</label>
                        <input type="file" name="image" class="form-control mb-2" />
                        @error('image')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="form-label">Sıra</label>
                        <input type="number" name="order" class="form-control mb-2" value="{{ old('order', 0) }}" />
                        @error('order')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <h4>Alt Text Translations (Optional)</h4>

                        <label class="form-label">Alt Text (AZ)</label>
                        <input type="text" name="alt[az]" class="form-control mb-2" value="{{ old('alt.az') }}" />

                        <label class="form-label">Alt Text (EN)</label>
                        <input type="text" name="alt[en]" class="form-control mb-2" value="{{ old('alt.en') }}" />

                        <label class="form-label">Alt Text (Ru)</label>
                        <input type="text" name="alt[ru]" class="form-control mb-2" value="{{ old('alt.ru') }}" />

                        <!-- Add more languages as needed -->
                        @error('alt.*')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Şəkil Yüklə</button>
                </form>
            </div>
        </div>
    </div>
@endsection
