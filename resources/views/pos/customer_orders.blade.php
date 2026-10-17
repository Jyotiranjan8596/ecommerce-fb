@extends('pos.layouts.master')

@section('content')
    @php
        $pendingCount = $orders->filter(fn($o) => strtolower(trim($o->status)) === \App\Models\CustomerOrder::STATUS_PENDING)->count();
        $readyCount = $orders->filter(fn($o) => strtolower(trim($o->status)) === \App\Models\CustomerOrder::STATUS_READY_TO_DISPATCH)->count();
    @endphp

    <div class="container-fluid px-2 px-md-3 py-3 co-page">

        {{-- Hero header --}}
        <section class="dashboard-hero mb-4">
            <span class="dashboard-hero__label"><i class="fa fa-store me-1" aria-hidden="true"></i> POS overview</span>
            <h1 class="dashboard-hero__title">Customer Orders</h1>
            <p class="dashboard-hero__text">Orders assigned to {{ $pos->name }}. Update each order through the next
                status.</p>
            <div class="dashboard-hero__stats co-hero-stats">
                <div class="dashboard-hero__stat">
                    <small>Active orders</small>
                    <strong>{{ $orders->count() }}</strong>
                </div>
                <div class="dashboard-hero__stat">
                    <small>Awaiting confirmation</small>
                    <strong>{{ $pendingCount }}</strong>
                </div>
                <div class="dashboard-hero__stat">
                    <small>Ready to despatch</small>
                    <strong>{{ $readyCount }}</strong>
                </div>
            </div>
        </section>

        @if (session('message'))
            <div class="co-alert-success mb-4" role="alert">
                <i class="fa fa-check-circle me-2" aria-hidden="true"></i> {{ session('message') }}
            </div>
        @endif

        {{-- Orders list --}}
        <div class="co-order-list">
            @forelse ($orders as $order)
                <section class="card border-0 shadow-lg rounded-4 overflow-hidden co-order-card">
                    <div class="card-header border-0 bg-white px-3 px-md-4 pt-4 pb-3">
                        <div
                            class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                            <div>
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                    <h2 class="h5 mb-0 fw-bold text-dark">Order #{{ $order->id }}</h2>
                                    <span
                                        class="co-status-badge co-status-{{ strtolower(trim($order->status)) }}">{{ $order->status_label }}</span>
                                    @include('shared.transaction-status-audit', ['statusChanges' => $order->statusChanges])
                                </div>
                                <div class="co-order-meta">
                                    <span><i class="fa fa-user me-1" aria-hidden="true"></i>
                                        {{ $order->customer?->name ?? 'Customer' }}</span>
                                    @if ($order->customer?->mobilenumber)
                                        <span><i class="fa fa-phone me-1" aria-hidden="true"></i>
                                            {{ $order->customer->mobilenumber }}</span>
                                    @endif
                                    <span><i class="fa fa-clock me-1" aria-hidden="true"></i> Placed
                                        {{ $order->created_at->format('M j, Y g:i A') }}</span>
                                </div>
                            </div>

                            @php
                                $nextStatus = match ($order->status) {
                                    \App\Models\CustomerOrder::STATUS_PENDING => \App\Models\CustomerOrder::STATUS_CONFIRMED,
                                    \App\Models\CustomerOrder::STATUS_CONFIRMED => \App\Models\CustomerOrder::STATUS_READY_TO_DISPATCH,
                                    \App\Models\CustomerOrder::STATUS_READY_TO_DISPATCH => \App\Models\CustomerOrder::STATUS_DELIVERED,
                                    default => null,
                                };
                                $nextStatusLabel = match ($nextStatus) {
                                    \App\Models\CustomerOrder::STATUS_CONFIRMED => 'Confirm Order',
                                    \App\Models\CustomerOrder::STATUS_READY_TO_DISPATCH => 'Mark Ready to Despatch',
                                    \App\Models\CustomerOrder::STATUS_DELIVERED => 'Mark Delivered',
                                    default => null,
                                };
                            @endphp

                            @if ($nextStatus)
                                <form class="m-0" action="{{ route('pos.customer-orders.status', $order) }}"
                                    method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $nextStatus }}">
                                    <button class="btn co-btn-primary" type="submit">
                                        <i class="fa fa-arrow-circle-right me-1" aria-hidden="true"></i>
                                        {{ $nextStatusLabel }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="card-body px-3 px-md-4 pt-2 pb-3">
                        @if ($order->items)
                            <div class="table-responsive co-table-wrap" tabindex="0" aria-label="Scrollable order items">
                                <table class="table align-middle mb-0 co-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Items</th>
                                            <th scope="col">Brand</th>
                                            <th scope="col">Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->items as $item)
                                            <tr>
                                                <td data-label="Items" class="co-item-name">{{ $item['item'] }}</td>
                                                <td data-label="Brand">{{ $item['brand'] ?: '—' }}</td>
                                                <td data-label="Quantity">{{ $item['quantity'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if ($order->handwritten_list_path)
                            <a class="btn co-btn-outline mt-3"
                                href="{{ route('pos.customer-orders.list.download', $order) }}">
                                <i class="fa fa-download me-1" aria-hidden="true"></i> Download handwritten list
                            </a>
                        @endif
                    </div>
                </section>
            @empty
                <section class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body">
                        <div class="co-empty-state">
                            <span class="co-empty-icon"><i class="fa fa-inbox" aria-hidden="true"></i></span>
                            <p class="mb-0">There are no current customer orders assigned to this POS.</p>
                        </div>
                    </div>
                </section>
            @endforelse
        </div>
    </div>

    <style>
        .co-page {
            color: #172033;
        }

        .co-page .co-hero-stats {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        }

        .co-alert-success {
            display: flex;
            align-items: center;
            padding: .9rem 1.1rem;
            border: 1px solid #c9ebe0;
            border-radius: .85rem;
            background: #e8f5f1;
            color: #08735f;
            font-weight: 650;
        }

        .co-order-list {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .co-order-meta {
            display: flex;
            flex-wrap: wrap;
            gap: .25rem 1rem;
            color: #66768a;
            font-size: .85rem;
        }

        .co-status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: .3rem .7rem;
            border-radius: 999px;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .02em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .co-status-pending {
            background: #fff4e0;
            color: #a15c00;
        }

        .co-status-confirmed {
            background: #edf3fb;
            color: #145da0;
        }

        .co-status-ready_to_dispatch {
            background: #e7f1fd;
            color: #0c6b8c;
        }

        .co-status-delivered {
            background: #e8f5f1;
            color: #08735f;
        }

        .co-page .btn.co-btn-primary,
        .co-page .btn.co-btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: .55rem 1.1rem;
            border-radius: .7rem;
            font-size: .88rem;
            font-weight: 750;
            white-space: nowrap;
            transition: transform .15s ease, box-shadow .15s ease, background-color .15s ease;
        }

        .co-page .btn.co-btn-primary:hover,
        .co-page .btn.co-btn-outline:hover {
            transform: translateY(-1px);
        }

        .co-page .btn.co-btn-primary {
            border: 1px solid transparent;
            background: linear-gradient(135deg, #1e293b, #1e40af);
            color: #fff;
            box-shadow: 0 .5rem 1rem rgba(30, 64, 175, .18);
        }

        .co-page .btn.co-btn-primary:hover {
            color: #fff;
            box-shadow: 0 .7rem 1.3rem rgba(30, 64, 175, .24);
        }

        .co-page .btn.co-btn-outline {
            border: 1px solid #dde5ec;
            background: #fff;
            color: #4a5a70;
        }

        .co-page .btn.co-btn-outline:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
            color: #1e293b;
        }

        .co-page .co-table-wrap {
            max-height: 320px;
            overflow-x: auto;
            overflow-y: auto;
            border: 1px solid #e4ebf1;
            border-radius: .85rem;
            scrollbar-color: #9abfc0 #f0f5f6;
            scrollbar-width: thin;
        }

        .co-page .co-table-wrap::-webkit-scrollbar {
            width: .55rem;
            height: .55rem;
        }

        .co-page .co-table-wrap::-webkit-scrollbar-thumb {
            border: 2px solid #f0f5f6;
            border-radius: 999px;
            background: #9abfc0;
        }

        .co-page .co-table-wrap::-webkit-scrollbar-track {
            background: #f0f5f6;
        }

        .co-page .co-table {
            min-width: 480px;
            color: #263449;
            /* The global .table rule sets overflow: hidden, which makes the table
               itself (not the scrollable .co-table-wrap) the sticky positioning
               context, so the sticky header would scroll away with the rows
               instead of staying fixed. Restore normal overflow here so sticky
               headers stick relative to the scrollable wrapper. */
            overflow: visible;
        }

        .co-page .co-table thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            padding: .75rem 1rem;
            border-bottom: 1px solid #dfe8ee;
            background: #f0f7f7;
            color: #174b54;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .05em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .co-page .co-table tbody td {
            padding: .75rem 1rem;
            border-color: #edf1f5;
            font-size: .88rem;
            vertical-align: middle;
        }

        .co-page .co-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .co-page .co-table tbody tr:hover {
            background: #f8fbfc;
        }

        .co-page .co-item-name {
            color: #25364a;
            font-weight: 650;
        }

        .co-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .65rem;
            padding: 2rem 1rem;
            color: #66768a;
        }

        .co-empty-icon {
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
            .co-page .co-table-wrap {
                max-height: min(55vh, 420px);
            }

            .co-page .co-table {
                min-width: 0;
                border-collapse: separate;
                border-spacing: 0 .6rem;
            }

            .co-page .co-table thead {
                display: none;
            }

            .co-page .co-table tbody {
                display: block;
            }

            .co-page .co-table tbody tr {
                display: grid;
                grid-template-columns: 1fr;
                overflow: hidden;
                border: 1px solid #e3ebf0;
                border-radius: .75rem;
                background: #fff;
                box-shadow: 0 .25rem .8rem rgba(23, 32, 51, .045);
            }

            .co-page .co-table tbody tr td {
                min-width: 0;
                padding: .5rem .8rem;
                border: 0;
                overflow-wrap: anywhere;
            }

            .co-page .co-table tbody tr td::before {
                display: block;
                margin-bottom: .15rem;
                color: #64748b;
                content: attr(data-label);
                font-size: .64rem;
                font-weight: 800;
                letter-spacing: .04em;
                text-transform: uppercase;
            }

            .co-page .co-table tbody tr td[data-label="Items"] {
                background: #f0f7f7;
                color: #17324d;
                font-size: .95rem;
                font-weight: 800;
            }

            .co-page .co-table tbody tr td[data-label="Items"]::before {
                color: #087f8c;
            }
        }
    </style>
@endsection
