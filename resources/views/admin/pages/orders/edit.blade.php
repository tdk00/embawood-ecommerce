@extends('admin.metronic')

@section('title', 'View Order')

@section('content')

    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Sifariş məlumatları</h1>
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
                <!--begin::Order details page-->
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
                        <!--begin:::Tabs-->
                        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-lg-n2 me-auto">
                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_sales_order_summary">Sifariş məlumatları</a>
                            </li>
                            <!--end:::Tab item-->
                        </ul>
                        <!--end:::Tabs-->
                    </div>
                    <!--begin::Order summary-->
                    <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
                        <!--begin::Customer details-->
                        <div class="card card-flush py-4 flex-row-fluid">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Sifariş məlumatı</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                        <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-calendar fs-2 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>Tarix</div>
                                            </td>
                                            <td class="fw-bold text-end">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-profile-circle fs-2 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>Müştəri</div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                <div class="d-flex align-items-center justify-content-end">
                                                    <!--begin::Name-->
                                                    <a href="../../demo1/dist/apps/ecommerce/customers/details.html" class="text-gray-600 text-hover-primary">{{$order->user->name}}</a>
                                                    <!--end::Name-->
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-sms fs-2 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>Email</div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                <a href="../../demo1/dist/apps/user-management/users/view.html" class="text-gray-600 text-hover-primary">{{$order->user->email}}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-phone fs-2 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>Telefon</div>
                                            </td>
                                            <td class="fw-bold text-end">{{$order->user->phone}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <!--end::Table-->
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Customer details-->

                        <!--begin::Shipping address-->
                        <div class="card card-flush py-4 flex-row-fluid position-relative">
                            <!--begin::Background-->
                            <div class="position-absolute top-0 end-0 bottom-0 opacity-10 d-flex align-items-center me-5">
                                <i class="ki-solid ki-delivery" style="font-size: 13em"></i>
                            </div>
                            <!--end::Background-->
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Çatdırılma Ünvanı</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">{{ $order->address }}</div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Shipping address-->
                    </div>
                    <!--end::Order summary-->
                    <!--begin::Tab content-->
                    <div class="tab-content">
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">
                            <!--begin::Orders-->
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <!--begin::Product List-->
                                <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2> Sifariş NYC{{$order->id}}C </h2>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pt-0">
                                        <div class="table-responsive">
                                            <!--begin::Table-->
                                            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                                <thead>
                                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                    <th class="min-w-175px">Adı</th>
                                                    <th class="min-w-100px text-end">SKU</th>
                                                    <th class="min-w-70px text-end">Say</th>
                                                    <th class="min-w-100px text-end">Ədəd qiyməti</th>
                                                    <th class="min-w-100px text-end">Cəm qiymət</th>
                                                </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                @foreach($orderItems as $orderItem)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <!--begin::Thumbnail-->
                                                                <a href="{{route('admin.products.edit', $orderItem->product->id)}}" class="symbol symbol-50px">
                                                                    <span class="symbol-label" style="background-image:url('{{$orderItem->product->image}}');"></span>
                                                                </a>
                                                                <!--end::Thumbnail-->
                                                                <!--begin::Title-->
                                                                <div class="ms-5">
                                                                    <a href="{{route('admin.products.edit', $orderItem->product->id)}}" class="fw-bold text-gray-600 text-hover-primary">{{$orderItem->product->name}}</a>
                                                                </div>
                                                                <!--end::Title-->
                                                            </div>
                                                        </td>
                                                        <td class="text-end">{{$orderItem->product->sku}}</td>
                                                        <td class="text-end">{{$orderItem->quantity}}</td>
                                                        <td class="text-end">{{$orderItem->price / $orderItem->quantity}} AZN</td>
                                                        <td class="text-end">{{$orderItem->price}} AZN</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="4" class="fs-3 text-dark text-end">Məhsullar endirim</td>
                                                    <td class="text-dark fs-3 fw-bolder text-end">{{($order->item_discounts_total )}} AZN</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="fs-3 text-dark text-end">Kupon endirim</td>
                                                    <td class="text-dark fs-3 fw-bolder text-end">{{ $order->coupon_discount }} AZN</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="fs-3 text-dark text-end">Bonus endirim</td>
                                                    <td class="text-dark fs-3 fw-bolder text-end">{{ $order->bonus_discount }} AZN</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="fs-3 text-dark text-end">Ümumi cəm</td>
                                                    <td class="text-dark fs-3 fw-bolder text-end">{{($order->total - $order->coupon_discount - $order->bonus_discount - $order->item_discounts_total )}} AZN</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <!--end::Table-->
                                        </div>
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Product List-->
                            </div>
                            <!--end::Orders-->
                        </div>
                        <!--end::Tab pane-->
                    </div>
                    <!--end::Tab content-->
                </div>
                <!--end::Order details page-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
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
