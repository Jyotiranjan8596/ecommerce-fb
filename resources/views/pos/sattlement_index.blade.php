@extends('pos.layouts.master')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('content')
    <div class="container my-4">
        {{-- {{ dd($settlements) }} --}}
        <h3 class="text-center text-dark mb-4"><b>Account Sattlements</b></h3>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
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
                        {{-- <th scope="col">Payment Mode</th>
                        <th scope="col">Wallet Balance</th>
                        <th scope="col">Credit</th>
                        <th scope="col">Debit</th>
                        <th scope="col">Remark</th>
                        <th scope="col">Status</th> --}}
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
                            <td>
                                @if ($settlement->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-success">Paid</span>
                                @endif
                            </td>
                            {{-- <td>
                                <i class="fas fa-ellipsis-h btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editModal"
                                    onclick="editsettlement({{ $settlement->id }}, '{{ $settlement->billing_amount }}', '{{ $settlement->amount }}', '{{ $settlement->amount_wallet }}')"></i>
                                @if ($settlement->status == 0)
                                    <a href="{{ route('pos.wallet.updateStatus', $settlement->id) }}"
                                        class="btn btn-danger btn-sm">Unverified</a>
                                @else
                                    <a href="{{ route('pos.wallet.updateStatus', $settlement->id) }}"
                                        class="btn btn-success btn-sm">Verified</a>
                                @endif
                            </td> --}}

                        </tr>
                        {{-- <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Billing Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editCustomerForm" method="POST"
                                            action="{{ route('pos.customers.update', ['id' => $customer->id]) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label for="billing_amount" class="form-label">Billing Amount</label>
                                                <input type="text" class="form-control" id="billing_amount"
                                                    name="billing_amount" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="amount" class="form-label">Amount</label>
                                                <input type="text" class="form-control" id="amount" name="amount"
                                                    required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="amount_wallet" class="form-label">Amount Wallet</label>
                                                <input type="text" class="form-control" id="amount_wallet"
                                                    name="amount_wallet" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    @endforeach
                </tbody>
            </table>
            {{-- <div class="d-flex justify-content-center">
                {{ $dsrLists->links() }}
            </div> --}}
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
