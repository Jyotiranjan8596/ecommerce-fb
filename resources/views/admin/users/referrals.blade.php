@extends('layouts.master')

@section('content')
    <div class="referrals-page">
        <section class="referrals-hero">
            <div class="referrals-hero__copy">
                <span class="referrals-eyebrow">Customer family</span>
                <h1>
                    Referrals of {{ \Illuminate\Support\Str::title($user->name) }}
                    @if ($user->is_prime_member)
                        <span class="prime-badge ms-1">Prime</span>
                    @endif
                </h1>
                <p>Customers who joined through this sponsor.</p>
                <div class="referrals-sponsor-id">
                    <span>Sponsor user ID</span>
                    <strong>{{ $user->user_id ?: '—' }}</strong>
                </div>
            </div>
            <div class="referrals-hero__summary" aria-label="{{ number_format($referrals->total()) }} total referrals">
                <span class="referrals-hero__icon" aria-hidden="true"><i class="fas fa-users"></i></span>
                <strong>{{ number_format($referrals->total()) }}</strong>
                <span>{{ $referrals->total() === 1 ? 'Direct referral' : 'Direct referrals' }}</span>
            </div>
            <a class="referrals-back" href="{{ route('admin.users.index') }}">
                <i class="fas fa-arrow-left" aria-hidden="true"></i>
                <span>Back to users</span>
            </a>
        </section>

        <section class="referrals-panel">
            <div class="referrals-panel__heading">
                <div>
                    <span class="referrals-panel__eyebrow">Sponsor network</span>
                    <h2>Direct referrals</h2>
                </div>
                <span class="referrals-panel__count">{{ number_format($referrals->total()) }} total</span>
            </div>

            <div class="referrals-table-wrap">
                <table class="referrals-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">User ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Number</th>
                            <th scope="col">Registered</th>
                            <th scope="col" class="referrals-table__action-heading">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($referrals as $key => $referral)
                            <tr>
                                <td data-label="#" class="referrals-table__row-number">{{ $referrals->firstItem() + $key }}</td>
                                <td data-label="User ID"><span class="referrals-user-id">{{ $referral->user_id ?: '—' }}</span></td>
                                <td data-label="Name">
                                    <div class="referrals-name">
                                        <span class="referrals-avatar" aria-hidden="true">
                                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($referral->name ?: '?', 0, 1)) }}
                                        </span>
                                        <strong>
                                            {{ \Illuminate\Support\Str::title($referral->name ?: 'Name unavailable') }}
                                            @if ($referral->is_prime_member)
                                                <span class="prime-badge ms-1">Prime</span>
                                            @endif
                                        </strong>
                                    </div>
                                </td>
                                <td data-label="Number">{{ $referral->mobilenumber ?: '—' }}</td>
                                <td data-label="Registered">{{ optional($referral->created_at)->format('d/m/Y') ?: '—' }}</td>
                                <td data-label="Action" class="referrals-table__action">
                                    <a
                                        class="referrals-edit"
                                        href="{{ route('admin.users.edit', $referral->id) }}"
                                        aria-label="Edit {{ $referral->name ?: 'user' }}"
                                        title="Edit customer"
                                    >
                                        <i class="fas fa-pen" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="referrals-empty">
                                        <span class="referrals-empty__icon" aria-hidden="true"><i class="fas fa-users"></i></span>
                                        <strong>No referrals yet</strong>
                                        <span>Customers sponsored by {{ \Illuminate\Support\Str::title($user->name) }} will appear here.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($referrals->hasPages())
                <div class="referrals-pagination">
                    <span>Showing {{ $referrals->firstItem() }}–{{ $referrals->lastItem() }} of {{ number_format($referrals->total()) }}</span>
                    {{ $referrals->links() }}
                </div>
            @endif
        </section>
    </div>

    <style>
        .referrals-page {
            display: grid;
            gap: 1.25rem;
            padding: 0.25rem 0 1.5rem;
        }

        .referrals-hero {
            position: relative;
            display: flex;
            min-height: 235px;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            overflow: hidden;
            padding: clamp(1.4rem, 4vw, 2.4rem);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 24px;
            background:
                radial-gradient(circle at 88% 10%, rgba(45, 212, 191, 0.22), transparent 27%),
                linear-gradient(130deg, #102b2b 0%, #173d50 58%, #1e40af 100%);
            color: #fff;
            box-shadow: 0 20px 48px rgba(15, 23, 42, 0.14);
        }

        .referrals-hero__copy {
            position: relative;
            z-index: 1;
            max-width: 650px;
        }

        .referrals-eyebrow,
        .referrals-panel__eyebrow {
            color: #9de8d4;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .referrals-hero h1 {
            margin: 0.45rem 0 0;
            color: #fff;
            font-size: clamp(1.65rem, 3vw, 2.35rem);
            font-weight: 800;
            line-height: 1.15;
            overflow-wrap: anywhere;
        }

        .referrals-hero__copy > p {
            margin: 0.55rem 0 1rem;
            color: rgba(255, 255, 255, 0.76);
        }

        .referrals-sponsor-id {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.48rem 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.17);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.09);
            font-size: 0.85rem;
        }

        .referrals-sponsor-id span {
            color: rgba(255, 255, 255, 0.7);
        }

        .referrals-sponsor-id strong {
            color: #fff;
        }

        .referrals-hero__summary {
            position: relative;
            z-index: 1;
            display: grid;
            min-width: 150px;
            justify-items: center;
            gap: 0.2rem;
            padding: 1.1rem 1.35rem;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
        }

        .referrals-hero__summary strong {
            color: #fff;
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.1;
        }

        .referrals-hero__summary > span:last-child {
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.78rem;
        }

        .referrals-hero__icon {
            display: grid;
            width: 2.2rem;
            height: 2.2rem;
            margin-bottom: 0.25rem;
            place-items: center;
            border-radius: 12px;
            background: rgba(45, 212, 191, 0.2);
            color: #a7f3d0;
        }

        .referrals-back {
            position: absolute;
            top: 1.1rem;
            right: 1.2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            color: rgba(255, 255, 255, 0.84);
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
        }

        .referrals-back:hover,
        .referrals-back:focus-visible {
            color: #fff;
            text-decoration: underline;
        }

        .referrals-panel {
            overflow: hidden;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.06);
        }

        .referrals-panel__heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.2rem 1.4rem;
            border-bottom: 1px solid #edf1f5;
        }

        .referrals-panel__eyebrow {
            color: #0f8a72;
            font-size: 0.65rem;
        }

        .referrals-panel__heading h2 {
            margin: 0.2rem 0 0;
            color: #172b3a;
            font-size: 1.15rem;
            font-weight: 800;
        }

        .referrals-panel__count {
            padding: 0.4rem 0.7rem;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 0.78rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .referrals-table-wrap {
            overflow-x: auto;
        }

        .referrals-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .referrals-table th,
        .referrals-table td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #edf1f5;
            text-align: left;
            vertical-align: middle;
        }

        .referrals-table th {
            background: #f8fafc;
            color: #64748b;
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .referrals-table td {
            color: #334155;
            font-size: 0.88rem;
        }

        .referrals-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .referrals-table tbody tr:hover {
            background: #fbfdff;
        }

        .referrals-table__row-number {
            color: #94a3b8 !important;
            font-variant-numeric: tabular-nums;
        }

        .referrals-user-id {
            display: inline-block;
            padding: 0.28rem 0.5rem;
            border-radius: 7px;
            background: #f1f5f9;
            color: #475569;
            font-size: 0.78rem;
            font-weight: 700;
            font-variant-numeric: tabular-nums;
        }

        .referrals-name {
            display: flex;
            min-width: 170px;
            align-items: center;
            gap: 0.6rem;
        }

        .referrals-name strong {
            color: #172b3a;
            font-weight: 700;
        }

        .referrals-avatar {
            display: grid;
            width: 2rem;
            height: 2rem;
            flex: 0 0 2rem;
            place-items: center;
            border-radius: 50%;
            background: linear-gradient(135deg, #d1fae5, #dbeafe);
            color: #0f766e;
            font-size: 0.78rem;
            font-weight: 800;
        }

        .referrals-table__action-heading,
        .referrals-table__action {
            text-align: right !important;
        }

        .referrals-edit {
            display: inline-grid;
            width: 2rem;
            height: 2rem;
            place-items: center;
            border: 1px solid #dbeafe;
            border-radius: 9px;
            background: #fff;
            color: #2563eb;
            text-decoration: none;
        }

        .referrals-edit:hover,
        .referrals-edit:focus-visible {
            border-color: #2563eb;
            background: #2563eb;
            color: #fff;
        }

        .referrals-empty {
            display: grid;
            min-height: 220px;
            justify-items: center;
            align-content: center;
            gap: 0.5rem;
            padding: 2rem 1rem;
            color: #64748b;
            text-align: center;
        }

        .referrals-empty strong {
            color: #172b3a;
            font-size: 1rem;
        }

        .referrals-empty__icon {
            display: grid;
            width: 3rem;
            height: 3rem;
            margin-bottom: 0.25rem;
            place-items: center;
            border-radius: 16px;
            background: #eff6ff;
            color: #2563eb;
            font-size: 1.2rem;
        }

        .referrals-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.4rem;
            border-top: 1px solid #edf1f5;
            color: #64748b;
            font-size: 0.8rem;
        }

        .referrals-pagination nav {
            margin-left: auto;
        }

        @media (max-width: 767.98px) {
            .referrals-hero {
                min-height: 0;
                align-items: flex-start;
                flex-direction: column;
                padding: 3.7rem 1.25rem 1.25rem;
            }

            .referrals-back {
                top: 1.15rem;
                right: auto;
                left: 1.25rem;
            }

            .referrals-hero__summary {
                width: 100%;
                grid-template-columns: auto 1fr;
                justify-items: start;
                column-gap: 0.75rem;
                padding: 0.8rem 0.95rem;
            }

            .referrals-hero__icon {
                grid-row: span 2;
                margin: 0;
            }

            .referrals-hero__summary strong {
                font-size: 1.4rem;
            }

            .referrals-panel__heading {
                padding: 1rem;
            }

            .referrals-table-wrap {
                overflow: visible;
                padding: 0.7rem;
            }

            .referrals-table,
            .referrals-table tbody,
            .referrals-table tr,
            .referrals-table td {
                display: block;
                width: 100%;
            }

            .referrals-table thead {
                display: none;
            }

            .referrals-table tbody tr {
                margin-bottom: 0.75rem;
                padding: 0.2rem 0.75rem;
                border: 1px solid #e6edf5;
                border-radius: 14px;
                background: #fff;
                box-shadow: 0 5px 16px rgba(15, 23, 42, 0.04);
            }

            .referrals-table tbody tr:last-child {
                margin-bottom: 0;
            }

            .referrals-table td {
                display: grid;
                grid-template-columns: minmax(92px, 0.38fr) minmax(0, 1fr);
                gap: 0.7rem;
                padding: 0.65rem 0;
                border-bottom: 1px solid #f1f5f9;
                overflow-wrap: anywhere;
            }

            .referrals-table td::before {
                content: attr(data-label);
                color: #64748b;
                font-size: 0.68rem;
                font-weight: 800;
                letter-spacing: 0.05em;
                text-transform: uppercase;
            }

            .referrals-table td[colspan] {
                display: block;
            }

            .referrals-table td[colspan]::before {
                content: none;
            }

            .referrals-table .referrals-table__row-number {
                display: none;
            }

            .referrals-table .referrals-name {
                min-width: 0;
            }

            .referrals-table .referrals-table__action {
                text-align: left !important;
            }

            .referrals-empty {
                min-height: 180px;
            }

            .referrals-pagination {
                align-items: flex-start;
                flex-direction: column;
                padding: 1rem;
            }

            .referrals-pagination nav {
                margin-left: 0;
            }
        }
    </style>
@endsection
