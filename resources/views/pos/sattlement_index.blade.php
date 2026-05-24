@extends('pos.layouts.master')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('content')
    <div class="container my-4">
        {{-- Header Section --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

            <h3 class="fw-bold text-primary m-0">Account Settlements
            </h3>

            <button class="btn btn-success px-4 shadow-sm">
                <i class="fas fa-file-export me-1"></i> Export
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Sl.no</th>
                        <th scope="col">Date</th>
                        <th scope="col">Total Transaction</th>
                        <th scope="col">Billing Amount</th>
                        <th scope="col">Cash/UPI</th>
                        <th scope="col">Wallet</th>
                        <th scope="col">Reward</th>
                        <th scope="col">Credit</th>
                        <th scope="col">Debit</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($settlements as $key => $settlement)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $settlement->intiate_date ?? 'N/A' }}</td>
                            <td>{{ $settlement->total_transaction ?? 'N/A' }}</td>
                            <td>₹{{ $settlement->total_billing_amount ?? 0 }}/-</td>
                            <td>₹{{ $settlement->by_cash ?? 0 }}/-</td>
                            <td>₹{{ $settlement->by_wallet }}</td>
                            <td>₹{{ $settlement->by_reward ?? 0 }}/-</td>
                            <td>₹{{ $settlement->pos_credit ?? 0 }}</td>
                            <td>₹{{ $settlement->pos_debit ?? 0 }}</td>

                            @if ($settlement->status == 'pending')
                                <td>
                                    <span data-id="{{ $settlement->id }}" class="badge bg-warning text-dark px-3 py-2">
                                        Pending
                                    </span>
                                </td>
                            @elseif ($settlement->status == 'rejected')
                                <td>
                                    <span class="badge bg-danger px-3 py-2">
                                        Rejected
                                    </span>
                                </td>
                            @else
                                <td>
                                    <a href="{{ route('pos.settlement.invoice', $settlement->id) }}"
                                        class="badge bg-success text-decoration-none px-3 py-2">
                                        Settled
                                    </a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
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
    <script>
        $(document).ready(function() {
            // $('#pay-form').on('submit',function(e){
            //     e.prevenetDefault();
            //     var formData = new FormData(this);
            // });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
@endsection
