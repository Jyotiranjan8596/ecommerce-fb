@extends('layouts.master')

@section('content')
    <div class="container-fluid py-3 admin-workflow-page">
        <section class="admin-workflow-hero mb-4">
            <div>
                <span class="admin-workflow-hero__eyebrow">Account records</span>
                <h1>Settelment Summary</h1>
                <p>Review summaries saved after generation from Admin DSR Summary.</p>
            </div>
            <span class="admin-workflow-hero__icon" aria-hidden="true"><i class="fas fa-list-alt"></i></span>
        </section>

        <section class="card admin-workflow-card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.payment-summaries.index') }}" class="row g-3 align-items-end">
                    <div class="col-12 col-sm-5 col-lg-4">
                        <label for="summary-date" class="form-label">Summary date</label>
                        <input type="date" id="summary-date" name="date" class="form-control"
                            value="{{ $selectedDate }}">
                    </div>
                    <div class="col-12 col-sm-5 col-lg-4">
                        <label for="summary-pos" class="form-label">POS account</label>
                        <select id="summary-pos" name="pos_id" class="form-select">
                            <option value="">All POS accounts</option>
                            @foreach ($all_pos as $pos)
                                <option value="{{ $pos->id }}" @selected((string) $selectedPosId === (string) $pos->id)>
                                    {{ $pos->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-sm-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                        <a href="{{ route('admin.payment-summaries.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </section>

        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif

        <section class="card admin-workflow-card">
            <div class="card-header bg-white py-3">
                <h2 class="h5 mb-0">Saved summaries</h2>
                <p class="text-muted small mb-0 mt-1">
                    {{ $summaries->total() }} saved payment summaries
                </p>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Summary date</th>
                            <th scope="col">POS account</th>
                            <th scope="col" class="text-end">Transactions</th>
                            <th scope="col" class="text-end">Billing</th>
                            <th scope="col" class="text-end">Cash / UPI</th>
                            <th scope="col" class="text-end">Wallet</th>
                            <th scope="col" class="text-end">Reward</th>
                            <th scope="col" class="text-end">POS credit</th>
                            <th scope="col" class="text-end">POS debit</th>
                            <th scope="col" class="text-end">Admin credit</th>
                            <th scope="col" class="text-end">Admin debit</th>
                            <th scope="col">Reference</th>
                            <th scope="col">Automation status</th>
                            <th scope="col">Saved at</th>
                            <th scope="col">Settlement</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($summaries as $summary)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($summary->date)->format('d-m-Y') }}</td>
                                <td>{{ $summary->pos_system?->name ?? 'Unknown POS' }}</td>
                                <td class="text-end">{{ number_format((int) $summary->total_transaction) }}</td>
                                <td class="text-end">₹{{ number_format((float) $summary->total_billing_amount, 2) }}</td>
                                <td class="text-end">₹{{ number_format((float) $summary->by_cash, 2) }}</td>
                                <td class="text-end">₹{{ number_format((float) $summary->by_wallet, 2) }}</td>
                                <td class="text-end">₹{{ number_format((float) $summary->by_reward, 2) }}</td>
                                <td class="text-end">₹{{ number_format((float) $summary->pos_credit, 2) }}</td>
                                <td class="text-end">₹{{ number_format((float) $summary->pos_debit, 2) }}</td>
                                <td class="text-end">₹{{ number_format((float) $summary->admin_credit, 2) }}</td>
                                <td class="text-end">₹{{ number_format((float) $summary->admin_debit, 2) }}</td>
                                <td>{{ $summary->reference_number ?: '—' }}</td>
                                <td class="text-nowrap">
                                    <div class="d-flex flex-column align-items-start gap-1">
                                        <span class="badge {{ $summary->has_generated_payment ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $summary->has_generated_payment ? 'Summary generated' : 'Summary not generated' }}
                                        </span>
                                        @if ((float) $summary->admin_credit <= 0)
                                            <span class="badge bg-light text-dark border">No POS payment due</span>
                                        @elseif ((float) $summary->pos_payment_amount >= (float) $summary->admin_credit)
                                            <span class="badge {{ $summary->has_auto_submitted_payment ? 'bg-success' : 'bg-info text-dark' }}">
                                                {{ $summary->has_auto_submitted_payment ? 'POS payment auto-submitted' : 'POS payment recorded' }}
                                            </span>
                                        @elseif ((float) $summary->auto_submitted_amount > 0)
                                            <span class="badge bg-warning text-dark">
                                                Auto-submitted ₹{{ number_format((float) $summary->auto_submitted_amount, 2) }} of ₹{{ number_format((float) $summary->admin_credit, 2) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">POS payment pending</span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $summary->created_at?->format('d-m-Y H:i') ?? '—' }}</td>
                                <td>
                                    @if ($summary->status === 'pending' && $summary->pos_system)
                                        <form method="POST" action="{{ route('admin.payment-summaries.settle', $summary->id) }}"
                                            onsubmit="return confirm('Confirm settlement and post any payable amount to the POS account ledger?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning fw-semibold">
                                                Review &amp; settle
                                            </button>
                                        </form>
                                    @elseif ($summary->status === 'pending')
                                        <button type="button" class="btn btn-sm btn-outline-secondary" disabled
                                            title="This summary has no linked POS account.">
                                            POS missing
                                        </button>
                                    @elseif ($summary->status === 'approved')
                                        <button type="button" class="btn btn-sm btn-success" disabled>Settled</button>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                    @include('shared.transaction-status-audit', ['statusChanges' => $summary->statusChanges])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="text-center text-muted py-4">
                                    No saved payment summaries match these filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($summaries->hasPages())
                <div class="card-footer bg-white">
                    {{ $summaries->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
