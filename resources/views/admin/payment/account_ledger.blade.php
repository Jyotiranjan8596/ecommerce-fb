@extends('layouts.master')

@section('content')
    <style>
        #wallet-tbl thead th {
            position: sticky;
            top: 0;
            background-color: #edf5f5;
            color: #174b54;
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

        .ledger-summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .8rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--et-border);
            background: #f8fbfc;
        }

        .ledger-summary-item {
            min-width: 0;
            padding: .8rem .9rem;
            border: 1px solid var(--et-border);
            border-radius: .75rem;
            background: #fff;
        }

        .ledger-summary-item span {
            display: block;
            color: var(--et-muted);
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .ledger-summary-item strong {
            display: block;
            margin-top: .2rem;
            color: #174b54;
            font-size: 1.05rem;
        }

        .ledger-summary-item--closing {
            border-color: rgba(15, 138, 122, .25);
            background: #eff9f6;
        }

        @media (max-width: 767.98px) {
            .ledger-summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 380px) {
            .ledger-summary-grid {
                grid-template-columns: minmax(0, 1fr);
            }
        }
    </style>
    <div id="form-loader-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="container-fluid py-3 admin-workflow-page">
        <section class="admin-workflow-hero">
            <div>
                <span class="admin-workflow-hero__eyebrow">Financial reporting</span>
                <h1>Account Ledger</h1>
                <p>Select a POS to review its opening balance, transaction details, period totals, and closing balance.</p>
            </div>
            <span class="admin-workflow-hero__icon" aria-hidden="true"><i class="bi bi-journal-text"></i></span>
        </section>

        <div class="row g-3 mb-3">
            {{-- Filters --}}
            <div class="col-12">
                <div class="card admin-workflow-card p-3">

                    <form id="ledger-form">
                        @csrf

                        <div class="row g-3 align-items-end">
                            <input type="hidden" id="pos_id" name="pos_id">

                            <div class="col-12 col-md-5">
                                <label for="pos-search" class="form-label small fw-semibold text-muted">
                                    Search and select POS
                                </label>
                                <input type="search" id="pos-search" class="form-control form-control-sm"
                                    list="pos-options" placeholder="Search by POS name or user ID" autocomplete="off"
                                    aria-describedby="pos-selection-help" required>
                                <datalist id="pos-options">
                                    @foreach ($posList as $pos)
                                        <option value="{{ $pos->name }} ({{ $pos->user_id }})"
                                            data-pos-id="{{ $pos->id }}"></option>
                                    @endforeach
                                </datalist>
                                <small id="pos-selection-help" class="form-text text-muted">
                                    Select a POS from the suggestions to view its ledger.
                                </small>
                            </div>

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
                            </div>


                            <!-- Buttons -->
                            <div class="col-12 col-md-3">
                                <div class="d-flex gap-2">

                                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1 fw-semibold">
                                        <i class="bi bi-funnel me-1"></i>
                                        View
                                    </button>

                                    <button type="button" id="reset-ledger-filters" class="btn btn-outline-secondary btn-sm"
                                        title="Reset date filters">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset dates
                                    </button>

                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>

        </div>
        <!-- Data Table -->
        <div id="tbl-div" hidden class="card admin-workflow-card">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-3 border-bottom">
            <div>
                <h2 class="h5 mb-0">Ledger Details — <span id="ledger-pos-name"></span></h2>
                <p class="text-muted small mb-0 mt-1" id="ledger-period-label">Opening balance, transactions, and period totals.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="#" id="download-pdf-btn" class="btn btn-primary btn-sm fw-semibold" target="_blank" rel="noopener" hidden>
                    <i class="bi bi-file-earmark-pdf me-1"></i>Download PDF
                </a>
                <form method="POST" action="{{ route('admin.ledger.export') }}" id="ledger-export-form" hidden>
                    @csrf
                    <input type="hidden" name="pos_id" id="export-pos-id">
                    <input type="hidden" name="from_date" id="export-from-date">
                    <input type="hidden" name="to_date" id="export-to-date">
                    <button type="submit" class="btn btn-outline-primary btn-sm fw-semibold">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i>Export Excel
                    </button>
                </form>
            </div>
            </div>
            <div class="ledger-summary-grid" aria-label="Ledger summary">
                <div class="ledger-summary-item">
                    <span>Opening balance</span>
                    <strong id="summary-opening">—</strong>
                </div>
                <div class="ledger-summary-item">
                    <span>Total debit</span>
                    <strong id="summary-debit">—</strong>
                </div>
                <div class="ledger-summary-item">
                    <span>Total credit</span>
                    <strong id="summary-credit">—</strong>
                </div>
                <div class="ledger-summary-item ledger-summary-item--closing">
                    <span>Closing balance</span>
                    <strong id="summary-closing">—</strong>
                </div>
            </div>
            <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 admin-workflow-table" id="wallet-tbl">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Voucher No</th>
                        <th>Account</th>
                        <th>Details</th>
                        <th>Reference No</th>
                        <th>Payment Method</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Due</th>
                        <th>GST No</th>
                        <th>Balance</th>
                    </tr>
                </thead>

                <tbody id="ldg-tbl-bdy">
                </tbody>
            </table>
            </div>
        </div>

        <!-- Pagination Links -->
        <div id="pagination-container" class="pagination-wrapper"></div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            const escapeHtml = value => $('<div>').text(value ?? '').html();
            const formatAmount = value => Number(value ?? 0).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            const $posSearch = $('#pos-search');
            const $posId = $('#pos_id');
            let resolvedPosId = '';

            function resolveSelectedPos() {
                const selectedOption = Array.from(document.querySelectorAll('#pos-options option'))
                    .find(option => option.value === $posSearch.val());
                const posId = selectedOption?.dataset.posId ?? '';
                $posId.val(posId);

                if (posId !== resolvedPosId) {
                    $('#tbl-div').prop('hidden', true);
                    $('#download-pdf-btn').prop('hidden', true);
                    $('#ledger-export-form').prop('hidden', true);
                    resolvedPosId = posId;
                }

                return selectedOption;
            }

            function showFormLoader() {
                $('#form-loader-overlay').css('display', 'flex');
            }

            function hideFormLoader() {
                $('#form-loader-overlay').css('display', 'none');
            }

            function loadLedger(page = 1, formElement) {

                let formData = new FormData(formElement); // Capital 'F'
                formData.append('page', page);
                const requestedPosId = $posId.val();
                const fromDate = $('#from_date').val();
                const toDate = $('#to_date').val();
                $.ajax({
                    url: "{{ route('admin.get.ledger') }}",
                    type: "POST",
                    data: formData,
                    processData: false, // Important when using FormData
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        showFormLoader();
                    },
                    success: function(response) {
                        if (requestedPosId !== $posId.val()) {
                            return;
                        }

                        let rows = '';
                        $('#tbl-div').prop('hidden', false);
                        const selectedPos = resolveSelectedPos();
                        $('#ledger-pos-name').text(selectedPos?.value ?? '');
                        let data = response.data;
                        const openingBalance = response.data.opening_balance;
                        const openingBalanceType = response.data.opening_balance_type;
                        const openingLabel = `₹${formatAmount(openingBalance)} ${escapeHtml(openingBalanceType)}`;
                        const closingLabel = `₹${formatAmount(data.total_balance)} ${escapeHtml(data.total_balance_type)}`;
                        $('#summary-opening').text(openingLabel);
                        $('#summary-debit').text(`₹${formatAmount(data.total_debit)}`);
                        $('#summary-credit').text(`₹${formatAmount(data.total_credit)}`);
                        $('#summary-closing').text(closingLabel);
                        $('#ledger-period-label').text(
                            fromDate || toDate
                                ? `Period: ${fromDate || 'Beginning'} to ${toDate || 'Today'}`
                                : 'All dates'
                        );

                        rows += `<tr class="opening-row">
                                    <td colspan="10" class="text-end">Opening Balance</td>
                                    <td>${openingLabel}</td>
                                </tr>`;

                        const transactions = Object.keys(response.data)
                            .filter(key => !isNaN(key))
                            .map(key => response.data[key]);
                        if (transactions.length) {
                            transactions.forEach(function(item) {
                                const details = [item.account_details, item.remark]
                                    .filter(value => value)
                                    .map(value => escapeHtml(value))
                                    .join('<br>');
                                rows += `
                                        <tr>
                                            <td>${escapeHtml(item.date)}</td>
                                            <td>${escapeHtml(item.voucher_number)}</td>
                                            <td>${escapeHtml(item.account_name)}</td>
                                            <td>${details || '—'}</td>
                                            <td>${escapeHtml(item.reference_number)}</td>
                                            <td>${escapeHtml(item.payment_method)}</td>
                                            <td class="text-end">${item.debit > 0 ? `₹${formatAmount(item.debit)}` : '—'}</td>
                                            <td class="text-end">${item.credit > 0 ? `₹${formatAmount(item.credit)}` : '—'}</td>
                                            <td class="text-end">${item.due > 0 ? `₹${formatAmount(item.due)}` : '—'}</td>
                                            <td>${escapeHtml(item.gst_no) || '—'}</td>
                                            <td class="text-end">₹${formatAmount(item.balance)} ${escapeHtml(item.balance_type)}</td>
                                        </tr>
                                    `;
                            });
                        } else {
                            rows += `<tr><td colspan="11"><div class="admin-workflow-empty">
                                <i class="bi bi-inbox" aria-hidden="true"></i>
                                No ledger transactions match this date range.
                            </div></td></tr>`;
                        }

                        rows += `<tr class="closing-row">
                                    <td colspan="6" class="text-end"><strong>Period Total / Closing Balance</strong></td>
                                    <td class="text-end"><strong>₹${formatAmount(data.total_debit)}</strong></td>
                                    <td class="text-end"><strong>₹${formatAmount(data.total_credit)}</strong></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-end"><strong>${closingLabel}</strong></td>
                                </tr>`;

                        const pdfUrl =
                            `{{ route('admin.ledger.download-pdf') }}?pos_id=${encodeURIComponent(requestedPosId)}&from_date=${encodeURIComponent(fromDate)}&to_date=${encodeURIComponent(toDate)}`;
                        $('#download-pdf-btn').attr('href', pdfUrl).prop('hidden', false);
                        $('#export-pos-id').val(requestedPosId);
                        $('#export-from-date').val(fromDate);
                        $('#export-to-date').val(toDate);
                        $('#ledger-export-form').prop('hidden', false);
                        $('#ldg-tbl-bdy').html(rows);

                    },
                    error: function(xhr) {
                        $('#tbl-div, #download-pdf-btn, #ledger-export-form').prop('hidden', true);
                        const message = xhr.responseJSON?.message
                            || xhr.responseJSON?.errors?.pos_id?.[0]
                            || 'Unable to load the ledger. Please try again.';
                        Swal.fire({ icon: 'error', title: 'Ledger unavailable', text: message });
                    },
                    complete: function() {
                        hideFormLoader();
                    }
                });
            }

            // Filter submit
            $('#ledger-form').on('submit', function(e) {
                e.preventDefault();
                if (!resolveSelectedPos()) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Select a POS',
                        text: 'Choose a POS from the search suggestions before viewing its ledger.'
                    });
                    $posSearch.trigger('focus');
                    return;
                }
                loadLedger(1, this);
            });
            $posSearch.on('input change', resolveSelectedPos);
            $('#reset-ledger-filters').on('click', function() {
                $('#from_date, #to_date').val('');
                if ($posId.val()) {
                    loadLedger(1, $('#ledger-form')[0]);
                }
            });

        });
    </script>
@endsection
