@extends('admin.metronic')

@section('title', 'Create Page')

@section('content')
    <div class="container">
        <h1>Create Page</h1>

        <form id="page-form" action="{{ route('admin.pages.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="show_in_footer">Show in App</label>
                <input type="checkbox" name="show_in_footer" value="1" {{ old('show_in_footer') ? 'checked' : '' }}>
            </div>

            @foreach(['az', 'en', 'ru'] as $locale)
                <div class="form-group">
                    <label for="title_{{ $locale }}">Title ({{ strtoupper($locale) }})</label>
                    <input type="text" name="title_{{ $locale }}" class="form-control" value="{{ old('title_' . $locale) }}">
                </div>

                <div class="form-group">
                    <label for="content_{{ $locale }}">Content ({{ strtoupper($locale) }})</label>

                    <!-- Textarea for content -->
                    <textarea name="content_{{ $locale }}" class="form-control" rows="10">{{ old('content_' . $locale) }}</textarea>
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
