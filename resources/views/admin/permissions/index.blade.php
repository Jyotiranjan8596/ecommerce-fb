@extends('layouts.master')

@section('content')
    <div class="permission-management">
        <section class="permission-management__heading">
            <div>
                <span class="permission-management__eyebrow">Access control</span>
                <h1>Permissions</h1>
                <p>Manage the capabilities that can be assigned to roles across the dashboard.</p>
            </div>
            <span class="permission-management__heading-icon" aria-hidden="true">
                <i class="fas fa-key"></i>
            </span>
        </section>

        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif

        <section class="card permission-management__card">
            <div class="permission-management__toolbar">
                <div>
                    <h2>Permission list</h2>
                    <p>{{ number_format($permissions->total()) }} {{ \Illuminate\Support\Str::plural('permission', $permissions->total()) }} available</p>
                </div>
                <div class="permission-management__toolbar-actions">
                    <form action="{{ route('admin.permissions.index') }}" method="GET" class="permission-management__search">
                        <label class="sr-only" for="permission-search">Search permissions</label>
                        <i class="fas fa-search" aria-hidden="true"></i>
                        <input id="permission-search" type="search" name="search" value="{{ $search }}"
                            placeholder="Search permissions" aria-label="Search permissions">
                        @if ($search !== '')
                            <a href="{{ route('admin.permissions.index') }}" class="permission-management__clear" aria-label="Clear search">
                                <i class="fas fa-times" aria-hidden="true"></i>
                            </a>
                        @endif
                    </form>
                    @can('permission_create')
                        <a class="btn permission-management__add" href="{{ route('admin.permissions.create') }}">
                            <i class="fas fa-plus" aria-hidden="true"></i> Add permission
                        </a>
                    @endcan
                </div>
            </div>

            <div class="table-responsive">
                <table class="table permission-management__table">
                    <thead>
                        <tr>
                            <th scope="col">Permission</th>
                            <th scope="col">Assigned roles</th>
                            <th scope="col" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permissions as $permission)
                            <tr data-entry-id="{{ $permission->id }}">
                                <td>
                                    <div class="permission-management__name">
                                        <span class="permission-management__row-icon" aria-hidden="true"><i class="fas fa-shield-alt"></i></span>
                                        <span>
                                            <strong>{{ $permission->name }}</strong>
                                            <small>ID {{ $permission->id }}</small>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="permission-management__role-count">
                                        <i class="fas fa-user-tag" aria-hidden="true"></i>
                                        {{ $permission->roles_count }} {{ \Illuminate\Support\Str::plural('role', $permission->roles_count) }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="permission-management__actions">
                                        @can('permission_access')
                                            <a class="permission-management__action permission-management__action--view"
                                                href="{{ route('admin.permissions.show', $permission) }}" aria-label="View {{ $permission->name }}">
                                                <i class="fas fa-eye" aria-hidden="true"></i><span>View</span>
                                            </a>
                                        @endcan
                                        @can('permission_edit')
                                            <a class="permission-management__action permission-management__action--edit"
                                                href="{{ route('admin.permissions.edit', $permission) }}" aria-label="Edit {{ $permission->name }}">
                                                <i class="fas fa-pen" aria-hidden="true"></i><span>Edit</span>
                                            </a>
                                        @endcan
                                        @can('permission_delete')
                                            <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}"
                                                onsubmit="return confirm('Delete this permission? It will also be removed from assigned roles.')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="permission-management__action permission-management__action--delete"
                                                    type="submit" aria-label="Delete {{ $permission->name }}">
                                                    <i class="fas fa-trash-alt" aria-hidden="true"></i><span>Delete</span>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="permission-management__empty">
                                        <span><i class="fas fa-key" aria-hidden="true"></i></span>
                                        <strong>{{ $search !== '' ? 'No matching permissions' : 'No permissions found' }}</strong>
                                        <p>{{ $search !== '' ? 'Try another search term or clear the filter.' : 'Create a permission to start assigning capabilities to roles.' }}</p>
                                        @if ($search !== '')
                                            <a href="{{ route('admin.permissions.index') }}">Clear search</a>
                                        @elseif (auth()->user()->can('permission_create'))
                                            <a href="{{ route('admin.permissions.create') }}">Create permission</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($permissions->hasPages())
                <div class="permission-management__pagination">
                    {{ $permissions->links() }}
                </div>
            @endif
        </section>
    </div>

    <style>
        .permission-management {
            --permission-ink: #172b3a;
            --permission-muted: #64748b;
            --permission-border: #e2eaf1;
            padding: .5rem 0;
            color: var(--permission-ink);
        }
        .permission-management__heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.25rem;
            padding: 1.5rem;
            border: 1px solid #dce8ef;
            border-radius: 1.1rem;
            background: linear-gradient(120deg, #f0faf8, #eef5ff);
        }
        .permission-management__eyebrow {
            color: #0d7a8a;
            font-size: .7rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
        }
        .permission-management__heading h1 {
            margin: .25rem 0;
            color: var(--permission-ink);
            font-size: clamp(1.4rem, 3vw, 1.8rem);
            font-weight: 800;
        }
        .permission-management__heading p,
        .permission-management__toolbar p {
            margin: 0;
            color: var(--permission-muted);
        }
        .permission-management__heading-icon {
            display: grid;
            width: 3.25rem;
            height: 3.25rem;
            flex: 0 0 3.25rem;
            place-items: center;
            border-radius: 1rem;
            background: #d9f2ef;
            color: #0d7a8a;
            font-size: 1.3rem;
        }
        .permission-management__card {
            overflow: hidden;
            border: 1px solid var(--permission-border);
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 .8rem 2rem rgba(24, 52, 74, .06);
        }
        .permission-management__toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--permission-border);
        }
        .permission-management__toolbar h2 {
            margin: 0 0 .25rem;
            font-size: 1.05rem;
            font-weight: 800;
        }
        .permission-management__toolbar p {
            font-size: .84rem;
        }
        .permission-management__toolbar-actions {
            display: flex;
            align-items: center;
            gap: .65rem;
        }
        .permission-management__search {
            position: relative;
            display: flex;
            align-items: center;
        }
        .permission-management__search > i {
            position: absolute;
            left: .8rem;
            color: #8493a3;
            font-size: .85rem;
        }
        .permission-management__search input {
            width: min(15rem, 35vw);
            min-height: 2.65rem;
            padding: .55rem 2.2rem .55rem 2.25rem;
            border: 1px solid #cbd7e2;
            border-radius: .65rem;
            color: var(--permission-ink);
        }
        .permission-management__clear {
            position: absolute;
            right: .7rem;
            color: var(--permission-muted);
        }
        .permission-management__add {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            min-height: 2.65rem;
            padding: .55rem .9rem;
            border: 0;
            border-radius: .65rem;
            background: linear-gradient(135deg, #0d7a8a, #145da0);
            color: #fff;
            font-weight: 750;
        }
        .permission-management__add:hover {
            color: #fff;
            filter: brightness(.96);
        }
        .permission-management__table {
            margin: 0;
        }
        .permission-management__table thead th {
            padding: .8rem 1.25rem;
            border-top: 0;
            border-bottom: 1px solid var(--permission-border);
            background: #f8fafc;
            color: #64748b;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .permission-management__table tbody td {
            padding: .9rem 1.25rem;
            border-color: #edf1f5;
            vertical-align: middle;
        }
        .permission-management__name {
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .permission-management__row-icon {
            display: grid;
            width: 2.25rem;
            height: 2.25rem;
            flex: 0 0 2.25rem;
            place-items: center;
            border-radius: .65rem;
            background: #eef5ff;
            color: #356f9f;
        }
        .permission-management__name strong {
            display: block;
            color: #263b4d;
            font-size: .9rem;
        }
        .permission-management__name small {
            color: #8795a4;
            font-size: .75rem;
        }
        .permission-management__role-count {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .35rem .6rem;
            border-radius: 2rem;
            background: #f1f5f9;
            color: #526579;
            font-size: .78rem;
            font-weight: 650;
            white-space: nowrap;
        }
        .permission-management__actions {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            gap: .4rem;
        }
        .permission-management__actions form {
            margin: 0;
        }
        .permission-management__action {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .4rem .55rem;
            border: 0;
            border-radius: .5rem;
            background: transparent;
            font-size: .78rem;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }
        .permission-management__action--view { color: #356f9f; }
        .permission-management__action--edit { color: #0d7a8a; }
        .permission-management__action--delete { color: #b42318; }
        .permission-management__action:hover { background: #f1f5f9; }
        .permission-management__empty {
            padding: 3rem 1rem;
            text-align: center;
        }
        .permission-management__empty > span {
            display: grid;
            width: 3rem;
            height: 3rem;
            margin: 0 auto .8rem;
            place-items: center;
            border-radius: 1rem;
            background: #eef5ff;
            color: #356f9f;
            font-size: 1.1rem;
        }
        .permission-management__empty strong { color: #263b4d; }
        .permission-management__empty p {
            margin: .3rem 0 .6rem;
            color: var(--permission-muted);
        }
        .permission-management__pagination {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--permission-border);
        }
        @media (max-width: 767.98px) {
            .permission-management__heading,
            .permission-management__toolbar {
                padding: 1.1rem;
            }
            .permission-management__toolbar,
            .permission-management__toolbar-actions {
                align-items: stretch;
                flex-direction: column;
            }
            .permission-management__search input { width: 100%; }
            .permission-management__add { justify-content: center; }
            .permission-management__table thead th,
            .permission-management__table tbody td {
                padding-right: .8rem;
                padding-left: .8rem;
            }
            .permission-management__actions span { display: none; }
            .permission-management__action { padding: .45rem; }
        }
    </style>
@endsection
