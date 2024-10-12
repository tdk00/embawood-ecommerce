@extends('admin.metronic')

@section('title', 'Create Region')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Create Region</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.regions.store') }}" method="POST">
                    @csrf

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Name</label>
                        <input type="text" name="name" class="form-control mb-2" value="{{ old('name') }}" />
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Create Region</button>
                </form>
            </div>
        </div>
    </div>
@endsection
