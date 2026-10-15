@extends('layouts.master')

@section('content')
    <div class="pos-list-page">
        <section class="pos-list-heading">
            <div>
                <span class="pos-list-eyebrow">POS management</span>
                <h1>POS List</h1>
                <p>Manage point-of-sale locations, transaction settings, and delivery access.</p>
            </div>
            @can('permission_create')
                <a class="btn btn-light pos-list-add" href="{{ route('admin.pos_system.create') }}">
                    <i class="fas fa-plus me-1" aria-hidden="true"></i> Add POS
                </a>
            @endcan
        </section>

        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @include('shared.pos-location-map', [
            'mapId' => 'admin-pos-locations-map',
            'mapTitle' => 'POS location map',
            'mapDescription' => 'View all POS locations with saved coordinates. Select a marker for details and directions.',
            'locations' => $mapLocations,
        ])

        <section class="card pos-list-panel">
            <div class="pos-list-panel__header">
                <div>
                    <h2>Point-of-sale locations</h2>
                    <p>Use the row actions to edit details or download POS information.</p>
                </div>
                <span class="pos-list-count">{{ number_format($pos->count()) }} shown</span>
            </div>

            <div class="table-responsive scroll-list-section pos-list-table">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead>
                        <tr>
                            <th scope="col">Sl.No</th>
                            <th scope="col">POS Name</th>
                            <th scope="col">POS ID</th>
                            <th scope="col">City</th>
                            <th scope="col">Email</th>
                            <th scope="col">Mobile Number</th>
                            <th scope="col">Transaction %</th>
                            <th scope="col">Min–Max Charge</th>
                            <th scope="col">Account Status</th>
                            <th scope="col">Actions</th>
                            <th scope="col">Delivery Orders</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pos as $key => $data)
                            <tr>
                                <td class="pos-list-row-number">{{ $pos->firstItem() + $key }}</td>
                                <td class="fw-semibold">{{ $data->name ?: '—' }}</td>
                                <td><span class="pos-list-id">{{ $data->user_id ?: '—' }}</span></td>
                                <td>{{ $data->city ?: '—' }}</td>
                                <td>{{ $data->email ?: '—' }}</td>
                                <td>{{ $data->mobilenumber ?: '—' }}</td>
                                <td>{{ number_format((float) $data->transaction_charge, 2) }}%</td>
                                <td>
                                    ₹{{ number_format((float) $data->min_charge, 2) }}
                                    – ₹{{ number_format((float) $data->max_charge, 2) }}
                                </td>
                                <td>
                                    @if ($data->user && (int) $data->user->role === 4)
                                        @if ((int) $data->user->status === 1)
                                            <span class="badge rounded-pill bg-success">Active</span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary">Suspended</span>
                                        @endif
                                    @else
                                        <span class="badge rounded-pill bg-warning text-dark">Account unavailable</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('admin.pos_system.edit', $data->id) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            aria-label="Edit {{ $data->name ?: 'POS' }}" title="Edit POS">
                                            <i class="fas fa-pen" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route('admin.pos_system.download', ['id' => $data->id, 'name' => $data->name]) }}"
                                            class="btn btn-sm btn-outline-secondary"
                                            aria-label="Download {{ $data->name ?: 'POS' }}" title="Download POS details">
                                            <i class="fas fa-download" aria-hidden="true"></i>
                                        </a>
                                        @if ($data->user && (int) $data->user->role === 4)
                                            <form action="{{ route('admin.pos_system.account-status', $data->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="{{ (int) $data->user->status === 1 ? 0 : 1 }}">
                                                <button class="btn btn-sm {{ (int) $data->user->status === 1 ? 'btn-outline-danger' : 'btn-outline-success' }}" type="submit">
                                                    {{ (int) $data->user->status === 1 ? 'Suspend' : 'Activate' }}
                                                </button>
                                            </form>
                                            <details class="pos-list-password">
                                                <summary class="btn btn-outline-secondary btn-sm">Change password</summary>
                                                <form action="{{ route('admin.pos_system.account-password', $data->id) }}" method="POST" class="pos-list-password__form">
                                                    @csrf
                                                    <label for="pos-password-{{ $data->id }}" class="form-label">New password</label>
                                                    <input id="pos-password-{{ $data->id }}" type="password" name="password" class="form-control form-control-sm" minlength="8" autocomplete="new-password" required>
                                                    <label for="pos-password-confirmation-{{ $data->id }}" class="form-label mt-2">Confirm password</label>
                                                    <input id="pos-password-confirmation-{{ $data->id }}" type="password" name="password_confirmation" class="form-control form-control-sm" minlength="8" autocomplete="new-password" required>
                                                    <button class="btn btn-primary btn-sm mt-2" type="submit">Update password</button>
                                                </form>
                                            </details>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if ($data->delivery_orders_enabled)
                                        <span class="badge rounded-pill bg-success">Approved</span>
                                    @elseif ($data->delivery_orders_requested)
                                        <span class="badge rounded-pill bg-warning text-dark">Approval requested</span>
                                        <div class="d-flex flex-wrap gap-1 mt-2">
                                            <form action="{{ route('admin.pos_system.delivery-orders', $data->id) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="decision" value="approve">
                                                <button class="btn btn-sm btn-success" type="submit">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.pos_system.delivery-orders', $data->id) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="decision" value="reject">
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Reject</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="badge rounded-pill bg-secondary">Not enabled</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11">
                                    <div class="pos-list-empty">
                                        <span class="pos-list-empty__icon" aria-hidden="true">
                                            <i class="fas fa-store"></i>
                                        </span>
                                        <strong>No POS locations found</strong>
                                        <span>Add a POS location to see it listed here.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $pos->links() }}
        </section>
    </div>

    <style>
        .pos-list-page {
            display: grid;
            gap: 1.25rem;
            padding-bottom: 1rem;
        }

        .pos-list-password {
            position: relative;
            text-align: left;
        }

        .pos-list-password summary {
            list-style: none;
            cursor: pointer;
        }

        .pos-list-password summary::-webkit-details-marker {
            display: none;
        }

        .pos-list-password__form {
            width: 16rem;
            margin-top: 0.4rem;
            padding: 0.85rem;
            border: 1px solid #dbe3ec;
            border-radius: 0.75rem;
            background: #fff;
            box-shadow: 0 0.75rem 1.75rem rgba(15, 23, 42, 0.16);
        }

        .pos-list-heading {
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

        .pos-list-eyebrow {
            display: inline-block;
            margin-bottom: 0.45rem;
            color: #a8e7d2;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .pos-list-heading h1 {
            margin: 0;
            color: #fff;
            font-size: clamp(1.5rem, 2vw, 2rem);
            font-weight: 800;
        }

        .pos-list-heading p {
            margin: 0.35rem 0 0;
            color: rgba(255, 255, 255, 0.76);
        }

        .pos-list-add {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            flex: 0 0 auto;
            padding: 0.75rem 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.92);
            border-radius: 12px;
            background: #fff;
            color: #183b65;
            font-size: 0.98rem;
            font-weight: 800;
            white-space: nowrap;
            box-shadow: 0 8px 18px rgba(8, 19, 45, 0.24);
            transition: transform 160ms ease, box-shadow 160ms ease, filter 160ms ease;
        }

        .pos-list-add:hover,
        .pos-list-add:focus {
            background: #eaf3ff;
            color: #102e54;
            box-shadow: 0 11px 22px rgba(8, 19, 45, 0.3);
            transform: translateY(-1px);
        }

        .pos-list-add:focus-visible {
            outline: 3px solid #fff;
            outline-offset: 3px;
        }

        .pos-list-panel {
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, 0.22) !important;
        }

        .pos-list-panel__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.18);
        }

        .pos-list-panel__header h2 {
            margin: 0;
            color: #0f172a;
            font-size: 1rem;
            font-weight: 800;
        }

        .pos-list-panel__header p {
            margin: 0.25rem 0 0;
            color: #64748b;
            font-size: 0.86rem;
        }

        .pos-list-count {
            flex: 0 0 auto;
            padding: 0.35rem 0.65rem;
            border: 1px solid rgba(37, 99, 235, 0.12);
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .pos-list-table {
            max-height: min(65vh, 600px);
        }

        .pos-list-table table {
            min-width: 1120px;
        }

        .pos-list-table thead th {
            color: #64748b;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .pos-list-row-number {
            color: #94a3b8 !important;
        }

        .pos-list-id {
            display: inline-block;
            padding: 0.28rem 0.55rem;
            border: 1px solid rgba(37, 99, 235, 0.12);
            border-radius: 8px;
            background: rgba(219, 234, 254, 0.45);
            color: #1d4ed8;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .pos-list-empty {
            display: flex;
            min-height: 210px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            color: #64748b;
            text-align: center;
        }

        .pos-list-empty strong {
            color: #0f172a;
        }

        .pos-list-empty__icon {
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
            .pos-list-heading,
            .pos-list-panel__header {
                align-items: flex-start;
                flex-direction: column;
            }

            .pos-list-heading {
                padding: 1.25rem;
            }

            .pos-list-add {
                width: 100%;
            }
        }
    </style>
@endsection
