@extends('layouts.master')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <div class="container" style="margin-top: 20px;">
        <h3 class="text-center"><b style="color: rgb(8, 7, 20)">DAILY SALES REPORT</b></h3>

        <div class="row">
            <!-- File Upload Form -->
            <div class="col-md-4 mb-3">
                <form action="{{ route('admin.dsr.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group">
                        <input type="file" class="form-control" name="file" accept=".csv">
                        <button class="btn btn-info" type="submit">UPLOAD DSR</button>
                    </div>
                </form>
            </div>

            <!-- Search Form -->
            <div class="col-md-4 mb-3">
                <form method="GET" action="{{ route('admin.dsr') }}">
                    <div class="input-group">
                        <input type="number" class="form-control" name="search" placeholder="Search By...">
                        <button class="btn btn-info" type="submit">SEARCH</button>
                    </div>
                </form>
            </div>

            <!-- Export Form -->
            <div class="col-md-4 mb-3 text-end">
                <form method="GET" action="{{ route('admin.dsr.export') }}">
                    <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                    <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                    <button class="btn btn-danger" type="submit">EXPORT</button>
                </form>
            </div>
        </div>

        <!-- Date Filter Form -->
        <div class="row justify-content-end">
            <div class="col-md-4">
                <form method="GET" action="{{ route('admin.dsr') }}">
                    <label for="start_date"><b>From:</b></label>
                    <input type="date" class="form-control mb-2" name="start_date" id="start_date">

                    <label for="end_date"><b>To:</b></label>
                    <input type="date" class="form-control mb-2" name="end_date" id="end_date">

                    <button class="btn btn-info w-100" type="submit">FILTER</button>
                </form>
            </div>
        </div>

        <hr class="my-4">

        <!-- Data Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>INVOICE</th>
                        <th>POS ID</th>
                        {{-- <th>USER ID</th> --}}
                        <th>MOBILE</th>
                        <th>NAME</th>
                        <th>BILLING AMOUNT</th>
                        <th>TRANSACTION DATE</th>
                        <th>Action</th>
                        {{-- <th>INSERT DATE</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @if ($wallets->isEmpty())
                        <tr>
                            <td colspan="8" class="text-center text-danger" style="font-size:22px;">No Data Available
                            </td>
                        </tr>
                    @else
                        @foreach ($wallets as $key => $data)
                            <tr>
                                <td>{{ $wallets->firstItem() + $key }}</td>
                                <td>{{ $data->invoice }}</td>
                                <td>{{ $data->getPos ? $data->getPos->user_id : '' }}</td>
                                {{-- <td>{{ $data->user_id }}</td> --}}
                                <td>{{ $data->mobilenumber }}</td>
                                <td>{{ $data->user ? $data->user->name : '' }}</td>
                                <td>₹{{ $data->billing_amount ?? 0 }}/-</td>
                                <td>{{ date('d/m/Y', strtotime($data->transaction_date)) }}</td>
                                {{-- <td>{{ date('d-m-Y h:i A', strtotime($data->insert_date)) }}</td> --}}
                                <td>
                                    {{-- <a href="" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModal">Edit</a> --}}
                                    <button class="btn btn-sm btn-primary" data-toggle="modal"
                                        data-id="{{ $data->id }}" data-target="#exampleModal">Edit</button>

                                    <button type="button" id="delete-dsr" data-id="{{ $data->id }}"
                                        class="btn btn-sm btn-danger">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $wallets->links() }}
        </div>
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
            $('#exampleModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var walletId = button.data('id'); // Extract wallet_id
                $('#wallet_id').val(walletId); // Set to hidden input
            });

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
