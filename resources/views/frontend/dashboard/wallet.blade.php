@extends('frontend.dashboard.layouts.master')

@section('content')
    <div class="container" style="margin-top: 20px;">

        <div class="col-md-6">
            <h6>CURRENT WALLET BALANCE: <br>
                <span style="font-size:55px; color:rgb(0, 162, 255);">
                    <i class="fa fa-inr" aria-hidden="true"></i>₹{{ $walletBalance }}/-
                </span>
            </h6>
            <h6>CURRENT REWARD BALANCE: <br>
                <span style="font-size:55px; color:rgb(0, 162, 255);">
                    <i class="fa fa-inr" aria-hidden="true"></i>₹{{ $rewardBalance }}/-
                </span>
            </h6>
            <br>
        </div>

        <div>
            <h3><b>MY Payback</b></h3>
        </div>
        <hr class="my-4">

        {{-- Filter Buttons --}}
        <div class="d-flex align-items-center gap-2 mb-3" style="flex-wrap: wrap;">
            <span style="font-weight: 600; margin-right: 6px;">Filter by Mode:</span>

            <a href="{{ request()->fullUrlWithQuery(['mode_filter' => '']) }}"
                class="btn btn-sm {{ !request('mode_filter') ? 'btn-primary' : 'btn-outline-primary' }}">
                All
            </a>

            <a href="{{ request()->fullUrlWithQuery(['mode_filter' => 'wallet']) }}"
                class="btn btn-sm {{ request('mode_filter') == 'wallet' ? 'btn-warning' : 'btn-outline-warning' }}">
                <i class="fa fa-credit-card" aria-hidden="true"></i> Wallet
            </a>

            <a href="{{ request()->fullUrlWithQuery(['mode_filter' => 'reward']) }}"
                class="btn btn-sm {{ request('mode_filter') == 'reward' ? 'btn-success' : 'btn-outline-success' }}">
                <i class="fa fa-gift" aria-hidden="true"></i> Reward
            </a>
        </div>

        {{-- Data Table with Fixed Header & Scrollable Body --}}
        <div class="row">
            <div class="container">
                <div class="col-md-12">

                    <style>
                        .fixed-header-table-wrapper {
                            max-height: 420px;
                            overflow-y: auto;
                            border: 1px solid #dee2e6;
                            position: relative;
                        }

                        .fixed-header-table-wrapper table {
                            margin-bottom: 0;
                        }

                        .fixed-header-table-wrapper thead th {
                            position: sticky;
                            top: 0;
                            z-index: 2;
                            background-color: #fff;
                            border-bottom: 2px solid #dee2e6;
                        }
                    </style>

                    <div class="fixed-header-table-wrapper">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sl.No</th>
                                    <th>Transaction Details</th>
                                    <th>Transaction Type</th>
                                    <th>Amount</th>
                                    <th>Mode</th>
                                    <th>DATE OF TRANSACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($userWallet as $key => $data)
                                    @php
                                        $isCredit = in_array(strtolower($data->trans_type ?? ''), ['credit']);
                                        if ($isCredit) {
                                            $rowMode = strtolower($data->display_type ?? 'wallet');
                                        } else {
                                            $rowMode = $data->remaining_amount > 0 ? 'reward' : 'wallet';
                                        }
                                        $modeFilter = strtolower(request('mode_filter', ''));
                                        $showRow = !$modeFilter || $rowMode === $modeFilter;
                                    @endphp

                                    @if ($showRow)
                                        <tr>
                                            {{-- Sl.No --}}
                                            <td>{{ $userWallet->firstItem() + $key }}</td>

                                            {{-- Transaction Details --}}
                                            <td>{{ $data->transaction_details ?? 'N/A' }}</td>

                                            {{-- Transaction Type --}}
                                            <td>{{ $data->trans_type ?? 'N/A' }}</td>

                                            {{-- Amount --}}
                                            <td>
                                                @if ($data->trans_type == 'credit' || $data->trans_type == 'Credit')
                                                    ₹{{ $data->amount ?? 0 }}
                                                @else
                                                    @if ($data->used_amount > 0)
                                                        ₹{{ $data->used_amount ?? 0 }}
                                                    @else
                                                        ₹{{ $data->used_points ?? 0 }}
                                                    @endif
                                                @endif
                                            </td>

                                            {{-- Mode --}}
                                            <td>
                                                @if ($data->trans_type == 'credit' || $data->trans_type == 'Credit')
                                                    {{ $data->display_type ?? 'NA' }}
                                                @else
                                                    @if ($data->remaining_amount > 0)
                                                        Reward
                                                    @else
                                                        Wallet
                                                    @endif
                                                @endif
                                            </td>

                                            {{-- Date --}}
                                            <td>{{ date('d-m-Y', strtotime($data->transaction_date)) }}</td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No transactions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        {{-- Pagination Links --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $userWallet->links() }}
        </div>

    </div>
    {{-- modal --}}
    @include('frontend.dashboard.includes.scanmodal')
@endsection
