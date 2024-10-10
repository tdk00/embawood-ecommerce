@extends('admin.metronic')

@section('title', isset($subIdeaItem) ? 'Edit SubIdeaItem' : 'Add New SubIdeaItem')

@section('content')
    <div class="container">
        <h1>{{ isset($subIdeaItem) ? 'Edit SubIdeaItem' : 'Add New SubIdeaItem' }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($subIdeaItem) ? route('admin.sub-idea-items.update', $subIdeaItem->id) : route('admin.sub-idea-items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($subIdeaItem))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="sub_idea_id" class="form-label">Parent SubIdea</label>
                <select class="form-control" name="sub_idea_id" id="sub_idea_id">
                    @foreach($subIdeas as $subIdea)
                        <option value="{{ $subIdea->id }}" {{ (isset($subIdeaItem) && $subIdeaItem->sub_idea_id == $subIdea->id) || (isset($selectedSubIdeaId) && $selectedSubIdeaId == $subIdea->id) ? 'selected' : '' }}>
                            {{ $subIdea->title }}
                        </option>
                    @endforeach
                </select>
                @error('sub_idea_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            @foreach(['az', 'en', 'ru'] as $locale)
                <h3>{{ strtoupper($locale) }} Translations</h3>
                <div class="mb-3">
                    <label for="title_{{ $locale }}" class="form-label">Title [{{ $locale }}]</label>
                    <input type="text" class="form-control" id="title_{{ $locale }}" name="translations[{{ $locale }}][title]"
                           value="{{ old('translations.'.$locale.'.title', isset($subIdeaItem) ? $subIdeaItem->translations->where('locale', $locale)->first()->title ?? '' : '') }}">
                    @error('translations.'.$locale.'.title')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description_{{ $locale }}" class="form-label">Description [{{ $locale }}]</label>
                    <textarea class="form-control" id="description_{{ $locale }}" name="translations[{{ $locale }}][description]">{{ old('translations.'.$locale.'.description', isset($subIdeaItem) ? $subIdeaItem->translations->where('locale', $locale)->first()->description ?? '' : '') }}</textarea>
                    @error('translations.'.$locale.'.description')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach

            <div class="mb-3">
                <label for="products" class="form-label">Associated Products</label>
                <select class="form-control" name="products[]" id="products" multiple data-control="select2">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ isset($subIdeaItem) && $subIdeaItem->products->contains($product->id) ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
                @error('products')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="images" class="form-label">Upload Images</label>
                <input type="file" class="form-control" id="images" name="images[]" multiple>
                @if (isset($subIdeaItem))
                    <div class="mt-2">
                        @foreach($subIdeaItem->images as $image)
                            <img src="{{ Storage::url('images/ideas/' . $image->image_url) }}" alt="Image" width="100">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <label for="is_active" class="form-label">Is Active</label>
                <select class="form-control" name="is_active" id="is_active">
                    <option value="1" {{ old('is_active', $subIdeaItem->is_active ?? 1) == 1 ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('is_active', $subIdeaItem->is_active ?? 1) == 0 ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">{{ isset($subIdeaItem) ? 'Update' : 'Save' }}</button>
        </form>
    </div>
@endsection
