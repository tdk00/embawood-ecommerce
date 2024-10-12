@extends('admin.metronic')

@section('title', 'Create FAQ Question')

@section('content')
    <div class="container">
        <h1>Create FAQ Question</h1>

        <form action="{{ route('admin.faq-page-questions.store') }}" method="POST">
            @csrf

            @foreach(['az', 'en', 'ru'] as $locale)
                <div class="form-group">
                    <label for="question_{{ $locale }}">Question ({{ strtoupper($locale) }})</label>
                    <input type="text" name="question_{{ $locale }}" class="form-control" value="{{ old('question_' . $locale) }}">
                </div>

                <div class="form-group">
                    <label for="answer_{{ $locale }}">Answer ({{ strtoupper($locale) }})</label>
                    <textarea name="answer_{{ $locale }}" class="form-control">{{ old('answer_' . $locale) }}</textarea>
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
