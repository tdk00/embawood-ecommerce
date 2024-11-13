@extends('admin.metronic')

@section('title', 'Sosial Media Linkini Redaktə Et')

@section('content')
    <div class="container">
        <h1>Sosial Media Linkini Redaktə Et</h1>

        <form action="{{ route('admin.social_media.update', $socialMedia->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="svg_icon">SVG İkon (Yüklə)</label>
                <input type="file" name="svg_icon" id="svg_icon" class="form-control">
                @if($socialMedia->svg_icon)
                    <p>Hazırda Yüklənmiş İkon:
                        <img src="{{ asset('storage/images/social_media_icons/' . $socialMedia->svg_icon) }}" alt="SVG Icon" width="50">
                    </p>
                @else
                    <p>İkon yoxdur</p>
                @endif
            </div>

            <div class="form-group">
                <label for="url">URL</label>
                <input type="url" name="url" id="url" class="form-control" value="{{ old('url', $socialMedia->url) }}" required>
            </div>

            <div class="form-group">
                <label for="type">Sosial Media Tipi</label>
                <select name="type" id="type" class="form-control" required>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ old('type', $socialMedia->type) == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="is_active">Status</label>
                <select name="is_active" id="is_active" class="form-control">
                    <option value="1" {{ $socialMedia->is_active ? 'selected' : '' }}>Aktiv</option>
                    <option value="0" {{ !$socialMedia->is_active ? 'selected' : '' }}>Passiv</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success mt-3">Yenilə</button>
        </form>
    </div>
@endsection
