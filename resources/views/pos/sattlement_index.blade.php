@extends('pos.layouts.master')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('content')
    <div class="container my-4">
        {{-- Header Section --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

            <h3 class="fw-bold text-primary m-0">Receipt Journal
            </h3>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <form id="filter-form" class="row g-3 align-items-end">
                    <div class="col-12 col-sm-4 col-md-3">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="date" name="from_date" id="from_date" class="form-control">
                    </div>

                    <div class="col-12 col-sm-4 col-md-3">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="date" name="to_date" id="to_date" class="form-control">
                    </div>

                    <div class="col-12 col-sm-4 col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100" id="apply-filter">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <button type="button" class="btn btn-outline-secondary w-100" id="reset-filter">
                            <i class="bi bi-x-circle"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
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
    <div class="modal fade" id="pay-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Initiate Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="pay-form" action="{{ route('pos.initiate.payment') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="file" name="screenshot" id="pay-img">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
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
                    url: "{{ route('pos.get.receipt') }}",
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
