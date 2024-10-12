@extends('admin.metronic')

@section('title', 'Dashboard')

@section('content')

    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Dashboard</h1>
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="row">
                    <!-- Call Requests -->

                    <div class="col-md-6">
                        <a href="{{ route('admin.orders') }}">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Daily Orders</h5>
                                    <canvas id="dailyOrderChart"></canvas>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.orders') }}">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Weekly Orders</h5>
                                    <canvas id="weeklyOrderChart"></canvas>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.orders') }}">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Monthly Orders</h5>
                                    <canvas id="monthlyOrderChart"></canvas>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Products & Categories -->
                <div class="row mt-5">

                    <div class="col-md-6">
                        <a href="{{ route('admin.products.index') }}">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Available Products</h5>
                                    <canvas id="availableProductsChart"></canvas>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6">
                        <a href="{{ route('admin.set_products') }}">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Available Sets</h5>
                                    <p class="card-text display-4">{{ $availableSets }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Orders & Users -->

                <a href="{{ route('admin.orders') }}">
                    <div class="row mt-5">
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Users with Orders</h5>
                                    <p class="card-text display-4">{{ $usersWithOrders }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Verified Users & Daily Verified Users -->

                <a href="{{ route('admin.customers') }}">
                    <div class="row mt-5">
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Verified Users</h5>
                                    <p class="card-text display-4">{{ $verifiedUsers }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Daily Verified Users</h5>
                                    <p class="card-text display-4">{{ $dailyVerifiedUsers }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Available Products Chart
        var ctxProducts = document.getElementById('availableProductsChart').getContext('2d');
        var availableProductsChart = new Chart(ctxProducts, {
            type: 'bar',
            data: {
                labels: @json($categoryProductCounts->pluck('name')),
                datasets: [{
                    label: 'Number of Products',
                    data: @json($categoryProductCounts->pluck('products_count')),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Orders Chart
        var ctxDailyOrders = document.getElementById('dailyOrderChart').getContext('2d');
        var dailyOrderChart = new Chart(ctxDailyOrders, {
            type: 'line',
            data: {
                labels: ['0:00', '1:00', '2:00', '3:00', '4:00', '5:00', '6:00', '7:00', '8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'], // 24 hours of the day
                datasets: [{
                    label: 'Orders',
                    data: @json(array_values($dailyOrdersFormatted)), // Daily data, formatted for 24 hours
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var ctxWeeklyOrders = document.getElementById('weeklyOrderChart').getContext('2d');
        var weeklyOrderChart = new Chart(ctxWeeklyOrders, {
            type: 'line',
            data: {
                labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'], // 7 days of the week
                datasets: [{
                    label: 'Orders',
                    data: @json(array_values($weeklyOrdersFormatted)), // Weekly data, formatted for 7 days
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var ctxMonthlyOrders = document.getElementById('monthlyOrderChart').getContext('2d');
        var monthlyOrderChart = new Chart(ctxMonthlyOrders, {
            type: 'line',
            data: {
                labels: Array.from({length: {{ \Carbon\Carbon::now()->format('t') }}}, (_, i) => (i + 1).toString()), // Labels for each day of the current month
                datasets: [{
                    label: 'Orders',
                    data: @json(array_values($monthlyOrdersFormatted)), // Monthly data, formatted for the number of days in the month
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


    </script>
@endpush
