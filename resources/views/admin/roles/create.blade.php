@extends('layouts.master')

@section('content')
    <div class="container-fluid py-3 admin-workflow-page">
        <section class="admin-workflow-hero">
            <div>
                <span class="admin-workflow-hero__eyebrow">Access control</span>
                <h1>Create role</h1>
                <p>Choose a role name and the permissions users assigned to it should receive.</p>
            </div>
            <a class="btn btn-light" href="{{ route('admin.roles.index') }}">
                <i class="bi bi-arrow-left me-1"></i> Back to roles
            </a>
        </section>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please correct the following:</strong>
                <ul class="mb-0 mt-2">@foreach ($errors->all() as $message)<li>{{ $message }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.roles.store') }}" method="POST" class="card admin-workflow-card">
            @csrf
            <div class="card-body p-4">
                <div class="mb-4">
                    <label for="name" class="form-label">Role name <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" maxlength="255" required
                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div>
                        <h2 class="h5 mb-1">Permissions</h2>
                        <p class="text-muted small mb-0">A role may be saved without permissions.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="select-all-permissions">Select all</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="clear-permissions">Clear</button>
                    </div>
                </div>
                <div class="row g-2">
                    @forelse ($permissions as $permission)
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-check border rounded p-3 h-100 d-flex align-items-start gap-2 role-permission-option">
                                <input class="form-check-input permission-checkbox mt-1" type="checkbox"
                                    name="permissions[]" value="{{ $permission->id }}"
                                    @checked(in_array($permission->id, old('permissions', [])))>
                                <span class="form-check-label">{{ $permission->name }}</span>
                            </label>
                        </div>
                    @empty
                        <div class="col-12"><div class="alert alert-info mb-0">No permissions are available to assign.</div></div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer bg-white p-3 d-flex justify-content-end gap-2">
                <a class="btn btn-outline-secondary" href="{{ route('admin.roles.index') }}">Cancel</a>
                <button class="btn btn-primary" type="submit"><i class="bi bi-shield-check me-1"></i> Create role</button>
            </div>
        </form>
        <script>
            document.getElementById('select-all-permissions')?.addEventListener('click', () => {
                document.querySelectorAll('.permission-checkbox').forEach((checkbox) => checkbox.checked = true);
            });
            document.getElementById('clear-permissions')?.addEventListener('click', () => {
                document.querySelectorAll('.permission-checkbox').forEach((checkbox) => checkbox.checked = false);
            });
        </script>
    </div>
@endsection
