@extends('admin.metronic')

@section('title', 'Edit Safety Information')

@section('content')

    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Safety Information</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.safety-informations.update', $safetyInformation->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Title for each language -->
                            <div class="form-group">
                                <label for="title_az">Title (AZ)</label>
                                <input type="text" name="title_az" class="form-control" value="{{ $safetyInformation->translations->where('locale', 'az')->first()->title ?? '' }}">
                            </div>

                            <div class="form-group">
                                <label for="title_en">Title (EN)</label>
                                <input type="text" name="title_en" class="form-control" value="{{ $safetyInformation->translations->where('locale', 'en')->first()->title ?? '' }}">
                            </div>

                            <div class="form-group">
                                <label for="title_ru">Title (RU)</label>
                                <input type="text" name="title_ru" class="form-control" value="{{ $safetyInformation->translations->where('locale', 'ru')->first()->title ?? '' }}">
                            </div>

                            <!-- Description for each language -->
                            <div class="form-group">
                                <label for="description_az">Description (AZ)</label>
                                <textarea name="description_az" class="form-control">{{ $safetyInformation->translations->where('locale', 'az')->first()->description ?? '' }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="description_en">Description (EN)</label>
                                <textarea name="description_en" class="form-control">{{ $safetyInformation->translations->where('locale', 'en')->first()->description ?? '' }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="description_ru">Description (RU)</label>
                                <textarea name="description_ru" class="form-control">{{ $safetyInformation->translations->where('locale', 'ru')->first()->description ?? '' }}</textarea>
                            </div>

                            <!-- Icon Upload -->
                            <div class="form-group">
                                <label for="icon">Icon</label>
                                <input type="file" name="icon" class="form-control">
                                <div style="padding: 50px">
                                    @if ($safetyInformation->icon)
                                        <img src="{{ Storage::url('images/icons/' . $safetyInformation->icon) }}" alt="Icon" width="100">
                                    @endif
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
