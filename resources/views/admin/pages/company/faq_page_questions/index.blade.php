@extends('admin.metronic')

@section('title', 'FAQ Questions')

@section('content')
    <div class="container">
        <h1>FAQ Questions</h1>
        <a href="{{ route('admin.faq-page-questions.create') }}" class="btn btn-primary mb-3">Create New Question</a>

        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Question (AZ)</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($faqPageQuestions as $faqPageQuestion)
                <tr>
                    <td>{{ $faqPageQuestion->id }}</td>
                    <td>{{ $faqPageQuestion->translations->where('locale', 'az')->first()->question ?? '' }}</td>
                    <td>
                        <a href="{{ route('admin.faq-page-questions.edit', $faqPageQuestion) }}" class="btn btn-info">Edit</a>
                        <form action="{{ route('admin.faq-page-questions.destroy', $faqPageQuestion) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
