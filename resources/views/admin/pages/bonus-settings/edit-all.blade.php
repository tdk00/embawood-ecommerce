@extends('admin.metronic')

@section('title', 'Edit Bonus Settings')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Edit Bonus Settings</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.bonus-settings.updateAll') }}" method="POST">
                    @csrf
                    @method('PUT')


                    @foreach ($bonusSettings as $bonusSetting)
                        <div class="card shadow-sm mt-5">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-dark">{{ ucfirst(str_replace('_', ' ', $bonusSetting->type)) }} Settings</span>
                                </h3>
                            </div>
                            <div class="card-body">


                                <!-- Title and Description Fields for Azerbaijani (az) -->
                                <div class="mb-10">
                                    <label class="form-label">Title (AZ)</label>
                                    <input type="text" name="title_az[{{ $bonusSetting->type }}]" class="form-control mb-2"
                                           value="{{ old('title_az.' . $bonusSetting->type, $bonusSetting->translations->where('locale', 'az')->first()->title ?? '') }}" />
                                    @error("title_az.{{ $bonusSetting->type }}")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Title and Description Fields for English (en) -->
                                <div class="mb-10">
                                    <label class="form-label">Title (EN)</label>
                                    <input type="text" name="title_en[{{ $bonusSetting->type }}]" class="form-control mb-2"
                                           value="{{ old('title_en.' . $bonusSetting->type, $bonusSetting->translations->where('locale', 'en')->first()->title ?? '') }}" />
                                    @error("title_en.{{ $bonusSetting->type }}")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Title and Description Fields for Russian (ru) -->
                                <div class="mb-10">
                                    <label class="form-label">Title (RU)</label>
                                    <input type="text" name="title_ru[{{ $bonusSetting->type }}]" class="form-control mb-2"
                                           value="{{ old('title_ru.' . $bonusSetting->type, $bonusSetting->translations->where('locale', 'ru')->first()->title ?? '') }}" />
                                    @error("title_ru.{{ $bonusSetting->type }}")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">Description (AZ)</label>
                                    <textarea name="description_az[{{ $bonusSetting->type }}]" class="form-control mb-2">{{ old('description_az.' . $bonusSetting->type, $bonusSetting->translations->where('locale', 'az')->first()->description ?? '') }}</textarea>
                                    @error("description_az.{{ $bonusSetting->type }}")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="mb-10">
                                    <label class="form-label">Description (EN)</label>
                                    <textarea name="description_en[{{ $bonusSetting->type }}]" class="form-control mb-2">{{ old('description_en.' . $bonusSetting->type, $bonusSetting->translations->where('locale', 'en')->first()->description ?? '') }}</textarea>
                                    @error("description_en.{{ $bonusSetting->type }}")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">Description (RU)</label>
                                    <textarea name="description_ru[{{ $bonusSetting->type }}]" class="form-control mb-2">{{ old('description_ru.' . $bonusSetting->type, $bonusSetting->translations->where('locale', 'ru')->first()->description ?? '') }}</textarea>
                                    @error("description_ru.{{ $bonusSetting->type }}")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Bonus Amount Field -->
                                <div class="mb-10">
                                    <label class="form-label">Bonus Amount</label>
                                    <input type="number" name="bonus_amount[{{ $bonusSetting->type }}]" class="form-control mb-2"
                                           value="{{ old('bonus_amount.' . $bonusSetting->type, $bonusSetting->bonus_amount) }}" />
                                    @error("bonus_amount.{{ $bonusSetting->type }}")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                            @if ($bonusSetting->type == 'product_view')
                                <!-- Target Count for product_view -->
                                    <div class="mb-10">
                                        <label class="form-label">Target Count</label>
                                        <input type="number" name="target_count[{{ $bonusSetting->type }}]" class="form-control mb-2"
                                               value="{{ old('target_count.' . $bonusSetting->type, $bonusSetting->target_count) }}" />
                                        @error("target_count.{{ $bonusSetting->type }}")
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Period for product_view -->
                                    <div class="mb-10">
                                        <label class="form-label">Period</label>
                                        <select name="period[{{ $bonusSetting->type }}]" class="form-control">
                                            <option value="daily" {{ old('period.' . $bonusSetting->type, $bonusSetting->period) == 'daily' ? 'selected' : '' }}>Daily</option>
                                            <option value="weekly" {{ old('period.' . $bonusSetting->type, $bonusSetting->period) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        </select>
                                        @error("period.{{ $bonusSetting->type }}")
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                <div class="mb-10">
                                    <button type="submit" class="btn btn-primary">Update Bonus Settings</button>
                                </div>
                            </div>
                        </div>


                    @endforeach

                </form>
            </div>
        </div>
    </div>
@endsection
