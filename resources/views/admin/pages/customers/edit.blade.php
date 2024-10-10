@extends('admin.metronic')

@section('title', 'İstifadəçi detallı')

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
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Müştəri məlumatları</h1>
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
                <div class="d-flex flex-column flex-xl-row">
                    <!--begin::Sidebar-->
                    <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                        <!--begin::Card-->
                        <div class="card mb-5 mb-xl-8">
                            <!--begin::Card body-->
                            <div class="card-body pt-15">
                                <!--begin::Summary-->
                                <div class="d-flex flex-center flex-column mb-5">
                                    <!--begin::Name-->
                                    <a class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">{{$customer->name}}</a>
                                    <!--end::Name-->
                                    <!--begin::Email-->
                                    <a class="fs-5 fw-semibold text-muted text-hover-primary mb-6">{{$customer->phone}}</a>
                                    <!--end::Email-->
                                </div>
                                <!--end::Summary-->
                                <div class="separator separator-dashed my-3"></div>
                                <!--begin::Details content-->
                                <div class="pb-5 fs-6">
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Telefon</div>
                                    <div class="text-gray-600">{{$customer->phone}}</div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Ünvan</div>
                                    <div class="text-gray-600">
                                        {{$customer->deliveryAddresses?->first()?->address_line1 ?? ""}}
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Ümumi bonus miqdarı</div>
                                    <div class="text-gray-600">{{$customer->total_bonus_amount}}</div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">İstifadə edilmiş bonus miqdarı</div>
                                    <div class="text-gray-600">{{$customer->used_bonus_amount}}</div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Qalan bonus miqdarı</div>
                                    <div class="text-gray-600">{{$customer->remaining_bonus_amount}}</div>
                                    <!--begin::Details item-->
                                </div>
                                <!--end::Details content-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Sidebar-->
                    <!--begin::Content-->
                    <div class="flex-lg-row-fluid ms-lg-15">
                        <!--begin:::Tabs-->
                        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_customer_overview">Sifarişlər</a>
                            </li>
                            <!--end:::Tab item-->
                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_customer_general">Ünvanlar</a>
                            </li>
                            <!--end:::Tab item-->
                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_customer_advanced">Səbət</a>
                            </li>
                            <!--end:::Tab item-->
                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_customer_favorites">Bəyənilənlər</a>
                            </li>
                            <!--end:::Tab item-->
                        </ul>
                        <!--end:::Tabs-->
                        <!--begin:::Tab content-->
                        <div class="tab-content" id="myTabContent">
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade show active" id="kt_ecommerce_customer_overview" role="tabpanel">
                                <!--begin::Card-->
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <!--begin::Card header-->
                                    <div class="card-header border-0">
                                        <!--begin::Card title-->
                                        <div class="card-title">
                                            <h2>Sifarişlər</h2>
                                        </div>
                                        <!--end::Card title-->
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pt-0 pb-5">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-dashed gy-5" id="kt_table_customers_payment">
                                            <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                            <tr class="text-start text-muted text-uppercase gs-0">
                                                <th class="min-w-100px">Sifariş nömrəsi</th>
                                                <th>Status</th>
                                                <th>Ümumi endirimsiz məbləğ</th>
                                                <th class="min-w-100px">Endirim</th>
                                                <th class="min-w-100px">Tarix</th>
                                            </tr>
                                            </thead>
                                            <tbody class="fs-6 fw-semibold text-gray-600">
                                            @foreach( $customer->orders as $order )
                                                <tr>
                                                    <td>
                                                        <a href="{{route('admin.orders.edit', $order->id)}}" class="text-gray-600 text-hover-primary mb-1">NYC{{$order->id}}C</a>
                                                    </td>
                                                    <td>
                                                        <div class="badge {{ $badgeClassMapping[$order->status] ?? 'badge-light-secondary' }}">
                                                            {{ $statusMapping[$order->status] ?? $order->status }}
                                                        </div>
                                                    </td>
                                                    <td>{{$order->total}}</td>
                                                    <td>{{ $order->coupon_discount + $order->item_discounts_total}}</td>
                                                    <td>{{$order->created_at}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Card-->
                            </div>
                            <!--end:::Tab pane-->
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade" id="kt_ecommerce_customer_general" role="tabpanel">
                                <!--begin::Card-->
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <!--begin::Card header-->
                                    <div class="card-header border-0">
                                        <!--begin::Card title-->
                                        <div class="card-title">
                                            <h2>Adresslər</h2>
                                        </div>
                                        <!--end::Card title-->
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div id="kt_ecommerce_customer_addresses" class="card-body pt-0 pb-5">
                                        <div class="accordion accordion-icon-toggle" id="kt_ecommerce_customer_addresses_accordion">
                                            <!--begin::Addresses-->
                                            <!--begin::Address-->
                                            @foreach($customer->deliveryAddresses as $address)
                                                <div class="py-0">
                                                    <!--begin::Header-->
                                                    <div class="py-3 d-flex flex-stack flex-wrap">
                                                        <!--begin::Toggle-->
                                                        <div class="accordion-header d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#kt_ecommerce_customer_addresses_1" role="button" aria-expanded="false" aria-controls="kt_customer_view_payment_method_1">
                                                            <!--begin::Summary-->
                                                            <div class="me-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="fs-4 fw-bold">{{$address->city}}</div>
                                                                    @if($address->is_default == 1)
                                                                        <div class="badge badge-light-primary ms-5">Default Address</div>
                                                                    @endif
                                                                </div>
                                                                <div class="text-muted">{{$address->address_line1}}</div>
                                                            </div>
                                                            <!--end::Summary-->
                                                        </div>
                                                        <!--end::Toggle-->
                                                    </div>
                                                    <!--end::Header-->
                                                </div>
                                            @endforeach
                                            <!--end::Address-->
                                            <!--end::Addresses-->
                                        </div>
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Card-->
                            </div>
                            <!--end:::Tab pane-->
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade" id="kt_ecommerce_customer_advanced" role="tabpanel">
                                <!--begin::Product List-->
                                <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Səbət</h2>
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
                                                    <th class="min-w-70px text-end">Stok</th>
                                                    <th class="min-w-100px text-end">Ədad qiymət</th>
                                                    <th class="min-w-100px text-end">Cəm qiymət</th>
                                                </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                @foreach($basketItems as $item)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <!--begin::Thumbnail-->
                                                                <a href="{{route('admin.products.edit', $item->product->id)}}" class="symbol symbol-50px">
                                                                    <span class="symbol-label" style="background-image:url('{{$item->product->image}}');"></span>
                                                                </a>
                                                                <!--end::Thumbnail-->
                                                                <!--begin::Title-->
                                                                <div class="ms-5">
                                                                    <a href="{{route('admin.products.edit', $item->product->id)}}" class="fw-bold text-gray-600 text-hover-primary">{{$item->product->name}}</a>
                                                                </div>
                                                                <!--end::Title-->
                                                            </div>
                                                        </td>
                                                        <td class="text-end">{{$item->product->sku}}</td>
                                                        <td class="text-end">{{$item->quantity}}</td>
                                                        <td class="text-end">{{$item->product->final_price}}</td>
                                                        <td class="text-end">{{$item->product->final_price * $item->quantity}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            <!--end::Table-->
                                        </div>
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Product List-->
                            </div>
                            <!--end:::Tab pane-->
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade" id="kt_ecommerce_customer_favorites" role="tabpanel">
                                <!--begin::Product List-->
                                <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Bəyənilən məhsullar</h2>
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
                                                    <th class="min-w-175px">Ad</th>
                                                    <th class="min-w-100px text-end">SKU</th>
                                                    <th class="min-w-70px text-end">Qiymət</th>
                                                </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                @foreach($favorites as $favorite)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <!--begin::Thumbnail-->
                                                                <a href="{{route('admin.products.edit', $favorite->product->id)}}" class="symbol symbol-50px">
                                                                    <span class="symbol-label" style="background-image:url('{{$favorite->product->image}}');"></span>
                                                                </a>
                                                                <!--end::Thumbnail-->
                                                                <!--begin::Title-->
                                                                <div class="ms-5">
                                                                    <a href="{{route('admin.products.edit', $favorite->product->id)}}" class="fw-bold text-gray-600 text-hover-primary">{{$favorite->product->name}}</a>
                                                                </div>
                                                                <!--end::Title-->
                                                            </div>
                                                        </td>
                                                        <td class="text-end">{{$favorite->product->sku}}</td>
                                                        <td class="text-end">{{$favorite->product->final_price}} AZN</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            <!--end::Table-->
                                        </div>
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Product List-->
                            </div>
                            <!--end:::Tab pane-->
                        </div>
                        <!--end:::Tab content-->
                    </div>
                    <!--end::Content-->
                </div>
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
