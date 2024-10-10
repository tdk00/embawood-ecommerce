@extends('admin.metronic')

@section('title', 'New Product Widgets')

@section('content')
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>New Product Widgets</h1>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <a href="{{ route('admin.new-products.create') }}" class="btn btn-primary mb-3">Yeni məhsullara məhsul əlavə et</a>

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
                    @foreach($newProducts as $newProduct)
                        <tr>
                            <td>{{ $newProduct->id }}</td>
                            <td>{{ $newProduct->product->name }}</td>
                            <td>{{ $newProduct->order }}</td>
                            <td>
                                <a href="{{ route('admin.new-products.edit', $newProduct->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.new-products.destroy', $newProduct->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
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
