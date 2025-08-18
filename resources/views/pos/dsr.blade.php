@extends('pos.layouts.master')

@section('content')
    <div class="container">
        <h4 class="text-center text-dark mb-4"><b>DAILY SALES REPORT</b></h4>

        <!-- Top Row: File Upload, Search, and Export -->
        <div class="row">
            <!-- File Upload -->
            {{-- <div class="col-md-4 mb-3">
                <form action="{{ route('pos.dsr.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group">
                        <input type="file" class="form-control" name="file" accept=".csv">
                        <button class="btn btn-secondary" type="submit">
                            <svg width="24px" height="24px" viewBox="0 -2 30 30" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" fill="#ffffff" stroke="#ffffff">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <title>upload</title>
                                    <desc>Created with Sketch Beta.</desc>
                                    <defs> </defs>
                                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                                        sketch:type="MSPage">
                                        <g id="Icon-Set-Filled" sketch:type="MSLayerGroup"
                                            transform="translate(-571.000000, -676.000000)" fill="#ffffff">
                                            <path
                                                d="M599,692 C597.896,692 597,692.896 597,694 L597,698 L575,698 L575,694 C575,692.896 574.104,692 573,692 C571.896,692 571,692.896 571,694 L571,701 C571,701.479 571.521,702 572,702 L600,702 C600.604,702 601,701.542 601,701 L601,694 C601,692.896 600.104,692 599,692 L599,692 Z M582,684 L584,684 L584,693 C584,694.104 584.896,695 586,695 C587.104,695 588,694.104 588,693 L588,684 L590,684 C590.704,684 591.326,684.095 591.719,683.7 C592.11,683.307 592.11,682.668 591.719,682.274 L586.776,676.283 C586.566,676.073 586.289,675.983 586.016,675.998 C585.742,675.983 585.465,676.073 585.256,676.283 L580.313,682.274 C579.921,682.668 579.921,683.307 580.313,683.7 C580.705,684.095 581.608,684 582,684 L582,684 Z"
                                                id="upload" sketch:type="MSShapeGroup"> </path>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </button>
                    </div>
                </form>
            </div> --}}

            <!-- Search -->
            <div class="col-md-4 mb-3">
                <form method="GET" action="{{ route('pos.dsr') }}">
                    <div class="input-group">
                        <input type="number" class="form-control" name="search" placeholder="Search By...">
                        <button class="btn btn-secondary" type="submit">SEARCH</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Date Filter -->
        <div class="row justify-content-end">
            <div class="col-12">
                <form method="GET" action="{{ route('pos.dsr') }}">
                    <div class="row">
                        <div class="col-6">
                            <label for="start_date"><b>From:</b></label>
                            <input type="date" value="{{ request('start_date') ?? now()->toDateString() }}"
                                class="form-control mb-2" name="start_date" id="start_date">
                        </div>
                        {{-- <div class="col-6">
                            <label for="end_date"><b>To:</b></label>
                            <input type="date" class="form-control mb-2" name="end_date" id="end_date">
                        </div> --}}
                        <div class="col-6 me-3">
                            <button class="btn btn-secondary w-50" type="submit">FILTER</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Divider -->
        <hr class="my-4">
        <div class="d-flex justify-content-end ">
            <div class="d-flex justify-content-end ">
                <button id="summary-btn" type="button" data-target="#exampleModalCenter"
                    class="btn btn-secondary text-white me-3 mb-3" data-toggle="modal">Summary</button>
            </div>
            <div class="d-flex justify-content-end ">
                <button id="approve-btn" type="button" class="btn btn-success text-white me-3 mb-3">Verify All</button>
            </div>
            <!-- Export -->
            <div class="d-flex justify-content-end ">
                <form method="GET" action="{{ route('pos.dsr.export') }}">
                    <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                    <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                    <button class="btn btn-danger" type="submit">EXPORT</button>
                </form>
            </div>
        </div>
        <!-- Data Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Sl.No</th>
                        <th>INVOICE</th>
                        {{-- <th>POS ID</th> --}}
                        <th>NAME</th>
                        <th>MOBILE</th>
                        <th>BILLING AMOUNT</th>
                        <th>Cash/Upi</th>
                        {{-- <th>Payment Mode</th> --}}
                        <th>BY Wallet</th>
                        <th>BY Reward</th>
                        {{-- <th>PAY BY</th> same as Payment mode --}}
                        <th>NET AMOUNT</th>
                        <th>TC</th> {{-- Transaction charge --}}
                        <th>TRANSACTION DATE</th>
                        <th>REMARK</th>
                        <th>STATUS</th>
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
                                {{-- <td>{{ $data->getPos ? $data->getPos->user_id : '' }}</td> --}}
                                <td>{{ optional($data->user)->name }}</td>
                                <td>{{ $data->mobilenumber }}</td>
                                <td>₹{{ $data->billing_amount ?? 0 }}/-</td>
                                <td>{{ $data->pay_by }}</td>
                                <td>{{ $data->amount_wallet }}</td>
                                <td>{{ $data->reward_amount }}</td>
                                <td>{{ $data->amount }}</td>
                                <td>{{ $data->transaction_amount }}</td>
                                {{-- <td>{{ date('d/m/Y', strtotime($data->transaction_date)) }}</td> --}}
                                <td>{{ date('d-m-Y h:i A', strtotime($data->insert_date)) }}</td>
                                <td>N/A</td>
                                <td>
                                    {{-- <i class="fas fa-ellipsis-h btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        onclick="editCustomer({{ $data->id }}, '{{ $data->billing_amount }}', '{{ $data->amount }}', '{{ $data->amount_wallet }}')"></i> --}}
                                    @if ($data->status == null || $data->status == 0)
                                        <a href="{{ route('pos.wallet.updateStatus', $data->id) }}"
                                            class="btn btn-danger btn-sm">Unverified</a>
                                    @else
                                        <a href="{{ route('pos.wallet.updateStatus', $data->id) }}"
                                            class="btn btn-success btn-sm">Verified</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $wallets->links() }}
        </div>
    </div>
    <!-- Modal -->
    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content shadow-lg rounded">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="exampleModalLongTitle">DSR Summary</h5>{{ request()->start_date }} to
                    {{ request()->end_date }}
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @if ($totalTransactions == 0)
                    <div class="modal-body">
                        There are no transactions for today !
                    </div>
                @else
                    <div class="modal-body">
                        <div class="container">
                            <div class="row mb-2">
                                <div class="col-6 font-weight-bold">Total Transactions:</div>
                                <div class="col-2 text-right" id="total-transactions-temp">--</div>
                                <div class="col-4 text-right" id="total-transactions-val">{{ $totalTransactions }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6 font-weight-bold">Total Billing Amount:</div>
                                <div class="col-2 text-right" id="billing-amount-temp">--</div>
                                <div class="col-4 text-right" id="billing-amount-val">₹ {{ $totalBillingAmount }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6 font-weight-bold">Pay by Cash/UPI:</div>
                                <div class="col-2 text-right" id="cash-upi-temp">--</div>
                                <div class="col-4 text-right" id="cash-upi-val">₹ {{ $payByCashOrUpi }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6 font-weight-bold">Pay by Wallet:</div>
                                <div class="col-2 text-right" id="wallet-temp">--</div>
                                <div class="col-4 text-right" id="wallet-val">₹ {{ $payByWallet }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6 font-weight-bold">Pay by Reward:</div>
                                <div class="col-2 text-right" id="reward-temp">--</div>
                                <div class="col-4 text-right" id="reward-val">₹ {{ $payByReward }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6 font-weight-bold">Credit Amount:</div>
                                <div class="col-2 text-right text-success" id="credit-amount-temp">--</div>
                                <div class="col-4 text-right" id="credit-amount-val">₹ {{ $creditAmount }}</div>
                            </div>
                            <div class="row">
                                <div class="col-6 font-weight-bold">Debit Amount:</div>
                                <div class="col-2 text-right text-danger" id="debit-amount-temp">--</div>
                                <div class="col-4 text-right" id="debit-amount-val">₹ {{ $debitAmount }}</div>
                            </div>

                        </div>
                    </div>
                @endif
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Optional: Save or Export button -->
                    <button type="button" id="submitData" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script> --}}
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {
            $('#approve-btn').on('click', function() {
                console.log("Coming Here!");

                Swal.fire({
                    title: 'Verify All Records?',
                    text: "Are you sure you want to verify all transactions?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Verify All'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('pos.dsr.verify.transaction') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                wallet_ids: @json($wallets->pluck('id'))
                            },
                            success: function(response) {
                                if (response.success) {
                                    if (response.count > 0) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Verified Successfully',
                                            text: response.message,
                                            confirmButtonText: 'OK'
                                        }).then(() => location.reload());
                                    } else {
                                        Swal.fire({
                                            icon: 'info',
                                            title: 'No transactions.',
                                            text: response.message,
                                            confirmButtonText: 'OK'
                                        });
                                    }
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Verification Failed',
                                        text: 'Something went wrong while verifying transactions.',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error("AJAX Error:", error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Verification Failed',
                                    text: 'Something went wrong while verifying transactions.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

            $("#submitData").on("click", function() {
                // Collect data
                let startDate = $("#start_date").val();
                console.log(startDate);

                if (!startDate) {
                    let today = new Date();
                    let month = ("0" + (today.getMonth() + 1)).slice(-2);
                    let day = ("0" + today.getDate()).slice(-2);
                    startDate = today.getFullYear() + "-" + month + "-" + day;
                }
                let data = {
                    date: startDate,
                    total_transactions: $("#total-transactions-val").text().trim(),
                    billing_amount: $("#billing-amount-val").text().replace("₹", "").trim(),
                    cash_upi: $("#cash-upi-val").text().replace("₹", "").trim(),
                    wallet: $("#wallet-val").text().replace("₹", "").trim(),
                    reward: $("#reward-val").text().replace("₹", "").trim(),
                    credit_amount: $("#credit-amount-val").text().replace("₹", "").trim(),
                    debit_amount: $("#debit-amount-val").text().replace("₹", "").trim(),
                    _token: "{{ csrf_token() }}" // for Laravel security
                };

                // Send AJAX
                $.ajax({
                    url: "{{ route('pos.save.summary.data') }}",
                    type: "POST",
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Verified Successfully',
                                text: response.message ?? 'Payment Submited!',
                                confirmButtonText: 'OK'
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Verification Failed',
                                text: response.message ??
                                    'Something went wrong while submit payment.',
                                confirmButtonText: 'OK'
                            }).then(() => location.reload());
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Verification Failed',
                            text: xhr.responseJSON?.message ??
                                'Something went wrong while submit payment.',
                            confirmButtonText: 'OK'
                        }).then(() => location.reload());
                    }
                });
            });
        });
    </script>


@endsection
