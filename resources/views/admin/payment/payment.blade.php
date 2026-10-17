@extends('layouts.master')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
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

@section('content')
    <div class="container-fluid py-3 admin-workflow-page admin-payment-page">
    <!-- Loader Overlay -->
    <div id="form-loader-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <section class="admin-workflow-hero">
        <div>
            <span class="admin-workflow-hero__eyebrow">Settlement operations</span>
            <h1>Payment</h1>
            <p>Review POS transactions and record settlement payments with clear balances.</p>
        </div>
        <span class="admin-workflow-hero__icon" aria-hidden="true"><i class="bi bi-cash-stack"></i></span>
    </section>

    <div class="admin-payment-grid">
        <div class="col-lg-6 mb-4">
            <div class="card-box admin-workflow-card p-4">

                <!-- POS Lookup -->
                <div class="admin-payment-section-heading">
                    <span class="admin-payment-section-icon"><i class="bi bi-shop"></i></span>
                    <div>
                        <h2 class="mb-0">Find POS</h2>
                        <p class="mb-0">Choose a POS and transaction date to review its settlement.</p>
                    </div>
                </div>

                <form id="pos_detail_form" class="mb-4">
                    @csrf
                    <div class="row g-2 align-items-end">
                        <div class="col-md-5">
                            <label for="myid" class="form-label small fw-semibold text-muted">POS ID</label>
                            <input required name="pos_id" type="text" id="myid" class="form-control"
                                placeholder="Enter POS ID">
                        </div>
                        <div class="col-md-5">
                            <label for="trans_date" class="form-label small fw-semibold text-muted">
                                TRANSACTION DATE
                            </label>
                            <input type="date" name="transaction_date" id="trans_date" class="form-control"
                                value="{{ old('transaction_date', now()->subDay()->format('Y-m-d')) }}"
                                max="{{ now()->subDay()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100 hover-blue" aria-label="Find POS">
                                <i class="bi bi-search me-1"></i><span>Find</span>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="admin-payment-section-heading admin-payment-section-heading--payment">
                    <span class="admin-payment-section-icon"><i class="bi bi-wallet2"></i></span>
                    <div>
                        <h2 class="mb-0">Manage Payment</h2>
                        <p class="mb-0">Payment information for <span class="text-danger fw-semibold" id="pos_name">—</span></p>
                    </div>
                </div>

                <div class="admin-payment-status mb-3">
                    <div class="admin-payment-status__amounts">
                        <span id="cradit-amount-span-one" hidden class="fw-semibold">
                            Credit Amount:
                            <span id="cradit-amount-span-two" class="text-success">₹0.00</span>
                        </span>

                        <span hidden id="debit-amount-span-one" class="fw-semibold">
                            Debit Amount:
                            <span id="debit-amount-span-two" class="text-danger">₹0.00</span>
                        </span>
                        <span hidden id="settlement_status"
                            class="fw-semibold text-success d-flex align-items-center gap-2">
                        </span>
                    </div>
                    <div class="admin-payment-status__payment">
                        <span hidden id="payment-span" class="fw-semibold">
                            <span id="payment_status">- -</span>
                            <button id="reminder-btn" hidden type="button" class="btn btn-sm px-3 py-1 rounded-pill">
                                <i class="bi bi-bell me-1"></i>
                            </button>
                        </span>
                    </div>
                </div>

                <div class="border rounded-3 p-3 mb-4 bg-light">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div>
                            <h3 class="h6 mb-1">Verified POS payment summaries</h3>
                            <p class="text-muted small mb-0">Generate or review summaries for every POS on the selected transaction date.</p>
                        </div>
                        <button type="button" id="generate-pos-summaries" class="btn btn-outline-primary btn-sm fw-semibold">
                            <i class="bi bi-calculator me-1"></i>Generate summaries
                        </button>
                    </div>
                    <div id="pos-payment-summary-message" class="small text-muted mb-2" role="status" aria-live="polite"></div>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0 bg-white">
                            <thead>
                                <tr>
                                    <th>POS</th>
                                    <th class="text-end">Transactions</th>
                                    <th class="text-end">Billing</th>
                                    <th class="text-end">Cash/UPI</th>
                                    <th class="text-end">Wallet</th>
                                    <th class="text-end">Reward</th>
                                    <th class="text-end">Credit</th>
                                    <th class="text-end">Debit</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="pos-payment-summary-tbody">
                                <tr><td colspan="9" class="text-center text-muted">Select a date and generate summaries.</td></tr>
                            </tbody>
                            <tfoot id="pos-payment-summary-tfoot" hidden></tfoot>
                        </table>
                    </div>
                </div>

                <!-- Wallet Form -->
                <form id="payment_submit_form" class="admin-payment-form">
                    @csrf
                    <input type="hidden" name="user_id" value="">
                    <input id="pos_id" type="hidden" name="pos_id" value="">
                    <input type="hidden" name="mobilenumber" value="">
                    <input type="hidden" name="transaction_date" value="{{ now()->format('Y-m-d') }}">
                    <input id="summary_date" type="hidden" name="summary_date" value="">
                    <input id="is_pos" type="hidden" name="is_pos" value="0">

                    <div class="row g-3 admin-payment-form-grid">
                        <div class="col-md-6">
                            <label for="amount" class="form-label fw-semibold">BILLING AMOUNT</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-currency-rupee"></i></span>
                                <input name="amount" id="amount" type="number" class="form-control" required
                                    min="0" step="any" placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="pay_by" class="form-label fw-semibold">PAYMENT BY</label>
                            <select name="pay_by" id="pay_by" class="form-select" required>
                                {{-- <option value="wallet">WALLET</option> --}}
                                <option selected value="1">UPI</option>
                                <option value="0">CASH</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="paying_amount" class="form-label fw-semibold">PAYING AMOUNT</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-currency-rupee"></i></span>
                                <input name="paying_amount" id="paying_amount" type="number" class="form-control"
                                    required step="0.01" min="0.01" placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="due" class="form-label fw-semibold">DUE</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-currency-rupee"></i></span>
                                <input id="due_balance" type="number" name="due" value="" 
                                    class="form-control text-danger" step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="reference_number" class="form-label fw-semibold">Reference Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                                <input name="reference_number" id="reference_number" type="text" class="form-control"
                                    required min="0" placeholder="Reference Number">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="remark" class="form-label fw-semibold">REMARK</label>
                            <textarea name="remark" id="remark" class="form-control" rows="2" placeholder="Add a note (optional)"></textarea>
                        </div>
                    </div>

                    <!-- Insufficient Balance Alert -->
                    {{-- <div class="insufficient-balance alert alert-warning mt-3 d-flex align-items-center gap-2"
                        style="display: none;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div class="flex-grow-1">
                            <strong>Wallet balance is insufficient.</strong> Please choose an alternative payment method:
                            <select name="alternative_pay_by" id="alternative_pay_by" class="form-select mt-2" required>
                                <option value="cash">CASH</option>
                                <option value="upi">UPI</option>
                            </select>
                        </div>
                    </div> --}}
                    <button id="submit-payment" type="submit" class="btn btn-primary w-100 mt-4 py-2 fw-semibold" disabled>
                        <i class="bi bi-check-circle me-1"></i> SUBMIT
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card-box admin-workflow-card p-4">

                <!-- Tab Nav -->
                <ul class="nav nav-tabs admin-payment-tabs" id="posTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="transactions-tab" data-bs-toggle="tab"
                            data-bs-target="#transactions-pane" type="button" role="tab"
                            aria-controls="transactions-pane" aria-selected="true">
                            TRANSACTIONS
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="allpos-tab" data-bs-toggle="tab" data-bs-target="#allpos-pane"
                            type="button" role="tab" aria-controls="allpos-pane" aria-selected="false">
                            ALL POS
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-3" id="posTabContent">

                    <!-- Transactions Tab -->
                    <div class="tab-pane fade show active" id="transactions-pane" role="tabpanel"
                        aria-labelledby="transactions-tab">

                        <!-- Transactions Table with Scrolling -->
                        <div class="scrollable-table admin-payment-table-wrap">
                            <table id="tech-companies-1" class="table table-striped table-bordered admin-workflow-table">
                                <thead>
                                    <tr>
                                        <th>Sl.No</th>
                                        <th>INVOICE</th>
                                        <th>MOBILE</th>
                                        <th>NAME</th>
                                        <th>BILLING AMOUNT</th>
                                        <th>Wallet Deduct</th>
                                        <th>Reward Deduct</th>
                                        <th>Net Pay</th>
                                        <th>Remaining Wallet</th>
                                        <th>Remaining Reward</th>
                                        <th>TRANSACTION DATE</th>
                                    </tr>
                                </thead>
                                <tbody id="transaction-tbody">
                                    <tr>
                                        <td colspan="11" class="text-center">
                                            <div class="alert alert-danger" role="alert">
                                                Please Select POS and Date to view transactions
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- All POS Tab -->
                    <div class="tab-pane fade" id="allpos-pane" role="tabpanel" aria-labelledby="allpos-tab">

                        <!-- Demo Table for All POS -->
                        <div class="scrollable-table admin-payment-table-wrap">
                            <table id="all-pos-table" class="table table-striped table-bordered admin-workflow-table">
                                <thead>
                                    <tr>
                                        <th>Sl No.</th>
                                        <th>NAME</th>
                                        <th>POS ID</th>
                                        <th>City</th>
                                        <th>Email</th>
                                        <th>MobileNumber</th>
                                        <th>Transaction %</th>
                                    </tr>
                                </thead>
                                <tbody id="posTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function showFormLoader() {
            $('#form-loader-overlay').css('display', 'flex');
        }

        function hideFormLoader() {
            $('#form-loader-overlay').css('display', 'none');
        }

        $(document).ready(function() {
            const escapeHtml = value => $('<div>').text(value ?? '').html();
            getAllPos();
            let check_credit_amount = 0;
            let paying_amount = 550;
            $('#summary_date').val($('#trans_date').val());

            $('#trans_date').on('change', function() {
                $('#summary_date').val($(this).val());
                $('#pos-payment-summary-message').text('');
                $('#pos-payment-summary-tbody').html('<tr><td colspan="9" class="text-center text-muted">Date changed. Generate summaries to refresh the results.</td></tr>');
                $('#pos-payment-summary-tfoot').prop('hidden', true).empty();
            });

            $('#generate-pos-summaries').on('click', function() {
                const button = $(this);
                const date = $('#trans_date').val();
                if (!date) {
                    $('#pos-payment-summary-message').text('Choose a transaction date first.').addClass('text-danger');
                    return;
                }

                button.prop('disabled', true);
                $('#pos-payment-summary-message').removeClass('text-danger text-success').text('Generating verified transaction summaries…');
                $('#pos-payment-summary-tbody').html('<tr><td colspan="9" class="text-center"><span class="spinner-border spinner-border-sm me-2" role="status"></span>Loading…</td></tr>');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.payment.generate.pos.summaries') }}",
                    data: { transaction_date: date },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        const rows = response.summaries || [];
                        if (!rows.length) {
                            $('#pos-payment-summary-tbody').html('<tr><td colspan="9" class="text-center text-muted">No verified transactions found for this date.</td></tr>');
                            $('#pos-payment-summary-tfoot').prop('hidden', true).empty();
                            $('#pos-payment-summary-message').removeClass('text-danger').addClass('text-success').text(response.message);
                            return;
                        }

                        const amount = value => `₹${Number(value || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                        let totals = { transactions: 0, billing: 0, cash: 0, wallet: 0, reward: 0, credit: 0, debit: 0 };
                        const html = rows.map(item => {
                            totals.transactions += Number(item.total_transactions || 0);
                            totals.billing += Number(item.total_billing_amount || 0);
                            totals.cash += Number(item.by_cash || 0);
                            totals.wallet += Number(item.by_wallet || 0);
                            totals.reward += Number(item.by_reward || 0);
                            totals.credit += Number(item.pos_credit || 0);
                            totals.debit += Number(item.pos_debit || 0);
                            return `<tr>
                                <td>${escapeHtml(item.pos_name)} <span class="text-muted small">(${escapeHtml(item.pos_user_id || item.pos_id)})</span></td>
                                <td class="text-end">${Number(item.total_transactions || 0)}</td>
                                <td class="text-end">${amount(item.total_billing_amount)}</td>
                                <td class="text-end">${amount(item.by_cash)}</td>
                                <td class="text-end">${amount(item.by_wallet)}</td>
                                <td class="text-end">${amount(item.by_reward)}</td>
                                <td class="text-end">${amount(item.pos_credit)}</td>
                                <td class="text-end">${amount(item.pos_debit)}</td>
                                <td>${escapeHtml(item.status)}<small class="d-block text-muted">Changed by ${escapeHtml(item.status_actor || 'History unavailable')}</small></td>
                            </tr>`;
                        }).join('');
                        $('#pos-payment-summary-tbody').html(html);
                        $('#pos-payment-summary-tfoot').html(`<tr class="fw-bold">
                            <td>Total</td>
                            <td class="text-end">${totals.transactions}</td>
                            <td class="text-end">${amount(totals.billing)}</td>
                            <td class="text-end">${amount(totals.cash)}</td>
                            <td class="text-end">${amount(totals.wallet)}</td>
                            <td class="text-end">${amount(totals.reward)}</td>
                            <td class="text-end">${amount(totals.credit)}</td>
                            <td class="text-end">${amount(totals.debit)}</td>
                            <td></td>
                        </tr>`).prop('hidden', false);
                        $('#pos-payment-summary-message').removeClass('text-danger').addClass('text-success').text(
                            `${response.message} ${response.created_count} created, ${response.existing_count} already existed.`
                        );
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON || {};
                        $('#pos-payment-summary-message').removeClass('text-success').addClass('text-danger')
                            .text(response.message || 'Unable to generate POS payment summaries.');
                        if (response.summaries) {
                            $('#pos-payment-summary-tbody').html(response.summaries.map(item => `<tr>
                                <td>${escapeHtml(item.pos_name)} <span class="text-muted small">(${escapeHtml(item.pos_user_id || item.pos_id)})</span></td>
                                <td class="text-end">${Number(item.total_transactions || 0)}</td>
                                <td class="text-end">₹${Number(item.total_billing_amount || 0).toFixed(2)}</td>
                                <td class="text-end">₹${Number(item.by_cash || 0).toFixed(2)}</td>
                                <td class="text-end">₹${Number(item.by_wallet || 0).toFixed(2)}</td>
                                <td class="text-end">₹${Number(item.by_reward || 0).toFixed(2)}</td>
                                <td class="text-end">₹${Number(item.pos_credit || 0).toFixed(2)}</td>
                                <td class="text-end">₹${Number(item.pos_debit || 0).toFixed(2)}</td>
                                <td>${escapeHtml(item.status)}<small class="d-block text-muted">Changed by ${escapeHtml(item.status_actor || 'History unavailable')}</small></td>
                            </tr>`).join(''));
                        } else {
                            $('#pos-payment-summary-tbody').html('<tr><td colspan="9" class="text-center text-danger">Summary generation failed.</td></tr>');
                        }
                    },
                    complete: function() {
                        button.prop('disabled', false);
                    }
                });
            });
            // Show loader for the plain GET "Search Transactions" form (page will reload/navigate)
            $('form[method="GET"]').on('submit', function() {
                showFormLoader();
            });

            $('#pos_detail_form').on('submit', function(event) {
                event.preventDefault();
                const lookupButton = $(this).find('button[type="submit"]');
                lookupButton.prop('disabled', true);
                showFormLoader();
                $('#transaction-tbody').html(`<tr><td colspan="11" class="text-center py-4">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Loading transactions…
                </td></tr>`);
                $('#pos_name').text('—');
                var formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.get.pos.payment.details') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(res) {
                        let trows = '';
                        $('#submit-payment').prop('disabled', true);
                        $('#amount').val('');
                        $('#paying_amount').val('');
                        $('#pos_id').val('');
                        $('#reference_number').val('');
                        $('#paying_amount').removeAttr('max');
                        $('#due_balance').val('');
                        $('#cradit-amount-span-one').attr('hidden', true);
                        $('#payment-span').attr('hidden', true);
                        $('#reminder-btn').attr('hidden', true);
                        $('#settlement_status').attr('hidden', true);
                        $('#debit-amount-span-one').attr('hidden', true);
                        $('#submit-payment').prop('disabled', true);
                        $('#reference_number').removeAttr('readonly');
                        $('#remark').removeAttr('readonly');
                        $('#payment_status').removeClass();
                        if (res.success) {
                            $.each(res.data, function(index, item) {
                                trows += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${escapeHtml(item.invoice ?? '-')}</td>
                                        <td>${escapeHtml(item.mobile ?? '-')}</td>
                                        <td>${escapeHtml(item.name ?? '-')}</td>
                                        <td>${escapeHtml(item.billing_amount ?? '-')}</td>
                                        <td>${escapeHtml(item.wallet_deduct ?? '-')}</td>
                                        <td>${escapeHtml(item.reward_deduct ?? '-')}</td>
                                        <td>${escapeHtml(item.net_pay ?? '-')}</td>
                                        <td>${escapeHtml(item.remaining_wallet ?? '-')}</td>
                                        <td>${escapeHtml(item.remaining_reward ?? '-')}</td>
                                        <td>${escapeHtml(item.transaction_date ?? '-')}</td>
                                    </tr>
                                `;
                            });
                            $('#transaction-tbody').html(trows);
                            if (!res.payment_data) {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Payment Summary Unavailable',
                                    text: 'The payment summary has not been generated yet. Please try again after some time.',
                                    confirmButtonText: 'OK'
                                });
                                return;
                            }
                            let payment_data = res.payment_data;
                            $('#pos_name').text(payment_data.pos_system.name);
                            $('#amount').val(payment_data.total_billing_amount);
                            $('#pos_id').val(payment_data.pos_id);
                            if (payment_data.status == 'approved') {
                                $('#settlement_status').removeAttr('hidden');
                                $('#settlement_status').text('Settled');
                                return;
                            }
                            const adminCredit = Number(payment_data.admin_credit) || 0;
                            const adminDebit = Number(payment_data.admin_debit) || 0;
                            if (adminCredit > 0) {
                                const actualPayments = (payment_data.payment || []).filter(payment =>
                                    String(payment.remark || '').toLowerCase() !== 'auto generated'
                                );
                                const paidCents = actualPayments.reduce((total, payment) =>
                                    total + Math.round(Number(payment.amount || 0) * 100), 0
                                );
                                const remainingCents = Math.max(0, Math.round(adminCredit * 100) - paidCents);
                                const remaining = remainingCents / 100;
                                $('#is_pos').val('1');
                                $('#paying_amount').val(remaining.toFixed(2)).attr('max', remaining.toFixed(2));
                                paying_amount = remaining;
                                check_credit_amount = remaining;
                                $('#due_balance').val('0.00');
                                $('#submit-payment').prop('disabled', remainingCents === 0);
                                $('#cradit-amount-span-one').removeAttr('hidden');
                                $('#remark').attr('readonly', false);
                                $('#cradit-amount-span-two').text(remaining.toFixed(2));
                                $('#reference_number').val('');

                                if (remainingCents === 0) {
                                    $('#payment-span').removeAttr('hidden');
                                    $('#payment_status').text('Fully paid; awaiting verification').addClass('text-success');
                                } else if (paidCents > 0) {
                                    $('#payment-span').removeAttr('hidden');
                                    $('#payment_status').text('Partially paid').addClass('text-warning');
                                    $('#reminder-btn').removeAttr('hidden');
                                } else {
                                    $('#payment-span').removeAttr('hidden');
                                    $('#payment_status').text('Not paid').addClass('text-danger');
                                    $('#reminder-btn').removeAttr('hidden');
                                }
                            } else if (adminDebit > 0) {
                                const actualPayments = (payment_data.payment || []).filter(payment =>
                                    String(payment.remark || '').toLowerCase() !== 'auto generated'
                                );
                                const paidCents = actualPayments.reduce((total, payment) =>
                                    total + Math.round(Number(payment.amount || 0) * 100), 0
                                );
                                const remainingCents = Math.max(0, Math.round(adminDebit * 100) - paidCents);
                                const remaining = remainingCents / 100;
                                $('#is_pos').val('0');
                                $('#paying_amount').val(remaining.toFixed(2)).attr('max', remaining.toFixed(2));
                                paying_amount = remaining;
                                check_credit_amount = 0;
                                $('#due_balance').val('0.00');
                                $('#submit-payment').prop('disabled', remainingCents === 0);
                                $('#reference_number').val('');
                                $('#debit-amount-span-two').text(remaining.toFixed(2));
                                $('#debit-amount-span-one').removeAttr('hidden');
                                $('#cradit-amount-span-one').attr('hidden', true);
                                $('#reference_number').removeAttr('readonly');
                                $('#remark').removeAttr('readonly');
                                if (paidCents > 0) {
                                    $('#payment-span').removeAttr('hidden');
                                    $('#payment_status').text('Partially paid').addClass('text-warning');
                                }
                            } else {
                                $('#payment-span').removeAttr('hidden');
                                $('#payment_status').text('No payment due').addClass('text-muted');
                            }

                        } else {
                            trows = `<tr>
                                        <td colspan="9" class="text-center">
                                            No Transactions Found!
                                        </td>
                                    </tr>`;
                            $('#transaction-tbody').html(trows);
                            hideFormLoader();
                        }
                    },
                    error: function(xhr) {
                        const message = Object.values(xhr.responseJSON?.errors || {})
                            .flat()
                            .join('\n');
                        Swal.fire({
                            icon: 'error',
                            title: 'Unable to load POS payment details',
                            text: message || xhr.responseJSON?.message || 'Check the POS ID and date, then try again.',
                        });
                    },
                    complete: function() {
                        hideFormLoader();
                        lookupButton.prop('disabled', false);
                    }
                });
            });

            $('#payment_submit_form').on('submit', function(event) {
                event.preventDefault();
                showFormLoader();
                const submitButton = $('#submit-payment');
                submitButton.prop('disabled', true);
                var formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.create.payment') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(res) {
                        hideFormLoader();
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Payment created successfully.',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#pos_detail_form').trigger('submit');
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed',
                                text: res.message || 'Payment creation failed.',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        hideFormLoader();
                        submitButton.prop('disabled', false);
                        const validationMessage = Object.values(xhr.responseJSON?.errors || {})
                            .flat()
                            .join('\n');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: validationMessage || xhr.responseJSON?.message || 'Something went wrong. Please try again.',
                            confirmButtonText: 'OK',
                        });
                    }
                });
            });

            function getAllPos() {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('admin.get.all.pos') }}',
                    success: function(res) {
                        let rows = '';
                        if (res.success && Array.isArray(res.data) && res.data.length) {
                            $.each(res.data, function(index, item) {

                                rows += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${escapeHtml(item.name ?? '-')}</td>
                                        <td>${escapeHtml(item.user_id ?? '-')}</td>
                                        <td>${escapeHtml(item.city ?? '-')}</td>
                                        <td>${escapeHtml(item.email ?? '-')}</td>
                                        <td>${escapeHtml(item.mobilenumber ?? '-')}</td>
                                        <td>${escapeHtml(item.transaction_charge ?? '-')}</td>
                                    </tr>
                                `;
                                $('#posTableBody').html(rows);
                            });
                        } else {
                            rows = `<tr><td colspan="7"><div class="admin-workflow-empty">
                                        <i class="bi bi-shop" aria-hidden="true"></i>
                                        No POS locations found.
                                    </div></td></tr>`;
                            $('#posTableBody').html(rows);
                        }
                    },
                    error: function() {
                        $('#posTableBody').html(`<tr><td colspan="7"><div class="admin-workflow-empty text-danger">
                            Unable to load POS locations. Refresh the page and try again.
                        </div></td></tr>`);
                    }
                });
            }

            $('#paying_amount').on('input', function() {
                let amount = Number($(this).val());
                if (amount > paying_amount) {
                    amount = paying_amount;
                    $(this).val(paying_amount.toFixed(2));
                }
                if (amount < paying_amount) {
                    let due = paying_amount - amount;
                    $('#due_balance').val(due);
                } else {
                    $('#due_balance').val('0.00');
                }
            });
        });
    </script>
@endsection
