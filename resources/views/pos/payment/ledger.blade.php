@extends('pos.layouts.master')

@section('content')
    <div class="container-fluid px-2 px-md-3 py-3 ldgr-page">
        <div id="form-loader-overlay">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        {{-- Hero header --}}
        <section class="dashboard-hero mb-4">
            <span class="dashboard-hero__label"><i class="fa fa-book me-1" aria-hidden="true"></i> Accounts</span>
            <h1 class="dashboard-hero__title">Account Ledger</h1>
            <p class="dashboard-hero__text">Review opening balance, transactions, and the running closing balance for a date range.</p>
        </section>

        {{-- Filters --}}
        <section class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4 ldgr-card">
            <div class="card-body p-3 p-lg-4">
                <form id="ledger-form">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <!-- From Date -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <label for="from_date" class="ldgr-field-label">From date</label>
                            <input type="date" id="from_date" name="from_date" class="form-control">
                        </div>

                        <!-- To Date -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <label for="to_date" class="ldgr-field-label">To date</label>
                            <input type="date" id="to_date" name="to_date" class="form-control">
                        </div>

                        <!-- Buttons -->
                        <div class="col-12 col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn ldgr-btn-primary flex-grow-1">
                                    <i class="fa fa-filter me-1" aria-hidden="true"></i>
                                    View
                                </button>

                                <button type="reset" class="btn ldgr-btn-outline" title="Reset Filter">
                                    <i class="fa fa-rotate-left" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        {{-- Export
        <div class="col-12 col-md-2 d-flex align-items-end justify-content-md-end">
            <form method="post" action="{{ route('pos.ledger.export') }}" class="w-100">
                @csrf
                <input id="hidden_search_type" type="hidden" name="hsearch_type">
                <input id="hidden_value" type="hidden" name="hvalue">
                <button class="btn btn-danger btn-sm fw-semibold w-100" type="submit">
                    <i class="bi bi-download me-1"></i> EXPORT
                </button>
            </form>
        </div> --}}

        <!-- Data Table -->
        <section class="card border-0 shadow-lg rounded-4 overflow-hidden ldgr-card">
            <div id="tbl-div" hidden>
                <div class="card-header border-0 bg-white px-3 px-md-4 pt-4 pb-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h2 class="h6 mb-0 fw-bold text-dark text-uppercase">Ledger entries</h2>
                    <a href="#" id="download-pdf-btn" class="btn ldgr-btn-primary btn-sm" target="_blank">
                        <i class="fa fa-download me-1" aria-hidden="true"></i>Download
                    </a>
                </div>
                <div class="card-body px-3 px-md-4 pt-2 pb-3">
                    <div class="table-responsive ldgr-table-wrap" tabindex="0" aria-label="Scrollable ledger">
                        <table class="table align-middle mb-0 ldgr-table" id="wallet-tbl">
                            <thead>
                                <tr>
                                    <th>Sl.No</th>
                                    <th>Date</th>
                                    <th>Voucher no</th>
                                    <th>Account</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>

                            <tbody id="ldg-tbl-bdy">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pagination Links -->
        <div id="pagination-container" class="ldgr-pagination-wrapper"></div>
    </div>

    <style>
        .ldgr-page {
            color: #172033;
        }

        .ldgr-page .ldgr-field-label {
            display: block;
            margin-bottom: .35rem;
            color: #536477;
            font-size: .78rem;
            font-weight: 750;
            letter-spacing: .03em;
            text-transform: uppercase;
        }

        .ldgr-page .btn.ldgr-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: .6rem 1.1rem;
            border: 1px solid transparent;
            border-radius: .7rem;
            background: linear-gradient(135deg, #1e293b, #1e40af);
            color: #fff;
            font-size: .9rem;
            font-weight: 750;
            box-shadow: 0 .5rem 1rem rgba(30, 64, 175, .18);
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .ldgr-page .btn.ldgr-btn-primary:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 .7rem 1.3rem rgba(30, 64, 175, .24);
        }

        .ldgr-page .btn.ldgr-btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: .6rem 1.1rem;
            border: 1px solid #d7dee6;
            border-radius: .7rem;
            background: #fff;
            color: #42536b;
            font-size: .9rem;
            font-weight: 700;
            transition: background .15s ease, border-color .15s ease;
        }

        .ldgr-page .btn.ldgr-btn-outline:hover {
            background: #f3f6f9;
            border-color: #c3ccd6;
            color: #1e293b;
        }

        .ldgr-page .ldgr-table-wrap {
            max-height: 520px;
            overflow-x: auto;
            overflow-y: auto;
            border: 1px solid #e4ebf1;
            border-radius: .85rem;
            scrollbar-color: #9abfc0 #f0f5f6;
            scrollbar-width: thin;
        }

        .ldgr-page .ldgr-table-wrap::-webkit-scrollbar {
            width: .55rem;
            height: .55rem;
        }

        .ldgr-page .ldgr-table-wrap::-webkit-scrollbar-thumb {
            border: 2px solid #f0f5f6;
            border-radius: 999px;
            background: #9abfc0;
        }

        .ldgr-page .ldgr-table-wrap::-webkit-scrollbar-track {
            background: #f0f5f6;
        }

        .ldgr-page .ldgr-table {
            min-width: 920px;
            color: #263449;
            /* Counteract the global .table { overflow: hidden; } rule so the
               sticky thead sticks relative to the scrollable wrapper. */
            overflow: visible;
        }

        .ldgr-page .ldgr-table thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            padding: .8rem 1rem;
            border-bottom: 1px solid #dfe8ee;
            background: #f0f7f7;
            color: #174b54;
            font-size: .7rem;
            font-weight: 800;
            letter-spacing: .05em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .ldgr-page .ldgr-table tbody td {
            padding: .8rem 1rem;
            border-color: #edf1f5;
            font-size: .86rem;
            vertical-align: middle;
        }

        .ldgr-page .ldgr-table tbody tr:hover {
            background: #f8fbfc;
        }

        .ldgr-page .ldgr-table .opening-row td,
        .ldgr-page .ldgr-table .total-row td,
        .ldgr-page .ldgr-table .closing-row {
            font-weight: 800;
            background: #f6f9fb;
        }

        .ldgr-page .voucher-type {
            display: inline-block;
            padding: .15rem .55rem;
            border-radius: 999px;
            background: #edf3fb;
            color: #145da0;
            font-size: .72rem;
            font-weight: 750;
        }

        .ldgr-page .remarks {
            margin-top: .2rem;
            color: #8192a6;
            font-size: .76rem;
        }

        /* Pagination */
        .ldgr-pagination-wrapper {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 1rem;
            padding: 0.5rem 0;
        }

        .ldgr-pagination-wrapper .pagination-info {
            font-size: 0.85rem;
            color: #6c757d;
            margin-right: 8px;
        }

        .ldgr-pagination-wrapper .pagination-buttons {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px;
        }

        .ldgr-pagination-wrapper .pagination-btn {
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            border: 1.5px solid #dee2e6;
            border-radius: 8px;
            background: #fff;
            color: #42536b;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .ldgr-pagination-wrapper .pagination-btn:hover:not(.disabled):not(.active) {
            background: #edf3fb;
            border-color: #1e40af;
            color: #1e40af;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(30, 64, 175, .15);
        }

        .ldgr-pagination-wrapper .pagination-btn.active {
            background: linear-gradient(135deg, #1e293b, #1e40af);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 2px 8px rgba(30, 64, 175, .35);
            cursor: default;
        }

        .ldgr-pagination-wrapper .pagination-btn.nav-btn {
            background: #f8f9fa;
            color: #42536b;
        }

        .ldgr-pagination-wrapper .pagination-btn.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }

        .ldgr-pagination-wrapper .pagination-ellipsis {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            color: #6c757d;
            font-size: 1rem;
            letter-spacing: 1px;
        }

        @media (max-width: 576px) {
            .ldgr-pagination-wrapper {
                justify-content: center;
            }

            .ldgr-pagination-wrapper .pagination-info {
                width: 100%;
                text-align: center;
                margin-right: 0;
            }

            .ldgr-pagination-wrapper .pagination-buttons {
                justify-content: center;
            }

            .ldgr-pagination-wrapper .pagination-btn {
                min-width: 32px;
                height: 32px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 767.98px) {
            .ldgr-page .ldgr-table thead {
                display: none;
            }

            .ldgr-page .ldgr-table,
            .ldgr-page .ldgr-table tbody,
            .ldgr-page .ldgr-table tr,
            .ldgr-page .ldgr-table td {
                display: block;
                width: 100%;
                min-width: 0;
            }

            .ldgr-page .ldgr-table tr {
                margin-bottom: .75rem;
                padding: .5rem .25rem;
                border: 1px solid #e4ebf1;
                border-radius: .75rem;
            }

            .ldgr-page .ldgr-table td {
                display: flex;
                justify-content: space-between;
                gap: .75rem;
                padding: .45rem .75rem;
                border: 0;
                text-align: right;
            }

            .ldgr-page .ldgr-table td::before {
                content: attr(data-label);
                font-weight: 750;
                color: #536477;
                text-transform: uppercase;
                font-size: .7rem;
                letter-spacing: .03em;
                text-align: left;
            }
        }

        #form-loader-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.6);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        #form-loader-overlay .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
        const escapeHtml = value => $('<div>').text(value ?? '').html();
        const formatAmount = value => Number(value ?? 0).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        function showFormLoader() {
                $('#form-loader-overlay').css('display', 'flex');
            }

            function hideFormLoader() {
                $('#form-loader-overlay').css('display', 'none');
            }

            function loadLedger(page = 1, formElement) {

                let formData = new FormData(formElement); // Capital 'F'
                formData.append('page', page);
                const fromDate = $('#from_date').val();
                const toDate = $('#to_date').val();
                $.ajax({
                    url: "{{ route('pos.get.ledger') }}",
                    type: "POST",
                    data: formData,
                    processData: false, // Important when using FormData
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        let rows = '';
                        let index = 1;
                        $('#tbl-div').attr('hidden', false);
                            
                        const openingBalance = response.data.opening_balance;
                        const openingBalanceType = response.data.opening_balance_type;

                        const transactions = Object.keys(response.data)
                            .filter(key => !isNaN(key))
                            .map(key => response.data[key]);
                        if (Object.keys(response.data).length > 0) {
                            const pagination = response.data;
                            let paginationHtml = '';
                            rows += `<tr class="opening-row">
                                        <td colspan="6" class="text-end">Opening Balance</td>
                                        <td class="text-end">₹${formatAmount(openingBalance)} ${escapeHtml(openingBalanceType)}</td>
                                    </tr>
                                `;
                            transactions.forEach(function(item) {

                                rows += `
                                        <tr>
                                            <td data-label="Sl.No">${index++}</td>
                                            <td data-label="Date">${escapeHtml(item.date)}</td>
                                            <td data-label="Voucher No">${escapeHtml(item.voucher_number)}</td>
                                            <td data-label="Account"><span class="voucher-type">${escapeHtml(item.account_name)}</span>
                                                <div class="remarks">Ref: ${escapeHtml(item.reference_number)}${item.remark ? `, ${escapeHtml(item.remark)}` : ''}
                                                </div></td>
                                            <td data-label="Debit" class="text-end">${item.debit > 0 ? `₹${formatAmount(item.debit)}` : '—'}</td>
                                            <td data-label="Credit" class="text-end">${item.credit > 0 ? `₹${formatAmount(item.credit)}` : '—'}</td>
                                            <td data-label="Balance" class="text-end">₹${formatAmount(item.balance)} ${escapeHtml(item.balance_type)}</td>
                                        </tr>
                                    `;
                            });
                            rows += `<tr>
                                            <td colspan="4" class="closing-row text-end bold"><strong>Total</strong></td>
                                            <td class="text-end"><strong>₹${formatAmount(response.data.total_debit)}</strong></td>
                                            <td class="text-end"><strong>₹${formatAmount(response.data.total_credit)}</strong></td>
                                            <td class="text-end"><strong>₹${formatAmount(response.data.total_balance)} ${escapeHtml(response.data.total_balance_type)}</strong></td>
                                        </tr>
                                    `;
                            // $('#pagination-container').html(buildPagination(pagination));
                            const pdfUrl =
                                `{{ route('pos.ledger.download-pdf') }}?from_date=${encodeURIComponent(fromDate)}&to_date=${encodeURIComponent(toDate)}`;
                            $('#download-pdf-btn').attr('href', pdfUrl);
                        } else {

                            rows = `
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            No data found
                                        </td>
                                    </tr>
                                `;
                        }

                        $('#ldg-tbl-bdy').html(rows);

                        // Pagination HTML
                        $('#pagination-link').html(response.pagination);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('Something went wrong');
                    }
                });
            }

            $('#download-pdf-btn').on('click', function() {
                console.log('clicked');
                let from = $('#from_date').val();
                let to = $('#to_date').val();
            });

            // Filter submit
            $('#ledger-form').on('submit', function(e) {
                e.preventDefault();
                loadLedger(1, this);
            });

        });
    </script>
@endsection
