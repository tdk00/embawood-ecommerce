@extends('admin.metronic')

@section('title', isset($idea) ? 'Edit Idea' : 'Add New Idea')

@section('content')
    <div class="container">
        <h1>{{ isset($idea) ? 'Edit Idea' : 'Add New Idea' }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($idea) ? route('admin.ideas.update', $idea->id) : route('admin.ideas.store') }}" method="POST">
            @csrf
            @if (isset($idea))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="is_active" class="form-label">Is Active</label>
                <select class="form-control" id="is_active" name="is_active">
                    <option value="1" {{ old('is_active', $idea->is_active ?? 1) == 1 ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('is_active', $idea->is_active ?? 1) == 0 ? 'selected' : '' }}>No</option>
                </select>
                @error('is_active')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            @foreach(['az', 'en', 'ru'] as $locale)
                <h3>{{ strtoupper($locale) }} Translations</h3>
                <div class="mb-3">
                    <label for="title_category_view_{{ $locale }}" class="form-label">Title (Category View) [{{ $locale }}]</label>
                    <input type="text" class="form-control" id="title_category_view_{{ $locale }}" name="translations[{{ $locale }}][title_category_view]"
                           value="{{ old('translations.'.$locale.'.title_category_view', isset($idea) ? $idea->translations->where('locale', $locale)->first()->title_category_view ?? '' : '') }}">
                    @error('translations.'.$locale.'.title_category_view')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="title_homepage_tab_view_{{ $locale }}" class="form-label">Title (Homepage Tab View) [{{ $locale }}]</label>
                    <input type="text" class="form-control" id="title_homepage_tab_view_{{ $locale }}" name="translations[{{ $locale }}][title_homepage_tab_view]"
                           value="{{ old('translations.'.$locale.'.title_homepage_tab_view', isset($idea) ? $idea->translations->where('locale', $locale)->first()->title_homepage_tab_view ?? '' : '') }}">
                    @error('translations.'.$locale.'.title_homepage_tab_view')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">{{ isset($idea) ? 'Update' : 'Save' }}</button>
        </form>
    </div>
@endsection
