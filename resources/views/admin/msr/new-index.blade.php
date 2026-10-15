@extends('layouts.master')

@section('content')
    <div class="new-msr-page">
        <section class="new-msr-heading">
            <div>
                <span>Sales performance</span>
                <h1>MSR (Monthly Sales Report)</h1>
                <p>View monthly sales totals for each POS and open its transactions.</p>
            </div>
            <div class="new-msr-period">
                <span>Report period</span>
                <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->format('F Y') }}</strong>
            </div>
        </section>

        <section class="card new-msr-card">
            <form method="GET" action="{{ route('admin.new.msr') }}" class="new-msr-filter">
                <div>
                    <label for="new-msr-month" class="form-label">Select month</label>
                    <input type="month" name="month" id="new-msr-month" class="form-control"
                        value="{{ $selectedMonth }}">
                </div>
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-filter me-1" aria-hidden="true"></i> Apply
                </button>
                @if (request()->filled('month'))
                    <a href="{{ route('admin.new.msr') }}" class="btn btn-outline-secondary">Current month</a>
                @endif
            </form>

            <div class="scroll-list-section new-msr-table-section">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">POS ID</th>
                            <th scope="col">POS Name</th>
                            <th scope="col">Mobile Number</th>
                            <th scope="col" class="text-end">Total Sales</th>
                            <th scope="col" class="text-end">Transactions</th>
                            <th scope="col">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($posSales as $key => $pos)
                            <tr>
                                <td class="new-msr-row-number">{{ $posSales->firstItem() + $key }}</td>
                                <td>{{ $pos->user_id ?: $pos->id }}</td>
                                <td class="fw-semibold">{{ $pos->name }}</td>
                                <td>{{ $pos->mobilenumber ?: '—' }}</td>
                                <td class="text-end fw-semibold">
                                    ₹{{ number_format((float) ($pos->total_billing_amount ?? 0), 2) }}
                                </td>
                                <td class="text-end">{{ number_format((int) $pos->total_transactions) }}</td>
                                <td>
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="{{ route('admin.new.msr.details', ['pos' => $pos->id, 'month' => $selectedMonth]) }}">
                                        View transactions
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="new-msr-empty">
                                        <strong>No POS locations found</strong>
                                        <span>There are no POS locations to include in this report.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $posSales->links() }}
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

        .new-msr-filter {
            display: flex;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 0.75rem;
            padding: 1.1rem 1.25rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.18);
            background: rgba(248, 250, 252, 0.72);
        }

        .new-msr-filter > div {
            width: min(100%, 220px);
        }

        .new-msr-filter label {
            margin-bottom: 0.35rem;
            color: #64748b;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .new-msr-table-section {
            max-height: min(65vh, 600px);
        }

        .new-msr-table-section table {
            min-width: 780px;
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

            .new-msr-filter > div {
                width: 100%;
            }
        }
    </style>
@endsection
