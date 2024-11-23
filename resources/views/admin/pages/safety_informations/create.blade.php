@extends('admin.metronic')

@section('title', 'Add Safety Information')

@section('content')

    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card card-flush h-md-100">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Add Safety Information</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.safety-informations.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Title for each language -->
                            <div class="mb-10">
                                <label for="title_az">Title (AZ)</label>
                                <input type="text" name="title_az" class="form-control mb-2">
                            </div>

                            <div class="mb-10">
                                <label for="title_en">Title (EN)</label>
                                <input type="text" name="title_en" class="form-control">
                            </div>

                            <div class="mb-10">
                                <label for="title_ru">Title (RU)</label>
                                <input type="text" name="title_ru" class="form-control">
                            </div>

                            <!-- Description for each language -->
                            <div class="mb-10">
                                <label for="description_az">Description (AZ)</label>
                                <textarea name="description_az" class="form-control"></textarea>
                            </div>

                            <div class="mb-10">
                                <label for="description_en">Description (EN)</label>
                                <textarea name="description_en" class="form-control"></textarea>
                            </div>

                            <div class="mb-10">
                                <label for="description_ru">Description (RU)</label>
                                <textarea name="description_ru" class="form-control"></textarea>
                            </div>

                            <!-- Icon Upload -->
                            <div class="mb-10">
                                <label for="icon">Icon</label>
                                <input type="file" name="icon" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
