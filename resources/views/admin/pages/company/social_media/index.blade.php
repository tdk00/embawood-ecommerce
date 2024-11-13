@extends('admin.metronic')

@section('title', 'Sosial Media Linkləri')

@section('content')
    <div class="container">
        <h1>Sosial Media Linkləri</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('admin.social_media.create') }}" class="btn btn-primary mb-4">Yeni Link Əlavə Et</a>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>SVG İkon</th>
                <th>URL</th>
                <th>Status</th>
                <th>Əməliyyatlar</th>
            </tr>
            </thead>
            <tbody>
            @foreach($socialMediaLinks as $link)
                <tr>
                    <td>
                        @if($link->svg_icon)
                            <img src="{{ asset('storage/images/social_media_icons/' . $link->svg_icon) }}" alt="SVG Icon" width="50">
                        @else
                            <span>No Icon</span>
                        @endif
                    </td>
                    <td>{{ $link->url }}</td>
                    <td>{{ $link->is_active ? 'Aktiv' : 'Passiv' }}</td>
                    <td>
                        <a href="{{ route('admin.social_media.edit', $link->id) }}" class="btn btn-info">Redaktə Et</a>
                        <form action="{{ route('admin.social_media.destroy', $link->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Əminsiniz?')">Sil</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
