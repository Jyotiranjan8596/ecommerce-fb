@extends('layouts.master')

@section('content')
    <style>
        .admin-dsr-page { max-width: 1500px; margin: 0 auto; padding: 1.25rem clamp(.75rem, 2vw, 1.5rem) 2.5rem; color: #172b4d; }
        .admin-dsr-hero { position: relative; overflow: hidden; margin-bottom: 1.25rem; padding: clamp(1.4rem, 3vw, 2.2rem); border-radius: 24px; color: #fff; background: linear-gradient(120deg, #123b72, #1769aa 62%, #2387bf); box-shadow: 0 16px 34px rgba(18, 59, 114, .18); }
        .admin-dsr-hero::after { position: absolute; right: -3rem; bottom: -8rem; width: 19rem; height: 19rem; border: 1px solid rgba(255,255,255,.12); border-radius: 50%; content: ""; box-shadow: 0 0 0 2rem rgba(255,255,255,.035), 0 0 0 4rem rgba(255,255,255,.025); }
        .admin-dsr-hero > * { position: relative; z-index: 1; }
        .admin-dsr-eyebrow { color: #c8e6ff; font-size: .72rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
        .admin-dsr-hero h1 { margin: .35rem 0 .45rem; color: #fff; font-size: clamp(1.6rem, 3vw, 2.25rem); font-weight: 800; }
        .admin-dsr-hero p { max-width: 650px; margin: 0; color: rgba(255,255,255,.82); }
        .admin-dsr-stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; margin-bottom: 1.25rem; }
        .admin-dsr-stat, .admin-dsr-panel { min-width: 0; border: 1px solid #e6edf5; border-radius: 18px; background: #fff; box-shadow: 0 8px 24px rgba(24,54,91,.06); }
        .admin-dsr-stat { padding: 1.1rem 1.25rem; }
        .admin-dsr-stat span { display: block; color: #718198; font-size: .78rem; font-weight: 700; }
        .admin-dsr-stat strong { display: block; margin-top: .35rem; color: #153e75; font-size: clamp(1.35rem, 2.5vw, 1.85rem); font-weight: 800; overflow-wrap: anywhere; }
        .admin-dsr-panel { margin-bottom: 1.25rem; padding: clamp(1rem, 2vw, 1.4rem); }
        .admin-dsr-panel-heading { margin-bottom: 1rem; }
        .admin-dsr-panel-heading h2 { margin: 0; color: #19385e; font-size: 1.05rem; font-weight: 800; }
        .admin-dsr-panel-heading p { margin: .25rem 0 0; color: #718198; font-size: .86rem; }
        .admin-dsr-controls { display: flex; flex-wrap: wrap; gap: .75rem; align-items: end; }
        .admin-dsr-search { flex: 1 1 220px; }
        .admin-dsr-date-field { flex: 1 1 145px; }
        .admin-dsr-controls label, .admin-dsr-upload label { display: block; margin-bottom: .35rem; color: #52657e; font-size: .76rem; font-weight: 750; }
        .admin-dsr-controls .form-control, .admin-dsr-upload .form-control { min-height: 42px; border-color: #dce5ef; border-radius: 10px; }
        .admin-dsr-controls .btn, .admin-dsr-actions .btn { min-height: 42px; border-radius: 10px; font-weight: 700; }
        .admin-dsr-icon-button { display: inline-grid !important; width: 46px; min-width: 46px; height: 46px; padding: 0 !important; place-items: center; border: 0 !important; border-radius: 14px !important; color: #fff !important; box-shadow: 0 5px 12px rgba(29,57,91,.2); transition: transform .18s ease, box-shadow .18s ease, filter .18s ease; }
        .admin-dsr-icon-button:hover { color: #fff !important; transform: translateY(-2px); filter: saturate(1.12); box-shadow: 0 8px 16px rgba(29,57,91,.25); }
        .admin-dsr-icon-button:focus-visible { outline: 3px solid #94c5ff; outline-offset: 3px; }
        .admin-dsr-icon-button svg { display: block; width: 23px; height: 23px; }
        .admin-dsr-icon-button--filter { background: linear-gradient(145deg,#3478f6,#1553c7) !important; }
        .admin-dsr-icon-button--reset { background: linear-gradient(145deg,#8292a8,#596b82) !important; }
        .admin-dsr-icon-button--upload { background: linear-gradient(145deg,#16b89a,#07846e) !important; }
        .admin-dsr-icon-button--export { background: linear-gradient(145deg,#9b61ed,#6b36c5) !important; }
        .admin-dsr-actions { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: end; gap: 1rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #edf1f6; }
        .admin-dsr-upload { display: flex; align-items: end; gap: .6rem; flex: 1 1 420px; }
        .admin-dsr-upload > div { flex: 1; min-width: 180px; }
        .admin-dsr-export { flex: 0 0 auto; }
        .admin-dsr-table-wrap { overflow: auto; border: 1px solid #e6edf5; border-radius: 16px; background: #fff; box-shadow: 0 8px 24px rgba(24,54,91,.06); }
        .admin-dsr-table { min-width: 760px; margin: 0; }
        .admin-dsr-table thead th { padding: .9rem 1rem; border: 0; background: #f3f7fb; color: #60738d; font-size: .69rem; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; white-space: nowrap; }
        .admin-dsr-table tbody td { padding: .9rem 1rem; border-color: #edf1f6; color: #344761; vertical-align: middle; }
        .admin-dsr-table tbody tr:hover { background: #f8fbff; }
        .admin-dsr-amount { color: #153e75 !important; font-weight: 800; }
        .admin-dsr-pos { color: #183f73; font-weight: 750; }
        .admin-dsr-detail { border-radius: 9px; font-weight: 700; white-space: nowrap; }
        .admin-dsr-empty { padding: 2.5rem 1rem !important; color: #728198 !important; text-align: center; }
        .admin-dsr-pagination { display: flex; justify-content: center; margin-top: 1rem; }
        @media (max-width: 600px) {
            .admin-dsr-page { padding: .75rem .5rem 1.5rem; }
            .admin-dsr-stats { grid-template-columns: 1fr; gap: .65rem; }
            .admin-dsr-stat { padding: .9rem 1rem; }
            .admin-dsr-controls { align-items: stretch; flex-direction: column; }
            .admin-dsr-search, .admin-dsr-date-field { flex-basis: auto; width: 100%; }
            .admin-dsr-controls .admin-dsr-icon-button { align-self: flex-start; }
            .admin-dsr-actions, .admin-dsr-upload { align-items: stretch; flex-direction: column; }
            .admin-dsr-upload > div { min-width: 0; }
            .admin-dsr-export { width: 100%; }
            .admin-dsr-icon-button { width: 46px !important; min-width: 46px !important; }
            .admin-dsr-table-wrap { overflow: visible; border: 0; background: transparent; box-shadow: none; }
            .admin-dsr-table { display: block; min-width: 0; }
            .admin-dsr-table thead { display: none; }
            .admin-dsr-table tbody { display: grid; gap: .75rem; }
            .admin-dsr-table tbody tr { display: block; overflow: hidden; border: 1px solid #e6edf5; border-radius: 14px; background: #fff; box-shadow: 0 6px 18px rgba(24,54,91,.06); }
            .admin-dsr-table tbody td { display: flex; justify-content: space-between; gap: 1rem; padding: .7rem .85rem; border-color: #edf1f6; text-align: right; white-space: normal; }
            .admin-dsr-table tbody td::before { flex: 0 0 auto; color: #718198; content: attr(data-label); font-size: .7rem; font-weight: 800; text-align: left; text-transform: uppercase; }
            .admin-dsr-table tbody td:last-child { border-bottom: 0; }
            .admin-dsr-table .admin-dsr-empty { display: block; text-align: center; }
        }
    </style>
    <div class="admin-dsr-page">
        <header class="admin-dsr-hero">
            <span class="admin-dsr-eyebrow">Admin reports</span>
            <h1>Daily Sales Report</h1>
            <p>Review POS billing totals and transaction activity. Filter by date or search by POS name and phone number.</p>
        </header>

        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <section class="admin-dsr-stats" aria-label="Sales report summary">
            <article class="admin-dsr-stat"><span>Total billing</span><strong>&#8377;{{ number_format((float) $totalBillingAmount, 2) }}</strong></article>
            <article class="admin-dsr-stat"><span>Total transactions</span><strong>{{ number_format((int) $totalTransactions) }}</strong></article>
            <article class="admin-dsr-stat"><span>Active POS locations</span><strong>{{ number_format((int) $totalPos) }}</strong></article>
        </section>

        <section class="admin-dsr-panel" aria-labelledby="admin-dsr-controls-title">
            <div class="admin-dsr-panel-heading">
                <h2 id="admin-dsr-controls-title">Filter and manage report</h2>
                <p>Leave both dates empty to view the default report.</p>
            </div>
            <form method="GET" action="{{ route('admin.dsr') }}" class="admin-dsr-controls">
                <div class="admin-dsr-search">
                    <label for="dsr-search">Search POS</label>
                    <input type="search" class="form-control" id="dsr-search" name="search"
                        value="{{ request('search') }}" placeholder="POS name or phone number">
                </div>
                <div class="admin-dsr-date-field">
                    <label for="start_date">From date</label>
                    <input type="date" class="form-control" name="start_date" id="start_date"
                        value="{{ request('start_date') }}" max="{{ today()->toDateString() }}">
                </div>
                <div class="admin-dsr-date-field">
                    <label for="end_date">To date</label>
                    <input type="date" class="form-control" name="end_date" id="end_date"
                        value="{{ request('end_date') }}" max="{{ today()->toDateString() }}">
                </div>
                <button class="btn admin-dsr-icon-button admin-dsr-icon-button--filter" type="submit"
                    title="Search POS" aria-label="Search POS">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="10.8" cy="10.8" r="6.3" stroke="currentColor" stroke-width="2"/>
                        <path d="m15.5 15.5 4.2 4.2" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                    </svg>
                </button>
                <a class="btn admin-dsr-icon-button admin-dsr-icon-button--reset"
                    href="{{ route('admin.dsr') }}" title="Reset filters" aria-label="Reset filters">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4.5 11a7.5 7.5 0 1 1 .8 4.5" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
                        <path d="M4.5 5.5V11H10" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </form>

            <div class="admin-dsr-actions">
                <form class="admin-dsr-upload" action="{{ route('admin.dsr.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="dsr-csv">Import DSR data (CSV)</label>
                        <input type="file" class="form-control" id="dsr-csv" name="file" accept=".csv,text/csv" required>
                    </div>
                    <button class="btn admin-dsr-icon-button admin-dsr-icon-button--upload" type="submit"
                        title="Upload CSV" aria-label="Upload CSV">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 15V4m0 0L8 8m4-4 4 4M5 14v4.5A1.5 1.5 0 0 0 6.5 20h11a1.5 1.5 0 0 0 1.5-1.5V14" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </form>
                <form class="admin-dsr-export" method="GET" action="{{ route('admin.dsr.export') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                    <button class="btn admin-dsr-icon-button admin-dsr-icon-button--export" type="submit"
                        title="Export report" aria-label="Export report">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M7 3.5h7l4.5 4.6v11.4a1.5 1.5 0 0 1-1.5 1.5H7A1.5 1.5 0 0 1 5.5 19.5v-14A2 2 0 0 1 7.5 3.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            <path d="M14 3.8v4.7h4.2M12 10v6m-2.5-2.5L12 16l2.5-2.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </form>
            </div>
        </section>

        <div class="admin-dsr-panel-heading d-flex flex-wrap align-items-end justify-content-between gap-2">
            <div><h2>Transactions by POS and date</h2><p>{{ $wallets->count() }} rows on this page</p></div>
        </div>
        <div class="admin-dsr-table-wrap">
            <table class="table table-hover align-middle admin-dsr-table" id="dsr-tbl">
                <thead><tr><th scope="col">No.</th><th scope="col">Transaction date</th><th scope="col">POS ID</th><th scope="col">Billing amount</th><th scope="col">Transactions</th><th scope="col">TC</th><th scope="col">Details</th></tr></thead>
                <tbody>
                    @forelse ($wallets as $key => $data)
                        <tr>
                            <td data-label="No.">{{ $wallets->firstItem() + $key }}</td>
                            <td data-label="Transaction date"><time datetime="{{ \Carbon\Carbon::parse($data->transaction_date)->toDateString() }}">{{ \Carbon\Carbon::parse($data->transaction_date)->format('d M Y') }}</time></td>
                            <td data-label="POS ID" class="admin-dsr-pos">{{ $data->getPos->user_id ?? $data->pos_id }}</td>
                            <td data-label="Billing amount" class="admin-dsr-amount">&#8377;{{ number_format((float) ($data->total_billing_amount ?? 0), 2) }}</td>
                            <td data-label="Transactions">{{ number_format((int) $data->total_transactions) }}</td>
                            <td data-label="TC">&#8377;{{ number_format((float) ($data->total_transaction_charges ?? 0), 2) }}</td>
                            <td data-label="Details">
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('admin.transaction.details', ['id' => encrypt($data->pos_id), 'start_date' => request('start_date'), 'end_date' => request('end_date'), 'transaction_date' => $data->transaction_date]) }}"
                                        class="btn btn-sm btn-primary admin-dsr-detail">
                                        <i class="fa fa-eye me-1" aria-hidden="true"></i> View details
                                    </a>
                                    <a href="{{ route('admin.transaction.details.export', ['id' => encrypt($data->pos_id), 'transaction_date' => \Carbon\Carbon::parse($data->transaction_date)->toDateString()]) }}"
                                        class="btn btn-sm btn-outline-success admin-dsr-detail" title="Export this POS date"
                                        aria-label="Export transactions for this POS and date">
                                        <i class="fa fa-download" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="admin-dsr-empty">No sales records match these filters. Try a different date range or search.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="admin-dsr-pagination">{{ $wallets->links() }}</div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');
            if (!startDate || !endDate) return;

            const syncDateRange = changedField => {
                startDate.max = endDate.value || startDate.getAttribute('max');
                endDate.min = startDate.value || '';
                if (changedField === startDate && endDate.value && endDate.value < startDate.value) {
                    endDate.value = startDate.value;
                }
            };

            startDate.addEventListener('change', () => syncDateRange(startDate));
            endDate.addEventListener('change', () => syncDateRange(endDate));
            syncDateRange();
        });
    </script>
@endsection
