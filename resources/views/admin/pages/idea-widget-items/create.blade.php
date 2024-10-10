@extends('admin.metronic')

@section('title', 'Create Item for ' . $ideaWidgetTab->name)

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Create Item for {{ $ideaWidgetTab->idea->title_homepage_tab_view }}</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.idea-widget-items.store', $ideaWidgetTab->id) }}" method="POST">
                    @csrf

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Sub Idea</label>
                        <select name="sub_idea_id" class="form-control">
                            @foreach ($subIdeas as $subIdea)
                                <option value="{{ $subIdea->id }}">{{ $subIdea->title }}</option>
                            @endforeach
                        </select>
                        @error('sub_idea_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control mb-2" value="{{ old('sort_order') }}" />
                        @error('sort_order')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Create Item</button>
                </form>
            </div>
        </div>
    </div>
@endsection