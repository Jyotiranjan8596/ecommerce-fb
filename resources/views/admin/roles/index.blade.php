@extends('layouts.master')

@section('content')
    <div class="container-fluid py-3 admin-workflow-page">
        <section class="admin-workflow-hero">
            <div>
                <span class="admin-workflow-hero__eyebrow">Access control</span>
                <h1>Roles</h1>
                <p>Manage user access, permission groups, and assigned accounts.</p>
            </div>
            @can('role_create')
                <a class="btn roles-create-button" href="{{ route('admin.roles.create') }}">
                    <i class="bi bi-plus-lg me-1"></i> Create role
                </a>
            @endcan
        </section>

        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif

        <div class="card admin-workflow-card">
            <div class="card-header roles-list-card-header">
                <div>
                    <h2 class="h5 mb-0">Role list</h2>
                    <p class="small text-muted mb-0 mt-1">Roles, access permissions, and account assignments.</p>
                </div>
                <span class="roles-total-count">{{ $roles->total() }} {{ \Illuminate\Support\Str::plural('role', $roles->total()) }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 admin-workflow-table roles-list-table">
                    <colgroup>
                        <col class="roles-column-role">
                        <col class="roles-column-permissions">
                        <col class="roles-column-users">
                        <col class="roles-column-actions">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="roles-role-heading">Role</th>
                            <th class="roles-permissions-column">Permissions</th>
                            <th class="roles-users-heading">Assigned users</th>
                            <th class="roles-actions-heading">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td class="roles-role-cell">
                                    <div class="roles-role-name">{{ $role->name }}</div>
                                    <small class="roles-role-id">Role ID {{ $role->id }}</small>
                                </td>
                                <td class="roles-permissions-column">
                                    <div class="roles-permission-list">
                                        @forelse ($role->permissions->take(4) as $permission)
                                            <span class="badge roles-permission-badge">{{ $permission->name }}</span>
                                        @empty
                                            <span class="roles-no-permissions">No permissions assigned</span>
                                        @endforelse
                                        @if ($role->permissions->count() > 4)
                                            <span class="badge roles-permission-count">+{{ $role->permissions->count() - 4 }} more</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="roles-users-cell">
                                    <span class="roles-user-count">
                                        <i class="bi bi-people-fill" aria-hidden="true"></i>
                                        {{ $role->assigned_users_count }}
                                        <span>{{ \Illuminate\Support\Str::plural('user', $role->assigned_users_count) }}</span>
                                    </span>
                                </td>
                                <td class="roles-actions-cell">
                                    <div class="roles-actions">
                                        @can('role_access')
                                            <a class="btn btn-sm roles-action-button roles-action-view"
                                                href="{{ route('admin.roles.show', $role) }}"
                                                aria-label="View {{ $role->name }} role" title="View role">
                                                <i class="bi bi-eye" aria-hidden="true"></i><span>View</span>
                                            </a>
                                        @endcan
                                        @can('role_edit')
                                            <a class="btn btn-sm roles-action-button roles-action-edit"
                                                href="{{ route('admin.roles.edit', $role) }}"
                                                aria-label="Edit {{ $role->name }} role" title="Edit role">
                                                <i class="bi bi-pencil" aria-hidden="true"></i><span>Edit</span>
                                            </a>
                                        @endcan
                                        @can('role_delete')
                                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}"
                                                onsubmit="return confirm('Delete this role?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm roles-action-button roles-action-delete" type="submit"
                                                    @disabled($role->assigned_users_count > 0)
                                                    aria-label="Delete {{ $role->name }} role"
                                                    title="{{ $role->assigned_users_count > 0 ? 'Role is assigned to users' : 'Delete role' }}">
                                                    <i class="bi bi-trash3" aria-hidden="true"></i><span>Delete</span>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4"><div class="admin-workflow-empty">
                                <i class="bi bi-shield-lock" aria-hidden="true"></i>
                                No roles have been created.
                            </div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($roles->hasPages())
                <div class="card-footer bg-white">{{ $roles->links() }}</div>
            @endif
        </div>
    </div>
@endsection
