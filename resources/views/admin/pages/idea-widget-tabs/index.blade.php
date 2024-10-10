@extends('admin.metronic')

@section('title', 'Idea Widget Tabs')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Idea Widget Tabs</h1>

                <a href="{{ route('admin.idea-widget-tabs.create') }}" class="btn btn-primary mb-5">Create New Tab</a>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Idea</th>
                        <th>Sort Order</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($ideaWidgetTabs as $tab)
                        <tr>
                            <td>{{ $tab->id }}</td>
                            <td>{{ $tab->idea->title_homepage_tab_view }}</td>
                            <td>{{ $tab->sort_order }}</td>
                            <td>
                                <a href="{{ route('admin.idea-widget-tabs.edit', $tab->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <!-- Optionally, add a delete form or link here -->

                                <a href="{{ route('admin.idea-widget-items.index', $tab->id) }}" class="btn btn-sm btn-primary">Manage Items</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
