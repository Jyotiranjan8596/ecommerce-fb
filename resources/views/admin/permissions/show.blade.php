@extends('layouts.master')

@section('content')
    <div class="permission-detail">
        <section class="permission-detail__heading">
            <div>
                <span class="permission-detail__eyebrow">Access control</span>
                <h1>Permission details</h1>
                <p>Review where this capability is currently assigned.</p>
            </div>
            <span class="permission-detail__heading-icon" aria-hidden="true"><i class="fas fa-shield-alt"></i></span>
        </section>

        <section class="card permission-detail__card">
            <div class="permission-detail__content">
                <span class="permission-detail__key-icon" aria-hidden="true"><i class="fas fa-key"></i></span>
                <div>
                    <span class="permission-detail__label">Permission name</span>
                    <h2>{{ $permission->name }}</h2>
                    <p>Permission ID {{ $permission->id }} · Guard {{ $permission->guard_name }}</p>
                </div>
            </div>

            <div class="permission-detail__roles">
                <div>
                    <h3>Assigned roles</h3>
                    <p>{{ $permission->roles_count }} {{ \Illuminate\Support\Str::plural('role', $permission->roles_count) }} can use this permission.</p>
                </div>
                @if ($permission->roles->isNotEmpty())
                    <div class="permission-detail__role-list">
                        @foreach ($permission->roles as $role)
                            <span><i class="fas fa-user-tag" aria-hidden="true"></i>{{ $role->name }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="permission-detail__unused">This permission is not assigned to any roles yet.</p>
                @endif
            </div>

            <div class="permission-detail__actions">
                <a href="{{ route('admin.permissions.index') }}" class="btn permission-detail__back">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i> Back to permissions
                </a>
                @can('permission_edit')
                    <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn permission-detail__edit">
                        <i class="fas fa-pen" aria-hidden="true"></i> Edit permission
                    </a>
                @endcan
            </div>
        </section>
    </div>

    <style>
        .permission-detail {
            --permission-detail-ink: #172b3a;
            --permission-detail-muted: #64748b;
            --permission-detail-border: #e2eaf1;
            padding: .5rem 0;
            color: var(--permission-detail-ink);
        }
        .permission-detail__heading {
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
        .permission-detail__eyebrow {
            color: #0d7a8a;
            font-size: .7rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
        }
        .permission-detail__heading h1 {
            margin: .25rem 0;
            font-size: clamp(1.4rem, 3vw, 1.8rem);
            font-weight: 800;
        }
        .permission-detail__heading p,
        .permission-detail__content p,
        .permission-detail__roles p { margin: 0; color: var(--permission-detail-muted); }
        .permission-detail__heading-icon,
        .permission-detail__key-icon {
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
        .permission-detail__card {
            overflow: hidden;
            border: 1px solid var(--permission-detail-border);
            border-radius: 1rem;
            box-shadow: 0 .8rem 2rem rgba(24, 52, 74, .06);
        }
        .permission-detail__content {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
        }
        .permission-detail__key-icon { background: #eef5ff; color: #356f9f; }
        .permission-detail__label {
            color: var(--permission-detail-muted);
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .permission-detail__content h2 { margin: .2rem 0; font-size: 1.2rem; font-weight: 800; }
        .permission-detail__roles {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid var(--permission-detail-border);
            background: #fbfdff;
        }
        .permission-detail__roles h3 { margin: 0 0 .25rem; font-size: .98rem; font-weight: 800; }
        .permission-detail__roles p { font-size: .84rem; }
        .permission-detail__role-list { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: .9rem; }
        .permission-detail__role-list span {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .4rem .65rem;
            border-radius: 2rem;
            background: #eaf3fb;
            color: #315b7e;
            font-size: .8rem;
            font-weight: 650;
        }
        .permission-detail__unused { margin-top: .8rem !important; }
        .permission-detail__actions {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.1rem 1.5rem;
            border-top: 1px solid var(--permission-detail-border);
        }
        .permission-detail__actions .btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            min-height: 2.7rem;
            padding: .6rem .9rem;
            border-radius: .65rem;
            font-weight: 700;
        }
        .permission-detail__back { border: 1px solid #d4dee8; color: #526579; }
        .permission-detail__edit { border: 0; background: linear-gradient(135deg, #0d7a8a, #145da0); color: #fff; }
        .permission-detail__edit:hover { color: #fff; filter: brightness(.96); }
        @media (max-width: 575.98px) {
            .permission-detail__heading,
            .permission-detail__content,
            .permission-detail__roles,
            .permission-detail__actions { padding: 1.1rem; }
        }
    </style>
@endsection
