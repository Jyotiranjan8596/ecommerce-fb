@extends('layouts.master')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .table-responsive::-webkit-scrollbar {
        width: 8px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .scrollable-table {
        max-height: 300px;
        overflow-y: auto;
        overflow-x: auto;
    }

    /* Mobile styling adjustments */
    @media (max-width: 768px) {
        .card-box {
            padding: 20px;
        }

        h3,
        h4 {
            font-size: 1.2rem;
        }

        .btn {
            width: 100%;
            margin-top: 10px;
        }

        .scrollable-table table {
            width: 100%;
            font-size: 0.9rem;
        }

        .scrollable-table th,
        .scrollable-table td {
            padding: 8px;
        }
    }

    /* Loader overlay */
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
    <!-- Loader Overlay -->
    <div id="form-loader-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div style="margin-top: 15px;">
        <h4><b>Payment</b></h4>
        <hr>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card-box p-4 shadow-lg rounded">

                <!-- POS Lookup -->
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-shop text-primary fs-4 me-2"></i>
                    <h5 class="mb-0 fw-bold text-primary">FIND POS</h5>
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
                            <button type="submit" class="btn btn-outline-primary w-100 hover-blue">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <hr class="my-4">

                <!-- Wallet Header -->
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <h4 class="mb-0"><i class="bi bi-wallet2 text-primary me-2"></i><b>MANAGE PAYMENT</b></h4>
                </div>
                <p class="text-muted mb-4 d-flex justify-content-between align-items-center">
                    <span>Payment info for <span class="text-danger fw-semibold" id="pos_name">—</span></span>
                    <span id="cradit-amount-span-one" hidden class="fw-semibold">Credit Amount: <span
                            id="cradit-amount-span-two" class="text-success">₹0.00</span></span>
                    <span id="cradit-amount-span-three" hidden class="fw-semibold text-success">Settled</span>
                </p>

                <!-- Wallet Form -->
                <form id="payment_submit_form">
                    @csrf
                    <input type="hidden" name="user_id" value="">
                    <input id="pos_id" type="hidden" name="pos_id" value="">
                    <input type="hidden" name="mobilenumber" value="">
                    <input type="hidden" name="transaction_date" value="{{ now()->format('Y-m-d') }}">
                    <input id="summary_date" type="hidden" name="summary_date" value="">

                    <div class="row g-3">
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
                                <option value="wallet">WALLET</option>
                                <option value="cash">CASH</option>
                                <option selected value="upi">UPI</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="paying_amount" class="form-label fw-semibold">PAYING AMOUNT</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-currency-rupee"></i></span>
                                <input name="paying_amount" id="paying_amount" type="number" class="form-control" required
                                    step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold d-block">DUE</label>
                            <div
                                class="wallet-balance-box p-2 px-3 rounded border bg-light d-flex align-items-center justify-content-between">
                                <span id="due" class="fw-bold text-danger">₹0.00</span>
                                <i class="bi bi-wallet-fill text-danger"></i>
                            </div>
                            <input id="due_balance" type="hidden" name="due" value="">
                        </div>
                        <div class="col-md-6">
                            <label for="reference_number" class="form-label fw-semibold">Reference Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                                <input name="reference_number" id="reference_number" type="text" class="form-control"
                                    required min="0" placeholder="Reference Number">
                            </div>
                        </div>

                        <div class="col-6">
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
                    <button type="submit" class="btn btn-primary w-100 mt-4 py-2 fw-semibold">
                        <i class="bi bi-check-circle me-1"></i> SUBMIT
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card-box p-4 shadow-lg rounded">

                <!-- Tab Nav -->
                <ul class="nav nav-tabs" id="posTab" role="tablist">
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
                        <div class="scrollable-table">
                            <table id="tech-companies-1" class="table table-striped table-bordered">
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
                                        <td colspan="6" class="text-center">
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
                        <div class="scrollable-table">
                            <table id="all-pos-table" class="table table-striped table-bordered">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function showFormLoader() {
            $('#form-loader-overlay').css('display', 'flex');
        }

        function hideFormLoader() {
            $('#form-loader-overlay').css('display', 'none');
        }

        $(document).ready(function() {
            getAllPos();
            let check_credit_amount = 0;
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
                console.log("pos details form submit");
                showFormLoader();
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
                        $('#amount').val('');
                        $('#paying_amount').val('');
                        $('#pos_id').val('');
                        $('#cradit-amount-span-one').attr('hidden', true);
                        $('#cradit-amount-span-three').attr('hidden', true);
                        $('#reference_number').removeAttr('readonly');
                        $('#remark').removeAttr('readonly');
                        if (res.success) {
                            console.log(res.data == null);

                            $.each(res.data, function(index, item) {
                                trows += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${item.invoice ?? '-'}</td>
                                        <td>${item.mobile ?? '-'}</td>
                                        <td>${item.name ?? '-'}</td>
                                        <td>${item.billing_amount ?? '-'}</td>
                                        <td>${item.wallet_deduct ?? '-'}</td>
                                        <td>${item.reward_deduct ?? '-'}</td>
                                        <td>${item.net_pay ?? '-'}</td>
                                        <td>${item.remaining_wallet ?? '-'}</td>
                                        <td>${item.remaining_reward ?? '-'}</td>    
                                        <td>${item.transaction_date ?? '-'}</td>
                                    </tr>
                                `;
                            });
                            $('#transaction-tbody').html(trows);
                            if (res.payment_data == null && res.data != []) {
                                // $('#cradit-amount-span-three').removeAttr('hidden');
                                // return;
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Payment Summary Unavailable',
                                    text: 'The payment summary has not been generated yet. Please try again after some time.',
                                    confirmButtonText: 'OK'
                                });
                                return;
                            }
                            let payment_data = res.payment_data;
                            check_credit_amount = payment_data.admin_credit;
                            $('#pos_name').text(payment_data.pos_system.name);
                            $('#amount').val(payment_data.total_billing_amount);
                            $('#paying_amount').val(payment_data.admin_credit);
                            $('#pos_id').val(payment_data.pos_id);
                            $('#reference_number').val(payment_data.payment?.[0]
                                ?.reference_number);
                            if (payment_data.admin_credit > 0) {
                                $('#paying_amount').val(payment_data.admin_credit);
                                $('#cradit-amount-span-one').removeAttr('hidden');
                                $('#remark').attr('readonly', false);
                                $('#cradit-amount-span-two').text(payment_data.admin_credit);
                            } else {
                                $('#paying_amount').val(payment_data.admin_credit);
                                $('#cradit-amount-span-one').attr('hidden', true);
                                $('#reference_number').removeAttr('readonly');
                                $('#remark').removeAttr('readonly');
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
                        console.log(xhr.responseText);
                    },
                    complete: function() {
                        hideFormLoader();
                    }
                });
            });

            // Note: this form had no existing submit handler in the original file,
            // so we only show the loader here (no new AJAX logic added).
            $('#payment_submit_form').on('submit', function(event) {
                event.preventDefault();
                showFormLoader();
                console.log(check_credit_amount);
                if (check_credit_amount == 0) {
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
                    url: "{{ route('admin.create.payment') }}",
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

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again.',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });

                        console.log(xhr.responseText);
                    }
                });
                // Continue with your AJAX request or form submission...
            });

            function getAllPos() {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('admin.get.all.pos') }}',
                    success: function(res) {
                        let rows = '';
                        if (res.success) {

                            let rows = '';

                            $.each(res.data, function(index, item) {

                                rows += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${item.name ?? '-'}</td>
                                        <td>${item.user_id ?? '-'}</td>
                                        <td>${item.city ?? '-'}</td>
                                        <td>${item.email ?? '-'}</td>
                                        <td>${item.mobilenumber ?? '-'}</td>
                                        <td>${item.transaction_charge ?? '-'}</td><td>
                                    </tr>
                                `;
                                $('#posTableBody').html(rows);
                            });
                        } else {
                            rows = `<tr>
                                        <td colspan="9" class="text-center">
                                            No POS Found!
                                        </td>
                                    </tr>`;
                            $('#posTableBody').html(rows);
                        }

                    }
                });
            }
        });
    </script>
@endsection
