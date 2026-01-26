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
                                    {{-- <th>USER ID</th> --}}
                                    <th>Transaction Details</th>
                                    {{-- <th>INVOICE</th> --}}
                                    <th>Transaction Type</th>
                                    <th>Wallet</th>
                                    <th>Reward</th>
                                    <th>Remaining Wallet</th>
                                    <th>Remaning Reward</th>
                                    {{-- <th>MOBILE NUMBER</th> --}}
                                    <th>DATE OF TRANSACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userWallet as $key => $data)
                                    {{-- {{ dd($data); }} --}}
                                    <tr>
                                        <td>{{ $userWallet->firstItem() + $key }}</td>
                                        {{-- <td>{{ $data->user->user_id }}</td> --}}
                                        <td>{{ $data->transaction_details }}</td>
                                        {{-- <td>{{ $data->invoice }}</td> --}}
                                        <td>{{ $data->trans_type ?? 'N/A' }}</td>
                                        @if ($data->trans_type == 'credit')
                                            <td>₹{{ $data->wallet_amount ?? 0 }}</td>
                                            <td>₹{{ $data->reward_points ?? 0 }}</td>
                                        @else
                                            <td>₹{{ $data->used_amount ?? 0 }}</td>
                                            <td>₹{{ $data->used_points ?? 0 }}</td>
                                        @endif
                                        <td>{{ $data->remaining_amount }}</td>
                                        <td>{{ $data->remaining_points }}</td>
                                        {{-- <td>{{ $data->mobilenumber }}</td> --}}
                                        <td>{{ date('d-m-Y', strtotime($data->transaction_date)) }}</td>
                                        </td>
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
