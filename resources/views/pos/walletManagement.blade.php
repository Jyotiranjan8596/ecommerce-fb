@extends('pos.layouts.master')

@if (session('current_balance'))
    <div class="alert alert-success text-center" role="alert">
        Current Wallet Balance: <strong>{{ number_format(session('current_balance'), 2) }} /-</strong>
    </div>
@endif

<style>
    .table-responsive::-webkit-scrollbar {
        width: 8px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .scrollable-table {
        max-height: 300px;
        overflow-y: auto;
        overflow-x: auto;
    }

    /* Mobile styling adjustments */
    @media (max-width: 768px) {
        .card-box {
            padding: 20px;
        }

        h3,
        h4 {
            font-size: 1.2rem;
        }

        .btn {
            width: 100%;
            margin-top: 10px;
        }

        .scrollable-table table {
            width: 100%;
            font-size: 0.9rem;
        }

        .scrollable-table th,
        .scrollable-table td {
            padding: 8px;
        }
    }
</style>

@section('content')
    <div style="margin-top: 15px;">
        <h4><b>Wallet Manage</b></h4>
        <hr>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card-box p-4 shadow-lg rounded">
                <div class="form-group row">
                    <div class="col-md-8">
                        <label for="myid">USER ID</label>
                        <input name="user_id" type="text" value="{{ $user->user_id }}" class="form-control">
                    </div>
                </div>
                <h3 class="mt-4 text-primary">WALLET INFO FOR <span class="text-danger">{{ $user->name }}</span></h3>
                <h4 class="mt-3 mb-4"><b>MANAGE WALLET</b></h4>
                <form action="{{ route('pos.dsr.list') }}" method="post">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <input type="hidden" name="mobilenumber" value="{{ $user->mobilenumber }}">
                    <input type="hidden" name="transaction_date" value="{{ now()->format('Y-m-d') }}">

                    <div class="form-group">
                        <label for="amount">BILLING AMOUNT</label>
                        <input name="amount" id="amount" type="number" class="form-control" required min="0"
                            step="any" oninput="checkWalletBalance()">
                    </div>

                    <div class="form-group mt-2">
                        <label for="pay_by">PAYMENT BY</label>
                        <select name="pay_by" id="pay_by" class="form-control" required onchange="checkWalletBalance()">
                            <option value="wallet">WALLET</option>
                            <option value="cash">CASH</option>
                            <option value="upi">UPI</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="paying_amount">PAYING AMOUNT</label>
                        <input name="paying_amount" id="paying_amount" type="number" class="form-control" required
                            min="0">
                    </div>
                    <div class="wallet-balance mt-2">
                        <strong>Your Wallet Balance: </strong>
                        <span id="wallet_balance">{{ $walletBalance }}</span>
                        <input type="hidden" name="wallet_balance" value="{{ $walletBalance }}">
                    </div>

                    <div class="insufficient-balance mt-2" style="display: none;">
                        <strong>Wallet balance is insufficient. Please choose an alternative payment method:</strong>
                        <select name="alternative_pay_by" id="alternative_pay_by" class="form-control" required>
                            <option value="cash">CASH</option>
                            <option value="upi">UPI</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">SUBMIT</button>
                </form>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card-box p-4 shadow-lg rounded">
                <h4 class="text-primary"><b>TRANSACTIONS</b></h4>
                <form method="GET" action="{{ route('pos.wallet.manage', $user->id) }}">
                    <div class="input-group mb-3">
                        <input type="date" class="form-control" name="transaction_date" id="transaction_date"
                            value="{{ request('transaction_date') }}">
                        <button class="btn btn-success" type="submit">Search Transactions</button>
                    </div>
                </form>
                <!-- Transactions Table with Scrolling -->
                <div class="scrollable-table">
                    <table id="tech-companies-1" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.no</th>
                                <th>Invoice</th>
                                <th>PosId</th>
                                <th>Amount</th>
                                <th>Wallet Amount</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($transactionsNotFound)
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="alert alert-danger" role="alert">
                                            No transaction record found for the selected date.
                                        </div>
                                    </td>
                                </tr>
                            @else
                                @foreach ($walletList as $key => $wallet)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $wallet->invoice }}</td>
                                        <td>{{ $wallet->getPos ? $wallet->getPos->user_id : 'N/A' }}</td>
                                        <td>{{ $wallet->amount ?? 0 }}/-</td>
                                        <td>{{ $wallet->amount_wallet ?? 0 }}/-</td>
                                        <td>{{ $wallet->insert_date }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        let isDeducted = false; // Flag to ensure deduction happens only once
        let originalWalletBalance = 0; // Store the original wallet balance

        function checkWalletBalance() {
            const billingAmount = parseFloat(document.getElementById('amount').value) || 0;
            const walletBalanceElement = document.getElementById('wallet_balance');
            let walletBalance = parseFloat(walletBalanceElement.textContent) || 0;

            // Save the original wallet balance when the function runs for the first time
            if (originalWalletBalance === 0) {
                originalWalletBalance = walletBalance;
            }

            const payBySelect = document.getElementById('pay_by');
            const payingAmountField = document.getElementById('paying_amount');
            const insufficientBalanceDiv = document.querySelector('.insufficient-balance');
            const alternativePayBySelect = document.getElementById('alternative_pay_by');

            if (payBySelect.value === "wallet") {
                // When "wallet" is selected, reset wallet balance to the original amount
                walletBalanceElement.textContent = originalWalletBalance.toFixed(2);

                // Show the full billing amount
                payingAmountField.value = billingAmount.toFixed(2);
                insufficientBalanceDiv.style.display = 'none';
                alternativePayBySelect.required = false;

                // Reset the deduction flag
                isDeducted = false;

            } else if (payBySelect.value === "cash" || payBySelect.value === "upi") {
                const walletDeduction = billingAmount * 0.05; // Calculate 5% charge

                if (!isDeducted) {
                    let deductedFromWallet = 0;

                    if (walletBalance >= walletDeduction) {
                        // Wallet balance is sufficient for 5% deduction
                        deductedFromWallet = walletDeduction;
                        walletBalance -= walletDeduction;
                    } else {
                        // Wallet balance is insufficient, deduct whatever is available
                        deductedFromWallet = walletBalance;
                        walletBalance = 0; // Wallet is now empty
                    }

                    // Update wallet balance
                    walletBalanceElement.textContent = walletBalance.toFixed(2);

                    // Calculate payable amount by subtracting the actual deduction
                    const updatedPayableAmount = (billingAmount - deductedFromWallet).toFixed(2);
                    payingAmountField.value = updatedPayableAmount;

                    isDeducted = true;
                }

                insufficientBalanceDiv.style.display = 'none';
                alternativePayBySelect.required = false;

            } else {
                // For other payment methods, show full billing amount
                payingAmountField.value = billingAmount.toFixed(2);
                insufficientBalanceDiv.style.display = 'none';
                alternativePayBySelect.required = false;

                // Reset the deduction flag
                isDeducted = false;
            }
        }

        // Event listeners for real-time updates
        document.getElementById('pay_by').addEventListener('change', checkWalletBalance);
        document.getElementById('amount').addEventListener('input', checkWalletBalance);
    </script>
@endsection
