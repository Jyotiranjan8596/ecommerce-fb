<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{
    public function index()
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->id;
        abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::query()->with('permissions')->withCount('users')->orderBy('name')->paginate(15);
        $roles->getCollection()->each(function (Role $role) {
            $role->setAttribute('assigned_users_count', User::query()
                ->where('role', (string) $role->id)
                ->orWhereHas('roles', fn ($query) => $query->where('roles.id', $role->id))
                ->count());
        });

        return view('admin.roles.index', compact('roles', 'userId', 'user_profile'));
    }

    public function create()
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->id;
        abort_if(Gate::denies('role_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::query()->where('guard_name', 'web')->orderBy('name')->get();

        return view('admin.roles.create', compact('permissions', 'userId', 'user_profile'));
    }

    public function store(StoreRoleRequest $request)
    {
        DB::transaction(function () use ($request) {
            $role = Role::create(['name' => $request->validated('name'), 'guard_name' => 'web']);
            $role->permissions()->sync($request->validated('permissions', []));
        });

        flash()->addSuccess('Role saved successfully.');

        return redirect()->route('admin.roles.index');
    }

    public function edit(Role $role)
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->id;
        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::query()->where('guard_name', 'web')->orderBy('name')->get();

        $role->load('permissions');

        return view('admin.roles.edit', compact('permissions', 'role', 'userId', 'user_profile'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        DB::transaction(function () use ($request, $role) {
            $role->update(['name' => $request->validated('name')]);
            $role->permissions()->sync($request->validated('permissions', []));
        });
        flash()->addSuccess('Role updated successfully.');

        return redirect()->route('admin.roles.index');
    }

    public function show(Role $role)
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->id;
        abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->load('permissions')->loadCount('users');
        $role->setAttribute('assigned_users_count', User::query()
            ->where('role', (string) $role->id)
            ->orWhereHas('roles', fn ($query) => $query->where('roles.id', $role->id))
            ->count());
        $assignedUsers = User::query()
            ->where(function ($query) use ($role) {
                $query->where('role', (string) $role->id)
                    ->orWhereHas('roles', fn ($roleQuery) => $roleQuery->where('roles.id', $role->id));
            })
            ->select(['id', 'user_id', 'name', 'email', 'mobilenumber'])
            ->orderBy('name')
            ->paginate(15, ['*'], 'users_page')
            ->withQueryString();

        return view('admin.roles.show', compact('role', 'assignedUsers', 'userId', 'user_profile'));
    }

    public function destroy(Role $role): RedirectResponse
    {
        abort_if(Gate::denies('role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (User::query()->where('role', (string) $role->id)
            ->orWhereHas('roles', fn ($query) => $query->where('roles.id', $role->id))
            ->exists()) {
            return back()->with('error', 'This role is assigned to users and cannot be deleted.');
        }

        $role->delete();
        flash()->addSuccess('Role deleted successfully.');
        return redirect()->route('admin.roles.index');
    }
}
