@extends('admin.metronic')

@section('title', 'Top List for Category: ' . $category->name)

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Top List for Category: {{ $category->name }}</h1>

                <div class="mb-10">
                    <a href="{{ route('admin.category.top-list.create', $category->id) }}" class="btn btn-primary">Add New Product to Top List</a>
                </div>

                @if ($topLists->isEmpty())
                    <div class="alert alert-info">
                        No products have been added to the top list for this category yet.
                    </div>
                @else
                    <table class="table align-middle table-row-dashed">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Position</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($topLists as $topList)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $topList->product->name }}</td>
                                <td>{{ $topList->position }}</td>
                                <td>
                                    <a href="{{ route('admin.category.top-list.edit', ['category_id' => $category->id, 'id' => $topList->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.category.top-list.destroy', ['category_id' => $category->id, 'id' => $topList->id]) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection
