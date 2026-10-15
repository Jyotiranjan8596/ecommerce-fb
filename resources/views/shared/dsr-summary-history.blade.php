@extends($layout)

@section('content')
    <div class="container-fluid px-2 px-md-3 py-3">
        <section class="dashboard-hero mb-4">
            <span class="dashboard-hero__label"><i class="fa fa-chart-bar me-1" aria-hidden="true"></i> Daily sales</span>
            <h1 class="dashboard-hero__title">DSR Summary</h1>
            <p class="dashboard-hero__text mb-0">Review saved daily sales summaries by date
                @if ($isAdmin)
                    and POS.
                @else
                    for your POS.
                @endif
            </p>
        </section>

        <section class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3 p-lg-4">
                <form method="GET" action="{{ $isAdmin ? route('admin.dsr-summary.index') : route('pos.dsr-summary.index') }}"
                    class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label for="summary-date" class="form-label fw-semibold">Summary date</label>
                        <input id="summary-date" type="date" name="date" value="{{ $selectedDate }}"
                            class="form-control">
                    </div>
                    @if ($isAdmin)
                        <div class="col-12 col-md-4">
                            <label for="summary-pos-search" class="form-label fw-semibold">Search by POS ID or mobile</label>
                            <input id="summary-pos-search" type="search" name="pos_search"
                                value="{{ $selectedPosSearch }}" class="form-control"
                                list="dsr-summary-pos-options" placeholder="Type a POS ID or mobile number">
                            <datalist id="dsr-summary-pos-options">
                                @foreach ($posList as $pos)
                                    @if ($pos->user_id)
                                        <option value="{{ $pos->user_id }}">{{ $pos->name }} — POS ID</option>
                                    @endif
                                    @if ($pos->mobilenumber)
                                        <option value="{{ $pos->mobilenumber }}">{{ $pos->name }} — Mobile</option>
                                    @endif
                                @endforeach
                            </datalist>
                        </div>
                    @else
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">POS</label>
                            <input type="text" class="form-control" value="{{ $posList->first()->name }}" disabled>
                        </div>
                    @endif
                    <div class="col-12 col-md-4 d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ $isAdmin ? route('admin.dsr-summary.index') : route('pos.dsr-summary.index') }}"
                            class="btn btn-outline-secondary">Clear</a>
                        @if ($isAdmin)
                            <button id="generate-payment-summary-btn" type="button" class="btn btn-success"
                                data-url="{{ route('admin.payment.generate.pos.summaries') }}"
                                data-token="{{ csrf_token() }}">
                                Generate Payment Summary
                            </button>
                        @endif
                    </div>
                </form>
                @if ($isAdmin)
                    <p class="small text-muted mt-3 mb-0">
                        Payment summaries are generated automatically once all transactions for a POS on a date are
                        verified. Use Generate to retry or backfill summaries for the selected date.
                    </p>
                @endif
            </div>
        </section>

        <section class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Date</th>
                                @if ($isAdmin)
                                    <th scope="col">POS</th>
                                @endif
                                <th scope="col" class="text-end">Transactions</th>
                                <th scope="col" class="text-end">Billing amount</th>
                                <th scope="col" class="text-end">Cash / UPI</th>
                                <th scope="col" class="text-end">Wallet</th>
                                <th scope="col" class="text-end">Reward</th>
                                <th scope="col" class="text-end">POS credit</th>
                                <th scope="col" class="text-end">POS debit</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($summaries as $summary)
                                <tr>
                                    <td>{{ \Illuminate\Support\Carbon::parse($summary->date)->format('d-m-Y') }}</td>
                                    @if ($isAdmin)
                                        <td>
                                            {{ $summary->pos_name ?? 'POS unavailable' }}
                                            @if ($summary->pos_user_id)
                                                <small class="d-block text-muted">
                                                    {{ $summary->pos_user_id }}
                                                    @if ($summary->pos_mobile)
                                                        · {{ $summary->pos_mobile }}
                                                    @endif
                                                </small>
                                            @endif
                                        </td>
                                    @endif
                                    <td class="text-end">{{ number_format((int) $summary->total_transaction) }}</td>
                                    <td class="text-end">₹{{ number_format((float) $summary->total_billing_amount, 2) }}</td>
                                    <td class="text-end">₹{{ number_format((float) $summary->by_cash, 2) }}</td>
                                    <td class="text-end">₹{{ number_format((float) $summary->by_wallet, 2) }}</td>
                                    <td class="text-end">₹{{ number_format((float) $summary->by_reward, 2) }}</td>
                                    <td class="text-end">₹{{ number_format((float) $summary->pos_credit, 2) }}</td>
                                    <td class="text-end">₹{{ number_format((float) $summary->pos_debit, 2) }}</td>
                                    <td>
                                        @if ($summary->has_generated_payment)
                                            <span class="badge bg-success">Confirmed</span>
                                        @else
                                            <span class="badge bg-secondary">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isAdmin ? 10 : 9 }}" class="py-5 text-center text-muted">
                                        No DSR summaries found for the selected filters.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($summaries->hasPages())
                <div class="card-footer bg-white d-flex justify-content-center">
                    {{ $summaries->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection

@if ($isAdmin)
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const generateButton = document.getElementById('generate-payment-summary-btn');
                const dateInput = document.getElementById('summary-date');

                generateButton.addEventListener('click', function() {
                    if (!dateInput.value) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Select a date',
                            text: 'Choose a summary date before generating payment summaries.'
                        });
                        return;
                    }

                    Swal.fire({
                        icon: 'question',
                        title: 'Generate payment summaries?',
                        text: 'This will generate ledger entries for all POSes with verified transactions on ' +
                            dateInput.value + '.',
                        showCancelButton: true,
                        confirmButtonText: 'Generate'
                    }).then(function(result) {
                        if (!result.isConfirmed) {
                            return;
                        }

                        generateButton.disabled = true;
                        fetch(generateButton.dataset.url, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': generateButton.dataset.token
                            },
                            body: JSON.stringify({
                                transaction_date: dateInput.value
                            })
                        })
                            .then(async function(response) {
                                const payload = await response.json();
                                if (!response.ok) {
                                    const failedPos = payload.failed_pos?.length
                                        ? ' Failed POS: ' + payload.failed_pos.join(', ')
                                        : '';
                                    const error = new Error(
                                        (payload.message || 'Payment summaries could not be generated.') + failedPos
                                    );
                                    error.partialResults = Array.isArray(payload.summaries) &&
                                        payload.summaries.length > 0;
                                    throw error;
                                }
                                return payload;
                            })
                            .then(function(payload) {
                                if (!payload.success) {
                                    const failedPos = payload.failed_pos?.length
                                        ? ' Failed POS: ' + payload.failed_pos.join(', ')
                                        : '';
                                    throw new Error((payload.message || 'Payment summaries could not be generated.') + failedPos);
                                }

                                const message = payload.created_count > 0
                                    ? payload.message + ' Created: ' + payload.created_count +
                                        ', repaired: ' + payload.repaired_count +
                                        ', already generated: ' + payload.existing_count + '.'
                                    : payload.repaired_count > 0
                                        ? payload.message + ' Repaired missing ledger entries: ' +
                                            payload.repaired_count + '.'
                                        : payload.message + ' Already generated: ' + payload.existing_count + '.';

                                Swal.fire({
                                    icon: payload.created_count > 0 || payload.repaired_count > 0 ||
                                        payload.existing_count > 0 ? 'success' : 'info',
                                    title: 'Payment summary generation',
                                    text: message
                                }).then(function() {
                                    window.location.reload();
                                });
                            })
                            .catch(function(error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Generation failed',
                                    text: error.message
                                }).then(function() {
                                    if (error.partialResults) {
                                        window.location.reload();
                                    }
                                });
                            })
                            .finally(function() {
                                generateButton.disabled = false;
                            });
                    });
                });
            });
        </script>
    @endpush
@endif
