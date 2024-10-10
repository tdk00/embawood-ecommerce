@extends('admin.metronic')

@section('title', 'Create Badge')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Create Badge</h1>

                <form action="{{ route('admin.badges.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="badge_image">Badge Image</label>
                        <input type="file" name="badge_image" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="is_active">Is Active</label>
                        <select name="is_active" class="form-control" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Create</button>
                </form>
            </div>
        </div>
    </div>
@endsection
