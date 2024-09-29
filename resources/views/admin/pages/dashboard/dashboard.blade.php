@extends('admin.metronic')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Bidding Dashboard</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="../../demo1/dist/index.html" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Dashboards</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <!--begin::Secondary button-->
                    <a href="#" class="btn btn-sm fw-bold btn-secondary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">Manage Bids</a>
                    <!--end::Secondary button-->
                    <!--begin::Primary button-->
                    <a href="#" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_campaign">Start Auction</a>
                    <!--end::Primary button-->
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-xxl">

                <!--begin::Row-->
                <div class="row g-5 g-xl-10">
                    <!--begin::Col-->
                    <div class="col-xl-4">
                        <!--begin::Engage widget 1-->
                        <div class="card h-md-100" dir="ltr">
                            <!--begin::Body-->
                            <div class="card-body d-flex flex-column flex-center">
                                <!--begin::Heading-->
                                <div class="mb-2">
                                    <!--begin::Title-->
                                    <h1 class="fw-semibold text-gray-800 text-center lh-lg">Have your tried
                                        <br />new
                                        <span class="fw-bolder">Invoice Manager?</span></h1>
                                    <!--end::Title-->
                                    <!--begin::Illustration-->
                                    <div class="py-10 text-center">
                                        <img src="assets/media/svg/illustrations/easy/2.svg" class="theme-light-show w-200px" alt="" />
                                        <img src="assets/media/svg/illustrations/easy/2-dark.svg" class="theme-dark-show w-200px" alt="" />
                                    </div>
                                    <!--end::Illustration-->
                                </div>
                                <!--end::Heading-->
                                <!--begin::Links-->
                                <div class="text-center mb-1">
                                    <!--begin::Link-->
                                    <a class="btn btn-sm btn-primary me-2" data-bs-target="#kt_modal_create_app" data-bs-toggle="modal">Try Now</a>
                                    <!--end::Link-->
                                    <!--begin::Link-->
                                    <a class="btn btn-sm btn-light" href="../../demo1/dist/account/settings.html">Learn More</a>
                                    <!--end::Link-->
                                </div>
                                <!--end::Links-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Engage widget 1-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-xl-8">
                        <!--begin::Table Widget 4-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Card header-->
                            <div class="card-header pt-7">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">My Sales in Details</span>
                                    <span class="text-gray-400 mt-1 fw-semibold fs-6">Avg. 57 orders per day</span>
                                </h3>
                                <!--end::Title-->
                                <!--begin::Actions-->
                                <div class="card-toolbar">
                                    <!--begin::Filters-->
                                    <div class="d-flex flex-stack flex-wrap gap-4">
                                        <!--begin::Destination-->
                                        <div class="d-flex align-items-center fw-bold">
                                            <!--begin::Label-->
                                            <div class="text-gray-400 fs-7 me-2">Cateogry</div>
                                            <!--end::Label-->
                                            <!--begin::Select-->
                                            <select class="form-select form-select-transparent text-graY-800 fs-base lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select an option">
                                                <option></option>
                                                <option value="Show All" selected="selected">Show All</option>
                                                <option value="a">Category A</option>
                                                <option value="b">Category A</option>
                                            </select>
                                            <!--end::Select-->
                                        </div>
                                        <!--end::Destination-->
                                        <!--begin::Status-->
                                        <div class="d-flex align-items-center fw-bold">
                                            <!--begin::Label-->
                                            <div class="text-gray-400 fs-7 me-2">Status</div>
                                            <!--end::Label-->
                                            <!--begin::Select-->
                                            <select class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select an option" data-kt-table-widget-4="filter_status">
                                                <option></option>
                                                <option value="Show All" selected="selected">Show All</option>
                                                <option value="Shipped">Shipped</option>
                                                <option value="Confirmed">Confirmed</option>
                                                <option value="Rejected">Rejected</option>
                                                <option value="Pending">Pending</option>
                                            </select>
                                            <!--end::Select-->
                                        </div>
                                        <!--end::Status-->
                                        <!--begin::Search-->
                                        <div class="position-relative my-1">
                                            <i class="ki-duotone ki-magnifier fs-2 position-absolute top-50 translate-middle-y ms-4">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            <input type="text" data-kt-table-widget-4="search" class="form-control w-150px fs-7 ps-12" placeholder="Search" />
                                        </div>
                                        <!--end::Search-->
                                    </div>
                                    <!--begin::Filters-->
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-2">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_4_table">
                                    <!--begin::Table head-->
                                    <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-100px">Order ID</th>
                                        <th class="text-end min-w-100px">Created</th>
                                        <th class="text-end min-w-125px">Customer</th>
                                        <th class="text-end min-w-100px">Total</th>
                                        <th class="text-end min-w-100px">Profit</th>
                                        <th class="text-end min-w-50px">Status</th>
                                        <th class="text-end"></th>
                                    </tr>
                                    <!--end::Table row-->
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody class="fw-bold text-gray-600">
                                    <tr data-kt-table-widget-4="subtable_template" class="d-none">
                                        <td colspan="2">
                                            <div class="d-flex align-items-center gap-3">
                                                <a href="#" class="symbol symbol-50px bg-secondary bg-opacity-25 rounded">
                                                    <img src="" data-kt-src-path="assets/media/stock/ecommerce/" alt="" data-kt-table-widget-4="template_image" />
                                                </a>
                                                <div class="d-flex flex-column text-muted">
                                                    <a href="#" class="text-gray-800 text-hover-primary fw-bold" data-kt-table-widget-4="template_name">Product name</a>
                                                    <div class="fs-7" data-kt-table-widget-4="template_description">Product description</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <div class="text-gray-800 fs-7">Cost</div>
                                            <div class="text-muted fs-7 fw-bold" data-kt-table-widget-4="template_cost">1</div>
                                        </td>
                                        <td class="text-end">
                                            <div class="text-gray-800 fs-7">Qty</div>
                                            <div class="text-muted fs-7 fw-bold" data-kt-table-widget-4="template_qty">1</div>
                                        </td>
                                        <td class="text-end">
                                            <div class="text-gray-800 fs-7">Total</div>
                                            <div class="text-muted fs-7 fw-bold" data-kt-table-widget-4="template_total">name</div>
                                        </td>
                                        <td class="text-end">
                                            <div class="text-gray-800 fs-7 me-3">On hand</div>
                                            <div class="text-muted fs-7 fw-bold" data-kt-table-widget-4="template_stock">32</div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary">#XGY-346</a>
                                        </td>
                                        <td class="text-end">7 min ago</td>
                                        <td class="text-end">
                                            <a href="#" class="text-gray-600 text-hover-primary">Albert Flores</a>
                                        </td>
                                        <td class="text-end">$630.00</td>
                                        <td class="text-end">
                                            <span class="text-gray-800 fw-bolder">$86.70</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge py-3 px-4 fs-7 badge-light-warning">Pending</span>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px" data-kt-table-widget-4="expand_row">
                                                <i class="ki-duotone ki-plus fs-4 m-0 toggle-off"></i>
                                                <i class="ki-duotone ki-minus fs-4 m-0 toggle-on"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary">#YHD-047</a>
                                        </td>
                                        <td class="text-end">52 min ago</td>
                                        <td class="text-end">
                                            <a href="#" class="text-gray-600 text-hover-primary">Jenny Wilson</a>
                                        </td>
                                        <td class="text-end">$25.00</td>
                                        <td class="text-end">
                                            <span class="text-gray-800 fw-bolder">$4.20</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge py-3 px-4 fs-7 badge-light-primary">Confirmed</span>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px" data-kt-table-widget-4="expand_row">
                                                <i class="ki-duotone ki-plus fs-4 m-0 toggle-off"></i>
                                                <i class="ki-duotone ki-minus fs-4 m-0 toggle-on"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary">#SRR-678</a>
                                        </td>
                                        <td class="text-end">1 hour ago</td>
                                        <td class="text-end">
                                            <a href="#" class="text-gray-600 text-hover-primary">Robert Fox</a>
                                        </td>
                                        <td class="text-end">$1,630.00</td>
                                        <td class="text-end">
                                            <span class="text-gray-800 fw-bolder">$203.90</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge py-3 px-4 fs-7 badge-light-warning">Pending</span>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px" data-kt-table-widget-4="expand_row">
                                                <i class="ki-duotone ki-plus fs-4 m-0 toggle-off"></i>
                                                <i class="ki-duotone ki-minus fs-4 m-0 toggle-on"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary">#PXF-534</a>
                                        </td>
                                        <td class="text-end">3 hour ago</td>
                                        <td class="text-end">
                                            <a href="#" class="text-gray-600 text-hover-primary">Cody Fisher</a>
                                        </td>
                                        <td class="text-end">$119.00</td>
                                        <td class="text-end">
                                            <span class="text-gray-800 fw-bolder">$12.00</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge py-3 px-4 fs-7 badge-light-success">Shipped</span>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px" data-kt-table-widget-4="expand_row">
                                                <i class="ki-duotone ki-plus fs-4 m-0 toggle-off"></i>
                                                <i class="ki-duotone ki-minus fs-4 m-0 toggle-on"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary">#XGD-249</a>
                                        </td>
                                        <td class="text-end">2 day ago</td>
                                        <td class="text-end">
                                            <a href="#" class="text-gray-600 text-hover-primary">Arlene McCoy</a>
                                        </td>
                                        <td class="text-end">$660.00</td>
                                        <td class="text-end">
                                            <span class="text-gray-800 fw-bolder">$52.26</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge py-3 px-4 fs-7 badge-light-success">Shipped</span>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px" data-kt-table-widget-4="expand_row">
                                                <i class="ki-duotone ki-plus fs-4 m-0 toggle-off"></i>
                                                <i class="ki-duotone ki-minus fs-4 m-0 toggle-on"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary">#SKP-035</a>
                                        </td>
                                        <td class="text-end">2 day ago</td>
                                        <td class="text-end">
                                            <a href="#" class="text-gray-600 text-hover-primary">Eleanor Pena</a>
                                        </td>
                                        <td class="text-end">$290.00</td>
                                        <td class="text-end">
                                            <span class="text-gray-800 fw-bolder">$29.00</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge py-3 px-4 fs-7 badge-light-danger">Rejected</span>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px" data-kt-table-widget-4="expand_row">
                                                <i class="ki-duotone ki-plus fs-4 m-0 toggle-off"></i>
                                                <i class="ki-duotone ki-minus fs-4 m-0 toggle-on"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary">#SKP-567</a>
                                        </td>
                                        <td class="text-end">7 min ago</td>
                                        <td class="text-end">
                                            <a href="#" class="text-gray-600 text-hover-primary">Dan Wilson</a>
                                        </td>
                                        <td class="text-end">$590.00</td>
                                        <td class="text-end">
                                            <span class="text-gray-800 fw-bolder">$50.00</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge py-3 px-4 fs-7 badge-light-success">Shipped</span>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px" data-kt-table-widget-4="expand_row">
                                                <i class="ki-duotone ki-plus fs-4 m-0 toggle-off"></i>
                                                <i class="ki-duotone ki-minus fs-4 m-0 toggle-on"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Table Widget 4-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
@endsection
@push('scripts')
    <!-- Local Scripts -->
    <script src="{{ asset('assets/admin/plugins/custom/fslightbox/fslightbox.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/custom/typedjs/typedjs.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/custom/datatables/datatables.bundle.js') }}"></script>

    <!-- External Scripts (amCharts) -->
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    <!-- Custom Scripts -->
    <script src="{{ asset('assets/admin/js/widgets.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/widgets.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/utilities/modals/create-project/type.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/utilities/modals/create-project/budget.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/utilities/modals/create-project/settings.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/utilities/modals/create-project/team.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/utilities/modals/create-project/targets.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/utilities/modals/create-project/files.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/utilities/modals/create-project/complete.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/utilities/modals/create-project/main.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/utilities/modals/create-campaign.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/utilities/modals/bidding.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/utilities/modals/users-search.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/utilities/modals/create-app.js') }}"></script>
@endpush
