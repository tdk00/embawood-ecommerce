@extends('admin.metronic')

@section('title', 'Special Offer Product Widgets')

@section('content')
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Special Offer Product Widgets</h1>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <a href="{{ route('admin.special-offer-products.create') }}" class="btn btn-primary mb-3">Add Special Offer Product Widget</a>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Order</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($specialOfferProducts as $specialOfferProduct)
                        <tr>
                            <td>{{ $specialOfferProduct->id }}</td>
                            <td>{{ $specialOfferProduct->product->name }}</td>
                            <td>{{ $specialOfferProduct->order }}</td>
                            <td>
                                <a href="{{ route('admin.special-offer-products.edit', $specialOfferProduct->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.special-offer-products.destroy', $specialOfferProduct->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this widget?')">Delete</button>
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
