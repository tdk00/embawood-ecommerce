@extends('admin.metronic')

@section('title', 'Edit Idea Widget Tab')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Edit Idea Widget Tab</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.idea-widget-tabs.update', $ideaWidgetTab->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Idea</label>
                        <select name="idea_id" class="form-control">
                            @foreach ($ideas as $idea)
                                <option value="{{ $idea->id }}" {{ $ideaWidgetTab->idea_id == $idea->id ? 'selected' : '' }}>
                                    {{ $idea->title_homepage_tab_view }}
                                </option>
                            @endforeach
                        </select>
                        @error('idea_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control mb-2" value="{{ old('sort_order', $ideaWidgetTab->sort_order) }}" />
                        @error('sort_order')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update Idea Widget Tab</button>
                </form>
            </div>
        </div>
    </div>
@endsection
