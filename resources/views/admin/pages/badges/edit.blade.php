@extends('admin.metronic')

@section('title', 'Edit Badge')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Edit Badge</h1>

                <form action="{{ route('admin.badges.update', $badge->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="badge_image">Badge Image</label>
                        <input type="file" name="badge_image" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="is_active">Is Active</label>
                        <select name="is_active" class="form-control" required>
                            <option value="1" {{ $badge->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$badge->is_active ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
