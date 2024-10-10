@extends('admin.metronic')

@section('title', 'Add New Product Widget')

@section('content')
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Add New Product Widget</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.new-products.store') }}" method="POST">
                    @csrf
                    <div class="mb-10 fv-row">
                        <label class="required form-label">Select Product</label>
                        <select class="form-select mb-2" name="product_id" data-control="select2" data-placeholder="Select a product">
                            <option></option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Order</label>
                        <input type="number" name="order" class="form-control mb-2" placeholder="Enter display order" value="{{ old('order') }}" />
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
