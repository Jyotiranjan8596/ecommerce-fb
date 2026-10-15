@extends('pos.layouts.master')

@section('content')
    <div class="pos-security-page">
        <section class="pos-profile-hero pos-security-hero">
            <div class="pos-profile-hero__content">
                <span class="pos-profile-hero__eyebrow">Account security</span>
                <h1>Change password</h1>
                <p>Choose a new password to keep your POS account secure.</p>
            </div>
            <div class="pos-profile-hero__identity">
                @if (auth()->user()->image)
                    <img src="{{ asset('images/' . auth()->user()->image) }}" alt="{{ auth()->user()->name }}">
                @else
                    <span aria-hidden="true">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                @endif
                <div>
                    <strong>{{ auth()->user()->name }}</strong>
                    <small>Signed in</small>
                </div>
            </div>
        </section>

        <section class="pos-profile-card pos-security-card">
            @if (session('success'))
                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
            @endif

            <header class="pos-profile-card__header">
                <span class="pos-profile-card__icon pos-profile-card__icon--blue" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <rect x="4" y="10" width="16" height="11" rx="2"
                            stroke="currentColor" stroke-width="1.8" />
                        <path d="M8 10V7a4 4 0 0 1 8 0v3m-4 5v2" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </span>
                <div>
                    <h2>Password details</h2>
                    <p>Enter your current password, then choose a new one with at least 8 characters.</p>
                </div>
            </header>

            <form action="{{ route('pos.new.password') }}" method="POST" autocomplete="off">
                @csrf
                <div class="pos-password-fields">
                    <div class="pos-profile-field">
                        <label for="current_password">Current password</label>
                        <input type="password" id="current_password" name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            autocomplete="current-password" required>
                        @error('current_password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="pos-profile-field">
                        <label for="password">New password</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            autocomplete="new-password" required>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="pos-profile-field">
                        <label for="password_confirmation">Confirm new password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control" autocomplete="new-password" required>
                    </div>
                </div>

                @if ($errors->has('password') || $errors->has('current_password'))
                    <div class="alert alert-danger mt-3" role="alert">
                        Please review the password fields and try again.
                    </div>
                @endif

                <div class="pos-profile-actions mt-4">
                    <a class="btn btn-outline-secondary" href="{{ route('pos.profile.index') }}">Back to profile</a>
                    <button class="btn btn-primary" type="submit">Update password</button>
                </div>
            </form>
        </section>
    </div>
@endsection
