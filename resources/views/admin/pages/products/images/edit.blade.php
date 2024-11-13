@extends('admin.metronic')

@section('title', 'Şəkli Redaktə Et')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Şəkli Redaktə Et</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.products.images.update', [$product->id, $productImage->id]) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-10 fv-row">
                        <label class="form-label">Sıra</label>
                        <input type="number" name="order" class="form-control mb-2" value="{{ old('order', $productImage->order) }}" />
                        @error('order')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <h4>Alt Text Translations (Optional)</h4>

                        <label class="form-label">Alt Text (AZ)</label>
                        <input type="text" name="alt[az]" class="form-control mb-2" value="{{ old('alt.az', $productImage->getTranslation('az')->alt_text ?? '') }}" />

                        <label class="form-label">Alt Text (EN)</label>
                        <input type="text" name="alt[en]" class="form-control mb-2" value="{{ old('alt.en', $productImage->getTranslation('en')->alt_text ?? '') }}" />

                        <label class="form-label">Alt Text (Ru)</label>
                        <input type="text" name="alt[ru]" class="form-control mb-2" value="{{ old('alt.ru', $productImage->getTranslation('ru')->alt_text ?? '') }}" />

                        <!-- Add more languages as needed -->
                        @error('alt.*')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Yadda Saxla</button>
                </form>
            </div>
        </div>
    </div>
@endsection
