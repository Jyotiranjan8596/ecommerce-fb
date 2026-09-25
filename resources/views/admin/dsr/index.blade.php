@extends('layouts.master')

@section('content')
    <style>
        .section-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .stats-card {
            background: #f8f9fa;
            border-radius: 10px;
            border: 0.5px solid #dee2e6;
            padding: 1rem 1.25rem;
            height: 100%;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
            padding: 0.5rem 0;
            border-bottom: 0.5px solid #dee2e6;
        }

        .stat-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .stat-item:first-child {
            padding-top: 0;
        }

        .stat-label {
            font-size: 12px;
            color: #6c757d;
        }

        .stat-value {
            font-size: 15px;
            font-weight: 500;
            color: #212529;
        }

        .date-card {
            background: #fff;
            border: 0.5px solid #dee2e6;
            border-radius: 10px;
            padding: 1rem 1.25rem;
        }

        #dsr-tbl thead th {
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
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <div class="container" style="margin-top: 20px;">
        <h3 class="text-center"><b style="color: rgb(8, 7, 20)">SALES REPORT</b></h3>



        <div class="row mb-3 align-items-start">
            <!-- Stats -->
            <div class="col-md-6 mb-2">
                <div class="stats-card">
                    <p class="section-label">Summary of today/selected date</p>
                    <div class="stat-item">
                        <span class="stat-label">Total billing</span>
                        <span id="total-billing" class="stat-value"></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Total transactions</span>
                        <span id="total-transaction" class="stat-value"></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Total active POS</span>
                        <span id="active-pos" class="stat-value"></span>
                    </div>
                </div>
            </div>

            <!-- Date Filter -->
            <div class="col-md-6 mb-2">
                <div class="date-card">
                    <p class="section-label">Date filter</p>
                    <form id="dateForm">
                        <label for="start_date"><b>From</b></label>
                        <input type="date" class="form-control mb-2" name="start_date" id="start_date">
                        <label for="end_date"><b>To</b></label>
                        <input type="date" class="form-control mb-2" name="end_date" id="end_date">
                        <button class="btn btn-info w-100" type="submit">Filter</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <!-- Upload -->
            <div class="col-md-4 mb-2">
                <p class="section-label">Upload</p>
                <form action="{{ route('admin.dsr.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group">
                        <input type="file" class="form-control" name="file" accept=".csv">
                        <button class="btn btn-info" type="submit">Upload DSR</button>
                    </div>
                </form>
            </div>

            <!-- Search -->
            <div class="col-md-4 mb-2">
                <p class="section-label">Search</p>
                <form id="searchForm">
                    <div class="input-group">
                        <input id="search" type="text" class="form-control" name="search" placeholder="Search by...">
                        <button class="btn btn-info" type="submit">Search</button>
                    </div>
                </form>
            </div>

            <!-- Export -->
            <div class="col-md-4 mb-2">
                <p class="section-label">Export</p>
                <form method="GET" action="{{ route('admin.dsr.export') }}">
                    <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                    <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                    <button class="btn btn-danger w-100" type="submit">Export</button>
                </form>
            </div>
        </div>
        <hr class="my-4">

        <!-- Data Table -->
        <div class="table-responsive">
            <table class="table table-striped" id="dsr-tbl">
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>POS ID</th>
                        {{-- <th>USER ID</th> --}}
                        <th>NAME</th>
                        <th>MOBILE</th>
                        <th>BILLING AMOUNT</th>
                        <th>Transactions</th>
                        <th>TRANSACTION DATE</th>
                        <th>Action</th>
                        {{-- <th>INSERT DATE</th> --}}
                    </tr>
                </thead>
                <tbody id="dsr-table">

                </tbody>
            </table>
        </div>
        <div id="pagination-container" class="pagination-wrapper"></div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="edit-form">
                    <input type="hidden" name="wallet_id" id="wallet_id"> <!-- Hidden input to store ID -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit DSR</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="billing_amount" class="form-label">Billing Amount</label>
                            <input type="number" class="form-control" id="billing_amount" name="billing_amount"
                                min="0" step="1">
                        </div>

                        <div class="mb-3">
                            <label for="transaction_date" class="form-label">Transaction Date</label>
                            <input type="date" class="form-control" id="transaction_date"
                                max="{{ \Carbon\Carbon::today()->toDateString() }}" name="transaction_date">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Load jQuery (full version only, not slim) FIRST -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Then Popper.js and Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>

    <!-- Your custom script wrapped in DOM ready -->
    <script>
        $(document).ready(function() {
            getDsr(1)
            $('#exampleModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var walletId = button.data('id'); // Extract wallet_id
                $('#wallet_id').val(walletId); // Set to hidden input
            });

            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                getDsr(1);
            });
            $('#dateForm').on('submit', function(e) {
                e.preventDefault();
                getDsr(1);
            });

            function getDsr(page = 1) {
                let search = $('#search').val();
                let startDate = $('#start_date').val();
                let endDate = $('#end_date').val();

                $.ajax({
                    url: "{{ route('admin.get.dsr.data') }}",
                    type: "post",
                    data: {
                        search: search,
                        start_date: startDate,
                        end_date: endDate,
                        page: page
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Update your table here
                        console.log(response);
                        let tbody = $('#dsr-table');
                        tbody.empty();

                        if (response.success) {
                            let wallet = response.data.wallet.data;
                            const pagination = response.data.wallet;
                            let paginationHtml = '';
                            $('#total-billing').text(response.data.totalBillingAmount);
                            $('#total-transaction').text(response.data.totalTransactions);
                            $('#active-pos').text(response.data.totalPos);
                            if (!wallet || wallet.length === 0) {
                                tbody.html(`
                                            <tr>
                                                <td colspan="8"
                                                    class="text-center text-danger"
                                                    style="font-size:22px;">
                                                    No Data Available
                                                </td>
                                            </tr>
                                        `);
                                return;
                            }
                            $.each(wallet, function(key, wdata) {

                                let posId = wdata.get_pos ? wdata.get_pos.user_id : '';
                                let posName = wdata.get_pos ? wdata.get_pos.name : '';
                                let mobile = wdata.get_pos ? wdata.get_pos.mobilenumber : '';

                                let transactionDate = new Date(wdata.transaction_date)
                                    .toLocaleDateString('en-GB');

                                tbody.append(`
                                            <tr>
                                                <td>${pagination.from + key}</td>
                                                <td>${posId}</td>
                                                <td>${posName}</td>
                                                <td>${mobile}</td>
                                                <td>₹${wdata.total_billing_amount ?? 0}/-</td>
                                                <td>${wdata.total_transactions}</td>
                                                <td>${transactionDate}</td>
                                                <td>
                                                    <a href="${wdata.details_url}" target="_blank" 
                                                        class="btn btn-sm btn-primary">
                                                            <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        `);
                            });
                            $('#pagination-container').html(buildPagination(pagination));
                        }

                    }
                });
            }

            $(document).on('click', '.pagination-btn', function() {
                const page = $(this).data('page');
                console.log(page);

                getDsr(page); // Reuse the same form
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


            $('#edit-form').on('submit', function(e) {
                e.preventDefault();
                console.log("form submit");

                // Create FormData object from form
                var formData = new FormData(this);

                // Get wallet ID from data-id attribute
                var walletId = $(this).data('id'); // make sure #edit-form has data-id set
                formData.append('_token', '{{ csrf_token() }}');
                $.ajax({
                    type: "POST",
                    url: '{{ route('admin.used.wallet.upload') }}',
                    data: formData,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        if (response.code == 200) {
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
                    }
                });
            });

            $(document).on('click', '#delete-dsr', function() {
                const walletId = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "This action cannot be undone.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('admin.used.wallet.delete') }}', // Replace with your actual route
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                wallet_id: walletId
                            },
                            success: function(response) {
                                if (response.code == 200) {
                                    Swal.fire("Deleted!", response.message, "success")
                                        .then(() => {
                                            location.reload();
                                        });
                                } else {
                                    Swal.fire("Error!", response.message ||
                                        "Something went wrong.", "error");
                                }
                            },
                            error: function(xhr) {
                                Swal.fire("Error!", "Server error occurred.", "error");
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
