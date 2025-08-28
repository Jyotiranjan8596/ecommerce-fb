@extends('layouts.master')
@section('content')
    <style>
        #status-id:hover {
            cursor: pointer;
        }
    </style>
    <div class="row">

        <!-- Search -->
        <div class="col-md-4 mb-3">
            <form method="GET" action="{{ route('admin.settlement.index') }}">
                <div class="input-group">
                    <input type="number" class="form-control" name="search" placeholder="Search By...">
                    <button class="btn btn-secondary" type="submit">SEARCH</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-end mb-2">
        <div class="col-12">
            <form method="GET" action="{{ route('admin.settlement.index') }}">
                <div class="row">
                    <div class="col-6">
                        <label for="start_date"><b>From:</b></label>
                        <input type="date" value="{{ request('start_date') ?? now()->toDateString() }}"
                            class="form-control mb-2" name="start_date" id="start_date">
                    </div>
                    <div class="col-6">
                        <label for="end_date"><b>To:</b></label>
                        <input type="date" value="{{ request('end_date') ?? now()->toDateString() }}"
                            class="form-control mb-2" name="end_date" id="end_date">
                    </div>
                    <div class="col-6 me-3">
                        <button class="btn btn-secondary w-50" type="submit">FILTER</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                DSR Settlement
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                {{-- {{ dd($settlements) }} --}}
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Sl.no</th>
                            <th scope="col">POS Name</th>
                            <th scope="col">Date</th>
                            <th scope="col">Total Transaction</th>
                            <th scope="col">Billing Amount</th>
                            <th scope="col">Cash/UPI</th>
                            <th scope="col">Wallet</th>
                            <th scope="col">Reward</th>
                            <th scope="col">Credit</th>
                            <th scope="col">Debit</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                    </thead>
                    <tbody>
                        @foreach ($settlements as $key => $settlement)
                            <tr>
                                <td> {{ $key + 1 }}</td>
                                <td>{{ $settlement->creator->name }}</td>
                                <td>
                                    {{ $settlement->intiate_date }}
                                </td>
                                <td>{{ $settlement->total_transaction }}</td>
                                <td>{{ $settlement->total_billing_amount }}</td>
                                <td>{{ $settlement->by_cash }}</td>
                                <td>
                                    {{ $settlement->by_wallet }}
                                </td>
                                <td>
                                    {{ $settlement->by_reward }}
                                </td>
                                <td>
                                    {{ $settlement->admin_credit }}
                                </td>
                                <td>
                                    {{ $settlement->admin_debit }}
                                </td>
                                @if ($settlement->status == 'pending')
                                    <td id="status-id">
                                        <span data-id="{{ $settlement->id }}"
                                            class="badge bg-warning text-dark">Pending</span>
                                    </td>
                                @elseif ($settlement->status == 'rejected')
                                    <td>
                                        <span class="badge bg-danger"><b>Rejected</b></span>
                                    </td>
                                @else
                                    <td>
                                        <span class="badge bg-success"><b>Settled</b></span>
                                    </td>
                                @endif
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                            id="actionMenu{{ $settlement->id }}" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            ⋮
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="actionMenu{{ $settlement->id }}">
                                            @if ($settlement->status == 'pending')
                                                <li>
                                                    <a class="dropdown-item text-success" href="javascript:void(0)"
                                                        data-bs-toggle="modal" data-bs-target="#reference-modal"
                                                        data-id="{{ $settlement->id }}" onclick="openVerifyModal(this)">
                                                        Approve
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                        onclick="rejectSettlement({{ $settlement->id }})">
                                                        Reject
                                                    </a>
                                                </li>
                                            @else
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.settlement.invoice', $settlement->id) }}">

                                                        Download Invoice
                                                    </a>
                                                </li>
                                            @endif

                                        </ul>
                                    </div>
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        {{-- <div class="d-flex justify-content-center">
            {{ $pos->links() }}
        </div> --}}
        {{-- <div class="card-footer clearfix">
            {{ $sector->links() }}
        </div> --}}
        {{-- <script>
            function confirmDelete(id) {
                if (confirm('Are you sure you want to delete this Product?')) {
                    document.getElementById('delete-form-' + id).submit();
                }
            }
        </script> --}}
    </div>
    <div class="modal fade" id="reference-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Initiate Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="settlement-form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="settlement_id">
                        <input type="text" class="form-control" id="reference_number" name="reference_number"
                            placeholder="Enter reference number">
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

            // Make openVerifyModal globally accessible
            window.openVerifyModal = function(el) {
                let settlementId = $(el).data('id');
                $("#settlement_id").val(settlementId);
                $("#verifyModal").modal('show'); // show modal
            }

            // Handle form submit
            $('#settlement-form').on('submit', function(e) {
                e.preventDefault();
                updateSettlement(this);
            });

            // AJAX Update
            function updateSettlement(formElement) {
                let formData = new FormData(formElement);

                $.ajax({
                    url: "{{ route('admin.settlement.verify') }}",
                    type: "POST",
                    data: formData,
                    processData: false, // important for FormData
                    contentType: false, // important for FormData
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Verified Successfully',
                                text: response.message ?? 'Settlement updated!',
                                confirmButtonText: 'OK'
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Verification Failed',
                                text: response.message ??
                                    'Something went wrong while verifying transactions.',
                                confirmButtonText: 'OK'
                            }).then(() => location.reload());
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Verification Failed',
                            text: xhr.responseJSON?.message ??
                                'Something went wrong while verifying transactions.',
                            confirmButtonText: 'OK'
                        }).then(() => location.reload());
                    }
                });
            }
        });
        window.rejectSettlement = function(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This settlement will be marked as rejected.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('admin/settlement/reject') }}/" + id,
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Rejected!', response.message, 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', xhr.responseJSON?.message ??
                                'Something went wrong.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection
