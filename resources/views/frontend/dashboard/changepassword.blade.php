@extends('frontend.dashboard.layouts.master')

@section('content')
    <div class="enterprise-dashboard-shell user-security-page">
        <section class="dashboard-hero user-security-hero">
            <div class="user-security-hero__copy">
                <span class="dashboard-hero__label">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 3 5 6v5c0 4.5 2.9 8.2 7 10 4.1-1.8 7-5.5 7-10V6l-7-3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                        <path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Account security
                </span>
                <h1 class="dashboard-hero__title">Change password</h1>
                <p class="dashboard-hero__text">Choose a strong password to keep your account secure.</p>
            </div>

            <div class="user-security-identity">
                @if (auth()->user()->image)
                    <img src="{{ asset('images/' . auth()->user()->image) }}"
                        alt="Profile photo of {{ auth()->user()->name }}"
                        onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
                    <span class="user-security-identity__fallback" aria-hidden="true" hidden>
                        {{ strtoupper(substr(auth()->user()->name ?? '?', 0, 1)) }}
                    </span>
                @else
                    <span class="user-security-identity__fallback" aria-hidden="true">
                        {{ strtoupper(substr(auth()->user()->name ?? '?', 0, 1)) }}
                    </span>
                @endif
                <div>
                    <strong>{{ auth()->user()->name }}</strong>
                    <small>Customer account</small>
                </div>
            </div>
        </section>

        <section class="user-security-card" aria-labelledby="user-security-card-title">
            <header class="user-security-card__header">
                <span class="user-security-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <rect x="4" y="10" width="16" height="11" rx="2" stroke="currentColor" stroke-width="1.8" />
                        <path d="M8 10V7a4 4 0 0 1 8 0v3m-4 5v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </span>
                <div>
                    <h2 id="user-security-card-title">Create a new password</h2>
                    <p>Use at least 6 characters, then enter it again to confirm.</p>
                </div>
            </header>

            @if ($errors->any())
                <div class="alert alert-danger user-security-alert" role="alert">
                    Please review the password fields and try again.
                </div>
            @endif

            <form action="{{ route('user.new.password') }}" method="POST" class="user-security-form" autocomplete="off">
                @csrf
                <div class="user-security-fields">
                    <div class="user-security-field">
                        <label for="password">New password</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            autocomplete="new-password" minlength="6" required>
                        @error('password')
                            <span class="text-danger small mt-1" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="user-security-field">
                        <label for="password_confirmation">Confirm new password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            autocomplete="new-password" minlength="6" required>
                        @error('password_confirmation')
                            <span class="text-danger small mt-1" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="user-security-form__actions">
                    <a class="btn btn-outline-secondary" href="{{ route('user.index') }}">Back to dashboard</a>
                    <button class="btn btn-primary" type="submit">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="m5 12 4 4L19 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Update password
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection
