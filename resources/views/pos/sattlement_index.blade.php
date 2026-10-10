@extends('pos.layouts.master')

@section('content')
    <div class="container-fluid px-2 px-md-3 py-3 rcpt-page">

        {{-- Hero header --}}
        <section class="dashboard-hero mb-4">
            <span class="dashboard-hero__label"><i class="fa fa-receipt me-1" aria-hidden="true"></i> Accounts</span>
            <h1 class="dashboard-hero__title">Receipt Journal</h1>
            <p class="dashboard-hero__text">Track every receipt voucher recorded against your store.</p>
        </section>

        <section class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4 rcpt-card">
            <div class="card-body p-3 p-lg-4">
                <form id="filter-form" class="row g-3 align-items-end">
                    <div class="col-12 col-sm-4 col-md-3">
                        <label for="from_date" class="rcpt-field-label">From date</label>
                        <input type="date" name="from_date" id="from_date" class="form-control">
                    </div>

                    <div class="col-12 col-sm-4 col-md-3">
                        <label for="to_date" class="rcpt-field-label">To date</label>
                        <input type="date" name="to_date" id="to_date" class="form-control">
                    </div>

                    <div class="col-12 col-sm-4 col-md-3 d-flex gap-2">
                        <button type="submit" class="btn rcpt-btn-primary w-100" id="apply-filter">
                            <i class="fa fa-filter" aria-hidden="true"></i> Filter
                        </button>
                        <button type="button" class="btn rcpt-btn-outline w-100" id="reset-filter">
                            <i class="fa fa-rotate-left" aria-hidden="true"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <section class="card border-0 shadow-lg rounded-4 overflow-hidden rcpt-card">
            <div class="card-header border-0 bg-white px-3 px-md-4 pt-4 pb-3">
                <h2 class="h6 mb-0 fw-bold text-dark text-uppercase">Receipts</h2>
            </div>
            <div class="card-body px-3 px-md-4 pt-2 pb-3">
                <div class="table-responsive rcpt-table-wrap" tabindex="0" aria-label="Scrollable receipt journal">
                    <table class="table align-middle mb-0 rcpt-table">
                        <thead>
                            <tr>
                                <th scope="col">Sl.No</th>
                                <th scope="col">Voucher</th>
                                <th scope="col">Date</th>
                                <th scope="col">Receive from</th>
                                <th scope="col">Receive by</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Narration</th>
                            </tr>
                        </thead>

                        <tbody id="receipt-tbl-bdy">
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="pay-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="exampleModalLongTitle">Initiate Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="pay-form" action="{{ route('pos.initiate.payment') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="file" name="screenshot" id="pay-img" class="form-control">
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn rcpt-btn-outline" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn rcpt-btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .rcpt-page {
            color: #172033;
        }

        .rcpt-page .rcpt-field-label {
            display: block;
            margin-bottom: .35rem;
            color: #536477;
            font-size: .78rem;
            font-weight: 750;
            letter-spacing: .03em;
            text-transform: uppercase;
        }

        .rcpt-page .btn.rcpt-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
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

        .rcpt-page .btn.rcpt-btn-primary:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 .7rem 1.3rem rgba(30, 64, 175, .24);
        }

        .rcpt-page .btn.rcpt-btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            padding: .6rem 1.1rem;
            border: 1px solid #d7dee6;
            border-radius: .7rem;
            background: #fff;
            color: #42536b;
            font-size: .9rem;
            font-weight: 700;
            transition: background .15s ease, border-color .15s ease;
        }

        .rcpt-page .btn.rcpt-btn-outline:hover {
            background: #f3f6f9;
            border-color: #c3ccd6;
            color: #1e293b;
        }

        .rcpt-page .rcpt-table-wrap {
            max-height: 520px;
            overflow-x: auto;
            overflow-y: auto;
            border: 1px solid #e4ebf1;
            border-radius: .85rem;
            scrollbar-color: #9abfc0 #f0f5f6;
            scrollbar-width: thin;
        }

        .rcpt-page .rcpt-table-wrap::-webkit-scrollbar {
            width: .55rem;
            height: .55rem;
        }

        .rcpt-page .rcpt-table-wrap::-webkit-scrollbar-thumb {
            border: 2px solid #f0f5f6;
            border-radius: 999px;
            background: #9abfc0;
        }

        .rcpt-page .rcpt-table-wrap::-webkit-scrollbar-track {
            background: #f0f5f6;
        }

        .rcpt-page .rcpt-table {
            min-width: 860px;
            color: #263449;
            /* Counteract the global .table { overflow: hidden; } rule so the
               sticky thead sticks relative to the scrollable wrapper. */
            overflow: visible;
        }

        .rcpt-page .rcpt-table thead th {
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

        .rcpt-page .rcpt-table tbody td {
            padding: .8rem 1rem;
            border-color: #edf1f5;
            font-size: .86rem;
            vertical-align: middle;
        }

        .rcpt-page .rcpt-table tbody tr:hover {
            background: #f8fbfc;
        }

        @media (max-width: 767.98px) {
            .rcpt-page .rcpt-table thead {
                display: none;
            }

            .rcpt-page .rcpt-table,
            .rcpt-page .rcpt-table tbody,
            .rcpt-page .rcpt-table tr,
            .rcpt-page .rcpt-table td {
                display: block;
                width: 100%;
                min-width: 0;
            }

            .rcpt-page .rcpt-table tr {
                margin-bottom: .75rem;
                padding: .5rem .25rem;
                border: 1px solid #e4ebf1;
                border-radius: .75rem;
            }

            .rcpt-page .rcpt-table td {
                display: flex;
                justify-content: space-between;
                gap: .75rem;
                padding: .45rem .75rem;
                border: 0;
                text-align: right;
            }

            .rcpt-page .rcpt-table td::before {
                content: attr(data-label);
                font-weight: 750;
                color: #536477;
                text-transform: uppercase;
                font-size: .7rem;
                letter-spacing: .03em;
                text-align: left;
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                                            <td data-label="Sl.No">${index++}</td>
                                            <td data-label="Voucher">${item.voucher ?? ''}</td>
                                            <td data-label="Date">${item.date ?? ''}</td>
                                            <td data-label="Receive From">${item.receive_from ?? ''}</td>
                                            <td data-label="Receive By">${item.receive_by}</td>
                                            <td data-label="Amount">${item.amount}</td>
                                            <td data-label="Narration">${item.remark}</td>
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
