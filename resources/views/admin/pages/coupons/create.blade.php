@extends('admin.metronic')

@section('title', '{{ isset($coupon) ? "Edit" : "Create" }} Coupon')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>{{ isset($coupon) ? 'Edit' : 'Create' }} Coupon</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ isset($coupon) ? route('admin.coupons.update', $coupon->id) : route('admin.coupons.store') }}" method="POST">
                    @csrf
                    @if(isset($coupon))
                        @method('PUT')
                    @endif

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Code</label>
                        <input type="text" name="code" class="form-control mb-2" value="{{ old('code', $coupon->code ?? '') }}" />
                        @error('code')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Discount Percentage</label>
                        <input type="number" name="discount_percentage" class="form-control mb-2" value="{{ old('discount_percentage', $coupon->discount_percentage ?? '') }}" />
                        @error('discount_percentage')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="form-label">Usage Limit</label>
                        <input type="number" name="usage_limit" class="form-control mb-2" value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}" />
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="form-label">Min Required Amount</label>
                        <input type="number" name="min_required_amount" class="form-control mb-2" value="{{ old('min_required_amount', $coupon->min_required_amount ?? '') }}" />
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="form-label">Max Required Amount</label>
                        <input type="number" name="max_required_amount" class="form-control mb-2" value="{{ old('max_required_amount', $coupon->max_required_amount ?? '') }}" />
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="required form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control mb-2" value="{{ old('start_date', $coupon->start_date ?? '') }}" />
                        @error('start_date')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="required form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control mb-2" value="{{ old('end_date', $coupon->end_date ?? '') }}" />
                        @error('end_date')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="form-label">Is Active</label>
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active ?? 1) ? 'checked' : '' }} />
                    </div>

                    <button type="submit" class="btn btn-primary">{{ isset($coupon) ? 'Update' : 'Create' }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        flatpickr("input[type='date']", {
            dateFormat: "Y-m-d"  // Set the desired date format here
        });
    </script>
@endpush
