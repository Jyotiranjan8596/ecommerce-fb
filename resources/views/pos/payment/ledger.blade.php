@extends('pos.layouts.master')

@section('content')
    <style>
        #wallet-tbl thead th {
            position: sticky;
            top: 0;
            background-color: #000;
            color: #fff;
            z-index: 10;
        }

        .table-responsive {
            max-height: 500px;
            overflow-y: auto;
        }

        .pagination-wrapper {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 1rem;
            padding: 0.5rem 0;
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
            border: 1.5px solid #dee2e6;
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
            background: #f0f4ff;
            border-color: #4f46e5;
            color: #4f46e5;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(79, 70, 229, 0.15);
        }

        .pagination-btn.active {
            background: #4f46e5;
            border-color: #4f46e5;
            color: #fff;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.35);
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

        .opening-row td,
        .total-row td {
            font-weight: bold;
        }

        .opening-row {
            border: #000;
        }

        .closing-row {
            border: #000;
        }
    </style>
    <div id="form-loader-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="container" style="margin-top: 20px;">
        <h3 class="text-center"><b style="color: rgb(8, 7, 20)">Account Ledger</b></h3>

        <div class="row g-3 mb-3">
            {{-- Filters --}}
            <div class="col-12">
                <div class="card shadow-sm border-0 p-3">

                    <form id="ledger-form">
                        @csrf

                        <div class="row g-3 align-items-end">

                            <!-- Voucher Number -->
                            {{-- <div class="col-12 col-sm-6 col-md-3">
                                <label for="voucher_no" class="form-label small fw-semibold text-muted">
                                    Voucher No
                                </label>

                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i class="bi bi-receipt"></i>
                                    </span>

                                    <input type="text" id="voucher_no" name="voucher_no" class="form-control"
                                        placeholder="Enter voucher no">
                                </div>
                            </div>


                            <!-- Reference Number -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="reference_no" class="form-label small fw-semibold text-muted">
                                    Reference No
                                </label>

                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i class="bi bi-hash"></i>
                                    </span>

                                    <input type="text" id="reference_no" name="reference_no" class="form-control"
                                        placeholder="Enter reference no">
                                </div>
                            </div> --}}


                            <!-- From Date -->
                            <div class="col-12 col-sm-6 col-md-2">
                                <label for="from_date" class="form-label small fw-semibold text-muted">
                                    From Date
                                </label>

                                <input type="date" id="from_date" name="from_date" class="form-control form-control-sm">
                            </div>


                            <!-- To Date -->
                            <div class="col-12 col-sm-6 col-md-2">
                                <label for="to_date" class="form-label small fw-semibold text-muted">
                                    To Date
                                </label>

                                <input type="date" id="to_date" name="to_date" class="form-control form-control-sm">
                                <input type="hidden" id="pos_id" name="pos_id" value="{{$userId}}">
                            </div>


                            <!-- Buttons -->
                            <div class="col-12 col-md-2">
                                <div class="d-flex gap-2">

                                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1 fw-semibold">
                                        <i class="bi bi-funnel me-1"></i>
                                        View
                                    </button>

                                    <button type="reset" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                                        <i class="bi bi-arrow-counterclockwise">Reset</i>
                                    </button>

                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>

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

        </div>
        <hr class="my-4">

        <!-- Data Table -->
        <div id="tbl-div" hidden class="table-responsive">
            <div class="d-flex justify-content-end my-3">
                <a href="#" id="download-pdf-btn" class="btn btn-primary btn-sm fw-semibold" target="_blank">
                    <i class="bi bi-download me-1"></i>Download
                </a>
            </div>
            <table class="table table-striped" id="wallet-tbl">
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>Date</th>
                        <th>Voucher No</th>
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

        <!-- Pagination Links -->
        <div id="pagination-container" class="pagination-wrapper"></div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            let user_id = {{ $userId }};

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
                        let data = response.data;
                        let rows = '';
                        let index = response.data.from ?? 1;
                        $('#tbl-div').attr('hidden', false);
                        console.log(response.data);

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
                                        <td>
                                            ${openingBalance} ${openingBalanceType}
                                        </td>
                                    </tr>
                                `;
                            transactions.forEach(function(item) {

                                rows += `
                                        <tr>
                                            <td>${index++}</td>
                                            <td>${item.date ?? ''}</td>
                                            <td>${item.voucher_number ?? ''}</td>
                                            <td><span class="voucher-type">{{ $row['voucher_type'] ?? '' }}</span>
                                                <div class="remarks">ref - ${item.reference_number},${item.remark}
                                                </div></td>
                                            <td>${item.debit}</td>
                                            <td>${item.credit}</td>
                                            <td>${item.balance} ${item.balance_type}</td>
                                        </tr>
                                    `;
                            });
                            rows += `<tr>
                                            <td colspan="4" class="closing-row text-end bold"><strong>Total</strong></td>
                                            <td><strong>${data.total_debit}</strong></td>
                                            <td><strong>${data.total_credit}</strong></td>
                                            <td><strong>${data.total_balance} ${data.total_balance_type}</strong></td>
                                        </tr>
                                    `;
                            // $('#pagination-container').html(buildPagination(pagination));
                            const pdfUrl =
                                `/freebazar/public/ledger/download-pdf?from_date=${fromDate}&to_date=${toDate}`;
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

            // Pagination click
            $(document).on('click', '.pagination-btn', function() {
                const page = $(this).data('page');
                loadLedger(page, $('#srch-form')[0]); // Reuse the same form
            });

            function buildPagination(pagination) {
                const {
                    current_page,
                    last_page
                } = pagination;
                let paginationHtml = '';

                const maxVisible = 5; // Max page buttons to show
                let startPage = Math.max(1, current_page - Math.floor(maxVisible / 2));
                let endPage = Math.min(last_page, startPage + maxVisible - 1);

                // Adjust start if end hits the limit
                if (endPage - startPage < maxVisible - 1) {
                    startPage = Math.max(1, endPage - maxVisible + 1);
                }

                // First + Ellipsis
                if (startPage > 1) {
                    paginationHtml += pageBtn(1, current_page);
                    if (startPage > 2) {
                        paginationHtml += `<span class="pagination-ellipsis">…</span>`;
                    }
                }

                // Page Numbers
                for (let i = startPage; i <= endPage; i++) {
                    paginationHtml += pageBtn(i, current_page);
                }

                // Ellipsis + Last
                if (endPage < last_page) {
                    if (endPage < last_page - 1) {
                        paginationHtml += `<span class="pagination-ellipsis">…</span>`;
                    }
                    paginationHtml += pageBtn(last_page, current_page);
                }

                // Wrap with Prev/Next
                const prevBtn = current_page > 1 ?
                    `<button class="pagination-btn nav-btn pagination-btn" data-page="${current_page - 1}" title="Previous">
                    <i class="fas fa-chevron-left"></i>
                    </button>` :
                    `<button class="pagination-btn nav-btn disabled" disabled title="Previous">
                        <i class="fas fa-chevron-left"></i>
                    </button>`;

                const nextBtn = current_page < last_page ?
                    `<button class="pagination-btn nav-btn pagination-btn" data-page="${current_page + 1}" title="Next">
                        <i class="fas fa-chevron-right"></i>
                    </button>` :
                    `<button class="pagination-btn nav-btn disabled" disabled title="Next">
                        <i class="fas fa-chevron-right"></i>
                    </button>`;

                return `
                    <div class="pagination-info">
                        Page <strong>${current_page}</strong> of <strong>${last_page}</strong>
                    </div>
                    <div class="pagination-buttons">
                        ${prevBtn}
                        ${paginationHtml}
                        ${nextBtn}
                    </div>
                `;
            }

            function pageBtn(i, current) {
                const isActive = i === current;
                return `<button 
                class="pagination-btn ${isActive ? 'active' : ''}" 
                data-page="${i}"
                ${isActive ? 'aria-current="page"' : ''}
            >${i}</button>`;
            }

        });
    </script>
@endsection
