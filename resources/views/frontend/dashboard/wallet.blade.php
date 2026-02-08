@extends('frontend.dashboard.layouts.master')

@section('content')
    <div class="container" style="margin-top: 20px;">

        <div class="col-md-6">

            <h6>CURRENT WALLET BALANCE: <br> <span style="font-size:55px; color:rgb(0, 162, 255); "><i class="fa fa-inr"
                        aria-hidden="true"></i>₹{{ $walletBalance }}/-</span></h6>
            <h6>CURRENT Reward BALANCE: <br> <span style="font-size:55px; color:rgb(0, 162, 255); "><i class="fa fa-inr"
                        aria-hidden="true"></i>₹{{ $rewardBalance }}/-</span></h6>
            <br>
        </div>
        <div>
            <h3><b>MY WALLET</b></h3>
        </div>
        <hr class="my-4">

        <!-- Data Table -->
        <div class="row">

            <div class="container">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sl.No</th>
                                    <th>Transaction Details</th>
                                    <th>Transaction Type</th>
                                    <th>Amount</th>
                                    <th>Mode</th>
                                    {{-- <th>Remaining Balance</th> --}}
                                    <th>DATE OF TRANSACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userWallet as $key => $data)
                                {{-- {{dd($userWallet[6]);}} --}}
                                <tr>
                                    {{-- Sl.No --}}
                                    <td>{{ $userWallet->firstItem() + $key }}</td>

                                    {{-- Transaction Details --}}
                                    <td>{{ $data->transaction_details ?? 'N/A' }}</td>

                                    {{-- Transaction Type --}}
                                    <td>{{ $data->trans_type ?? 'N/A' }}</td>

                                    {{-- Amount --}}
                                    <td>
                                        @if ($data->trans_type == 'credit')
                                            ₹{{ $data->amount ?? 0 }}
                                        @else
                                            @if ($data->remaining_amount > 0)
                                                ₹{{ $data->used_amount ?? 0 }}
                                            @else
                                                ₹{{ $data->used_points ?? 0 }}
                                            @endif
                                        @endif
                                        
                                    </td>
                                    {{-- Mode --}}
                                    <td>
                                        @if ($data->trans_type == 'credit')
                                            {{ $data->display_type ?? 'NA' }}
                                        @else
                                            @if ($data->remaining_amount > 0)
                                                Reward
                                            @else
                                                Wallet
                                            @endif
                                        @endif
                                    </td>

                                    {{-- Remaining Balance --}}
                                    {{-- <td>{{ $data->remaining_amount ?? 0 }}</td> --}}

                                    {{-- Remaining Points --}}
                                    {{-- <td>
                                        @if ($data->trans_type == 'credit')
                                            {{ $data->remaning_balance ?? 0 }}
                                        @else
                                            
                                        @endif
                                    </td> --}}

                                    {{-- Date --}}
                                    <td>{{ date('d-m-Y', strtotime($data->transaction_date)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>


                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $userWallet->links() }}
        </div>
    </div>
    {{-- modal --}}
    @include('frontend.dashboard.includes.scanmodal')
@endsection
