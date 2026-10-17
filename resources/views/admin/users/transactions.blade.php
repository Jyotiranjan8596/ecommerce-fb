@extends('layouts.master')

@section('content')
    <main class="container-fluid py-3 customer-transactions-page">
        <header class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 p-4 rounded-4 text-white"
            style="background: linear-gradient(120deg, #123b72, #1769aa 68%, #2783bd);">
            <div>
                <a href="{{ route('admin.users.index') }}" class="link-light text-decoration-none">
                    <i class="fas fa-arrow-left me-1" aria-hidden="true"></i> Customer management
                </a>
                <h1 class="h3 mt-3 mb-1 text-white">Customer transaction history</h1>
                <p class="mb-0 text-white-50">All recorded sales and wallet/reward activity for this customer.</p>
            </div>
            <div class="text-lg-end">
                <strong class="d-block fs-5">{{ $user->name ?: 'Name unavailable' }}</strong>
                <span>{{ $user->user_id ?: '—' }} · {{ $user->mobilenumber ?: 'No mobile number' }}</span>
                @if ($user->email)
                    <span class="d-block">{{ $user->email }}</span>
                @endif
            </div>
        </header>

        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h5 mb-1">Sales transactions</h2>
                    <p class="text-muted small mb-0">Billing, payment, invoice, POS, and verification details.</p>
                </div>
                <span class="badge bg-primary">{{ number_format($salesTransactions->total()) }} records</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Invoice</th>
                            <th scope="col">POS</th>
                            <th scope="col">Description</th>
                            <th scope="col">Billing amount</th>
                            <th scope="col">Wallet</th>
                            <th scope="col">Reward</th>
                            <th scope="col">Net amount</th>
                            <th scope="col">Payment method</th>
                            <th scope="col">Transaction type</th>
                            <th scope="col">Date</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($salesTransactions as $transaction)
                            <tr>
                                <td>{{ $transaction->invoice ?: '—' }}</td>
                                <td>{{ $transaction->getPos?->name ?: '—' }}</td>
                                <td>{{ $transaction->description ?: '—' }}</td>
                                <td>{{ $transaction->billing_amount ?? '—' }}</td>
                                <td>{{ $transaction->amount_wallet ?? '—' }}</td>
                                <td>{{ $transaction->reward_amount ?? '—' }}</td>
                                <td>{{ $transaction->amount ?? $transaction->transaction_amount ?? '—' }}</td>
                                <td>{{ $transaction->pay_by_wallet ?: $transaction->pay_by ?: '—' }}</td>
                                <td>{{ $transaction->tran_type ?: '—' }}</td>
                                <td>
                                    @php($transactionDate = $transaction->transaction_date ?? $transaction->insert_date)
                                    {{ $transactionDate ? \Illuminate\Support\Carbon::parse($transactionDate)->format('d/m/Y h:i A') : '—' }}
                                </td>
                                <td>
                                    @if ((int) $transaction->status === 1)
                                        <span class="badge bg-success">Verified</span>
                                    @elseif ($transaction->status !== null)
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="11" class="text-center text-muted py-4">No sales transactions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($salesTransactions->hasPages())
                <div class="card-footer bg-white">{{ $salesTransactions->links() }}</div>
            @endif
        </section>

        <section class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h5 mb-1">Wallet and reward ledger</h2>
                    <p class="text-muted small mb-0">Wallet balances, debits, credits, and reward-point activity.</p>
                </div>
                <span class="badge bg-secondary">{{ number_format($walletTransactions->total()) }} records</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Invoice</th>
                            <th scope="col">POS</th>
                            <th scope="col">Source</th>
                            <th scope="col">Type</th>
                            <th scope="col">Used amount</th>
                            <th scope="col">Wallet amount</th>
                            <th scope="col">Wallet remaining</th>
                            <th scope="col">Reward points</th>
                            <th scope="col">Points used</th>
                            <th scope="col">Points remaining</th>
                            <th scope="col">Paid by</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($walletTransactions as $transaction)
                            <tr>
                                <td>{{ $transaction->invoice ?: '—' }}</td>
                                <td>{{ $transaction->getPos?->name ?: '—' }}</td>
                                <td>{{ $transaction->source ?: '—' }}</td>
                                <td>{{ $transaction->trans_type ?: '—' }}</td>
                                <td>{{ $transaction->used_amount ?? '—' }}</td>
                                <td>{{ $transaction->wallet_amount ?? '—' }}</td>
                                <td>{{ $transaction->remaining_amount ?? '—' }}</td>
                                <td>{{ $transaction->reward_points ?? '—' }}</td>
                                <td>{{ $transaction->used_points ?? '—' }}</td>
                                <td>{{ $transaction->remaining_points ?? '—' }}</td>
                                <td>{{ $transaction->pay_by ?: '—' }}</td>
                                <td>
                                    @php($transactionDate = $transaction->transaction_date ?? $transaction->created_at)
                                    {{ $transactionDate ? \Illuminate\Support\Carbon::parse($transactionDate)->format('d/m/Y h:i A') : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="12" class="text-center text-muted py-4">No wallet or reward activity found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($walletTransactions->hasPages())
                <div class="card-footer bg-white">{{ $walletTransactions->links() }}</div>
            @endif
        </section>
    </main>
@endsection
