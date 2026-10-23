@extends('layouts.master')

@section('content')
    <style>
        .wallet-manage-page { max-width: 1480px; margin: 1rem auto; padding: 0 1rem 2rem; }
        .wallet-manage-hero { display:flex; align-items:center; justify-content:space-between; gap:1.25rem; margin-bottom:1rem; padding:1.35rem 1.5rem; border:1px solid #d8e7f5; border-radius:18px; background:linear-gradient(120deg,#123b72,#1769aa 68%,#2783bd); color:#fff; box-shadow:0 12px 28px rgba(18,59,114,.14); }
        .wallet-manage-hero__copy { min-width:0; }
        .wallet-manage-hero__eyebrow { color:#bfe0ff; font-size:.72rem; font-weight:800; letter-spacing:.12em; text-transform:uppercase; }
        .wallet-manage-hero h1 { margin:.28rem 0 .2rem; color:#fff; font-size:clamp(1.45rem,2.5vw,1.95rem); font-weight:850; }
        .wallet-manage-hero p { margin:0; color:rgba(255,255,255,.78); font-size:.87rem; }
        .wallet-manage-hero__user { display:flex; flex-wrap:wrap; gap:.45rem; margin-top:.7rem; }
        .wallet-manage-hero__user span { padding:.3rem .6rem; border:1px solid rgba(255,255,255,.22); border-radius:999px; background:rgba(255,255,255,.1); color:#fff; font-size:.76rem; font-weight:700; }
        .wallet-manage-search { display:flex; flex:0 1 310px; gap:.45rem; }
        .wallet-manage-search input { min-width:0; border:1px solid rgba(255,255,255,.75); border-radius:9px; }
        .wallet-manage-search button { display:grid; width:42px; flex:0 0 42px; place-items:center; border:0; border-radius:9px; background:#fff; color:#174a79; }
        .wallet-manage-balance { display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:1rem; padding:1rem 1.2rem; border:1px solid #d8e9f7; border-radius:14px; background:linear-gradient(105deg,#eff7ff,#fff); }
        .wallet-manage-balance span { color:#64778b; font-size:.82rem; font-weight:700; }
        .wallet-manage-balance strong { color:#123b72; font-size:1.45rem; font-weight:850; }
        .wallet-manage-grid { display:grid; grid-template-columns:minmax(320px, .85fr) minmax(0,1.35fr); align-items:start; gap:1rem; }
        .wallet-manage-column { display:grid; gap:1rem; min-width:0; }
        .wallet-manage-panel { min-width:0; overflow:hidden; border:1px solid #e0e8f1; border-radius:16px; background:#fff; box-shadow:0 8px 22px rgba(19,48,79,.055); }
        .wallet-manage-panel__head { display:flex; align-items:center; gap:.75rem; padding:1rem 1.15rem; border-bottom:1px solid #edf2f7; }
        .wallet-manage-panel__icon { display:grid; width:38px; height:38px; flex:0 0 38px; place-items:center; border-radius:12px; background:#eaf3ff; color:#1769aa; }
        .wallet-manage-panel__icon--credit { background:#e8f7ef; color:#17824c; }
        .wallet-manage-panel__icon--debit { background:#fff0ef; color:#c43b42; }
        .wallet-manage-panel__head h2 { margin:0; color:#1d334a; font-size:1rem; font-weight:800; }
        .wallet-manage-panel__head p { margin:.15rem 0 0; color:#748397; font-size:.77rem; }
        .wallet-manage-panel__body { padding:1.1rem 1.15rem 1.2rem; }
        .wallet-manage-page .form-label { margin-bottom:.35rem; color:#52657a; font-size:.77rem; font-weight:750; }
        .wallet-manage-page .form-control,.wallet-manage-page .form-select { min-height:42px; border-color:#d9e3ed; border-radius:9px; color:#30465d; font-size:.86rem; }
        .wallet-manage-page .form-control:focus,.wallet-manage-page .form-select:focus { border-color:#5ba0dc; box-shadow:0 0 0 .2rem rgba(23,105,170,.12); }
        .wallet-adjustment-type { display:grid; grid-template-columns:1fr 1fr; gap:.55rem; margin-bottom:.9rem; }
        .wallet-adjustment-type label { display:flex; align-items:center; justify-content:center; gap:.45rem; min-height:42px; padding:.55rem; border:1px solid #d9e3ed; border-radius:9px; color:#52657a; cursor:pointer; font-size:.84rem; font-weight:750; }
        .wallet-adjustment-type input { accent-color:#1769aa; }
        .wallet-adjustment-type label:has(input:checked) { border-color:#1769aa; background:#eff6ff; color:#174a79; }
        .wallet-adjustment-type label:has(input[value="credit"]:checked) { border-color:#83d3a8; background:#effaf3; color:#167346; }
        .wallet-adjustment-type label:has(input[value="debit"]:checked) { border-color:#f3a4a0; background:#fff3f2; color:#b52f37; }
        .wallet-adjustment-help { margin:.5rem 0 0; color:#748397; font-size:.75rem; line-height:1.45; }
        .wallet-adjustment-submit { display:inline-flex; width:100%; min-height:43px; align-items:center; justify-content:center; gap:.45rem; margin-top:1rem; border:1px solid #1769aa; border-radius:9px; background:#1769aa; color:#fff; font-size:.86rem; font-weight:800; }
        .wallet-adjustment-submit:hover { border-color:#123b72; background:#123b72; color:#fff; }
        .wallet-adjustment-submit[data-type="debit"] { border-color:#c43b42; background:#c43b42; }
        .wallet-adjustment-submit[data-type="debit"]:hover { border-color:#a92e35; background:#a92e35; }

        .wallet-transactions-filter { display:flex; gap:.5rem; padding:1rem 1.15rem 0; }
        .wallet-transactions-filter button { flex:0 0 auto; min-height:42px; border-radius:9px; font-size:.82rem; font-weight:750; }
        .wallet-transactions-scroll { max-height:590px; margin-top:.8rem; overflow:auto; }
        .wallet-transactions-table { min-width:780px; margin:0; }
        .wallet-transactions-table thead th { position:sticky; top:0; z-index:2; padding:.8rem .75rem; border:0; background:#123b72; color:#fff; font-size:.7rem; font-weight:800; letter-spacing:.04em; white-space:nowrap; }
        .wallet-transactions-table tbody td { padding:.72rem .75rem; border-color:#edf1f6; color:#40546a; font-size:.81rem; vertical-align:middle; }
        .wallet-transactions-table tbody tr:nth-child(even) { background:#f8fbff; }
        .wallet-transaction-type { display:inline-flex; padding:.24rem .52rem; border-radius:999px; background:#edf3f9; color:#52657a; font-size:.7rem; font-weight:800; text-transform:capitalize; }
        .wallet-transaction-type--credit { background:#e8f7ef; color:#167346; }
        .wallet-transaction-type--debit { background:#fff0ef; color:#b52f37; }
        .wallet-transactions-empty { padding:2rem 1rem; color:#718096; text-align:center; }
        @media (max-width: 991.98px) { .wallet-manage-grid { grid-template-columns:1fr; } .wallet-transactions-scroll { max-height:440px; } }
        @media (max-width: 575.98px) { .wallet-manage-page { margin:.5rem auto; padding:0 .65rem 1rem; } .wallet-manage-hero { align-items:stretch; flex-direction:column; padding:1.1rem; } .wallet-manage-search { flex:1 1 auto; } .wallet-manage-balance { align-items:flex-start; flex-direction:column; gap:.3rem; } .wallet-manage-panel__head,.wallet-manage-panel__body { padding-left:.9rem; padding-right:.9rem; } .wallet-transactions-filter { padding-left:.9rem; padding-right:.9rem; } }
    </style>

    <main class="wallet-manage-page">
        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <header class="wallet-manage-hero">
            <div class="wallet-manage-hero__copy">
                <span class="wallet-manage-hero__eyebrow"><i class="fas fa-wallet me-1" aria-hidden="true"></i> Wallet management</span>
                <h1>Manage User Wallet</h1>
                <p>{{ $user ? 'Review account activity and make balance adjustments for this customer.' : 'Search for a customer to view or manage their wallet.' }}</p>
                @if ($user)
                    <div class="wallet-manage-hero__user">
                        <span>{{ $user->name }}</span>
                        <span>ID {{ $user->user_id }}</span>
                    </div>
                @endif
            </div>
            <form class="wallet-manage-search" action="{{ route('admin.wallet.manage') }}" method="GET">
                <label class="visually-hidden" for="myid">Search by user ID or mobile number</label>
                <input name="user_id" type="search" id="myid" value="{{ $searchTerm }}" class="form-control" placeholder="User ID or mobile number" required>
                <button type="submit" aria-label="Find customer"><i class="fas fa-arrow-right" aria-hidden="true"></i></button>
            </form>
        </header>

        @if (! $user && $searchTerm !== '')
            <div class="alert alert-warning" role="status">No customer found for {{ $searchTerm }}. Check the user ID or mobile number and try again.</div>
        @endif

        <div class="wallet-manage-balance" aria-live="polite">
            <span>Available wallet balance</span>
            @if ($user)
                <strong>&#8377;{{ number_format((float) ($walletBalance ?? 0), 2) }}</strong>
            @else
                <strong>&mdash;</strong>
            @endif
        </div>
        <div class="wallet-manage-grid">
            <div class="wallet-manage-column">
                <section class="wallet-manage-panel" aria-labelledby="adjust-wallet-title">
                    <div class="wallet-manage-panel__head">
                        <span class="wallet-manage-panel__icon wallet-manage-panel__icon--credit"><i class="fas fa-exchange-alt" aria-hidden="true"></i></span>
                        <div><h2 id="adjust-wallet-title">Credit or debit wallet</h2><p>Enter a direct balance adjustment.</p></div>
                    </div>
                    <div class="wallet-manage-panel__body">
                        <form action="{{ route('admin.wallet.adjust') }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user?->id }}">
                            <div class="wallet-adjustment-type" role="group" aria-label="Adjustment type">
                                <label><input type="radio" name="type" value="credit" @checked(old('type', 'credit') === 'credit')> <i class="fas fa-plus-circle" aria-hidden="true"></i> Credit</label>
                                <label><input type="radio" name="type" value="debit" @checked(old('type') === 'debit')> <i class="fas fa-minus-circle" aria-hidden="true"></i> Debit</label>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="adjustment-amount">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">&#8377;</span>
                                    <input id="adjustment-amount" name="amount" type="number" class="form-control" min="0.01" max="99999999.99" step="0.01" value="{{ old('amount') }}" required>
                                </div>
                            </div>
                            <div>
                                <label class="form-label" for="adjustment-description">Note <span class="fw-normal text-muted">(optional)</span></label>
                                <input id="adjustment-description" name="description" type="text" class="form-control" maxlength="200" value="{{ old('description') }}" placeholder="Reason for this adjustment">
                            </div>
                            <p class="wallet-adjustment-help">Debits cannot exceed the available wallet balance. Each adjustment is recorded in transaction history.</p>
                            <button class="wallet-adjustment-submit" id="adjustment-submit" type="submit" data-type="credit" @disabled(! $user)>
                                <i class="fas fa-plus-circle" aria-hidden="true"></i> Add credit
                            </button>
                        </form>
                    </div>
                </section>
            </div>

            <section class="wallet-manage-panel" aria-labelledby="wallet-transactions-title">
                <div class="wallet-manage-panel__head">
                    <span class="wallet-manage-panel__icon"><i class="fas fa-history" aria-hidden="true"></i></span>
                    <div>
                        <h2 id="wallet-transactions-title">{{ ucfirst($historyType) }} history</h2>
                        <p>{{ $user ? 'History for ' . $user->name . '.' : 'Search for a customer to see their wallet activity.' }}</p>
                    </div>
                </div>
                <form class="wallet-transactions-filter" method="GET" action="{{ route('admin.wallet.manage') }}">
                    <input type="hidden" name="user_id" value="{{ $user?->user_id }}">
                    <label class="visually-hidden" for="history_type">History type</label>
                    <select class="form-select" name="history_type" id="history_type" aria-label="History type">
                        <option value="transaction" @selected($historyType === 'transaction')>Transaction History</option>
                        <option value="payback" @selected($historyType === 'payback')>Payback History</option>
                        <option value="referral" @selected($historyType === 'referral')>Referral History</option>
                    </select>
                    <label class="visually-hidden" for="transaction_month">Choose month and year</label>
                    <input type="month" class="form-control" name="transaction_month" id="transaction_month"
                        value="{{ request('transaction_month') }}" aria-label="Choose month and year">
                    <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search me-1" aria-hidden="true"></i> Search</button>
                </form>
                <div class="wallet-transactions-scroll table-responsive">
                    <table class="table wallet-transactions-table align-middle">
                        <thead>
                            @if ($historyType === 'payback')
                                <tr><th>#</th><th>Month</th><th>Invoice</th><th>Type</th><th>Wallet Amount</th><th>Reward Points</th><th>Transaction Date</th></tr>
                            @elseif ($historyType === 'referral')
                                <tr><th>#</th><th>Date</th><th>Type</th><th>Details</th><th>Referral Reward</th></tr>
                            @else
                                <tr><th>#</th><th>Date</th><th>Invoice</th><th>Type</th><th>Details</th><th>Amount</th><th>Wallet used</th><th>POS ID</th></tr>
                            @endif
                        </thead>
                        <tbody>
                            @forelse ($walletList as $key => $wallet)
                                @if ($historyType === 'payback')
                                    <tr>
                                        <td>{{ $walletList->firstItem() + $key }}</td>
                                        <td>{{ $wallet->display_month }}</td>
                                        <td>{{ $wallet->invoice ?: '-' }}</td>
                                        <td><span class="wallet-transaction-type wallet-transaction-type--{{ strtolower($wallet->trans_type ?: 'other') }}">{{ $wallet->trans_type ?: 'Payback' }}</span></td>
                                        <td>&#8377;{{ number_format((float) ($wallet->wallet_amount ?? 0), 2) }}</td>
                                        <td>{{ number_format((float) ($wallet->reward_points ?? 0), 2) }}</td>
                                        <td>{{ $wallet->transaction_date ? \Illuminate\Support\Carbon::parse($wallet->transaction_date)->format('d M Y') : '-' }}</td>
                                    </tr>
                                @elseif ($historyType === 'referral')
                                    <tr>
                                        <td>{{ $walletList->firstItem() + $key }}</td>
                                        <td>{{ $wallet->transaction_date ? \Illuminate\Support\Carbon::parse($wallet->transaction_date)->format('d M Y') : '-' }}</td>
                                        <td><span class="wallet-transaction-type wallet-transaction-type--credit">Credit</span></td>
                                        <td>Referral reward</td>
                                        <td>{{ number_format((float) ($wallet->reward_points ?? 0), 2) }} points</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $wallet->transaction_date ? \Illuminate\Support\Carbon::parse($wallet->transaction_date)->format('d M Y') : '-'  }}</td>
                                        <td>{{ $wallet->invoice ?: '-'  }}</td>
                                        <td><span class="wallet-transaction-type wallet-transaction-type--{{ strtolower($wallet->tran_type ?: 'other') }}">{{ $wallet->tran_type ?: 'Payment' }}</span></td>
                                        <td>{{ $wallet->description ?: '-'  }}</td>
                                        <td>&#8377;{{ number_format((float) ($wallet->amount ?? 0), 2) }}</td>
                                        <td>&#8377;{{ number_format((float) ($wallet->amount_wallet ?? 0), 2) }}</td>
                                        <td>{{ $wallet->getPos?->user_id ?: 'Admin / N/A' }}</td>
                                    </tr>
                                @endif
                            @empty
                                <tr><td colspan="{{ $historyType === 'payback' ? 7 : ($historyType === 'referral' ? 5 : 8) }}" class="wallet-transactions-empty">{{ ! $user ? 'Search by user ID or mobile number to load wallet history.' : ($transactionsNotFound ? 'No records found for this month and year.' : 'No history records found.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($walletList instanceof \Illuminate\Contracts\Pagination\Paginator && $walletList->hasPages())
                        <div class="px-3 py-2">{{ $walletList->links() }}</div>
                    @endif
                </div>
            </section>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const historyType = document.getElementById('history_type');
            if (historyType) {
                historyType.addEventListener('change', function () {
                    document.getElementById('transaction_month').value = '';
                    this.form.submit();
                });
            }

            const adjustmentSubmit = document.getElementById('adjustment-submit');
            document.querySelectorAll('input[name="type"]').forEach(function (radio) {
                radio.addEventListener('change', function () {
                    const isDebit = this.value === 'debit';
                    adjustmentSubmit.dataset.type = isDebit ? 'debit' : 'credit';
                    adjustmentSubmit.innerHTML = isDebit
                        ? '<i class="fas fa-minus-circle" aria-hidden="true"></i> Record debit'
                        : '<i class="fas fa-plus-circle" aria-hidden="true"></i> Add credit';
                });
            });
            const selectedType = document.querySelector('input[name="type"]:checked');
            if (selectedType) selectedType.dispatchEvent(new Event('change'));
        });
    </script>
@endsection
