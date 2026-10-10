@extends('layouts.master')

@section('content')
    <div class="admin-password-page">
        <header class="admin-password-hero">
            <div class="admin-password-identity">
                <span class="admin-password-hero-icon" aria-hidden="true"><i class="fas fa-shield-alt"></i></span>
                <div>
                    <span class="admin-password-eyebrow">Account security</span>
                    <h1>Change password</h1>
                    <p>Choose a new password to keep your admin account secure.</p>
                </div>
            </div>
            <a class="btn btn-light admin-password-back" href="{{ route('admin.profile.index') }}">
                <i class="fas fa-arrow-left me-2" aria-hidden="true"></i>Back to profile
            </a>
        </header>

        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Please check the highlighted fields.</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.password.update') }}" method="POST" class="admin-password-card" id="admin-password-form">
            @csrf
            @method('PUT')

            <div class="admin-password-card-heading">
                <span class="admin-password-card-icon" aria-hidden="true"><i class="fas fa-key"></i></span>
                <div>
                    <h2>Password details</h2>
                    <p>Enter your current password, then choose and confirm a new one.</p>
                </div>
            </div>

            <div class="admin-password-fields">
                <div class="admin-password-field">
                    <label for="old_password">Current password <span>*</span></label>
                    <div class="admin-password-input-wrap">
                        <input type="password" id="old_password" name="old_password"
                            class="form-control @error('old_password') is-invalid @enderror"
                            autocomplete="current-password" required>
                        <button type="button" class="admin-password-toggle" data-password-target="old_password"
                            aria-label="Show current password" aria-pressed="false">
                            <i class="fas fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    @error('old_password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="admin-password-field">
                    <label for="new_password">New password <span>*</span></label>
                    <div class="admin-password-input-wrap">
                        <input type="password" id="new_password" name="new_password"
                            class="form-control @error('new_password') is-invalid @enderror"
                            autocomplete="new-password" minlength="8" required>
                        <button type="button" class="admin-password-toggle" data-password-target="new_password"
                            aria-label="Show new password" aria-pressed="false">
                            <i class="fas fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    <small>Use at least 8 characters.</small>
                </div>

                <div class="admin-password-field">
                    <label for="new_password_confirmation">Confirm new password <span>*</span></label>
                    <div class="admin-password-input-wrap">
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                            class="form-control @error('new_password') is-invalid @enderror"
                            autocomplete="new-password" minlength="8" required>
                        <button type="button" class="admin-password-toggle" data-password-target="new_password_confirmation"
                            aria-label="Show password confirmation" aria-pressed="false">
                            <i class="fas fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    @error('new_password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>

            <footer class="admin-password-actions">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-lock me-2" aria-hidden="true"></i>Update password
                </button>
            </footer>
        </form>
    </div>

    <style>
        .admin-password-page { display: grid; gap: 1.25rem; max-width: 1050px; margin: 0 auto; padding: 1rem 0 2rem; color: #172b4d; }
        .admin-password-hero { display: flex; align-items: center; justify-content: space-between; gap: 1.25rem; padding: clamp(1.3rem, 3vw, 2rem); border-radius: 24px; color: #fff; background: linear-gradient(120deg, #123b72, #1769aa 62%, #2387bf); box-shadow: 0 16px 34px rgba(18,59,114,.18); }
        .admin-password-identity { display: flex; min-width: 0; align-items: center; gap: .95rem; }
        .admin-password-hero-icon { display: grid; width: 4rem; height: 4rem; flex: 0 0 4rem; place-items: center; border: 1px solid rgba(255,255,255,.3); border-radius: 18px; background: rgba(255,255,255,.13); color: #fff; font-size: 1.4rem; }
        .admin-password-eyebrow { color: #c8e6ff; font-size: .72rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
        .admin-password-hero h1 { margin: .3rem 0 .35rem; color: #fff; font-size: clamp(1.55rem, 3vw, 2.1rem); font-weight: 800; }
        .admin-password-hero p { margin: 0; color: rgba(255,255,255,.82); }
        .admin-password-back { flex: 0 0 auto; min-height: 44px; padding: .65rem 1rem; border: 1px solid rgba(255,255,255,.85); border-radius: 10px; color: #183b65; font-weight: 750; box-shadow: 0 7px 16px rgba(8,19,45,.18); }
        .admin-password-back:hover { background: #eaf3ff; color: #102e54; }
        .admin-password-card { overflow: hidden; border: 1px solid #e5edf6; border-radius: 20px; background: #fff; box-shadow: 0 12px 32px rgba(24,54,91,.08); }
        .admin-password-card-heading { display: flex; align-items: center; gap: .85rem; padding: 1.25rem clamp(1.1rem, 2.5vw, 1.75rem); border-bottom: 1px solid #eaf0f6; }
        .admin-password-card-icon { display: grid; width: 2.75rem; height: 2.75rem; flex: 0 0 2.75rem; place-items: center; border-radius: 13px; background: #e8f3ff; color: #1769aa; }
        .admin-password-card-heading h2 { margin: 0; color: #183b65; font-size: 1.05rem; font-weight: 800; }
        .admin-password-card-heading p { margin: .2rem 0 0; color: #718198; font-size: .84rem; }
        .admin-password-fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.1rem 1.25rem; padding: clamp(1.1rem, 2.5vw, 1.75rem); }
        .admin-password-field { min-width: 0; }
        .admin-password-field:last-child { grid-column: 1 / -1; }
        .admin-password-field label { display: block; margin-bottom: .4rem; color: #465a74; font-size: .82rem; font-weight: 750; }
        .admin-password-field label span { color: #d14343; }
        .admin-password-input-wrap { position: relative; }
        .admin-password-input-wrap .form-control { min-height: 46px; padding-right: 3rem; border-color: #d8e2ee; border-radius: 10px; color: #243b58; }
        .admin-password-input-wrap .form-control:focus { border-color: #4784c2; box-shadow: 0 0 0 .2rem rgba(38,112,184,.14); }
        .admin-password-toggle { position: absolute; top: 50%; right: .5rem; display: grid; width: 2rem; height: 2rem; place-items: center; border: 0; border-radius: 8px; background: transparent; color: #718198; transform: translateY(-50%); }
        .admin-password-toggle:hover, .admin-password-toggle:focus-visible { background: #eff6ff; color: #1769aa; }
        .admin-password-toggle:focus-visible { outline: 2px solid #4784c2; }
        .admin-password-field small { display: block; margin-top: .35rem; color: #718198; }
        .admin-password-actions { display: flex; justify-content: flex-end; padding: 1rem clamp(1.1rem, 2.5vw, 1.75rem); border-top: 1px solid #eaf0f6; background: #f8fafc; }
        .admin-password-actions .btn { min-height: 44px; padding: .65rem 1rem; border-radius: 10px; font-weight: 750; box-shadow: 0 6px 14px rgba(21,82,153,.18); }
        @media (max-width: 640px) { .admin-password-page { padding: .5rem 0 1.5rem; } .admin-password-hero { align-items: stretch; flex-direction: column; } .admin-password-hero-icon { width: 3.25rem; height: 3.25rem; flex-basis: 3.25rem; font-size: 1.2rem; } .admin-password-back { width: 100%; } .admin-password-fields { grid-template-columns: 1fr; gap: .9rem; } .admin-password-field:last-child { grid-column: auto; } .admin-password-actions .btn { width: 100%; } }
    </style>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.admin-password-toggle').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = document.getElementById(button.dataset.passwordTarget);
                if (!input) return;
                const visible = input.type === 'password';
                input.type = visible ? 'text' : 'password';
                button.setAttribute('aria-pressed', visible ? 'true' : 'false');
                button.setAttribute('aria-label', (visible ? 'Hide ' : 'Show ') + button.dataset.passwordTarget.replaceAll('_', ' '));
                button.innerHTML = '<i class="fas fa-eye' + (visible ? '-slash' : '') + '" aria-hidden="true"></i>';
            });
        });
    });
</script>
