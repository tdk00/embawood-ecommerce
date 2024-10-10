@extends('admin.metronic')

@section('title', '{{ isset($topList) ? "Edit" : "Create" }} Top List')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>{{ isset($topList) ? 'Edit' : 'Create' }} Top List for Category: {{ $category->name }}</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ isset($topList) ? route('admin.category.top-list.update', ['category_id' => $category->id, 'id' => $topList->id]) : route('admin.category.top-list.store', $category->id) }}" method="POST">
                    @csrf
                    @if(isset($topList))
                        @method('PUT')
                    @endif

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Product</label>
                        <select name="product_id" class="form-control mb-2" data-control="select2" >
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id', $topList->product_id ?? '') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Position</label>
                        <input type="number" name="position" class="form-control mb-2" value="{{ old('position', $topList->position ?? '') }}" />
                        @error('position')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">{{ isset($topList) ? 'Update' : 'Create' }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        flatpickr("input[type='date']", {
            dateFormat: "Y-m-d"  // Set the desired date format here
        });
    </script>
@endpush
