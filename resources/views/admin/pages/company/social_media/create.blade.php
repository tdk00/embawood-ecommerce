@extends('admin.metronic')

@section('title', 'Yeni Sosial Media Linki Əlavə Et')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card card-flush h-md-100">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Yeni Sosial Media Linki Əlavə Et</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.social_media.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-10">
                                <label for="svg_icon">SVG İkon (Yüklə)</label>
                                <input type="file" name="svg_icon" id="svg_icon" class="form-control" required>
                            </div>

                            <div class="mb-10">
                                <label for="url">URL</label>
                                <input type="url" name="url" id="url" class="form-control" value="{{ old('url') }}" required>
                            </div>

                            <div class="mb-10">
                                <label for="type">Sosial Media Tipi</label>
                                <select name="type" id="type" class="form-control" required>
                                    @foreach($types as $type)
                                        <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-10">
                                <label for="is_active">Status</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Aktiv</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Passiv</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success mt-3">Yarat</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
