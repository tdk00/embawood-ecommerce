@extends('admin.metronic')

@section('title', 'Edit Region')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Edit Region</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.regions.update', $region->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Name</label>
                        <input type="text" name="name" class="form-control mb-2" value="{{ old('name', $region->name) }}" />
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update Region</button>
                </form>
            </div>
        </div>
    </div>
@endsection
