@extends('admin.metronic')

@section('title', 'Create Store')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Create Store</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.stores.store') }}" method="POST">
                    @csrf

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Name</label>
                        <input type="text" name="name" class="form-control mb-2" value="{{ old('name') }}" />
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Address</label>
                        <input type="text" name="address" class="form-control mb-2" value="{{ old('address') }}" />
                        @error('address')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="required form-label">City</label>
                        <input type="text" name="city" class="form-control mb-2" value="{{ old('city') }}" />
                        @error('city')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Region</label>
                        <select name="region_id" class="form-select mb-2" data-control="select2">
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}" {{ old('region_id', $store->region_id ?? '') == $region->id ? 'selected' : '' }}>
                                    {{ $region->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('region_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Latitude</label>
                        <input type="text" id="latitude" name="latitude" class="form-control mb-2" value="{{ old('latitude') }}" />
                        @error('latitude')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Longitude</label>
                        <input type="text" id="longitude" name="longitude" class="form-control mb-2" value="{{ old('longitude') }}" />
                        @error('longitude')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="form-label">Google Maps Link</label>
                        <input type="text" id="maps_link" class="form-control mb-2" placeholder="Enter Google Maps link" />
                    </div>

                    <button type="submit" class="btn btn-primary">Create Store</button>
                </form>

                <script>
                    document.getElementById('maps_link').addEventListener('input', function () {
                        const mapsLink = this.value;

                        // Regex to capture the decimal coordinates from "3dLATITUDE!4dLONGITUDE" in the URL
                        const regex = /3d([-+]?\d*\.\d+)!4d([-+]?\d*\.\d+)/;

                        const match = mapsLink.match(regex);
                        if (match) {
                            const latitude = match[1];
                            const longitude = match[2];

                            // Set values in the latitude and longitude input fields
                            document.getElementById('latitude').value = latitude;
                            document.getElementById('longitude').value = longitude;
                        }
                    });
                </script>
            </div>
        </div>
    </div>
@endsection
