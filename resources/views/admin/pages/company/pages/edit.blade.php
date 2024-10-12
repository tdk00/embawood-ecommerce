@extends('admin.metronic')

@section('title', 'Edit Page')

@section('content')
    <div class="container">
        <h1>Edit Page</h1>

        <form id="page-form" action="{{ route('admin.pages.update', $page) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="show_in_footer">Show in App</label>
                <input type="checkbox" name="show_in_footer" value="1" {{ $page->show_in_footer ? 'checked' : '' }}>
            </div>

            @foreach(['az', 'en', 'ru'] as $locale)
                <div class="form-group">
                    <label for="title_{{ $locale }}">Title ({{ strtoupper($locale) }})</label>
                    <input type="text" name="title_{{ $locale }}" class="form-control" value="{{ $page->translations->where('locale', $locale)->first()->title ?? '' }}">
                </div>

                <div class="form-group">
                    <label for="content_{{ $locale }}">Content ({{ strtoupper($locale) }})</label>

                    <!-- Textarea for content -->
                    <textarea name="content_{{ $locale }}" class="form-control" rows="10">{{ $page->translations->where('locale', $locale)->first()->content ?? '' }}</textarea>
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
