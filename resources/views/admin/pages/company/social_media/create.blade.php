@extends('admin.metronic')

@section('title', 'Yeni Sosial Media Linki Əlavə Et')

@section('content')
    <div class="container">
        <h1>Yeni Sosial Media Linki Əlavə Et</h1>

        <form action="{{ route('admin.social_media.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="svg_icon">SVG İkon (Yüklə)</label>
                <input type="file" name="svg_icon" id="svg_icon" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="url">URL</label>
                <input type="url" name="url" id="url" class="form-control" value="{{ old('url') }}" required>
            </div>

            <div class="form-group">
                <label for="type">Sosial Media Tipi</label>
                <select name="type" id="type" class="form-control" required>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="is_active">Status</label>
                <select name="is_active" id="is_active" class="form-control">
                    <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Aktiv</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Passiv</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success mt-3">Yarat</button>
        </form>
    </div>
@endsection
