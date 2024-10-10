@extends('admin.metronic')

@section('title', 'Edit Store Phone Number')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Edit Store Phone Number</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.store-phone-numbers.update', $storePhoneNumber->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Select Store</label>
                        <select name="store_id" class="form-control mb-2">
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ $storePhoneNumber->store_id == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                            @endforeach
                        </select>
                        @error('store_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Phone Number</label>
                        <input type="text" name="phone_number" class="form-control mb-2" value="{{ old('phone_number', $storePhoneNumber->phone_number) }}" />
                        @error('phone_number')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update Phone Number</button>
                </form>
            </div>
        </div>
    </div>
@endsection