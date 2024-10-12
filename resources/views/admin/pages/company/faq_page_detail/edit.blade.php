@extends('admin.metronic')

@section('title', 'Edit FAQ Page Detail')

@section('content')
    <div class="container">
        <h1>Edit FAQ Page Detail</h1>
        <a href="{{ route('admin.faq-page-questions.index') }}" class="btn btn-primary mb-3">FAQ questions</a>

        <form action="{{ route('admin.faq-page-detail.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="email_address">Email</label>
                <input type="email_address" name="email_address" class="form-control" value="{{ old('email_address', $faqPageDetail->email_address) }}">
                @error('email_address')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone_number">Phone</label>
                <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $faqPageDetail->phone_number) }}">
                @error('phone_number')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            @foreach(['az', 'en', 'ru'] as $locale)
                <div class="form-group">
                    <label for="email_title_{{ $locale }}">Email Title ({{ strtoupper($locale) }})</label>
                    <input type="text" name="email_title_{{ $locale }}" class="form-control" value="{{ old('email_title_' . $locale, $faqPageDetail->translations->where('locale', $locale)->first()->email_title ?? '') }}">
                    @error('email_title_' . $locale)
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="phone_title_{{ $locale }}">Phone Title ({{ strtoupper($locale) }})</label>
                    <input type="text" name="phone_title_{{ $locale }}" class="form-control" value="{{ old('phone_title_' . $locale, $faqPageDetail->translations->where('locale', $locale)->first()->phone_title ?? '') }}">
                    @error('phone_title_' . $locale)
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email_description_{{ $locale }}">Email Description ({{ strtoupper($locale) }})</label>
                    <textarea name="email_description_{{ $locale }}" class="form-control">{{ old('email_description_' . $locale, $faqPageDetail->translations->where('locale', $locale)->first()->email_description ?? '') }}</textarea>
                    @error('email_description_' . $locale)
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone_description_{{ $locale }}">Phone Description ({{ strtoupper($locale) }})</label>
                    <textarea name="phone_description_{{ $locale }}" class="form-control">{{ old('phone_description_' . $locale, $faqPageDetail->translations->where('locale', $locale)->first()->phone_description ?? '') }}</textarea>
                    @error('phone_description_' . $locale)
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
