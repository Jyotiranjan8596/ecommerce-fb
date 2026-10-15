@extends('layouts.master')

@section('content')
    <div class="enterprise-dashboard-shell">
        <section class="dashboard-hero">
            <span class="dashboard-hero__label">Operations overview</span>
            <h1 class="dashboard-hero__title">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="dashboard-hero__text">Track registrations, sales, wallet movement, and daily performance from a single enterprise dashboard built for operational clarity.</p>
            <div class="dashboard-hero__stats">
                <a class="dashboard-hero__stat dashboard-hero__stat-link" href="{{ route('admin.users.index') }}" aria-label="Open user list">
                    <small>Total users</small>
                    <strong>{{ $totaluser }}</strong>
                </a>
                <a class="dashboard-hero__stat dashboard-hero__stat-link" href="{{ route('admin.pos_system.index') }}" aria-label="Open POS list">
                    <small>Total POS</small>
                    <strong>{{ $totalpos }}</strong>
                </a>
                <a class="dashboard-hero__stat dashboard-hero__stat-link" href="{{ route('admin.dsr', ['start_date' => today()->toDateString(), 'end_date' => today()->toDateString()]) }}" aria-label="Open today's sales report">
                    <small>Today's sales</small>
                    <strong>&#8377;{{ number_format($todaySales, 2) }}</strong>
                </a>
            </div>
        </section>
        <div class="row g-3">
        {{-- <div class="col-12 mb-4">
        <div class="card bg-yellow-100 border-0 shadow">
            <div class="card-header d-sm-flex flex-row align-items-center flex-0">
                <div class="d-block mb-3 mb-sm-0">
                    <div class="fs-5 fw-normal mb-2">Sales Value</div>
                    <h2 class="fs-3 fw-extrabold">₹ 10,567</h2>
                    <div class="small mt-2">
                        <span class="fw-normal me-2">Yesterday</span>
                        <span class="fas fa-angle-up text-success"></span>
                        <span class="text-success fw-bold">10.57%</span>
                    </div>
                </div>
                <div class="d-flex ms-auto">
                    <a href="#" class="btn btn-secondary text-dark btn-sm me-2">Month</a>
                    <a href="#" class="btn btn-dark btn-sm me-3">Week</a>
                </div>
            </div>
            <div class="card-body p-2">
                <div class="ct-chart-sales-value ct-double-octave ct-series-g"></div>
            </div>
        </div>
    </div> --}}

        @unless (auth()->user()->hasRole('Admin'))
            <div class="col-12 col-sm-6 col-xl-4 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row d-block d-xl-flex align-items-center">
                            <div
                                class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                <div class="icon-shape icon-shape-primary rounded me-4 me-sm-0">
                                    <svg style="width: 35px; height: 35px;" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-book-open align-middle text-primary">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="h5">Total Read</h2>
                                    <h3 class="fw-extrabold mb-1">{{ $count['posts_read'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Total Read</h2>
                                    <h3 class="fw-extrabold mb-2">{{ $count['posts_read'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endunless

        @unless (auth()->user()->hasRole('Admin'))
            <div class="col-12 col-sm-6 col-xl-4 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row d-block d-xl-flex align-items-center">
                            <div
                                class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                <div class="icon-shape icon-shape-tertiary rounded me-4 me-sm-0">
                                    <svg style="width: 35px; height: 35px;" xmlns="http://www.w3.org/2000/svg"
                                        width="34" height="34" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="feather feather-message-square align-middle text-info">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="fw-extrabold h5">Total Comments</h2>
                                    <h3 class="mb-1">{{ $count['comments'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Total Comments</h2>
                                    <h3 class="fw-extrabold mb-2">{{ $count['comments'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endunless
        <div class="row mt-4">
            <div class="col-md-12 col-lg-4 mb-4">
                <div class="card shadow border-0">
                    <div class="card-header">User Registrations · {{ $year }}</div>
                    <div class="card-body">
                        <canvas id="chart1" style="min-height: 300px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-4 mb-4">
                <div class="card shadow border-0">
                    <div class="card-header">Monthly Sales · {{ $year }}</div>
                    <div class="card-body">
                        <canvas id="chart2" style="min-height: 300px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-4 mb-4">
                <div class="card shadow border-0">
                    <div class="card-header">Rewards Redeemed · {{ $year }}</div>
                    <div class="card-body">
                        <canvas id="chart3" style="min-height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartNumber = new Intl.NumberFormat('en-IN');
        const chartCurrency = new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: 'INR',
            maximumFractionDigits: 0
        });

        new Chart(document.getElementById('chart1'), {
            type: 'line',
            data: {
                labels: {!! json_encode($userChartLabels) !!},
                datasets: [{
                        label: 'New users',
                        data: {!! json_encode($userChartData) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.16)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        fill: true,
                        tension: 0.35
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, callback: value => chartNumber.format(value) }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: context => `${context.dataset.label}: ${chartNumber.format(context.parsed.y)}` } }
                }
            }
        });

        new Chart(document.getElementById('chart2'), {
            type: 'line',
            data: {
                labels: {!! json_encode($salesChartLabels) !!},
                datasets: [{
                    label: 'Sales',
                    data: {!! json_encode($salesChartData) !!},
                    borderColor: 'rgba(16, 122, 87, 1)',
                    backgroundColor: 'rgba(16, 122, 87, 0.14)',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    fill: true,
                    tension: 0.35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: context => chartCurrency.format(context.parsed.y) } }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: value => chartCurrency.format(value) }
                    }
                }
            }
        });

        new Chart(document.getElementById('chart3'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($salesChartLabels) !!},
                datasets: [{
                    label: 'Rewards redeemed',
                    data: {!! json_encode($rewardChartData) !!},
                    backgroundColor: 'rgba(245, 158, 11, 0.72)',
                    borderColor: 'rgba(217, 119, 6, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: context => chartCurrency.format(context.parsed.y) } }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: value => chartCurrency.format(value) }
                    }
                }
            }
        });
    </script>
@endsection
