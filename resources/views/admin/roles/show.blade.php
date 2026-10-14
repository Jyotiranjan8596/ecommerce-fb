@extends('layouts.master')

@section('content')
    <div class="container-fluid py-3 admin-workflow-page">
        <section class="admin-workflow-hero">
            <div>
                <span class="admin-workflow-hero__eyebrow">Access control</span>
                <h1>Role details</h1>
                <p>Review role membership and assigned permissions.</p>
            </div>
            <div class="d-flex gap-2">
                @can('role_edit')
                    <a class="btn btn-primary" href="{{ route('admin.roles.edit', $role) }}">
                        <i class="bi bi-pencil me-1"></i> Edit role
                    </a>
                @endcan
                <a class="btn btn-outline-secondary" href="{{ route('admin.roles.index') }}">Back to roles</a>
            </div>
        </section>
        <div class="card admin-workflow-card mb-4">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="text-muted small">Role name</div>
                        <div class="fs-5 fw-semibold">{{ $role->name }}</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="text-muted small">Users assigned</div>
                        <div class="fs-5 fw-semibold">{{ $role->assigned_users_count }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card admin-workflow-card mb-4">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h2 class="h5 mb-0">Users assigned to {{ $role->name }}</h2>
                    <p class="small text-muted mb-0 mt-1">
                        Accounts assigned directly or through role membership.
                    </p>
                </div>
                <span class="badge bg-primary-subtle text-primary-emphasis">
                    {{ $role->assigned_users_count }} {{ \Illuminate\Support\Str::plural('user', $role->assigned_users_count) }}
                </span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 admin-workflow-table">
                    <thead>
                        <tr>
                            <th class="ps-4">User</th>
                            <th>User ID</th>
                            <th>Email</th>
                            <th>Mobile</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assignedUsers as $assignedUser)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-semibold">{{ $assignedUser->name }}</span>
                                </td>
                                <td>{{ $assignedUser->user_id }}</td>
                                <td>{{ $assignedUser->email ?: '—' }}</td>
                                <td>{{ $assignedUser->mobilenumber ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="admin-workflow-empty">
                                        <i class="bi bi-people" aria-hidden="true"></i>
                                        No users are assigned to the {{ $role->name }} role.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($assignedUsers->hasPages())
                <div class="card-footer bg-white">
                    {{ $assignedUsers->links() }}
                </div>
            @endif
        </div>
        <div class="card admin-workflow-card">
            <div class="card-header bg-white py-3"><h2 class="h5 mb-0">Assigned permissions</h2></div>
            <div class="card-body">
                @forelse ($role->permissions as $permission)
                    <span class="badge bg-primary-subtle text-primary-emphasis me-1 mb-2">{{ $permission->name }}</span>
                @empty
                    <p class="text-muted mb-0">No permissions are assigned to this role.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
