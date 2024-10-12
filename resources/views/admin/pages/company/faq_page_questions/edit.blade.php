@extends('admin.metronic')

@section('title', 'Edit FAQ Question')

@section('content')
    <div class="container">
        <h1>Edit FAQ Question</h1>

        <form action="{{ route('admin.faq-page-questions.update', $faqPageQuestion) }}" method="POST">
            @csrf
            @method('PUT')

            @foreach(['az', 'en', 'ru'] as $locale)
                <div class="form-group">
                    <label for="question_{{ $locale }}">Question ({{ strtoupper($locale) }})</label>
                    <input type="text" name="question_{{ $locale }}" class="form-control" value="{{ $faqPageQuestion->translations->where('locale', $locale)->first()->question ?? '' }}">
                </div>

                <div class="form-group">
                    <label for="answer_{{ $locale }}">Answer ({{ strtoupper($locale) }})</label>
                    <textarea name="answer_{{ $locale }}" class="form-control">{{ $faqPageQuestion->translations->where('locale', $locale)->first()->answer ?? '' }}</textarea>
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
