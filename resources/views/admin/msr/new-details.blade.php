@extends('layouts.master')

@section('content')
    <div class="new-msr-page">
        <section class="new-msr-heading">
            <div>
                <span>POS transactions</span>
                <h1>{{ $pos->name }} — Details</h1>
                <p>POS ID: {{ $pos->user_id ?: $pos->id }} · {{ $pos->mobilenumber ?: 'No mobile number' }}</p>
            </div>
            <div class="new-msr-period">
                <span>Report period</span>
                <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->format('F Y') }}</strong>
            </div>
        </section>

        <section class="card new-msr-card">
            <div class="new-msr-detail-toolbar">
                <a href="{{ route('admin.new.msr', ['month' => $selectedMonth]) }}"
                    class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-1" aria-hidden="true"></i> Back to MSR Report
                </a>
                <a href="{{ route('admin.new.msr.details.export', ['pos' => $pos->id, 'month' => $selectedMonth]) }}"
                    class="btn btn-outline-success">
                    <i class="fas fa-download me-1" aria-hidden="true"></i> Export Transactions
                </a>
            </div>

            <div class="scroll-list-section new-msr-table-section">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Invoice</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Mobile Number</th>
                            <th scope="col">Billing Amount</th>
                            <th scope="col">Wallet Deduct</th>
                            <th scope="col">Reward Deduct</th>
                            <th scope="col">Net Pay</th>
                            <th scope="col">Transaction Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $key => $transaction)
                            <tr>
                                <td class="new-msr-row-number">{{ $transactions->firstItem() + $key }}</td>
                                <td>{{ $transaction->invoice ?: '—' }}</td>
                                <td>{{ $transaction->user->name ?? '—' }}</td>
                                <td>{{ $transaction->mobilenumber ?: '—' }}</td>
                                <td>₹{{ number_format((float) ($transaction->billing_amount ?? 0), 2) }}</td>
                                <td>₹{{ number_format((float) ($transaction->amount_wallet ?? 0), 2) }}</td>
                                <td>₹{{ number_format((float) ($transaction->reward_amount ?? 0), 2) }}</td>
                                <td>₹{{ number_format((float) ($transaction->amount ?? 0), 2) }}</td>
                                <td>
                                    {{ $transaction->transaction_date
                                        ? \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y')
                                        : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="new-msr-empty">
                                        <strong>No transactions for this POS and month</strong>
                                        <span>Choose another month from the MSR report.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $transactions->links() }}
        </section>
    </div>

    <style>
        .new-msr-page {
            display: grid;
            gap: 1.25rem;
            padding-bottom: 1rem;
        }

        .new-msr-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.5rem 1.6rem;
            border: 1px solid rgba(148, 163, 184, 0.22);
            border-radius: 22px;
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 28%),
                linear-gradient(135deg, #102b2b 0%, #142f43 55%, #1e40af 100%);
            color: #fff;
            box-shadow: 0 18px 42px rgba(15, 23, 42, 0.12);
        }

        .new-msr-heading > div:first-child > span {
            display: inline-block;
            margin-bottom: 0.45rem;
            color: #a8e7d2;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .new-msr-heading h1 {
            margin: 0;
            color: #fff;
            font-size: clamp(1.5rem, 2vw, 2rem);
            font-weight: 800;
        }

        .new-msr-heading p {
            margin: 0.35rem 0 0;
            color: rgba(255, 255, 255, 0.76);
        }

        .new-msr-period {
            display: flex;
            min-width: 150px;
            flex-direction: column;
            padding: 0.8rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.1);
        }

        .new-msr-period span {
            color: rgba(255, 255, 255, 0.72);
            font-size: 0.72rem;
        }

        .new-msr-period strong {
            color: #fff;
            font-size: 1rem;
        }

        .new-msr-card {
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, 0.22) !important;
        }

        .new-msr-detail-toolbar {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .75rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.18);
            background: rgba(248, 250, 252, 0.72);
        }

        .new-msr-table-section {
            max-height: min(65vh, 600px);
        }

        .new-msr-table-section table {
            min-width: 920px;
        }

        .new-msr-table-section thead th {
            color: #64748b;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .new-msr-row-number {
            color: #94a3b8 !important;
        }

        .new-msr-empty {
            display: flex;
            min-height: 180px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            color: #64748b;
            text-align: center;
        }

        .new-msr-empty strong {
            color: #0f172a;
        }

        @media (max-width: 767.98px) {
            .new-msr-heading {
                align-items: stretch;
                flex-direction: column;
                padding: 1.25rem;
            }
        }
    </style>
@endsection
