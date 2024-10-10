@extends('admin.metronic')

@section('title', 'Safety Information List')

@section('content')

    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Safety Information List
                    </h1>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Safety Information</h3>
                        <div class="card-toolbar">
                            <a href="{{ route('admin.safety-informations.create') }}" class="btn btn-primary">Add New</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title (AZ)</th>
                                <th>Title (EN)</th>
                                <th>Title (RU)</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($safetyInformations as $info)
                                <tr>
                                    <td>{{ $info->id }}</td>
                                    <td>{{ $info->translations->where('locale', 'az')->first()->title ?? 'N/A' }}</td>
                                    <td>{{ $info->translations->where('locale', 'en')->first()->title ?? 'N/A' }}</td>
                                    <td>{{ $info->translations->where('locale', 'ru')->first()->title ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('admin.safety-informations.edit', $info->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('admin.safety-informations.destroy', $info->id) }}" method="POST" style="display:inline-block;">
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
        </div>
    </div>

@endsection
