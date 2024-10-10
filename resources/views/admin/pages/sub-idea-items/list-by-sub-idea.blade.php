@extends('admin.metronic')

@section('title', 'SubIdeaItems for ' . $subIdea->title)

@section('content')
    <div class="container">
        <h1>SubIdeaItems for {{ $subIdea->title }}</h1>

        <a href="{{ route('admin.sub-idea-items.create', ['sub_idea_id' => $subIdea->id]) }}" class="btn btn-primary mb-4">Add New SubIdeaItem</a>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title (AZ)</th>
                <th>Title (EN)</th>
                <th>Title (RU)</th>
                <th>Products</th>
                <th>Images</th>
                <th>Is Active</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($subIdeaItems as $subIdeaItem)
                <tr>
                    <td>{{ $subIdeaItem->id }}</td>
                    <td>{{ $subIdeaItem->translations->where('locale', 'az')->first()->title ?? '' }}</td>
                    <td>{{ $subIdeaItem->translations->where('locale', 'en')->first()->title ?? '' }}</td>
                    <td>{{ $subIdeaItem->translations->where('locale', 'ru')->first()->title ?? '' }}</td>
                    <td>
                        @foreach($subIdeaItem->products as $product)
                            <span>{{ $product->name }}</span><br>
                        @endforeach
                    </td>
                    <td>
                        @foreach($subIdeaItem->images as $image)
                            <img src="{{ Storage::url('images/ideas/' . $image->image_url) }}" alt="Image" width="50">
                        @endforeach
                    </td>
                    <td>{{ $subIdeaItem->is_active ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('admin.sub-idea-items.edit', $subIdeaItem->id) }}" class="btn btn-info btn-sm">Edit</a>
                        <form action="{{ route('admin.sub-idea-items.destroy', $subIdeaItem->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
