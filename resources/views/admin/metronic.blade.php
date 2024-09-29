<!-- resources/views/admin/metronic.blade.php -->
<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    <!-- Base URL for Laravel project -->
    <base href="{{ url('/') }}"/>

    <title>@yield('title', 'Admin Dashboard')</title>
    <meta charset="utf-8" />
    <meta name="description" content="Admin Panel using Metronic Bootstrap 5 Template" />
    <meta name="keywords" content="admin, bootstrap, laravel, metronic, dashboard" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Admin Panel using Metronic Bootstrap 5 Template" />
    <meta property="og:url" content="{{ url('/') }}" />
    <meta property="og:site_name" content="Admin Panel" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ url('/') }}" />
    <link rel="shortcut icon" href="{{ asset('assets/admin/media/logos/favicon.ico') }}" />

    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->

    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{ asset('assets/admin/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Vendor Stylesheets-->

    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets/admin/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking)
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>
</head>
<!--end::Head-->

<!--begin::Body-->
<body id="kt_app_body"
      data-kt-app-layout="dark-sidebar"
      data-kt-app-header-fixed="true"
      data-kt-app-sidebar-enabled="true"
      data-kt-app-sidebar-fixed="true"
      data-kt-app-sidebar-hoverable="true"
      data-kt-app-sidebar-push-header="true"
      data-kt-app-sidebar-push-toolbar="true"
      data-kt-app-sidebar-push-footer="true"
      data-kt-app-toolbar-enabled="true"
      class="app-default">
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">

<!--begin::Header-->
@include('admin.partials.header')
<!--end::Header-->
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                <!--begin::Sidebar-->
                @include('admin.partials.sidebar')
                <!--end::Sidebar-->
                <!--begin::Main Content-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    @yield('content')
                    @include('admin.partials.footer')
                </div>
                <!--end::Main Content-->
            </div>
        </div>
    </div>
    <!--begin::Global Scripts-->
    <script src="{{ asset('assets/admin/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin/js/scripts.bundle.js') }}"></script>
    @stack('scripts')
    <!--end::Global Scripts-->
</body>
<!--end::Body-->
</html>
