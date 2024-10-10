@extends('admin.metronic')

@section('title', 'Most Viewed Product Widgets')

@section('content')
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Most Viewed Product Widgets</h1>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <a href="{{ route('admin.most-viewed-products.create') }}" class="btn btn-primary mb-3">Add Most Viewed Product Widget</a>

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
                    @foreach($mostViewedProducts as $mostViewedProduct)
                        <tr>
                            <td>{{ $mostViewedProduct->id }}</td>
                            <td>{{ $mostViewedProduct->product->name }}</td>
                            <td>{{ $mostViewedProduct->order }}</td>
                            <td>
                                <a href="{{ route('admin.most-viewed-products.edit', $mostViewedProduct->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.most-viewed-products.destroy', $mostViewedProduct->id) }}" method="POST" class="d-inline">
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
