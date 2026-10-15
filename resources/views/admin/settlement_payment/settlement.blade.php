@extends('layouts.master')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
@section('content')
    <div class="container-fluid py-3 admin-workflow-page">
        {{-- Header Section --}}
        <section class="admin-workflow-hero">
            <div>
                <span class="admin-workflow-hero__eyebrow">Settlement operations</span>
                <h1>Receipt Journal</h1>
                <p>Review incoming POS receipts, filter the journal, or record a payment received.</p>
            </div>
            <span class="admin-workflow-hero__icon" aria-hidden="true"><i class="bi bi-journal-check"></i></span>
        </section>

        {{-- Filter + Add Receipt Section --}}
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3">

            <form id="filter-form" class="row g-2 align-items-end flex-grow-1">
                <div class="col-6 col-sm-4 col-md-3">
                    <label for="from_date" class="form-label">From Date</label>
                    <input type="date" name="from_date" id="from_date" class="form-control">
                </div>

                <div class="col-6 col-sm-4 col-md-3">
                    <label for="to_date" class="form-label">To Date</label>
                    <input type="date" name="to_date" id="to_date" class="form-control">
                </div>

                <div class="col-12 col-sm-4 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary" id="apply-filter">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <button type="reset" class="btn btn-outline-secondary" id="reset-filter">
                        <i class="bi bi-x-circle"></i> Reset
                    </button>
                </div>
            </form>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReceiptModal">
                <i class="bi bi-plus-circle"></i> Add Receipt
            </button>

        </div>

        <div class="card admin-workflow-card">
            <div class="card-header bg-white py-3">
                <h2 class="h5 mb-0">Received payments</h2>
                <p class="text-muted small mb-0 mt-1">Receipts recorded from POS accounts to the admin account.</p>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 admin-workflow-table">
                <thead>
                    <tr>
                        <th scope="col">Sl.no</th>
                        <th scope="col">Voucher</th>
                        <th scope="col">Date</th>
                        <th scope="col">Account</th>
                        <th scope="col">Particulars</th>
                        <th scope="col" class="text-end">Debit</th>
                        <th scope="col" class="text-end">Credit</th>
                        <th scope="col" class="text-end">Balance</th>
                        <th scope="col">Payment Method</th>
                        <th scope="col">Reference</th>
                        <th scope="col">Narration</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>

                <tbody id="receipt-tbl-bdy">
                    <tr><td colspan="11"><div class="admin-workflow-empty">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Loading receipts…
                    </div></td></tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addReceiptModal" tabindex="-1" aria-labelledby="addReceiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg modal-fullscreen-sm-down">
            <div class="modal-content">
                <form id="submit-receipt">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addReceiptModalLabel">Add Receipt</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-12 col-sm-6 mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" step="0.01" min="0.01" max="9999999999999.99" name="amount" id="amount"
                                    class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}"
                                    required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-sm-6 mb-3">
                                <label for="pay_by" class="form-label">Pay By</label>
                                <select name="pay_by" id="pay_by"
                                    class="form-select @error('pay_by') is-invalid @enderror" required>
                                    <option value="" disabled {{ old('pay_by') === null ? 'selected' : '' }}>-- Select
                                        Pay By --</option>
                                    <option value="1" {{ old('pay_by') == '1' ? 'selected' : '' }}>UPI</option>
                                    <option value="0"
                                        {{ old('pay_by') !== null && old('pay_by') == '0' ? 'selected' : '' }}>Cash</option>
                                </select>
                                @error('pay_by')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-sm-6 mb-3">
                                <label for="pay_to" class="form-label">Receive From POS</label>
                                <select name="pay_to" id="pay_to"
                                    class="form-select @error('pay_to') is-invalid @enderror" required>
                                    <option value="" disabled {{ old('pay_to') ? '' : 'selected' }}>-- Select POS
                                        --</option>
                                    @foreach ($all_pos as $payee)
                                        <option value="{{ $payee->user_id }}"
                                            {{ old('pay_to') == $payee->user_id ? 'selected' : '' }}>
                                            {{ $payee->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pay_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-sm-6 mb-3">
                                <label for="reference_number" class="form-label">Reference Number</label>
                                <input type="text" name="reference_number" id="reference_number"
                                    class="form-control @error('reference_number') is-invalid @enderror"
                                    value="{{ old('reference_number') }}">
                                @error('reference_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="remark" class="form-label">Remark</label>
                                <textarea name="remark" id="remark" rows="3" class="form-control @error('remark') is-invalid @enderror">{{ old('remark') }}</textarea>
                                @error('remark')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const escapeHtml = value => $('<div>').text(value ?? '').html();

            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                loadReceipt(1, this);
            });
            $('#submit-receipt').on('submit', function(e) {
                e.preventDefault();
                const receiptForm = this;
                const submitButton = receiptForm.querySelector('[type="submit"]');
                submitButton.disabled = true;
                var formData = new FormData(receiptForm);
                $.ajax({
                    url: "{{ route('admin.store.receipt') }}",
                    type: "POST",
                    data: formData,
                    processData: false, // Important when using FormData
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                                Swal.fire({ title: response.message, icon: "success" }).then(() => {
                                    const modal = bootstrap.Modal.getInstance(document.getElementById('addReceiptModal'));
                                    modal?.hide();
                                    receiptForm.reset();
                                    loadReceipt(1, document.getElementById('filter-form'));
                            });
                        } else {
                            Swal.fire({
                                title: "Something went wrong!",
                                text: response.message || "Please try again later.",
                                icon: "error"
                            });
                        }
                    },
                    error: function(xhr) {
                        const validationMessage = Object.values(xhr.responseJSON?.errors || {})
                            .flat()
                            .join('\n');
                        const message = validationMessage || xhr.responseJSON?.message || 'Please check the receipt details and try again.';
                        Swal.fire({ title: 'Unable to save receipt', text: message, icon: 'error' });
                    },
                    complete: function() {
                        submitButton.disabled = false;
                    }
                });
            });



            $('#reset-filter').on('click', function() {
                $('#from_date, #to_date').val('');
                loadReceipt(1, document.getElementById('filter-form'));
            });

            loadReceipt(1, document.getElementById('filter-form'));

            function loadReceipt(page = 1, formElement) {
                let formData = new FormData(formElement);
                $.ajax({
                    url: "{{ route('admin.get.receipt') }}",
                    type: "POST",
                    data: formData,
                    processData: false, // Important when using FormData
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#apply-filter').prop('disabled', true);
                    },
                    success: function(response) {

                        let rows = '';
                        let index = 1;

                        const transactions = response.data.rows ?? [];
                        if (transactions.length > 0) {
                            transactions.forEach(function(item) {
                                const amount = `₹${Number(item.amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                                const debitBalance = `₹${Number(item.debit_balance || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})} ${escapeHtml(item.debit_balance_type)}`;
                                const creditBalance = `₹${Number(item.credit_balance || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})} ${escapeHtml(item.credit_balance_type)}`;
                                const posName = escapeHtml(item.receive_from);
                                const method = escapeHtml(item.receive_by);
                                const bankAccount = escapeHtml(item.cash_bank_account);
                                rows += `
                                        <tr>
                                            <td rowspan="2">${index++}</td>
                                            <td rowspan="2">${escapeHtml(item.voucher)}</td>
                                            <td rowspan="2">${escapeHtml(item.date ? new Date(item.date + 'T00:00:00').toLocaleDateString() : '—')}</td>
                                            <td>${bankAccount}</td>
                                            <td>Receipt from ${posName}</td>
                                            <td class="text-end">${amount}</td>
                                            <td class="text-end">—</td>
                                            <td class="text-end">${debitBalance}</td>
                                            <td rowspan="2">${method}</td>
                                            <td rowspan="2">${escapeHtml(item.ref_number) || '—'}</td>
                                            <td rowspan="2">${escapeHtml(item.remark) || '—'}</td>
                                        </tr>
                                        <tr>
                                            <td>POS Account — ${posName}</td>
                                            <td>Received through ${bankAccount}</td>
                                            <td class="text-end">—</td>
                                            <td class="text-end">${amount}</td>
                                            <td class="text-end">${creditBalance}</td>
                                        </tr>
                                    `;
                            });
                            rows += `
                                <tr class="table-light fw-bold">
                                    <td colspan="5" class="text-end">Total</td>
                                    <td class="text-end">₹${Number(response.data.total_debit || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                                    <td class="text-end">₹${Number(response.data.total_credit || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                                    <td colspan="4"></td>
                                </tr>
                            `;
                        } else {

                            rows = `
                                    <tr>
                                        <td colspan="11"><div class="admin-workflow-empty">
                                            <i class="bi bi-inbox" aria-hidden="true"></i>
                                            No receipts match this date range.
                                        </div></td>
                                    </tr>
                                `;
                        }

                        $('#receipt-tbl-bdy').html(rows);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('Something went wrong');
                    }
                });
            }


        });
    </script>
@endsection
