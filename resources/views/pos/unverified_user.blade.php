@extends('pos.layouts.master')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@section('content')
    <div class="container my-4">
        <h3 class="text-center text-dark mb-4"><b>Customer List</b></h3>

        <!-- Responsive table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Sl.no</th>
                        <th scope="col">Name</th>
                        <th scope="col">UserId</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Billing Amount</th>
                        <th scope="col">Cash/Upi</th>
                        <th scope="col">Payment Mode</th>
                        <th scope="col">Wallet Balance</th>
                        <th scope="col">Transation Date</th>
                        <th scope="col">Status</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($DsrList as $key => $customer)
                        <tr>
                            <td>{{ $DsrList->firstItem() + $key }}</td>
                            <td>{{ $customer->user->name ?? 'N/A' }}</td>
                            <td>{{ $customer->user->user_id ?? 'N/A' }}</td>
                            <td>{{ $customer->user->mobilenumber ?? 'N/A' }}</td>
                            <td>₹{{ $customer->billing_amount ?? 0 }}/-</td>
                            <td>₹{{ $customer->amount ?? 0}}/-</td>
                            <td>{{ $customer->pay_by }}</td>
                            <td>₹{{ $customer->amount_wallet ?? 0}}/-</td>
                            <td>{{ date('d/m/Y', strtotime($customer->transaction_date)) }}</td>
                            <td>
                                <i class="fas fa-ellipsis-h btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editModal"
                                    onclick="editCustomer({{ $customer->id }}, '{{ $customer->billing_amount }}', '{{ $customer->amount }}', '{{ $customer->amount_wallet }}')"></i>
                                @if ($customer->status == 0)
                                    <a href="{{ route('pos.wallet.updateStatus', $customer->id) }}"
                                        class="btn btn-danger btn-sm">Unverified</a>
                                @else
                                    <a href="{{ route('pos.wallet.updateStatus', $customer->id) }}"
                                        class="btn btn-success btn-sm">Verified</a>
                                @endif
                            </td>

                        </tr>
                        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
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
                        </div>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $DsrList->links() }}
            </div>
        </div>
    </div>
    <script>
        function editCustomer(id, billingAmount, amount, amountWallet) {
            // Set form action URL dynamically if you have multiple customers
            // document.getElementById('editCustomerForm').action = 'customers/' + id;

            // Populate form fields with existing customer data
            document.getElementById('billing_amount').value = billingAmount;
            document.getElementById('amount').value = amount;
            document.getElementById('amount_wallet').value = amountWallet;
        }
    </script>
@endsection
