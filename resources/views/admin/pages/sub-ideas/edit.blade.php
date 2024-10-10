@extends('admin.metronic')

@section('title', isset($subIdea) ? 'Edit SubIdea' : 'Add New SubIdea')

@section('content')
    <div class="container">
        <h1>{{ isset($subIdea) ? 'Edit SubIdea' : 'Add New SubIdea' }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($subIdea) ? route('admin.sub-ideas.update', $subIdea->id) : route('admin.sub-ideas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($subIdea))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="idea_id" class="form-label">Parent Idea</label>
                <select class="form-control" name="idea_id" id="idea_id">
                    @foreach($ideas as $idea)
                        <option value="{{ $idea->id }}" {{ isset($subIdea) && $subIdea->idea_id == $idea->id ? 'selected' : '' }}>{{ $idea->title_category_view }}</option>
                    @endforeach
                </select>
                @error('idea_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            @foreach(['az', 'en', 'ru'] as $locale)
                <h3>{{ strtoupper($locale) }} Translations</h3>
                <div class="mb-3">
                    <label for="title_{{ $locale }}" class="form-label">Title [{{ $locale }}]</label>
                    <input type="text" class="form-control" id="title_{{ $locale }}" name="translations[{{ $locale }}][title]"
                           value="{{ old('translations.'.$locale.'.title', isset($subIdea) ? $subIdea->translations->where('locale', $locale)->first()->title ?? '' : '') }}">
                    @error('translations.'.$locale.'.title')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach

            <div class="mb-3">
                <label for="image_category_view" class="form-label">Image (Category View)</label>
                <input type="file" class="form-control" id="image_category_view" name="image_category_view">
                @if (isset($subIdea) && $subIdea->image_category_view)
                    <img src="{{ Storage::url('images/ideas/' . $subIdea->image_category_view) }}" alt="" width="100">
                @endif
            </div>

            <div class="mb-3">
                <label for="image_homepage_tab_view" class="form-label">Image (Homepage Tab View)</label>
                <input type="file" class="form-control" id="image_homepage_tab_view" name="image_homepage_tab_view">
                @if (isset($subIdea) && $subIdea->image_homepage_tab_view)
                    <img src="{{ Storage::url('images/ideas/' . $subIdea->image_homepage_tab_view) }}" alt="" width="100">
                @endif
            </div>

            <div class="mb-3">
                <label for="is_active" class="form-label">Is Active</label>
                <select class="form-control" name="is_active" id="is_active">
                    <option value="1" {{ old('is_active', $subIdea->is_active ?? 1) == 1 ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('is_active', $subIdea->is_active ?? 1) == 0 ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">{{ isset($subIdea) ? 'Update' : 'Save' }}</button>
        </form>
    </div>
@endsection
