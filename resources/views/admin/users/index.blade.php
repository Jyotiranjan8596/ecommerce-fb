@extends('layouts.master')

@section('content')
    <div class="user-list-page">
        <section class="user-list-page__heading">
            <div>
                <span class="user-list-page__eyebrow">Customer management</span>
                <h1>User List</h1>
                <p>Find, manage, and export registered customer accounts.</p>
            </div>
            <div class="user-list-heading-actions">
                <div class="user-list-page__count" aria-label="{{ $user->count() }} {{ $membership === 'prime' ? 'prime users' : 'users' }} loaded">
                    <strong id="user-list-loaded-count">{{ number_format($user->count()) }}</strong>
                    <span>{{ $membership === 'prime' ? 'Prime users loaded' : 'All users loaded' }}</span>
                </div>
                @can('permission_create')
                    <a class="btn btn-light user-list-add" href="{{ route('admin.user.customer') }}">
                        <i class="fas fa-user-plus me-1" aria-hidden="true"></i> Add User
                    </a>
                @endcan
            </div>
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

        <section class="card user-list-panel">
            <div class="user-list-tools">
                @php
                    $primeToggleUrl = route('admin.users.index', array_merge(request()->except('membership'), [
                        'membership' => $membership === 'prime' ? 'all' : 'prime',
                    ]));
                @endphp
                <nav class="user-membership-switch" aria-label="Toggle prime user filter">
                    <a href="{{ $primeToggleUrl }}"
                        class="user-membership-switch__option {{ $membership === 'prime' ? 'is-active' : '' }}"
                        @if ($membership === 'prime') aria-current="page" @endif
                        aria-pressed="{{ $membership === 'prime' ? 'true' : 'false' }}">
                        <i class="fas fa-crown" aria-hidden="true"></i> Prime Users
                    </a>
                </nav>
                <form action="{{ route('admin.users.index') }}" method="GET" class="user-list-search">
                    <input type="hidden" name="membership" value="{{ $membership }}">
                    <label class="visually-hidden" for="user-search">Search users</label>
                    <div class="input-group">
                        <span class="input-group-text" aria-hidden="true"><i class="fas fa-search"></i></span>
                        <input
                            id="user-search"
                            type="search"
                            class="form-control"
                            name="search"
                            placeholder="Search by user ID, name, mobile, or registration date"
                            value="{{ request('search') }}"
                        >
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>

                <div class="user-list-actions">
                    <form action="{{ route('admin.user.import') }}" method="POST" enctype="multipart/form-data" class="user-list-upload">
                        @csrf
                        <label class="visually-hidden" for="user-import-file">Choose user import spreadsheet</label>
                        <input id="user-import-file" type="file" class="form-control form-control-sm" name="file" accept=".xlsx">
                        <button class="btn btn-outline-primary btn-sm" type="submit">
                            <i class="fas fa-upload me-1" aria-hidden="true"></i> Upload
                        </button>
                    </form>

                    <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.user.export') }}">
                        <i class="fas fa-download me-1" aria-hidden="true"></i> Export
                    </a>
                </div>
            </div>

            <div class="table-responsive user-list-table">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">User ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Number</th>
                            <th scope="col">Sponsor</th>
                            <th scope="col">Referrals</th>
                            <th scope="col">Registered</th>
                            <th scope="col">Account Status</th>
                            <th scope="col" class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($user as $key => $data)
                            <tr data-entry-id="{{ $data->id }}">
                                <td class="user-list-row-number">{{ $user->firstItem() + $key }}</td>
                                <td><span class="user-list-id">{{ $data->user_id ?: '—' }}</span></td>
                                <td>
                                    <div class="user-list-customer">
                                        <span class="user-list-avatar" aria-hidden="true">
                                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($data->name ?: '?', 0, 1)) }}
                                        </span>
                                        <span class="user-list-name">
                                            {{ $data->name ?: 'Name unavailable' }}
                                            @if ($data->is_prime_member)
                                                <span class="prime-badge ms-1">Prime</span>
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td>{{ $data->mobilenumber ?: '—' }}</td>
                                <td>{{ $data->sponsorUser?->user_id ?: '—' }}</td>
                                <td>
                                    <a
                                        class="user-list-referrals-link"
                                        href="{{ route('admin.users.referrals', $data->id) }}"
                                        aria-label="View {{ $data->referrals_count }} referrals for {{ $data->name ?: 'user' }}"
                                        title="View referral list"
                                    >{{ number_format($data->referrals_count) }}</a>
                                </td>
                                <td>{{ optional($data->created_at)->format('d/m/Y') ?: '—' }}</td>
                                <td>
                                    @if ((int) $data->status === 1)
                                        <span class="badge rounded-pill bg-success">Active</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary">Suspended</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex flex-wrap justify-content-end align-items-start gap-2">
                                        <a
                                            href="{{ route('admin.users.transactions', $data->id) }}"
                                            class="btn btn-outline-success btn-sm"
                                            aria-label="View transactions for {{ $data->name ?: 'user' }}"
                                            title="View customer transactions"
                                        >
                                            <i class="fas fa-receipt" aria-hidden="true"></i>
                                        </a>
                                        <a
                                            href="{{ route('admin.users.edit', $data->id) }}"
                                            class="btn btn-outline-primary btn-sm user-list-edit"
                                            aria-label="Edit {{ $data->name ?: 'user' }}"
                                            title="Edit user"
                                        >
                                            <i class="fas fa-pen" aria-hidden="true"></i>
                                        </a>
                                        <form action="{{ route('admin.users.status', $data->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="{{ (int) $data->status === 1 ? 0 : 1 }}">
                                            <button class="btn btn-sm {{ (int) $data->status === 1 ? 'btn-outline-danger' : 'btn-outline-success' }}" type="submit">
                                                {{ (int) $data->status === 1 ? 'Suspend' : 'Activate' }}
                                            </button>
                                        </form>
                                        <details class="user-list-password">
                                            <summary class="btn btn-outline-secondary btn-sm">Change password</summary>
                                            <form action="{{ route('admin.users.password', $data->id) }}" method="POST" class="user-list-password__form">
                                                @csrf
                                                <label for="customer-password-{{ $data->id }}" class="form-label">New password</label>
                                                <input id="customer-password-{{ $data->id }}" type="password" name="password" class="form-control form-control-sm" minlength="8" autocomplete="new-password" required>
                                                <label for="customer-password-confirmation-{{ $data->id }}" class="form-label mt-2">Confirm password</label>
                                                <input id="customer-password-confirmation-{{ $data->id }}" type="password" name="password_confirmation" class="form-control form-control-sm" minlength="8" autocomplete="new-password" required>
                                                <button class="btn btn-primary btn-sm mt-2" type="submit">Update password</button>
                                            </form>
                                        </details>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="user-list-empty">
                                        <span class="user-list-empty__icon" aria-hidden="true"><i class="fas fa-users"></i></span>
                                        <strong>No users found</strong>
                                        <span>Try changing your search or clear the filter to see all users.</span>
                                        @if (request('search'))
                                            <a href="{{ route('admin.users.index', ['membership' => $membership]) }}" class="btn btn-outline-primary btn-sm">Clear search</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($user->hasPages())
                <div class="user-list-pagination">
                    <span class="user-list-pagination__summary">
                        Showing {{ $user->firstItem() }}–{{ $user->lastItem() }} users
                    </span>
                    {{ $user->appends(request()->query())->links() }}
                </div>
            @endif
        </section>
    </div>

    <style>
        .user-list-page {
            display: grid;
            gap: 1.25rem;
            padding-bottom: 1rem;
        }

        .user-list-password {
            position: relative;
            text-align: left;
        }

        .user-list-password summary {
            list-style: none;
            cursor: pointer;
        }

        .user-list-password summary::-webkit-details-marker {
            display: none;
        }

        .user-list-password__form {
            width: 16rem;
            margin-top: 0.4rem;
            padding: 0.85rem;
            border: 1px solid #dbe3ec;
            border-radius: 0.75rem;
            background: #fff;
            box-shadow: 0 0.75rem 1.75rem rgba(15, 23, 42, 0.16);
        }

        .user-list-page__heading {
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

        .user-list-page__eyebrow {
            display: inline-block;
            margin-bottom: 0.45rem;
            color: #a8e7d2;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .user-list-page__heading h1 {
            margin: 0;
            color: #fff;
            font-size: clamp(1.6rem, 2vw, 2.1rem);
            font-weight: 800;
        }

        .user-list-page__heading p {
            margin: 0.35rem 0 0;
            color: rgba(255, 255, 255, 0.76);
        }

        .user-list-page__count {
            display: flex;
            min-width: 145px;
            flex-direction: column;
            padding: 0.8rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.1);
            text-align: right;
        }

        .user-list-page__count strong {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .user-list-page__count span {
            color: rgba(255, 255, 255, 0.72);
            font-size: 0.75rem;
        }

        .user-list-heading-actions {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .user-list-add {
            display: inline-flex;
            min-height: 48px;
            align-items: center;
            justify-content: center;
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

        .user-list-add:hover,
        .user-list-add:focus {
            background: #eaf3ff;
            color: #102e54;
            box-shadow: 0 11px 22px rgba(8, 19, 45, 0.3);
            transform: translateY(-1px);
        }

        .user-list-add:focus-visible {
            outline: 3px solid #fff;
            outline-offset: 3px;
        }

        .user-list-panel {
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, 0.22) !important;
        }

        .user-list-tools {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.1rem 1.25rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.18);
            background: rgba(248, 250, 252, 0.72);
        }

        .user-list-search {
            flex: 1 1 360px;
            max-width: 520px;
        }

        .user-membership-switch {
            display: inline-flex;
            flex: 0 0 auto;
            gap: 0.25rem;
            padding: 0.25rem;
            border: 1px solid #dbe4ef;
            border-radius: 12px;
            background: #edf3f9;
        }

        .user-membership-switch__option {
            display: inline-flex;
            min-height: 40px;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            padding: 0.55rem 0.8rem;
            border-radius: 9px;
            color: #53677d;
            font-size: 0.82rem;
            font-weight: 750;
            text-decoration: none;
            white-space: nowrap;
            transition: background 150ms ease, color 150ms ease, box-shadow 150ms ease;
        }

        .user-membership-switch__option:hover,
        .user-membership-switch__option:focus-visible {
            color: #174a79;
            background: #fff;
        }

        .user-membership-switch__option.is-active {
            color: #fff;
            background: #1769aa;
            box-shadow: 0 3px 8px rgba(23, 105, 170, 0.2);
        }

        .user-membership-switch__option:last-child.is-active {
            background: linear-gradient(135deg, #9b6b18, #d4a93e);
            box-shadow: 0 3px 8px rgba(155, 107, 24, 0.22);
        }

        .user-list-search .input-group-text {
            color: #64748b;
            background: #fff;
        }

        .user-list-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 0.55rem;
        }

        .user-list-upload {
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        .user-list-upload input[type="file"] {
            width: min(220px, 22vw);
        }

        .user-list-table {
            overflow-x: auto;
        }

        .user-list-table table {
            min-width: 860px;
        }

        .user-list-referrals-link {
            display: inline-flex;
            min-width: 2rem;
            min-height: 2rem;
            align-items: center;
            justify-content: center;
            padding: 0.2rem 0.55rem;
            border-radius: 999px;
            background: #e8f1ff;
            color: #1d4ed8;
            font-weight: 700;
            text-decoration: none;
        }

        .user-list-referrals-link:hover,
        .user-list-referrals-link:focus-visible {
            background: #1d4ed8;
            color: #fff;
        }

        .user-list-table thead th {
            padding-top: 0.85rem;
            padding-bottom: 0.85rem;
            color: #64748b;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .user-list-table tbody td {
            color: #334155;
        }

        .user-list-row-number {
            width: 55px;
            color: #94a3b8 !important;
            font-size: 0.82rem;
        }

        .user-list-id {
            display: inline-block;
            padding: 0.28rem 0.55rem;
            border: 1px solid rgba(37, 99, 235, 0.12);
            border-radius: 8px;
            background: rgba(219, 234, 254, 0.45);
            color: #1d4ed8;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .user-list-customer {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .user-list-avatar {
            display: inline-grid;
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            place-items: center;
            border-radius: 11px;
            background: linear-gradient(135deg, #dbeafe, #d1fae5);
            color: #0f766e;
            font-size: 0.85rem;
            font-weight: 800;
        }

        .user-list-name {
            color: #0f172a;
            font-weight: 700;
        }

        .user-list-edit {
            width: 34px;
            height: 34px;
            padding: 0;
            display: inline-grid;
            place-items: center;
        }

        .user-list-empty {
            display: flex;
            min-height: 230px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            color: #64748b;
            text-align: center;
        }

        .user-list-empty strong {
            color: #0f172a;
            font-size: 1rem;
        }

        .user-list-empty__icon {
            display: grid;
            width: 48px;
            height: 48px;
            margin-bottom: 0.35rem;
            place-items: center;
            border-radius: 16px;
            background: #eff6ff;
            color: #2563eb;
            font-size: 1.1rem;
        }

        .user-list-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.9rem 1.25rem;
            border-top: 1px solid rgba(148, 163, 184, 0.18);
        }

        .user-list-pagination__summary {
            color: #64748b;
            font-size: 0.83rem;
        }

        .user-list-pagination nav p {
            display: none;
        }

        .user-list-pagination nav .pagination {
            margin: 0;
        }

        @media (max-width: 991.98px) {
            .user-list-page__heading,
            .user-list-tools,
            .user-list-pagination {
                align-items: stretch;
                flex-direction: column;
            }

            .user-list-page__heading {
                padding: 1.25rem;
            }

            .user-list-heading-actions {
                align-items: stretch;
                flex-direction: column;
            }

            .user-list-add {
                width: 100%;
            }

            .user-list-page__count {
                min-width: 0;
                align-items: flex-start;
                text-align: left;
            }

            .user-list-search {
                flex-basis: auto;
                max-width: none;
            }

            .user-membership-switch {
                width: fit-content;
                max-width: 100%;
            }

            .user-list-actions {
                justify-content: flex-start;
            }

            .user-list-upload {
                width: 100%;
            }

            .user-list-upload input[type="file"] {
                width: 100%;
                min-width: 0;
            }

            .user-list-pagination {
                gap: 0.6rem;
            }
        }
    </style>
@endsection
