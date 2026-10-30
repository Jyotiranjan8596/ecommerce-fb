@extends('frontend.dashboard.layouts.master')

@section('content')
    @php
        $modeFilter = strtolower(request('mode_filter', ''));
        $walletTransactions = $userWallet->filter(function ($transaction) use ($modeFilter) {
            if (!$modeFilter) {
                return true;
            }

            $isCredit = in_array(strtolower($transaction->trans_type ?? ''), ['credit']);
            $rowMode = $isCredit
                ? strtolower($transaction->display_type ?? 'wallet')
                : ($transaction->remaining_amount > 0 ? 'reward' : 'wallet');

            return $rowMode === $modeFilter;
        });
    @endphp
    <div class="container-fluid px-2 px-md-3 py-3 payback-page">
        <section class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
            <div class="card-header border-0 px-4 py-4 payback-header">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <div class="text-uppercase small fw-semibold mb-1 payback-eyebrow">Account overview</div>
                        <h1 class="h4 mb-1 fw-bold payback-title">My Payback</h1>
                        <p class="mb-0 payback-subtitle">View your wallet and reward balances and payback history.</p>
                    </div>
                    <span class="badge rounded-pill px-3 py-2 payback-overview-badge">
                        <i class="fa fa-history me-1" aria-hidden="true"></i> Payback history
                    </span>
                </div>
            </div>

            <div class="card-body p-3 p-lg-4">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <section class="payback-balance-card payback-wallet-balance h-100" aria-label="Current wallet balance">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <div class="payback-balance-label">Current wallet balance</div>
                                    <div class="payback-balance-amount">₹{{ $walletBalance }}/-</div>
                                </div>
                                <span class="payback-balance-icon" aria-hidden="true">
                                    <i class="fa fa-credit-card"></i>
                                </span>
                            </div>
                            <div class="payback-balance-caption">Available in your wallet</div>
                        </section>
                    </div>
                    <div class="col-12 col-md-6">
                        <section class="payback-balance-card payback-reward-balance h-100" aria-label="Current reward balance">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <div class="payback-balance-label">Current reward balance</div>
                                    <div class="payback-balance-amount">₹{{ $rewardBalance }}/-</div>
                                </div>
                                <span class="payback-balance-icon" aria-hidden="true">
                                    <i class="fa fa-gift"></i>
                                </span>
                            </div>
                            <div class="payback-balance-caption">Rewards earned through your activity</div>
                        </section>
                    </div>
                </div>
            </div>
        </section>

        <section class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header border-0 bg-white px-3 px-md-4 pt-4 pb-3">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div>
                        <h2 class="h5 mb-1 fw-bold text-dark">Payback History</h2>
                        <p class="small text-muted mb-0">Review wallet and reward credits. Scroll to view your payback details.</p>
                        <p class="small text-muted mb-0 mt-1">{{ $walletTransactions->count() }} transactions</p>
                    </div>

                    <nav class="payback-filter" aria-label="Filter transactions by mode">
                        <span class="payback-filter-label">Filter by mode</span>
                        <div class="payback-filter-options">
                            <a href="{{ request()->fullUrlWithQuery(['mode_filter' => '']) }}"
                                class="payback-filter-option {{ !request('mode_filter') ? 'active' : '' }}"
                                @if (!request('mode_filter')) aria-current="page" @endif>
                                All
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['mode_filter' => 'wallet']) }}"
                                class="payback-filter-option {{ request('mode_filter') == 'wallet' ? 'active' : '' }}"
                                @if (request('mode_filter') == 'wallet') aria-current="page" @endif>
                                <i class="fa fa-credit-card" aria-hidden="true"></i> Wallet
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['mode_filter' => 'reward']) }}"
                                class="payback-filter-option {{ request('mode_filter') == 'reward' ? 'active' : '' }}"
                                @if (request('mode_filter') == 'reward') aria-current="page" @endif>
                                <i class="fa fa-gift" aria-hidden="true"></i> Reward
                            </a>
                        </div>
                    </nav>
                </div>
            </div>

            <div class="card-body px-3 px-md-4 pt-2 pb-3">
                <form method="GET" action="{{ route('user.wallet') }}" class="row g-2 align-items-end mb-3 payback-date-filter">
                    @if (request('mode_filter'))
                        <input type="hidden" name="mode_filter" value="{{ request('mode_filter') }}">
                    @endif
                    <div class="col-12 col-sm-5 col-lg-4">
                        <label for="from_date" class="form-label mb-1">From date</label>
                        <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-12 col-sm-5 col-lg-4">
                        <label for="to_date" class="form-label mb-1">To date</label>
                        <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-12 col-sm-auto">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search me-1" aria-hidden="true"></i> Search</button>
                        @if (request('from_date') || request('to_date'))
                            <a href="{{ route('user.wallet', request('mode_filter') ? ['mode_filter' => request('mode_filter')] : []) }}" class="btn btn-outline-secondary">Clear dates</a>
                        @endif
                    </div>
                </form>
                <div class="table-responsive payback-table-wrap" tabindex="0" aria-label="Scrollable payback history">
                    <table class="table align-middle mb-0 payback-table">
                        <thead>
                            <tr>
                                <th scope="col">Sl.No</th>
                                <th scope="col">Transaction details</th>
                                <th scope="col">Transaction type</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Mode</th>
                                <th scope="col">Date of transaction</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($walletTransactions as $key => $data)
                                @php
                                    $isCredit = in_array(strtolower($data->trans_type ?? ''), ['credit']);
                                @endphp

                                <tr>
                                    <td data-label="Sl.No">{{ $key + 1 }}</td>
                                    <td data-label="Transaction details" class="payback-details">
                                        {{ $data->transaction_details ?? 'N/A' }}
                                    </td>
                                    <td data-label="Transaction type">
                                        <span class="payback-type {{ $isCredit ? 'payback-type-credit' : 'payback-type-debit' }}">
                                            {{ $data->trans_type ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td data-label="Amount" class="payback-amount">
                                        @if ($isCredit)
                                            ₹{{ $data->amount ?? 0 }}
                                        @elseif ($data->used_amount > 0)
                                            ₹{{ $data->used_amount ?? 0 }}
                                        @else
                                            ₹{{ $data->used_points ?? 0 }}
                                        @endif
                                    </td>
                                    <td data-label="Mode">
                                        @if ($isCredit)
                                            <span class="payback-mode {{ strtolower($data->display_type ?? '') === 'reward' ? 'payback-mode-reward' : 'payback-mode-wallet' }}">
                                                {{ $data->display_type ?? 'NA' }}
                                            </span>
                                        @elseif ($data->remaining_amount > 0)
                                            <span class="payback-mode payback-mode-reward">Reward</span>
                                        @else
                                            <span class="payback-mode payback-mode-wallet">Wallet</span>
                                        @endif
                                    </td>
                                    <td data-label="Date of transaction">
                                        {{ date('d-m-Y', strtotime($data->transaction_date)) }}
                                    </td>
                                </tr>
                            @empty
                                <tr class="payback-empty-row">
                                    <td colspan="6" class="text-center py-5">
                                        <div class="payback-empty-state">
                                            <span class="payback-empty-icon" aria-hidden="true">
                                                <i class="fa fa-receipt"></i>
                                            </span>
                                            <strong>No transactions found.</strong>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </section>
    </div>

    <style>
        .payback-page {
            color: #172033;
        }

        .payback-page .payback-header {
            color: #fff;
            background: linear-gradient(115deg, #0f766e 0%, #087f8c 55%, #145da0 100%);
        }

        .payback-page .payback-eyebrow {
            color: #d5fff5;
            letter-spacing: .08em;
        }

        .payback-page .payback-title {
            color: #fff;
            text-shadow: 0 1px 2px rgba(0, 0, 0, .18);
        }

        .payback-page .payback-subtitle {
            color: #e3f4f4;
            font-size: .92rem;
        }

        .payback-page .payback-overview-badge {
            border: 1px solid rgba(255, 255, 255, .45);
            background: rgba(255, 255, 255, .15);
            color: #fff;
            font-size: .88rem;
        }

        .payback-page .payback-balance-card {
            position: relative;
            overflow: hidden;
            padding: clamp(1.15rem, 2.5vw, 1.65rem);
            border: 1px solid #e1ebef;
            border-radius: 1rem;
            background: linear-gradient(135deg, #fff 0%, #f5fbfa 100%);
            box-shadow: 0 .35rem 1rem rgba(23, 32, 51, .045);
        }

        .payback-page .payback-balance-card::after {
            position: absolute;
            right: -2.25rem;
            bottom: -3.25rem;
            width: 8rem;
            height: 8rem;
            border-radius: 50%;
            background: rgba(8, 127, 140, .055);
            content: "";
        }

        .payback-page .payback-reward-balance {
            background: linear-gradient(135deg, #fff 0%, #f7f8ff 100%);
        }

        .payback-page .payback-reward-balance::after {
            background: rgba(20, 93, 160, .06);
        }

        .payback-page .payback-balance-label {
            color: #536477;
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: .055em;
            text-transform: uppercase;
        }

        .payback-page .payback-balance-amount {
            margin-top: .4rem;
            color: #075e59;
            font-size: clamp(1.8rem, 4vw, 2.55rem);
            font-weight: 850;
            letter-spacing: -.035em;
            line-height: 1.15;
            overflow-wrap: anywhere;
        }

        .payback-page .payback-reward-balance .payback-balance-amount {
            color: #145da0;
        }

        .payback-page .payback-balance-caption {
            margin-top: .55rem;
            color: #718096;
            font-size: .84rem;
        }

        .payback-page .payback-balance-icon {
            position: relative;
            z-index: 1;
            display: grid;
            width: 2.8rem;
            height: 2.8rem;
            flex: 0 0 2.8rem;
            place-items: center;
            border: 1px solid #d4eeea;
            border-radius: .85rem;
            background: #eaf6f4;
            color: #087f8c;
            font-size: 1.1rem;
        }

        .payback-page .payback-reward-balance .payback-balance-icon {
            border-color: #dce6f4;
            background: #edf3fb;
            color: #145da0;
        }

        .payback-page .payback-filter {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .65rem;
        }

        .payback-page .payback-filter-label {
            color: #59697b;
            font-size: .82rem;
            font-weight: 750;
        }

        .payback-page .payback-filter-options {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
        }

        .payback-page .payback-filter-option {
            display: inline-flex;
            min-height: 2.25rem;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            padding: .4rem .8rem;
            border: 1px solid #d9e5e9;
            border-radius: 999px;
            background: #fff;
            color: #425367;
            font-size: .83rem;
            font-weight: 750;
            text-decoration: none;
            transition: background .2s ease, border-color .2s ease, color .2s ease;
        }

        .payback-page .payback-filter-option:hover {
            border-color: #087f8c;
            background: #f0f8f7;
            color: #075e59;
        }

        .payback-page .payback-filter-option.active {
            border-color: #087f8c;
            background: #087f8c;
            color: #fff;
            box-shadow: 0 .2rem .55rem rgba(8, 127, 140, .18);
        }

        .payback-page .payback-table-wrap {
            max-height: 460px;
            overflow-x: auto;
            overflow-y: auto;
            border: 1px solid #e4ebf1;
            border-radius: .85rem;
            scrollbar-color: #9abfc0 #f0f5f6;
            scrollbar-width: thin;
        }

        .payback-page .payback-table-wrap::-webkit-scrollbar {
            width: .55rem;
            height: .55rem;
        }

        .payback-page .payback-table-wrap::-webkit-scrollbar-thumb {
            border: 2px solid #f0f5f6;
            border-radius: 999px;
            background: #9abfc0;
        }

        .payback-page .payback-table-wrap::-webkit-scrollbar-track {
            background: #f0f5f6;
        }

        .payback-page .payback-table {
            min-width: 780px;
            color: #263449;
            /* The global .table rule sets overflow: hidden, which makes the table
               itself (not the scrollable .payback-table-wrap) the sticky
               positioning context, so the sticky header scrolls away with the
               rows instead of staying fixed. Restore normal overflow here so
               sticky headers stick relative to the scrollable wrapper. */
            overflow: visible;
        }

        .payback-page .payback-table thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            padding: .9rem 1rem;
            border-bottom: 1px solid #dfe8ee;
            background: #f0f7f7;
            color: #174b54;
            font-size: .73rem;
            font-weight: 800;
            letter-spacing: .05em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .payback-page .payback-table tbody td {
            padding: .9rem 1rem;
            border-color: #edf1f5;
            font-size: .9rem;
            vertical-align: middle;
        }

        .payback-page .payback-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .payback-page .payback-table tbody tr:hover {
            background: #f8fbfc;
        }

        .payback-page .payback-details {
            color: #25364a;
            font-weight: 650;
        }

        .payback-page .payback-amount {
            color: #17324d;
            font-weight: 800;
            white-space: nowrap;
        }

        .payback-page .payback-type,
        .payback-page .payback-mode {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: .28rem .65rem;
            border-radius: 999px;
            font-size: .76rem;
            font-weight: 750;
            white-space: nowrap;
        }

        .payback-page .payback-type-credit,
        .payback-page .payback-mode-wallet {
            background: #e8f5f1;
            color: #08735f;
        }

        .payback-page .payback-type-debit {
            background: #fff3e8;
            color: #9b5316;
        }

        .payback-page .payback-mode-reward {
            background: #edf3fb;
            color: #145da0;
        }

        .payback-page .payback-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .65rem;
            color: #66768a;
        }

        .payback-page .payback-empty-icon {
            display: grid;
            width: 2.8rem;
            height: 2.8rem;
            place-items: center;
            border-radius: 50%;
            background: #edf5f6;
            color: #087f8c;
            font-size: 1.1rem;
        }

        @media (max-width: 767.98px) {
            .payback-page .payback-header {
                padding: 1.2rem !important;
            }

            .payback-page .payback-filter {
                align-items: flex-start;
                flex-direction: column;
            }

            .payback-page .payback-table-wrap {
                max-height: min(65vh, 560px);
                overflow-x: hidden;
                overflow-y: auto;
                border: 0;
            }

            .payback-page .payback-table {
                min-width: 0;
                border-collapse: separate;
                border-spacing: 0 .7rem;
            }

            .payback-page .payback-table thead {
                display: none;
            }

            .payback-page .payback-table tbody {
                display: block;
            }

            .payback-page .payback-table tbody tr:not(.payback-empty-row) {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                overflow: hidden;
                border: 1px solid #e3ebf0;
                border-radius: .85rem;
                background: #fff;
                box-shadow: 0 .25rem .8rem rgba(23, 32, 51, .045);
            }

            .payback-page .payback-table tbody tr:not(.payback-empty-row) td {
                min-width: 0;
                padding: .7rem .85rem;
                border: 0;
                overflow-wrap: anywhere;
            }

            .payback-page .payback-table tbody tr:not(.payback-empty-row) td::before {
                display: block;
                margin-bottom: .22rem;
                color: #64748b;
                content: attr(data-label);
                font-size: .67rem;
                font-weight: 800;
                letter-spacing: .04em;
                text-transform: uppercase;
            }

            .payback-page .payback-table tbody tr:not(.payback-empty-row) td[data-label="Sl.No"] {
                display: none;
            }

            .payback-page .payback-table tbody tr:not(.payback-empty-row) td[data-label="Transaction details"] {
                grid-column: 1 / -1;
                background: #f0f7f7;
                color: #17324d;
                font-size: .98rem;
                font-weight: 800;
            }

            .payback-page .payback-table tbody tr:not(.payback-empty-row) td[data-label="Transaction details"]::before {
                color: #087f8c;
            }

            .payback-page .payback-table tbody tr.payback-empty-row {
                display: table-row;
            }

            .payback-page .payback-table tbody tr.payback-empty-row td {
                display: table-cell;
            }
        }

        @media (max-width: 420px) {
            .payback-page .payback-balance-amount {
                font-size: 1.8rem;
            }

            .payback-page .payback-balance-icon {
                width: 2.45rem;
                height: 2.45rem;
                flex-basis: 2.45rem;
            }

            .payback-page .payback-filter-options {
                width: 100%;
            }

            .payback-page .payback-filter-option {
                flex: 1;
                padding-inline: .55rem;
            }
        }
    </style>
@endsection
