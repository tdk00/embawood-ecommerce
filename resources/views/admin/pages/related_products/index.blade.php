@extends('admin.metronic')

@section('title', 'Məhsulu Redaktə Et')

@section('content')

    <!--begin::Content wrapper-->
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0"></h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-xxl">
                <!--begin::Layout-->
                <h1>{{ $product->name }} Bənzər məhsullar</h1>

                <a href="{{ route('admin.related-products.create', $product->id) }}" class="btn btn-primary">Yeni Bənzər Məhsul</a>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table">
                    <thead>
                    <tr>
                        <th>Başlıq</th>
                        <th>Şəkil</th>
                        <th>Əməliyyat</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($product->similarProducts as $similiarProduct)
                        <tr>
                            <td>{{ $similiarProduct->name }}</td>
                            <td>
                                @if ($similiarProduct->main_image)
                                    <img src="{{ asset('storage/images/products/' . $similiarProduct->main_image) }}" alt="{{ $similiarProduct->name }}" width="50">
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.related-products.detach', ['productId' => $product->id, 'relatedProductId' => $similiarProduct->id]) }}" class="btn btn-sm btn-warning">Əlaqəli məhsulu sil</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <!--end::Layout-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
    <!--end::Content wrapper-->

@endsection

@push('scripts')
    <!-- Local Scripts -->
    <script src="{{ asset('assets/admin/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>

    <script src="{{ asset('assets/admin/js/custom/widgets.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="{{ asset('assets/admin/js/custom/apps/ecommerce/customers/listing/listing.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/apps/ecommerce/customers/listing/add.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/apps/ecommerce/customers/listing/export.js') }}"></script>
@endpush
