@extends('admin.metronic')

@section('title', 'Items for ' . $ideaWidgetTab->name)

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Items for {{ $ideaWidgetTab->idea->title_homepage_tab_view }}</h1>

                <a href="{{ route('admin.idea-widget-items.create', $ideaWidgetTab->id) }}" class="btn btn-primary mb-5">Create New Item</a>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sub Idea</th>
                        <th>Sort Order</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($ideaWidgetItems as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->subIdea->title }}</td>
                            <td>{{ $item->sort_order }}</td>
                            <td>
                                <a href="{{ route('admin.idea-widget-items.edit', [$ideaWidgetTab->id, $item->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
