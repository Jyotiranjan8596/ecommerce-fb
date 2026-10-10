@extends('layouts.master')

@section('content')
    <style>
        .all-user-transactions { max-width: 1600px; margin: 1.25rem auto; padding: 0 1rem 2rem; }
        .all-user-transactions__hero { display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:1.15rem; padding:1.45rem 1.65rem; border:1px solid #d7e7f6; border-radius:20px; background:linear-gradient(120deg,#123b72,#1769aa 68%,#2783bd); color:#fff; box-shadow:0 14px 32px rgba(18,59,114,.14); }
        .all-user-transactions__eyebrow { display:inline-flex; align-items:center; gap:.45rem; color:#c6e4ff; font-size:.72rem; font-weight:800; letter-spacing:.12em; text-transform:uppercase; }
        .all-user-transactions__hero h1 { margin:.35rem 0 .2rem; color:#fff; font-size:clamp(1.5rem,2.6vw,2rem); font-weight:850; }
        .all-user-transactions__hero p { margin:0; color:rgba(255,255,255,.78); font-size:.88rem; }
        .all-user-transactions__hero-icon { display:grid; width:58px; height:58px; flex:0 0 58px; place-items:center; border:1px solid rgba(255,255,255,.2); border-radius:17px; background:rgba(255,255,255,.12); color:#fff; font-size:1.35rem; }
        .all-user-transactions__toolbar { display:flex; align-items:flex-end; justify-content:space-between; gap:1rem; margin-bottom:1rem; padding:1.1rem 1.2rem; border:1px solid #e0e8f1; border-radius:16px; background:#fff; box-shadow:0 8px 22px rgba(19,48,79,.055); }
        .all-user-transactions__filters { display:flex; flex:1 1 auto; align-items:flex-end; gap:.75rem; }
        .all-user-transactions__field { min-width:150px; }
        .all-user-transactions__field label { display:block; margin-bottom:.35rem; color:#52657a; font-size:.76rem; font-weight:750; }
        .all-user-transactions__field input { min-height:42px; border-color:#d9e3ed; border-radius:9px; }
        .all-user-transactions__filters .btn,.all-user-transactions__export .btn { min-height:42px; border-radius:9px; font-size:.82rem; font-weight:750; }
        .all-user-transactions__export .btn { min-width:130px; border-color:#1769aa; background:#1769aa; color:#fff; }
        .all-user-transactions__export .btn:hover { border-color:#123b72; background:#123b72; }
        .all-user-transactions__table-card { overflow:hidden; border:1px solid #e0e8f1; border-radius:16px; background:#fff; box-shadow:0 8px 22px rgba(19,48,79,.055); }
        .user-transactions-table-wrap { max-height:620px; overflow:auto; }
        #utrans-tbl { min-width:1100px; margin:0; }
        #utrans-tbl thead th { position:sticky; top:0; z-index:2; padding:.85rem .75rem; border:0; background:#123b72; color:#fff; font-size:.7rem; font-weight:800; letter-spacing:.04em; white-space:nowrap; }
        #utrans-tbl tbody td { padding:.78rem .75rem; border-color:#edf1f6; color:#40546a; font-size:.81rem; vertical-align:middle; white-space:nowrap; }
        #utrans-tbl tbody tr:nth-child(even) { background:#f8fbff; }
        #utrans-tbl tbody tr:hover { background:#eef6ff; }

        .pagination-wrapper {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 8px;
            margin: 0;
            padding: 1rem 1.2rem;
            border-top: 1px solid #edf1f6;
        }

        .pagination-info {
            font-size: 0.85rem;
            color: #6c757d;
            margin-right: 8px;
        }

        .pagination-buttons {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px;
        }

        .pagination-btn {
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            border: 1px solid #d9e3ed;
            border-radius: 8px;
            background: #fff;
            color: #495057;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .pagination-btn:hover:not(.disabled):not(.active) {
            background: #eff6ff;
            border-color: #1769aa;
            color: #1769aa;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(23, 105, 170, 0.15);
        }

        .pagination-btn.active {
            background: #1769aa;
            border-color: #1769aa;
            color: #fff;
            box-shadow: 0 2px 8px rgba(23, 105, 170, 0.25);
            cursor: default;
        }

        .pagination-btn.nav-btn {
            background: #f8f9fa;
            color: #495057;
        }

        .pagination-btn.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }

        .pagination-ellipsis {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            color: #6c757d;
            font-size: 1rem;
            letter-spacing: 1px;
        }

        /* Mobile responsive */
        @media (max-width: 576px) {
            .all-user-transactions { margin:.5rem auto; padding:0 .65rem 1rem; }
            .all-user-transactions__hero { padding:1.1rem; }
            .all-user-transactions__hero-icon { display:none; }
            .all-user-transactions__toolbar { align-items:stretch; flex-direction:column; padding:.9rem; }
            .all-user-transactions__filters { align-items:stretch; flex-direction:column; }
            .all-user-transactions__field { min-width:0; }
            .all-user-transactions__filters .btn,.all-user-transactions__export .btn { width:100%; }
            .pagination-wrapper {
                justify-content: center;
            }

            .pagination-info {
                width: 100%;
                text-align: center;
                margin-right: 0;
            }

            .pagination-buttons {
                justify-content: center;
            }

            .pagination-btn {
                min-width: 32px;
                height: 32px;
                font-size: 0.8rem;
            }
        }
    </style>
    <main class="all-user-transactions">
        <header class="all-user-transactions__hero">
            <div>
                <span class="all-user-transactions__eyebrow"><i class="fas fa-chart-line" aria-hidden="true"></i> Account reports</span>
                <h1>All User Transactions</h1>
                <p>Review customer payment and wallet activity across the platform.</p>
            </div>
            <span class="all-user-transactions__hero-icon" aria-hidden="true"><i class="fas fa-receipt"></i></span>
        </header>

        <section class="all-user-transactions__toolbar">
            <form id="trans-form" class="all-user-transactions__filters">
                @csrf
                <div class="all-user-transactions__field">
                    <label for="start_date">From date</label>
                    <input required id="start_date" name="start_date" class="form-control" type="date">
                </div>
                <div class="all-user-transactions__field">
                    <label for="end_date">To date</label>
                    <input required id="end_date" name="end_date" class="form-control" type="date">
                </div>
                <button type="submit" class="btn btn-primary px-3"><i class="bi bi-funnel me-1"></i> Filter</button>
            </form>
            <form method="POST" action="{{ route('admin.all.users.transactions.export') }}" class="all-user-transactions__export">
                @csrf
                <input id="hidden_start_date" type="hidden" name="from_date" value="{{ request()->start_date }}">
                <input id="hidden_end_date" type="hidden" name="to_date" value="{{ request()->end_date }}">
                <button class="btn" type="submit"><i class="bi bi-download me-1"></i> Export</button>
            </form>
        </section>

        <!-- Data Table -->
        <div class="table-responsive">
            <table class="table table-striped" id="utrans-tbl">
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>POS</th>
                        <th>Invoice</th>
                        <th>Billing Amount</th>
                        <th>Wallet Deduct</th>
                        <th>Reward Deduct</th>
                        <th>Net Pay</th>
                        <th>Remaining Wallet</th>
                        <th>Remaining Reward</th>
                        <th>Transaction Date</th>
                    </tr>
                </thead>

                <tbody id="wlt-tbl-bdy">
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div id="pagination-container" class="pagination-wrapper"></div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            const scrollList = window.FreebazarScrollList.mount(
                document.getElementById('pagination-container'),
                function(page) {
                    return transactionsLoad(page, $('#trans-form')[0], true);
                }
            );

            $('#start_date').on('change', function() {
                $('#hidden_start_date').val(this.value);
            });

            $('#end_date').on('change', function() {
                $('#hidden_end_date').val(this.value);
            });
            // Initial load
            transactionsLoad(1, $('#trans-form')[0]);

            function transactionsLoad(page = 1, formElement, append = false) {
                let formData = new FormData(formElement || document.createElement('form'));
                formData.append('page', page);
                return $.ajax({
                    url: "{{ route('admin.all.users.transactions') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
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
                                            <td>${item.user_name ?? ''}</td>
                                            <td>${item.mobile ?? ''}</td>
                                            <td>${item.pos_name ?? ''}</td>
                                            <td>${item.invoice ?? ''}</td>
                                            <td>${item.billing_amount ?? ''}</td>
                                            <td>${item.amount_wallet ?? ''}</td>
                                            <td>${item.reward_amount ?? ''}</td>
                                            <td>${item.amount ?? ''}</td>
                                            <td>₹${item.remaining_amount ?? 0}/-</td>
                                            <td>₹${item.remaining_reward ?? 0}/-</td>
                                            <td>${item.trans_date ?? 0}</td>
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
            $('#trans-form').on('submit', function(e) {
                e.preventDefault();
                scrollList.reset();
                transactionsLoad(1, this);
            });

        });
    </script>
@endsection
