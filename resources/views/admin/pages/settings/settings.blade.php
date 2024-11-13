@extends('admin.metronic')

@section('title', 'Edit Settings')

@section('content')
    <div class="container">
        <div class="card shadow-sm mt-5">
            <div class="card-header">
                <h3>Edit Settings</h3>
            </div>

            <div class="card-body">
                {{-- Success Message --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Settings Update Form --}}
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf

                    @foreach($settings as $setting)
                        <div class="form-group mb-3">
                            <label for="{{ $setting->key }}" class="form-label">
                                {{ ucfirst(str_replace('_', ' ', $setting->key)) }}
                            </label>

                            @if($setting->type === 'boolean') {{-- Check if the setting type is boolean --}}
                            <select name="{{ $setting->key }}" id="{{ $setting->key }}" class="form-control" required>
                                <option value="1" {{ old($setting->key, $setting->value) == 1 ? 'selected' : '' }}>True</option>
                                <option value="0" {{ old($setting->key, $setting->value) == 0 ? 'selected' : '' }}>False</option>
                            </select>
                            @else {{-- Default to text input for non-boolean values --}}
                            <input
                                type="text"
                                name="{{ $setting->key }}"
                                id="{{ $setting->key }}"
                                class="form-control"
                                value="{{ old($setting->key, $setting->value) }}"
                                required>
                            @endif
                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection
