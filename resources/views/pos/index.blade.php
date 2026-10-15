@extends('pos.layouts.master')

@section('content')
    <div class="enterprise-dashboard-shell">
        <section class="dashboard-hero">
            <span class="dashboard-hero__label">POS overview</span>
            <h1 class="dashboard-hero__title">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="dashboard-hero__text">Keep a close view of current sales, wallet credit, and operational performance for your POS network.</p>
        </section>

        <section class="pos-dashboard-stats" aria-label="POS sales and wallet statistics">
            <div class="pos-dashboard-stat-card">
                    <small>CMS</small>
                    <strong>&#8377;{{ $msales ?? 0 }}</strong>
                    <span class="pos-dashboard-stat-detail">
                        Current Month Sales
                        <span class="pos-dashboard-stat-trend pos-dashboard-stat-trend-up">
                            <svg aria-hidden="true" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>
                            <b>2%</b>
                        </span>
                    </span>
                </div>
            <div class="pos-dashboard-stat-card">
                    <small>CDS</small>
                    <strong>&#8377;{{ $dsales ?? 0 }}</strong>
                    <span class="pos-dashboard-stat-detail">
                        Current Day Sales
                        <span class="pos-dashboard-stat-trend pos-dashboard-stat-trend-down">
                            <svg aria-hidden="true" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            <b>2%</b>
                        </span>
                    </span>
                </div>
            <div class="pos-dashboard-stat-card">
                    <small>&#8377;RBW</small>
                    <strong>&#8377;{{ $currentMonthWalletCredit ?? 0 }}</strong>
                    <span class="pos-dashboard-stat-detail">
                        Received By Wallet
                        <span class="pos-dashboard-stat-trend pos-dashboard-stat-trend-up">
                            <svg aria-hidden="true" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>
                            <b>4%</b>
                        </span>
                    </span>
                </div>
        </section>

        <section class="row g-4 pos-dashboard-charts" aria-label="POS statistics charts">
            <div class="col-12 col-xl-8">
                <article class="card border-0 shadow-sm h-100 pos-dashboard-chart-card">
                    <div class="card-body">
                        <div class="pos-dashboard-chart-heading">
                            <div>
                                <span class="pos-dashboard-chart-eyebrow">Sales activity</span>
                                <h2>Daily sales trend</h2>
                            </div>
                            <span class="pos-dashboard-chart-period">Last 7 days</span>
                        </div>
                        @if (count($salesTrendLabels ?? []))
                            <div id="pos-sales-trend" class="pos-sales-trend-chart" role="img" aria-label="Daily POS sales over the last seven days"></div>
                        @else
                            <p class="pos-dashboard-chart-empty">Sales trend will appear when POS data is available.</p>
                        @endif
                    </div>
                </article>
            </div>
            <div class="col-12 col-xl-4">
                <article class="card border-0 shadow-sm h-100 pos-dashboard-chart-card">
                    <div class="card-body">
                        <div class="pos-dashboard-chart-heading">
                            <div>
                                <span class="pos-dashboard-chart-eyebrow">Month to date</span>
                                <h2>Sales &amp; wallet credit</h2>
                            </div>
                        </div>
                        <div id="pos-sales-summary" class="pos-sales-summary-chart" role="img" aria-label="Current month sales compared with wallet credit"></div>
                        <div class="pos-dashboard-chart-legend">
                            <span><i class="pos-chart-legend-sales"></i> CMS</span>
                            <span><i class="pos-chart-legend-wallet"></i> Received by wallet</span>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        @include('shared.pos-location-map', [
            'mapId' => 'pos-panel-location-map',
            'mapTitle' => 'Your POS location',
            'mapDescription' => 'View your business location and open directions.',
            'locations' => $posLocation ? [$posLocation] : [],
        ])
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Chartist === 'undefined') return;

            var rupees = function (value) {
                return '\u20b9' + Number(value || 0).toLocaleString('en-IN');
            };

            var salesTrend = document.getElementById('pos-sales-trend');
            if (salesTrend) {
                new Chartist.Line('#pos-sales-trend', {
                    labels: @json($salesTrendLabels ?? []),
                    series: [@json($salesTrendValues ?? [])]
                }, {
                    low: 0,
                    fullWidth: true,
                    showArea: true,
                    chartPadding: { top: 16, right: 18, bottom: 4, left: 4 },
                    axisX: { showGrid: false },
                    axisY: {
                        offset: 58,
                        labelInterpolationFnc: function (value) { return rupees(value); }
                    }
                });
            }

            var salesSummary = document.getElementById('pos-sales-summary');
            if (salesSummary) {
                new Chartist.Bar('#pos-sales-summary', {
                    labels: ['CMS', 'RBW'],
                    series: [
                        [@json($salesSummaryValues[0] ?? 0), 0],
                        [0, @json($salesSummaryValues[2] ?? 0)]
                    ]
                }, {
                    low: 0,
                    seriesBarDistance: 28,
                    chartPadding: { top: 12, right: 12, bottom: 0, left: 0 },
                    axisX: { showGrid: false },
                    axisY: {
                        offset: 58,
                        labelInterpolationFnc: function (value) { return rupees(value); }
                    }
                });
            }
        });
    </script>
@endpush
