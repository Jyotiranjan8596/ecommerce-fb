@extends('layouts.master')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('content')
    <style>
        .btn-add-receipt {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            font-size: 0.95rem;
            font-weight: 500;
            color: #fff;
            background: #353e4b;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 3px 8px rgba(78, 115, 223, 0.35);
            transition: all 0.2s ease-in-out;
        }

        .btn-add-receipt:hover {
            background: #7a8087;
            box-shadow: 0 5px 12px rgba(78, 115, 223, 0.45);
            transform: translateY(-2px);
        }

        .btn-add-receipt:active {
            transform: translateY(0);
            box-shadow: 0 2px 6px rgba(78, 115, 223, 0.4);
        }
    </style>
    <div class="container my-4">
        {{-- Header Section --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h3 class="fw-bold text-primary m-0">Receipt Journal</h3>
        </div>

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
                    <button type="button" class="btn btn-outline-secondary" id="reset-filter">
                        <i class="bi bi-x-circle"></i> Reset
                    </button>
                </div>
            </form>

            <button type="button" class="btn-add-receipt" data-bs-toggle="modal" data-bs-target="#addReceiptModal">
                <i class="bi bi-plus-circle"></i> Add Receipt
            </button>

        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Sl.no</th>
                        <th scope="col">Voucher</th>
                        <th scope="col">Date</th>
                        <th scope="col">Receive From</th>
                        <th scope="col">Receive By</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Narration</th>
                    </tr>
                </thead>

                <tbody id="receipt-tbl-bdy">
                </tbody>
            </table>
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
                                <input type="number" step="0.01" name="amount" id="amount"
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
                                <label for="pay_to" class="form-label">Received From</label>
                                <select name="pay_to" id="pay_to"
                                    class="form-select @error('pay_to') is-invalid @enderror" required>
                                    <option value="" disabled {{ old('pay_to') ? '' : 'selected' }}>-- Select Pay To
                                        --</option>
                                    @foreach ($all_pos as $payee)
                                        <option value="{{ $payee->user_id }}"
                                            {{ old('pay_to') == $payee->id ? 'selected' : '' }}>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                var fromDate = $('#from_date').val();
                var toDate = $('#to_date').val();
                loadReceipt(1, this);
            });
            $('#submit-receipt').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
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
                            Swal.fire({
                                title: response.message,
                                icon: "success"
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Something went wrong!",
                                text: response.message || "Please try again later.",
                                icon: "error"
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('Something went wrong');
                    }
                });
            });



            // $('#export-smry').on('click', function() {
            //     $.ajax({
            //         type: 'POST', // HTTP method
            //         url: '{{ route('pos.export.settlement') }}',
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
            //                 'content') // CSRF token for Laravel
            //         },
            //         success: function(response) {
            //             if (response.code == 200) {
            //                 // Show SweetAlert
            //                 Swal.fire({
            //                     title: 'Success',
            //                     text: response.message,
            //                     icon: 'success',
            //                     confirmButtonText: 'Okay'
            //                 }).then((result) => {
            //                     if (result.isConfirmed) {
            //                         // Reload the page after confirmation
            //                         location.reload();
            //                     }
            //                 });
            //             }
            //         },
            //         error: function(xhr, status, error) {
            //             console.error('Error:', error);
            //             alert('An error occurred while processing the request.');
            //         }
            //     });
            // });
            loadReceipt();

            function loadReceipt(page = 1, formElement) {
                let formData = new FormData(formElement); // Capital 'F'
                formData.append('page', page);
                const fromDate = $('#from_date').val();
                const toDate = $('#to_date').val();
                $.ajax({
                    url: "{{ route('admin.get.receipt') }}",
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
                        $('#tbl-div').attr('hidden', false);
                        console.log(response.data);
                        let data = response.data;

                        const transactions = Object.keys(response.data)
                            .filter(key => !isNaN(key))
                            .map(key => response.data[key]);
                        if (Object.keys(response.data).length > 0) {
                            const pagination = response.data;
                            let paginationHtml = '';
                            transactions.forEach(function(item) {

                                rows += `
                                        <tr>
                                            <td>${index++}</td>
                                            <td>${item.voucher ?? ''}</td>
                                            <td>${item.date ?? ''}</td>
                                            <td>${item.receive_from ?? ''}</td>
                                            <td>${item.receive_by}</td>
                                            <td>${item.amount}</td>
                                            <td>${item.remark}</td>
                                        </tr>
                                    `;
                            });
                            // $('#pagination-container').html(buildPagination(pagination));

                        } else {

                            rows = `
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            No data found
                                        </td>
                                    </tr>
                                `;
                        }

                        $('#receipt-tbl-bdy').html(rows);

                        // Pagination HTML
                        $('#pagination-link').html(response.pagination);
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
