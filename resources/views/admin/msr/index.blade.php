@extends('layouts.master')

@section('content')
    <div class="msr-page">
        <section class="msr-heading">
            <div>
                <span class="msr-heading__eyebrow">Sales performance</span>
                <h1>Payback Run Data</h1>
                <p>Review monthly customer billing and sponsor expenditure.</p>
            </div>
            <div class="msr-heading__period">
                <span>Report period</span>
                <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->format('F Y') }}</strong>
            </div>
        </section>

        <section class="card msr-panel">
            <div class="msr-tools">
                <form method="GET" action="{{ route('admin.msr') }}" class="msr-filters">
                    <div class="msr-filter-field msr-filter-field--month">
                        <label for="msr-month">Month</label>
                        <input type="month" name="month" id="msr-month" class="form-control"
                            value="{{ $selectedMonth }}">
                    </div>

                    <div class="msr-filter-actions">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-filter me-1" aria-hidden="true"></i> Apply month
                        </button>
                        @if (request()->filled('month'))
                            <a href="{{ route('admin.msr') }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </form>

                <form method="GET" action="{{ route('admin.msr.export') }}" class="msr-export">
                    <input type="hidden" name="month" value="{{ $selectedMonth }}">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-download me-1" aria-hidden="true"></i> Export report
                    </button>
                </form>
            </div>

            <div class="msr-table-section scroll-list-section">
                <table class="table table-hover align-middle mb-0 text-nowrap" id="msr-tbl">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Month</th>
                            <th scope="col">User ID</th>
                            <th scope="col">Mobile Number</th>
                            <th scope="col">Sponsor ID</th>
                            <th scope="col" class="text-end">Total Billing Amount</th>
                            <th scope="col" class="text-end">Sponsor Expenditure</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($monthlySales as $key => $data)
                            <tr>
                                <td class="msr-row-number">{{ $monthlySales->firstItem() + $key }}</td>
                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $data->transaction_month)->format('F Y') }}</td>
                                <td><span class="msr-user-id">{{ $data->user->user_id ?? 'N/A' }}</span></td>
                                <td>{{ $data->mobilenumber ?: '—' }}</td>
                                <td>
                                    @if ($data->user && $data->user->sponsor_id)
                                        @php
                                            $sponsorUser = \App\Models\User::find($data->user->sponsor_id);
                                        @endphp
                                        {{ $sponsorUser ? $sponsorUser->user_id : 'N/A' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="text-end">₹{{ number_format((float) ($data->total_billing_amount ?? 0), 2) }}</td>
                                <td class="text-end">₹{{ number_format((float) ($data->sponsor_expenditure ?? 0), 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="msr-empty">
                                        <span class="msr-empty__icon" aria-hidden="true">
                                            <i class="fas fa-chart-line"></i>
                                        </span>
                                        <strong>No sales records found</strong>
                                        <span>Try a different month.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $monthlySales->links() }}
        </section>
    </div>

    <style>
        .msr-page {
            display: grid;
            gap: 1.25rem;
            padding-bottom: 1rem;
        }

        .msr-heading {
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

        .msr-heading__eyebrow {
            display: inline-block;
            margin-bottom: 0.45rem;
            color: #a8e7d2;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .msr-heading h1 {
            margin: 0;
            color: #fff;
            font-size: clamp(1.5rem, 2vw, 2rem);
            font-weight: 800;
        }

        .msr-heading p {
            margin: 0.35rem 0 0;
            color: rgba(255, 255, 255, 0.76);
        }

        .msr-heading__period {
            display: flex;
            min-width: 150px;
            flex-direction: column;
            padding: 0.8rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.1);
        }

        .msr-heading__period span {
            color: rgba(255, 255, 255, 0.72);
            font-size: 0.72rem;
        }

        .msr-heading__period strong {
            color: #fff;
            font-size: 1rem;
        }

        .msr-panel {
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, 0.22) !important;
        }

        .msr-tools {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.1rem 1.25rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.18);
            background: rgba(248, 250, 252, 0.72);
        }

        .msr-filters {
            display: flex;
            flex: 1 1 auto;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .msr-filter-field {
            display: grid;
            gap: 0.35rem;
        }

        .msr-filter-field label {
            margin: 0;
            color: #64748b;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .msr-filter-field--month {
            flex: 0 1 165px;
        }

        .msr-filter-actions {
            display: flex;
            gap: 0.5rem;
        }

        .msr-export {
            flex: 0 0 auto;
        }

        .msr-table-section {
            max-height: min(65vh, 600px);
        }

        #msr-tbl {
            min-width: 850px;
        }

        #msr-tbl thead th {
            color: #64748b;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .msr-row-number {
            color: #94a3b8 !important;
        }

        .msr-user-id {
            display: inline-block;
            padding: 0.28rem 0.55rem;
            border: 1px solid rgba(37, 99, 235, 0.12);
            border-radius: 8px;
            background: rgba(219, 234, 254, 0.45);
            color: #1d4ed8;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .msr-empty {
            display: flex;
            min-height: 210px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            color: #64748b;
            text-align: center;
        }

        .msr-empty strong {
            color: #0f172a;
        }

        .msr-empty__icon {
            display: grid;
            width: 48px;
            height: 48px;
            margin-bottom: 0.3rem;
            place-items: center;
            border-radius: 16px;
            background: #eff6ff;
            color: #2563eb;
            font-size: 1.1rem;
        }

        @media (max-width: 767.98px) {
            .msr-heading,
            .msr-tools {
                align-items: stretch;
                flex-direction: column;
            }

            .msr-heading {
                padding: 1.25rem;
            }

            .msr-filter-field--month {
                flex: 1 1 100%;
            }

            .msr-filter-actions,
            .msr-export,
            .msr-filter-actions .btn,
            .msr-export .btn {
                width: 100%;
            }

            .msr-filter-actions {
                flex-wrap: wrap;
            }
        }
    </style>
@endsection
