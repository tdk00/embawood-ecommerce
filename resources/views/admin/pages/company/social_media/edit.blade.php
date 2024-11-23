@extends('admin.metronic')

@section('title', 'Sosial Media Linkini Redaktə Et')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card card-flush h-md-100">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Sosial Media Linkini Redaktə Et</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.social_media.update', $socialMedia->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-10">
                                <label for="svg_icon">SVG İkon (Yüklə)</label>
                                <input type="file" name="svg_icon" id="svg_icon" class="form-control">

                            </div>
                            @if($socialMedia->svg_icon)
                                <div class="mb-10">
                                    <span class="card-label fw-bold text-dark">Hazırda Yüklənmiş İkon</span>
                                    <p>
                                        <img src="{{ asset('storage/images/social_media_icons/' . $socialMedia->svg_icon) }}" alt="SVG Icon" width="50">
                                    </p>
                                </div>
                            @else
                                <p>İkon yoxdur</p>
                            @endif

                            <div class="mb-10">
                                <label for="url">URL</label>
                                <input type="url" name="url" id="url" class="form-control" value="{{ old('url', $socialMedia->url) }}" required>
                            </div>

                            <div class="mb-10">
                                <label for="type">Sosial Media Tipi</label>
                                <select name="type" id="type" class="form-control" required>
                                    @foreach($types as $type)
                                        <option value="{{ $type }}" {{ old('type', $socialMedia->type) == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-10">
                                <label for="is_active">Status</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1" {{ $socialMedia->is_active ? 'selected' : '' }}>Aktiv</option>
                                    <option value="0" {{ !$socialMedia->is_active ? 'selected' : '' }}>Passiv</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success mt-3">Yenilə</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
