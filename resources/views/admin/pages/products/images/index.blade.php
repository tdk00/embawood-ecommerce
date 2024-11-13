@extends('admin.metronic')

@section('title', 'Məhsul Şəkilləri')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Məhsul Şəkilləri</h1>

                @if (session('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif

                <a href="{{ route('admin.products.images.create', $product->id) }}" class="btn btn-primary mb-4">Yeni Şəkil Əlavə et</a>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Şəkil</th>
                        <th>Sıra</th>
                        <th>Əməliyyatlar</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($images as $image)
                        <tr>
                            <td><img src="{{ asset('storage/images/products/' . $image->image_path) }}" alt="Şəkil" width="100"></td>
                            <td>{{ $image->order }}</td>
                            <td>
                                <a href="{{ route('admin.products.images.edit', [$product->id, $image->id]) }}" class="btn btn-warning btn-sm">Redaktə et</a>
                                <form action="{{ route('admin.products.images.destroy', [$product->id, $image->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bu şəkli silmək istədiyinizdən əminsiniz?')">Sil</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
