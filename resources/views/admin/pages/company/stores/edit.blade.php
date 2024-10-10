@extends('admin.metronic')

@section('title', 'Edit Store')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Edit Store</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.stores.update', $store->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Name</label>
                        <input type="text" name="name" class="form-control mb-2" value="{{ old('name', $store->name) }}" />
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Address</label>
                        <input type="text" name="address" class="form-control mb-2" value="{{ old('address', $store->address) }}" />
                        @error('address')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="required form-label">City</label>
                        <input type="text" name="city" class="form-control mb-2" value="{{ old('city', $store->city) }}" />
                        @error('city')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Latitude</label>
                        <input type="text" name="latitude" class="form-control mb-2" value="{{ old('latitude', $store->latitude) }}" />
                        @error('latitude')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Longitude</label>
                        <input type="text" name="longitude" class="form-control mb-2" value="{{ old('longitude', $store->longitude) }}" />
                        @error('longitude')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update Store</button>
                </form>
            </div>
        </div>
    </div>
@endsection
