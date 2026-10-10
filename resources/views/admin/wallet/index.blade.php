@extends('layouts.master')

@section('content')
    <style>
        .admin-payback-report { max-width: 1500px; margin: 1.2rem auto; padding: 0 1rem 2rem; }
        .payback-report-hero { display:flex; align-items:center; justify-content:space-between; gap:1.25rem; margin-bottom:1.15rem; padding:1.4rem 1.6rem; border:1px solid #dbe8f5; border-radius:18px; background:linear-gradient(120deg,#123b72,#1769aa 68%,#2783bd); color:#fff; box-shadow:0 12px 28px rgba(18,59,114,.13); }
        .payback-report-hero__eyebrow { display:inline-flex; align-items:center; gap:.4rem; color:#bfe0ff; font-size:.72rem; font-weight:800; letter-spacing:.12em; text-transform:uppercase; }
        .payback-report-hero h1 { margin:.35rem 0 .2rem; color:#fff; font-size:clamp(1.45rem,2.5vw,2rem); font-weight:850; }
        .payback-report-hero p { margin:0; color:rgba(255,255,255,.78); font-size:.9rem; }
        .payback-report-upload { display:inline-flex; align-items:center; justify-content:center; gap:.45rem; min-height:42px; padding:.65rem 1rem; border:1px solid rgba(255,255,255,.8); border-radius:10px; background:#fff; color:#174a79; font-size:.86rem; font-weight:750; text-decoration:none; white-space:nowrap; transition:transform .16s ease,box-shadow .16s ease; }
        .payback-report-upload:hover { color:#123b72; transform:translateY(-1px); box-shadow:0 6px 15px rgba(4,30,58,.2); }
        .payback-report-card { margin-bottom:1rem; overflow:hidden; border:1px solid #e1e9f2; border-radius:16px; background:#fff; box-shadow:0 8px 22px rgba(19,48,79,.055); }
        .payback-report-card__heading { display:flex; align-items:center; gap:.75rem; padding:1rem 1.2rem; border-bottom:1px solid #edf2f7; }
        .payback-report-card__icon { display:grid; width:38px; height:38px; flex:0 0 38px; place-items:center; border-radius:12px; background:#eaf3ff; color:#1769aa; font-size:1.05rem; }
        .payback-report-card__heading h2 { margin:0; color:#1d334a; font-size:1rem; font-weight:800; }
        .payback-report-card__heading p { margin:.15rem 0 0; color:#748397; font-size:.78rem; }
        .payback-report-filters { display:flex; align-items:flex-end; gap:.75rem; padding:1rem 1.2rem 1.2rem; }
        .payback-report-filters form { flex:1 1 auto; min-width:0; }
        .payback-report-filters .payback-report-export-form { flex:0 0 auto; width:auto!important; }
        .payback-report-filters__fields { display:grid; grid-template-columns:repeat(4,minmax(115px,1fr)) auto; align-items:end; gap:.65rem; }
        .payback-report-filters .form-label { color:#52657a; font-size:.75rem; font-weight:700; }
        .payback-report-filters .form-select { min-height:40px; border-color:#d9e3ed; border-radius:9px; color:#30465d; font-size:.84rem; }
        .payback-report-filters .form-select:focus { border-color:#5ba0dc; box-shadow:0 0 0 .2rem rgba(23,105,170,.12); }
        .payback-report-filter-button,.payback-report-export { display:inline-flex; min-height:40px; align-items:center; justify-content:center; gap:.4rem; border-radius:9px; font-size:.82rem; font-weight:750; white-space:nowrap; }
        .payback-report-filter-button { padding:.55rem .9rem; border:1px solid #1769aa; background:#1769aa; color:#fff; }
        .payback-report-filter-button:hover { border-color:#123b72; background:#123b72; color:#fff; }
        .payback-report-export { min-width:130px; padding:.55rem .85rem; border:1px solid #1769aa; background:#fff; color:#1769aa; }
        .payback-report-export:hover { background:#eff6ff; color:#123b72; }
        .payback-report-table-wrap { max-height: min(62vh,650px); overflow:auto; }
        .payback-report-table-wrap.table-responsive { max-height:min(62vh,650px); }
        #wallet-tbl { min-width:900px; margin:0; }
        #wallet-tbl thead th { position:sticky; top:0; z-index:2; padding:.85rem .9rem; border:0; background:#123b72; color:#fff; font-size:.72rem; font-weight:800; letter-spacing:.045em; white-space:nowrap; }
        #wallet-tbl tbody td { padding:.78rem .9rem; border-color:#edf1f6; color:#40546a; font-size:.84rem; vertical-align:middle; }
        #wallet-tbl tbody tr:nth-child(even) { background:#f8fbff; }
        #wallet-tbl tbody tr:hover { background:#eef6ff; }
        #wallet-tbl tbody td:nth-child(3) { color:#1769aa; font-weight:750; }
        #wallet-tbl tbody td:nth-child(7),#wallet-tbl tbody td:nth-child(8) { color:#243b53; font-weight:750; white-space:nowrap; }
        .payback-report-pagination { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.75rem; padding:.85rem 1.1rem; border-top:1px solid #edf2f7; }
        .pagination-info { margin-right:.5rem; color:#718096; font-size:.82rem; }
        .pagination-buttons { display:flex; align-items:center; flex-wrap:wrap; gap:4px; }
        .pagination-btn,.pagination-ellipsis { display:inline-flex; min-width:36px; height:36px; align-items:center; justify-content:center; border-radius:9px; }
        .pagination-btn { padding:0 10px; border:1px solid #d8e2ed; background:#fff; color:#41566e; font-size:.84rem; font-weight:650; cursor:pointer; transition:all .16s ease; }
        .pagination-btn:hover:not(.disabled):not(.active) { border-color:#1769aa; background:#eff6ff; color:#1769aa; }
        .pagination-btn.active { border-color:#1769aa; background:#1769aa; color:#fff; cursor:default; }
        .pagination-btn.disabled { opacity:.42; cursor:not-allowed; pointer-events:none; }
        .pagination-ellipsis { color:#718096; }
        .payback-report-card .scroll-list-controls { display:flex; align-items:center; justify-content:space-between; gap:.75rem; padding:.85rem 1.1rem; border-top:1px solid #edf2f7; color:#718096; font-size:.82rem; }
        .payback-report-card .scroll-list-load-more { border-color:#1769aa; border-radius:9px; color:#1769aa; font-weight:700; }
        @media (max-width: 991.98px) { .payback-report-filters { align-items:stretch; flex-direction:column; } .payback-report-filters__fields { grid-template-columns:repeat(2,minmax(0,1fr)); } .payback-report-filters .payback-report-export-form { flex:1 1 auto; width:100%!important; } .payback-report-export { width:100%; } }
        @media (max-width: 575.98px) { .admin-payback-report { margin:.5rem auto; padding:0 .65rem 1rem; } .payback-report-hero { align-items:flex-start; flex-direction:column; padding:1.1rem; } .payback-report-upload { width:100%; } .payback-report-card__heading,.payback-report-filters { padding-left:.9rem; padding-right:.9rem; } .payback-report-filters__fields { grid-template-columns:1fr 1fr; gap:.55rem; } .payback-report-filter-button { grid-column:1/-1; width:100%; } .payback-report-pagination { justify-content:center; } .payback-report-card .scroll-list-controls { justify-content:center; flex-wrap:wrap; } .pagination-info { width:100%; margin:0; text-align:center; } .pagination-buttons { justify-content:center; } .pagination-btn,.pagination-ellipsis { min-width:32px; height:32px; font-size:.78rem; } }
    </style>
    <main class="admin-payback-report">
        <header class="payback-report-hero">
            <div>
                <span class="payback-report-hero__eyebrow"><i class="bi bi-wallet2" aria-hidden="true"></i> Payback management</span>
                <h1>Payback Report</h1>
                <p>Review, filter, and export wallet and reward payback records.</p>
            </div>
            <a href="{{ route('admin.wallet.upload.page') }}" class="payback-report-upload">
                <i class="bi bi-cloud-arrow-up" aria-hidden="true"></i> Upload payback data
            </a>
        </header>

        <section class="payback-report-card" aria-labelledby="payback-filter-title">
            <div class="payback-report-card__heading">
                <span class="payback-report-card__icon"><i class="bi bi-funnel" aria-hidden="true"></i></span>
                <div><h2 id="payback-filter-title">Filter records</h2><p>Select a period or transaction type to narrow the report.</p></div>
            </div>
            <div class="payback-report-filters">
                <form id="wallet-form">
                    @csrf
                    <div class="payback-report-filters__fields">
                        <div>
                            <label class="form-label mb-1" for="month">Month</label>
                            <select name="month" id="month" class="form-select">
                                <option value="">Month</option>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label mb-1" for="year">Year</label>
                            <select name="year" id="year" class="form-select">
                                <option value="">Year</option>
                                @foreach (range(now()->year, 2020) as $y)
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label mb-1" for="trans_type">Transaction type</label>
                            <select name="type" id="trans_type" class="form-select">
                                <option value="all" {{ request('type', 'all') === 'all' ? 'selected' : '' }}>All Types</option>
                                <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>Credit</option>
                                <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>Debit</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label mb-1" for="pay_by">Payment mode</label>
                            <select name="pay_by" id="pay_by" class="form-select">
                                <option value="">All modes</option>
                                <option value="wallet" {{ request('pay_by') === 'wallet' ? 'selected' : '' }}>Wallet</option>
                                <option value="reward" {{ request('pay_by') === 'reward' ? 'selected' : '' }}>Reward</option>
                            </select>
                        </div>

                        <div>
                            <button type="submit" class="payback-report-filter-button">
                                <i class="bi bi-funnel" aria-hidden="true"></i> Apply filters
                            </button>
                        </div>
                    </div>
                </form>
                <form method="post" action="{{ route('admin.wallet.export') }}" class="payback-report-export-form">
                    @csrf
                    <input id="hidden_month" type="hidden" name="month">
                    <input id="hidden_year" type="hidden" name="year">
                    <input id="hidden_trans_type" type="hidden" name="trans_type">
                    <input id="hidden_pay_by" type="hidden" name="pay_by">
                    <button class="payback-report-export" type="submit">
                        <i class="bi bi-download" aria-hidden="true"></i> Export report
                    </button>
                </form>
            </div>
        </section>

        <section class="payback-report-card" aria-label="Payback report results">
            <div class="payback-report-table-wrap table-responsive">
            <table class="table align-middle" id="wallet-tbl">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Month</th>
                        <th>USER ID</th>
                        <th>USER NAME</th>
                        <th>MOBILE NUMBER</th>
                        <th>PAYMENT MODE</th>
                        <th>WALLET AMOUNT</th>
                        <th>REWARD POINTS</th>
                    </tr>
                </thead>

                <tbody id="wlt-tbl-bdy">
                </tbody>
            </table>
            </div>
            <div id="pagination-container" class="payback-report-pagination"></div>
        </section>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            const scrollList = window.FreebazarScrollList.mount(
                document.getElementById('pagination-container'),
                function(page) {
                    return walletLoad(page, $('#wallet-form')[0], true);
                }
            );

            // Initial load
            walletLoad(1, $('#wallet-form')[0]);


            $('#month').on('change', function() {
                $('#hidden_month').val(this.value);
            });

            $('#year').on('change', function() {
                $('#hidden_year').val(this.value);
            });
            $('#trans_type').on('change', function() {
                $('#hidden_trans_type').val(this.value);
            });

            $('#pay_by').on('change', function() {
                $('#hidden_pay_by').val(this.value);
            });

            function walletLoad(page = 1, formElement, append = false) {

                let formData = new FormData(formElement || document.createElement('form'));
                formData.append('page', page);
                return $.ajax({
                    url: "{{ route('admin.wallet.index') }}",
                    type: "POST",
                    data: formData,
                    processData: false, // Important when using FormData
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        let rows = '';
                        let index = response.data.from ?? 1;

                        if (response.data.data.length > 0) {
                            response.data.data.forEach(function(item) {

                                rows += `
                                        <tr>
                                            <td>${index++}</td>
                                            <td>${item.month ?? ''}</td>
                                            <td>${item.user_data?.user_id ?? ''}</td>
                                            <td>${item.user_data?.name ?? ''}</td>
                                            <td>${item.mobilenumber ?? ''}</td>
                                            <td>${item.trans_type ?? ''}</td>
                                            <td>₹${item.rounded_wallet_amount ?? 0}</td>
                                            <td>₹${item.rounded_reward_point ?? 0}</td>
                                        </tr>
                                    `;
                            });
                        } else {

                            rows = append ? '' : `
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            No data found
                                        </td>
                                    </tr>
                                `;
                        }

                        if (append) {
                            $('#wlt-tbl-bdy').append(rows);
                        } else {
                            $('#wlt-tbl-bdy').html(rows);
                        }

                        scrollList.update(
                            response.data.current_page ?? page,
                            response.data.last_page ?? page
                        );
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('Something went wrong');
                    }
                });
            }

            // Filter submit
            $('#wallet-form').on('submit', function(e) {
                e.preventDefault();
                scrollList.reset();
                walletLoad(1, this);
            });

        });
    </script>
@endsection
