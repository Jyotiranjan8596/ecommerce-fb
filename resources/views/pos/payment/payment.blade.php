@extends('pos.layouts.master')

@section('content')
    <div class="container-fluid px-2 px-md-3 py-3 pay-page">
        <!-- Loader Overlay -->
        <div id="form-loader-overlay">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        {{-- Hero header --}}
        <section class="dashboard-hero mb-4">
            <span class="dashboard-hero__label"><i class="fa fa-store me-1" aria-hidden="true"></i> POS overview</span>
            <h1 class="dashboard-hero__title">Payment</h1>
            <p class="dashboard-hero__text">Look up a day's transactions and settle the pending dues for your store.
                POS dues are automatically submitted at next-day noon after all transactions and payment summaries are
                complete.</p>
        </section>

        @if (session('current_balance'))
            <div class="pay-alert-success mb-4" role="alert">
                <i class="fa fa-check-circle me-2" aria-hidden="true"></i>
                Current Wallet Balance: <strong>₹{{ number_format(session('current_balance'), 2) }}/-</strong>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-6">
                <section class="card border-0 shadow-lg rounded-4 overflow-hidden h-100 pay-card">
                    <div class="card-body p-3 p-lg-4">

                        {{-- POS Lookup --}}
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="pay-section-icon"><i class="fa fa-store" aria-hidden="true"></i></span>
                            <h2 class="h6 mb-0 fw-bold text-dark">Find transactions</h2>
                        </div>

                        <form id="pos_detail_form" class="mb-4">
                            @csrf
                            <div class="row g-2 align-items-end">
                                <div class="col-md-9">
                                    <label for="trans_date" class="pay-field-label">Transaction date</label>
                                    <input type="hidden" name="pos_id" value="{{ $userId }}">
                                    <input type="date" name="transaction_date" id="trans_date" class="form-control"
                                        value="{{ old('transaction_date', now()->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn pay-btn-primary w-100">
                                        <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <hr class="my-4">

                        {{-- Wallet Header --}}
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="pay-section-icon"><i class="fa fa-wallet" aria-hidden="true"></i></span>
                            <h2 class="h6 mb-0 fw-bold text-dark">Manage payment</h2>
                        </div>
                        <p class="pay-subtext mb-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <span>Payment info for <span class="text-primary fw-semibold"
                                    id="pos_name">{{ $name }}</span></span>
                            <span id="cradit-amount-span-one" hidden class="fw-semibold">Credit Amount: <span
                                    id="cradit-amount-span-two" class="text-success">₹0.00</span></span>
                            <span id="cradit-amount-span-three" hidden>--</span>
                        </p>

                        {{-- Wallet Form --}}
                        <form id="payment_submit_form">
                            @csrf
                            <input type="hidden" name="user_id" value="">
                            <input id="pos_id" type="hidden" name="pos_id" value="">
                            <input type="hidden" name="mobilenumber" value="">
                            <input id="summary_date" type="hidden" name="summary_date" value="">
                            <input type="hidden" name="transaction_date" value="{{ now()->format('Y-m-d') }}">
                            <input type="hidden" name="is_pos" value="1">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="amount" class="pay-field-label">Billing amount</label>
                                    <div class="input-group pay-input-group">
                                        <span class="input-group-text"><i class="fa fa-rupee-sign"
                                                aria-hidden="true"></i></span>
                                        <input readonly name="amount" id="amount" type="number" class="form-control"
                                            required min="0" step="any" placeholder="0.00"
                                            oninput="checkWalletBalance()">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="pay_by" class="pay-field-label">Payment by</label>
                                    <select disabled id="pay_by" class="form-select">
                                        <option value="wallet">WALLET</option>
                                        <option value="cash">CASH</option>
                                        <option selected value="upi">UPI</option>
                                    </select>
                                    <input type="hidden" name="pay_by" value="1">
                                </div>

                                <div class="col-md-6">
                                    <label for="paying_amount" class="pay-field-label">Paying amount</label>
                                    <div class="input-group pay-input-group">
                                        <span class="input-group-text"><i class="fa fa-rupee-sign"
                                                aria-hidden="true"></i></span>
                                        <input readonly name="paying_amount" id="paying_amount" type="number"
                                            class="form-control" required min="0" placeholder="0.00">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="pay-field-label d-block">Due</label>
                                    <div class="pay-due-box">
                                        <span id="due" class="pay-due-amount">₹0.00</span>
                                        <i class="fa fa-wallet" aria-hidden="true"></i>
                                    </div>
                                    <input id="due_balance" type="hidden" name="due" value="">
                                </div>
                                <div class="col-md-6">
                                    <label for="reference_number" class="pay-field-label">Reference number</label>
                                    <div class="input-group pay-input-group">
                                        <span class="input-group-text"><i class="fa fa-barcode"
                                                aria-hidden="true"></i></span>
                                        <input readonly name="reference_number" id="reference_number" type="text"
                                            class="form-control" required min="0" placeholder="Reference Number">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="remark" class="pay-field-label">Remark</label>
                                    <textarea name="remark" id="remark" class="form-control" rows="2"
                                        placeholder="Add a note (optional)"></textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn pay-btn-primary w-100 mt-4">
                                <i class="fa fa-check-circle me-1" aria-hidden="true"></i> Submit
                            </button>
                        </form>
                    </div>
                </section>
            </div>

            <div class="col-lg-6">
                <section class="card border-0 shadow-lg rounded-4 overflow-hidden h-100 pay-card">
                    <div class="card-header border-0 bg-white px-3 px-md-4 pt-4 pb-3">
                        <h2 class="h6 mb-0 fw-bold text-dark text-uppercase">Transactions</h2>
                    </div>
                    <div class="card-body px-3 px-md-4 pt-2 pb-3">
                        <div class="table-responsive pay-table-wrap" tabindex="0"
                            aria-label="Scrollable transactions">
                            <table id="table-1" class="table align-middle mb-0 pay-table">
                                <thead>
                                    <tr>
                                        <th>Sl.No</th>
                                        <th>Invoice</th>
                                        <th>Name</th>
                                        <th>Bill amount</th>
                                        <th>By wallet</th>
                                        <th>By reward</th>
                                        <th>Net pay</th>
                                        <th>TC</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody id="transaction-tbody">
                                    <tr>
                                        <td colspan="9">
                                            <div class="pay-empty-state">
                                                <span class="pay-empty-icon"><i class="fa fa-calendar-day"
                                                        aria-hidden="true"></i></span>
                                                <p class="mb-0">Select a date to view the transactions.</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <style>
        .pay-page {
            color: #172033;
        }

        .pay-alert-success {
            display: flex;
            align-items: center;
            padding: .9rem 1.1rem;
            border: 1px solid #c9ebe0;
            border-radius: .85rem;
            background: #e8f5f1;
            color: #08735f;
            font-weight: 650;
        }

        .pay-page .pay-section-icon {
            display: grid;
            width: 2.1rem;
            height: 2.1rem;
            place-items: center;
            border-radius: 50%;
            background: #edf3fb;
            color: #145da0;
            font-size: .9rem;
        }

        .pay-page .pay-field-label {
            display: block;
            margin-bottom: .35rem;
            color: #536477;
            font-size: .78rem;
            font-weight: 750;
            letter-spacing: .03em;
            text-transform: uppercase;
        }

        .pay-page .pay-subtext {
            color: #66768a;
            font-size: .88rem;
        }

        .pay-page .pay-input-group .input-group-text {
            border-right: 0;
            background: #fff;
            color: #94a3b8;
        }

        .pay-page .pay-input-group .form-control {
            border-left: 0;
        }

        .pay-page .pay-input-group .form-control:focus {
            box-shadow: none;
            border-color: #cbd5e1;
        }

        .pay-page .pay-due-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .55rem .9rem;
            border: 1px solid #f3d6d2;
            border-radius: .7rem;
            background: #fff5f3;
            color: #c0321f;
        }

        .pay-page .pay-due-amount {
            font-weight: 800;
        }

        .pay-page .btn.pay-btn-primary {
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

        .pay-page .btn.pay-btn-primary:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 .7rem 1.3rem rgba(30, 64, 175, .24);
        }

        .pay-page .pay-table-wrap {
            max-height: 420px;
            overflow-x: auto;
            overflow-y: auto;
            border: 1px solid #e4ebf1;
            border-radius: .85rem;
            scrollbar-color: #9abfc0 #f0f5f6;
            scrollbar-width: thin;
        }

        .pay-page .pay-table-wrap::-webkit-scrollbar {
            width: .55rem;
            height: .55rem;
        }

        .pay-page .pay-table-wrap::-webkit-scrollbar-thumb {
            border: 2px solid #f0f5f6;
            border-radius: 999px;
            background: #9abfc0;
        }

        .pay-page .pay-table-wrap::-webkit-scrollbar-track {
            background: #f0f5f6;
        }

        .pay-page .pay-table {
            min-width: 920px;
            color: #263449;
            /* The global .table rule sets overflow: hidden, which makes the table
               itself (not the scrollable .pay-table-wrap) the sticky positioning
               context, so the sticky header would scroll away with the rows
               instead of staying fixed. Restore normal overflow here so sticky
               headers stick relative to the scrollable wrapper. */
            overflow: visible;
        }

        .pay-page .pay-table thead th {
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

        .pay-page .pay-table tbody td {
            padding: .8rem 1rem;
            border-color: #edf1f5;
            font-size: .86rem;
            vertical-align: middle;
        }

        .pay-page .pay-table tbody tr:hover {
            background: #f8fbfc;
        }

        @media (max-width: 767.98px) {
            .pay-page .pay-table thead {
                display: none;
            }

            .pay-page .pay-table,
            .pay-page .pay-table tbody,
            .pay-page .pay-table tr,
            .pay-page .pay-table td {
                display: block;
                width: 100%;
                min-width: 0;
            }

            .pay-page .pay-table tr {
                margin-bottom: .75rem;
                padding: .5rem .25rem;
                border: 1px solid #e4ebf1;
                border-radius: .75rem;
            }

            .pay-page .pay-table td {
                display: flex;
                justify-content: space-between;
                gap: .75rem;
                padding: .45rem .75rem;
                border: 0;
                text-align: right;
            }

            .pay-page .pay-table td::before {
                content: attr(data-label);
                font-weight: 750;
                color: #536477;
                text-transform: uppercase;
                font-size: .7rem;
                letter-spacing: .03em;
                text-align: left;
            }
        }

        .pay-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .5rem;
            padding: 2rem 1rem;
            color: #66768a;
            text-align: center;
        }

        .pay-empty-icon {
            display: grid;
            width: 2.6rem;
            height: 2.6rem;
            place-items: center;
            border-radius: 50%;
            background: #edf5f6;
            color: #087f8c;
            font-size: 1rem;
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
        function showFormLoader() {
            $('#form-loader-overlay').css('display', 'flex');
        }

        function hideFormLoader() {
            $('#form-loader-overlay').css('display', 'none');
        }

        $(document).ready(function() {
            // getPosTransactions();
            let check_debit_amount = 0;
            $('#summary_date').val($('#trans_date').val());

            $('#trans_date').on('change', function() {
                $('#summary_date').val($(this).val());
            });
            // Show loader for the plain GET "Search Transactions" form (page will reload/navigate)
            $('form[method="GET"]').on('submit', function() {
                showFormLoader();
            });

            $('#pos_detail_form').on('submit', function(event) {
                event.preventDefault();
                getPosTransactions();
            });

            // Note: this form had no existing submit handler in the original file,
            // so we only show the loader here (no new AJAX logic added).
            function getPosTransactions() {
                showFormLoader();
                var form = $('#pos_detail_form')[0];
                var formData = new FormData(form);
                $.ajax({
                    type: "POST",
                    url: "{{ route('pos.get.payment.details') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",

                    success: function(res) {

                        let trows = '';
                        $('#amount').val('');
                        $('#paying_amount').val('');
                        $('#pos_id').val('');
                        $('#reference_number').val('');
                        $('#cradit-amount-span-one').attr('hidden', true);
                        $('#cradit-amount-span-three').attr('hidden', true).removeClass();
                        $('#reference_number').attr('readonly', true);
                        $('#remark').removeAttr('readonly');
                        $('#payment_submit_form button[type="submit"]').prop('disabled', false);
                        if (res.success) {
                            if (res.data.length > 0) {

                                $.each(res.data, function(index, item) {

                                    trows += `<tr>
                                                    <td data-label="Sl.No">${index + 1}</td>
                                                    <td data-label="Invoice">${item.invoice ?? '-'}</td>
                                                    <td data-label="Name">${item.name ?? '-'}</td>
                                                    <td data-label="Bill amount">${item.billing_amount ?? '-'}</td>
                                                    <td data-label="By wallet">${item.wallet_deduct ?? '-'}</td>
                                                    <td data-label="By reward">${item.reward_deduct ?? '-'}</td>
                                                    <td data-label="Net pay">${item.net_pay ?? '-'}</td>
                                                    <td data-label="TC">${item.transaction_charge ?? '-'}</td>
                                                    <td data-label="Date">${item.transaction_date ?? '-'}</td>
                                                </tr>
                                            `;
                                });

                            } else {
                                trows = `<tr>
                                                <td colspan="9" class="text-center">
                                                    No Transaction Found!
                                                </td>
                                            </tr>
                                        `;
                            }

                            $('#transaction-tbody').html(trows);
                            console.log(res.payment_data == null && res.data != []);
                            if (res.payment_data == null && res.data != []) {
                                // $('#settlement_status').removeAttr('hidden');
                                // return;
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Payment Summary Unavailable',
                                    text: 'The payment summary has not been generated yet. Please try again after some time.',
                                    confirmButtonText: 'OK'
                                });
                                return;
                            }
                            let payment_data = res.payment_data ?? {};
                            console.log(typeof payment_data);
                            const adminLedgerEntry = (payment_data.payment ?? []).find(
                                item => item.remark === 'Auto Generated'
                            );
                            $('#reference_number').val(adminLedgerEntry?.reference_number ?? '');
                            if (payment_data.status == 'approved') {
                                $('#cradit-amount-span-three').removeAttr('hidden').text(
                                        'Settled')
                                    .addClass('text-success');
                                $('#payment_submit_form button[type="submit"]').prop('disabled', true);
                                return;
                            }
                            if ((payment_data.admin_credit ?? 0) > 0 &&
                                (payment_data.pos_payment_remaining ?? payment_data.admin_credit) <= 0) {
                                $('#cradit-amount-span-three').removeAttr('hidden').text(
                                    'Submitted — awaiting admin settlement'
                                ).addClass('text-info');
                                $('#payment_submit_form button[type="submit"]').prop('disabled', true);
                                return;
                            }
                            if (Object.keys(payment_data).length > 0) {
                                let payment = payment_data.payment?.[0];
                                check_debit_amount = payment_data.pos_payment_remaining ??
                                    payment_data.pos_debit ?? 0;
                                $('#amount').val(payment_data.total_billing_amount ?? '');
                                $('#paying_amount').val(check_debit_amount);
                                $('#pos_id').val(payment_data.pos_id ?? '');
                                $('#due').text(res.total_credit);
                                $('#due-text').text(res.balance_type);
                                if ((payment_data.pos_credit ?? 0) > 0) {
                                    $('#cradit-amount-span-one').removeAttr('hidden');
                                    $('#remark').attr('readonly', true);

                                    $('#cradit-amount-span-two').text(
                                        '₹' + parseFloat(payment_data.pos_credit).toFixed(2)
                                    );
                                } else {
                                    $('#cradit-amount-span-one').attr('hidden', true);
                                    $('#remark').removeAttr('readonly');
                                }
                            } else {
                                console.log('Testing');
                            }


                        } else {

                            $('#transaction-tbody').html(`
                                <tr>
                                    <td colspan="9" class="text-center">
                                        No Transaction Found!
                                    </td>
                                </tr>
                                `);
                            hideFormLoader();
                        }
                    },

                    error: function(xhr) {
                        console.log(xhr.responseText);
                    },

                    complete: function() {
                        hideFormLoader();
                    }
                });
            }

            $('#payment_submit_form').on('submit', function(event) {
                event.preventDefault();
                showFormLoader();
                console.log(check_debit_amount);
                if (check_debit_amount == 0) {
                    hideFormLoader(); // Hide the loader before showing the alert

                    Swal.fire({
                        icon: 'info',
                        title: 'No Pending Amount',
                        text: 'There is no pending amount.',
                        confirmButtonText: 'OK'
                    });

                    return;
                }
                var formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('pos.create.payment') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(res) {
                        console.log(res);
                        hideFormLoader();
                        let trows = '';
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Payment created successfully.',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error', // not 'danger'
                                title: 'Failed',
                                text: res.message || 'Payment creation failed.',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        hideFormLoader();

                        const response = xhr.responseJSON || {};
                        const validationMessages = Object.values(response.errors || {}).flat();
                        const message = validationMessages[0] || response.message ||
                            (xhr.status >= 500
                                ? 'The server could not complete the payment. Please try again.'
                                : 'The payment request was rejected. Please check the payment details and try again.');

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message,
                            confirmButtonText: 'OK'
                        });

                        console.log(xhr.responseText);
                    }
                });
                // Continue with your AJAX request or form submission...
            });
        });
    </script>
@endsection
