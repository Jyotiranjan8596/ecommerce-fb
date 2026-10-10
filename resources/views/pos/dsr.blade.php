@extends('pos.layouts.master')

@section('content')
    <div class="container-fluid px-2 px-md-3 py-3 dsr-page">

        {{-- Hero header --}}
        <section class="dashboard-hero mb-4">
            <span class="dashboard-hero__label"><i class="fa fa-store me-1" aria-hidden="true"></i> POS overview</span>
            <h1 class="dashboard-hero__title">Sales Report</h1>
            <p class="dashboard-hero__text">Track billing, wallet & reward transactions, and verify entries for your
                store.</p>
            <div class="dashboard-hero__stats dsr-hero-stats">
                <div class="dashboard-hero__stat">
                    <small>Total transactions</small>
                    <strong id="total-transactions-val">{{ $totalTransactions }}</strong>
                </div>
                <div class="dashboard-hero__stat">
                    <small>Total billing amount</small>
                    <strong id="billing-amount-val">₹{{ $totalBillingAmount }}</strong>
                </div>
            </div>
        </section>

        {{-- Filters --}}
        <section class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4 dsr-filter-card">
            <div class="card-body p-3 p-lg-4">
                <form method="GET" action="{{ route('pos.dsr') }}" class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="dsr-field-label" for="search">Search by mobile number</label>
                        <div class="input-group dsr-search-group">
                            <span class="input-group-text"><i class="fa fa-search" aria-hidden="true"></i></span>
                            <input type="number" class="form-control" id="search" name="search"
                                placeholder="Enter mobile number" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="dsr-field-label" for="start_date">From</label>
                        <input type="date" value="{{ request('start_date') ?? now()->toDateString() }}"
                            class="form-control" name="start_date" id="start_date">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="dsr-field-label" for="end_date">To</label>
                        <input type="date" value="{{ request('end_date') ?? now()->toDateString() }}"
                            class="form-control" name="end_date" id="end_date">
                    </div>
                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button class="btn dsr-btn-primary flex-grow-1" type="submit">
                            <i class="fa fa-filter me-1" aria-hidden="true"></i> Apply
                        </button>
                    </div>
                </form>
            </div>
        </section>

        {{-- Transactions table --}}
        <section class="card border-0 shadow-lg rounded-4 overflow-hidden dsr-table-card">
            <div class="card-header border-0 bg-white px-3 px-md-4 pt-4 pb-3">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div>
                        <h2 class="h5 mb-1 fw-bold text-dark">Transaction details</h2>
                        <p class="small text-muted mb-0">All billing, wallet & reward transactions for the selected
                            period.</p>
                    </div>

                    <div class="d-flex flex-wrap gap-2 dsr-actions">
                        <button id="summary-btn" type="button" class="btn dsr-btn-soft" data-bs-toggle="modal"
                            data-bs-target="#exampleModalCenter">
                            <i class="fa fa-list-alt me-1" aria-hidden="true"></i> Summary
                        </button>

                        <button id="approve-btn" type="button" class="btn dsr-btn-success">
                            <i class="fa fa-check-circle me-1" aria-hidden="true"></i> Verify all
                        </button>

                        <form method="GET" action="{{ route('pos.dsr.export') }}" class="m-0">
                            <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                            <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                            <button class="btn dsr-btn-danger" type="submit">
                                <i class="fa fa-file-export me-1" aria-hidden="true"></i> Export
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body px-3 px-md-4 pt-2 pb-3">
                <div class="table-responsive dsr-table-wrap" tabindex="0" aria-label="Scrollable sales report">
                    <table class="table align-middle mb-0 dsr-table">
                        <thead>
                            <tr>
                                <th scope="col">Sl.No</th>
                                <th scope="col">Invoice</th>
                                <th scope="col">Name</th>
                                <th scope="col">User ID</th>
                                <th scope="col">Billing amount</th>
                                <th scope="col">By wallet</th>
                                <th scope="col">By reward</th>
                                <th scope="col">Net amount</th>
                                <th scope="col">Txn charge</th>
                                <th scope="col">Transaction date</th>
                                <th scope="col">Remark</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($wallets as $key => $data)
                                <tr>
                                    <td data-label="Sl.No">{{ $wallets->firstItem() + $key }}</td>
                                    <td data-label="Invoice" class="dsr-details">{{ $data->invoice }}</td>
                                    <td data-label="Name">{{ optional($data->user)->name }}</td>
                                    <td data-label="User ID">{{ optional($data->user)->user_id }}</td>
                                    <td data-label="Billing amount" class="dsr-amount">
                                        ₹{{ $data->billing_amount ?? 0 }}/-</td>
                                    <td data-label="By wallet">{{ $data->amount_wallet }}</td>
                                    <td data-label="By reward">{{ $data->reward_amount }}</td>
                                    <td data-label="Net amount" class="dsr-amount">{{ $data->amount }}</td>
                                    <td data-label="Txn charge">{{ $data->transaction_amount }}</td>
                                    <td data-label="Transaction date">
                                        {{ date('d-m-Y h:i A', strtotime($data->insert_date)) }}</td>
                                    <td data-label="Remark">N/A</td>
                                    <td data-label="Status">
                                        @if ($data->status == null || $data->status == 0)
                                            <a href="{{ route('pos.wallet.updateStatus', $data->id) }}"
                                                class="dsr-status-badge dsr-status-unverified">
                                                <i class="fa fa-exclamation-circle me-1" aria-hidden="true"></i>
                                                Unverified
                                            </a>
                                        @else
                                            <a href="{{ route('pos.wallet.updateStatus', $data->id) }}"
                                                class="dsr-status-badge dsr-status-verified">
                                                <i class="fa fa-check-circle me-1" aria-hidden="true"></i> Verified
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr class="dsr-empty-row">
                                    <td colspan="12">
                                        <div class="dsr-empty-state">
                                            <span class="dsr-empty-icon"><i class="fa fa-receipt" aria-hidden="true"></i></span>
                                            <span>No transactions found for the selected filters.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $wallets->links() }}
                </div>
            </div>
        </section>
    </div>

    {{-- Summary modal --}}
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg rounded-4 overflow-hidden dsr-modal">
                <div class="modal-header dsr-modal-header text-white">
                    <h5 class="modal-title" id="exampleModalCenterTitle">DSR Summary</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                @if (request()->start_date == request()->end_date)
                    @if ($totalTransactions == 0)
                        <div class="modal-body">
                            There are no transactions for today!
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    @else
                        <div class="modal-body">
                            <div class="dsr-summary-list">
                                <div class="dsr-summary-row">
                                    <span class="dsr-summary-label">Total transactions</span>
                                    <span class="dsr-summary-value">{{ $totalTransactions }}</span>
                                </div>
                                <div class="dsr-summary-row">
                                    <span class="dsr-summary-label">Total billing amount</span>
                                    <span class="dsr-summary-value">₹ {{ $totalBillingAmount }}</span>
                                </div>
                                <div class="dsr-summary-row">
                                    <span class="dsr-summary-label">Pay by Cash/UPI</span>
                                    <span class="dsr-summary-value">₹ {{ $payByCashOrUpi }}</span>
                                </div>
                                <div class="dsr-summary-row">
                                    <span class="dsr-summary-label">Pay by wallet</span>
                                    <span class="dsr-summary-value">₹ {{ $payByWallet }}</span>
                                </div>
                                <div class="dsr-summary-row">
                                    <span class="dsr-summary-label">Pay by reward</span>
                                    <span class="dsr-summary-value">₹ {{ $payByReward }}</span>
                                </div>
                                <div class="dsr-summary-row">
                                    <span class="dsr-summary-label">Receivable amount</span>
                                    <span class="dsr-summary-value dsr-summary-value--credit">₹
                                        {{ $creditAmount }}</span>
                                </div>
                                <div class="dsr-summary-row dsr-summary-row--last">
                                    <span class="dsr-summary-label">Payable amount</span>
                                    <span class="dsr-summary-value dsr-summary-value--debit">₹
                                        {{ $debitAmount }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    @endif
                @else
                    <div class="modal-body">
                        Multiple dates cannot be selected for summary!
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .dsr-page {
            color: #172033;
        }

        .dsr-page .dsr-hero-stats {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }

        .dsr-page .dsr-field-label {
            display: block;
            margin-bottom: .35rem;
            color: #536477;
            font-size: .78rem;
            font-weight: 750;
            letter-spacing: .03em;
            text-transform: uppercase;
        }

        .dsr-page .dsr-search-group .input-group-text {
            border-right: 0;
            background: #fff;
            color: #94a3b8;
        }

        .dsr-page .dsr-search-group .form-control {
            border-left: 0;
        }

        .dsr-page .dsr-search-group .form-control:focus {
            box-shadow: none;
            border-color: #cbd5e1;
        }

        .dsr-page .btn.dsr-btn-primary,
        .dsr-page .btn.dsr-btn-soft,
        .dsr-page .btn.dsr-btn-success,
        .dsr-page .btn.dsr-btn-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: .55rem 1.1rem;
            border: 1px solid transparent;
            border-radius: .7rem;
            font-size: .88rem;
            font-weight: 750;
            white-space: nowrap;
            transition: transform .15s ease, box-shadow .15s ease, background-color .15s ease;
        }

        .dsr-page .btn.dsr-btn-primary:hover,
        .dsr-page .btn.dsr-btn-soft:hover,
        .dsr-page .btn.dsr-btn-success:hover,
        .dsr-page .btn.dsr-btn-danger:hover {
            transform: translateY(-1px);
        }

        .dsr-page .btn.dsr-btn-primary {
            background: linear-gradient(135deg, #1e293b, #1e40af);
            color: #fff;
            box-shadow: 0 .5rem 1rem rgba(30, 64, 175, .18);
        }

        .dsr-page .btn.dsr-btn-primary:hover {
            color: #fff;
            box-shadow: 0 .7rem 1.3rem rgba(30, 64, 175, .24);
        }

        .dsr-page .btn.dsr-btn-soft {
            background: #edf3fb;
            color: #145da0;
        }

        .dsr-page .btn.dsr-btn-soft:hover {
            background: #dfeafb;
            color: #0c4a85;
        }

        .dsr-page .btn.dsr-btn-success {
            background: #0f9d6c;
            color: #fff;
        }

        .dsr-page .btn.dsr-btn-success:hover {
            background: #0c7f58;
            color: #fff;
        }

        .dsr-page .btn.dsr-btn-danger {
            background: #fff0ed;
            color: #c0321f;
        }

        .dsr-page .btn.dsr-btn-danger:hover {
            background: #ffe1db;
            color: #9b2817;
        }

        .dsr-page .dsr-table-wrap {
            max-height: 560px;
            overflow-x: auto;
            overflow-y: auto;
            border: 1px solid #e4ebf1;
            border-radius: .85rem;
            scrollbar-color: #9abfc0 #f0f5f6;
            scrollbar-width: thin;
        }

        .dsr-page .dsr-table-wrap::-webkit-scrollbar {
            width: .55rem;
            height: .55rem;
        }

        .dsr-page .dsr-table-wrap::-webkit-scrollbar-thumb {
            border: 2px solid #f0f5f6;
            border-radius: 999px;
            background: #9abfc0;
        }

        .dsr-page .dsr-table-wrap::-webkit-scrollbar-track {
            background: #f0f5f6;
        }

        .dsr-page .dsr-table {
            min-width: 1100px;
            color: #263449;
            /* The global .table rule sets overflow: hidden, which makes the table
               itself (not the scrollable .dsr-table-wrap) the sticky positioning
               context, so the sticky header would scroll away with the rows
               instead of staying fixed. Restore normal overflow here so sticky
               headers stick relative to the scrollable wrapper. */
            overflow: visible;
        }

        .dsr-page .dsr-table thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            padding: .9rem 1rem;
            border-bottom: 1px solid #dfe8ee;
            background: #f0f7f7;
            color: #174b54;
            font-size: .73rem;
            font-weight: 800;
            letter-spacing: .05em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .dsr-page .dsr-table tbody td {
            padding: .9rem 1rem;
            border-color: #edf1f5;
            font-size: .9rem;
            vertical-align: middle;
        }

        .dsr-page .dsr-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .dsr-page .dsr-table tbody tr:hover {
            background: #f8fbfc;
        }

        .dsr-page .dsr-details {
            color: #25364a;
            font-weight: 650;
        }

        .dsr-page .dsr-amount {
            color: #17324d;
            font-weight: 800;
            white-space: nowrap;
        }

        .dsr-page .dsr-status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: .35rem .75rem;
            border-radius: 999px;
            font-size: .78rem;
            font-weight: 750;
            text-decoration: none;
            white-space: nowrap;
            transition: background-color .15s ease, color .15s ease;
        }

        .dsr-page .dsr-status-verified {
            background: #e8f5f1;
            color: #08735f;
        }

        .dsr-page .dsr-status-verified:hover {
            background: #d7ece5;
            color: #065947;
        }

        .dsr-page .dsr-status-unverified {
            background: #fff0ed;
            color: #c0321f;
        }

        .dsr-page .dsr-status-unverified:hover {
            background: #ffe1db;
            color: #9b2817;
        }

        .dsr-page .dsr-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .65rem;
            padding: 2rem 1rem;
            color: #66768a;
        }

        .dsr-page .dsr-empty-icon {
            display: grid;
            width: 2.8rem;
            height: 2.8rem;
            place-items: center;
            border-radius: 50%;
            background: #edf5f6;
            color: #087f8c;
            font-size: 1.1rem;
        }

        .dsr-modal .dsr-modal-header {
            background: linear-gradient(135deg, #1e293b, #1e40af);
        }

        .dsr-summary-list {
            display: flex;
            flex-direction: column;
            gap: .1rem;
        }

        .dsr-summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .7rem .25rem;
            border-bottom: 1px solid #eef2f6;
        }

        .dsr-summary-row--last {
            border-bottom: 0;
        }

        .dsr-summary-label {
            color: #536477;
            font-weight: 700;
        }

        .dsr-summary-value {
            color: #17324d;
            font-weight: 800;
        }

        .dsr-summary-value--credit {
            color: #08735f;
        }

        .dsr-summary-value--debit {
            color: #c0321f;
        }

        @media (max-width: 767.98px) {
            .dsr-page .dsr-table-wrap {
                max-height: min(65vh, 560px);
                overflow-x: hidden;
                overflow-y: auto;
                border: 0;
            }

            .dsr-page .dsr-table {
                min-width: 0;
                border-collapse: separate;
                border-spacing: 0 .7rem;
            }

            .dsr-page .dsr-table thead {
                display: none;
            }

            .dsr-page .dsr-table tbody {
                display: block;
            }

            .dsr-page .dsr-table tbody tr:not(.dsr-empty-row) {
                display: grid;
                grid-template-columns: 1fr;
                overflow: hidden;
                border: 1px solid #e3ebf0;
                border-radius: .85rem;
                background: #fff;
                box-shadow: 0 .25rem .8rem rgba(23, 32, 51, .045);
            }

            .dsr-page .dsr-table tbody tr:not(.dsr-empty-row) td {
                min-width: 0;
                padding: .55rem .85rem;
                border: 0;
                overflow-wrap: anywhere;
            }

            .dsr-page .dsr-table tbody tr:not(.dsr-empty-row) td::before {
                display: block;
                margin-bottom: .2rem;
                color: #64748b;
                content: attr(data-label);
                font-size: .67rem;
                font-weight: 800;
                letter-spacing: .04em;
                text-transform: uppercase;
            }

            .dsr-page .dsr-table tbody tr:not(.dsr-empty-row) td[data-label="Sl.No"] {
                display: none;
            }

            .dsr-page .dsr-table tbody tr:not(.dsr-empty-row) td[data-label="Invoice"] {
                background: #f0f7f7;
                color: #17324d;
                font-size: .98rem;
                font-weight: 800;
            }

            .dsr-page .dsr-table tbody tr:not(.dsr-empty-row) td[data-label="Invoice"]::before {
                color: #087f8c;
            }

            .dsr-page .dsr-table tbody tr.dsr-empty-row {
                display: table-row;
            }

            .dsr-page .dsr-table tbody tr.dsr-empty-row td {
                display: table-cell;
            }

            .dsr-page .dsr-actions {
                width: 100%;
            }

            .dsr-page .dsr-actions .btn,
            .dsr-page .dsr-actions form {
                flex: 1 1 auto;
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#approve-btn').on('click', function() {
                Swal.fire({
                    title: 'Verify All Records?',
                    text: "Are you sure you want to verify all transactions?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Verify All'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('pos.dsr.verify.transaction') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                wallet_ids: @json($wallets->pluck('id'))
                            },
                            success: function(response) {
                                if (response.success) {
                                    if (response.count > 0) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Verified Successfully',
                                            text: response.message,
                                            confirmButtonText: 'OK'
                                        }).then(() => location.reload());
                                    } else {
                                        Swal.fire({
                                            icon: 'info',
                                            title: 'No transactions.',
                                            text: response.message,
                                            confirmButtonText: 'OK'
                                        });
                                    }
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Verification Failed',
                                        text: 'Something went wrong while verifying transactions.',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error("AJAX Error:", error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Verification Failed',
                                    text: 'Something went wrong while verifying transactions.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

            $("#submitData").on("click", function() {
                // Collect data
                let startDate = $("#start_date").val();

                if (!startDate) {
                    let today = new Date();
                    let month = ("0" + (today.getMonth() + 1)).slice(-2);
                    let day = ("0" + today.getDate()).slice(-2);
                    startDate = today.getFullYear() + "-" + month + "-" + day;
                }
                let data = {
                    date: startDate,
                    total_transactions: $("#total-transactions-val").text().trim(),
                    billing_amount: $("#billing-amount-val").text().replace("₹", "").trim(),
                    cash_upi: $("#cash-upi-val").text().replace("₹", "").trim(),
                    wallet: $("#wallet-val").text().replace("₹", "").trim(),
                    reward: $("#reward-val").text().replace("₹", "").trim(),
                    credit_amount: $("#credit-amount-val").text().replace("₹", "").trim(),
                    debit_amount: $("#debit-amount-val").text().replace("₹", "").trim(),
                    _token: "{{ csrf_token() }}" // for Laravel security
                };

                // Send AJAX
                $.ajax({
                    url: "{{ route('pos.save.summary.data') }}",
                    type: "POST",
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Verified Successfully',
                                text: response.message ?? 'Payment Submited!',
                                confirmButtonText: 'OK'
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Verification Failed',
                                text: response.message ??
                                    'Something went wrong while submit payment.',
                                confirmButtonText: 'OK'
                            }).then(() => location.reload());
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Verification Failed',
                            text: xhr.responseJSON?.message ??
                                'Something went wrong while submit payment.',
                            confirmButtonText: 'OK'
                        }).then(() => location.reload());
                    }
                });
            });
        });
    </script>
@endsection
