@extends('admin.metronic')

@section('title', 'Coupons')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <h1>Coupons</h1>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary mb-4">Add New Coupon</a>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Discount</th>
                        <th>Usage Limit</th>
                        <th>Usage Count</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($coupons as $coupon)
                        <tr>
                            <td>{{ $coupon->code }}</td>
                            <td>
                                @if($coupon->type === 'percentage')
                                    {{ $coupon->discount_percentage }}%
                                @elseif($coupon->type === 'amount')
                                    {{ number_format($coupon->amount, 2) }} AZN
                                @endif
                            </td>
                            <td>{{ $coupon->usage_limit }}</td>
                            <td>{{ $coupon->usage_count }}</td>
                            <td>{{ $coupon->is_active ? 'Active' : 'Inactive' }}</td>
                            <td>
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-info">Edit</a>
                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" style="display:inline-block;">
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
        </div>
    </div>
@endsection
