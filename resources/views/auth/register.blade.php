@extends('layouts.app')
@section('title', 'Register')
@section('content')
    <div class="registration-page">
        <section class="registration-card" aria-labelledby="registration-title">
            <header class="registration-header">
                <span class="registration-eyebrow">Welcome to Freebazar</span>
                <h1 id="registration-title">Create your account</h1>
                <p>Join us for a better everyday shopping experience.</p>
            </header>

            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="registration-form">
                @csrf

                @if (request()->filled('ref'))
                    <div class="registration-referral" role="status">
                        <span class="registration-referral-icon" aria-hidden="true">✓</span>
                        <span>You were invited with sponsor ID <strong>{{ request('ref') }}</strong>.</span>
                    </div>
                @endif

                <div class="registration-grid">
                    <div class="registration-field">
                        <label for="name">Full name <span>*</span></label>
                        <input type="text" name="name" id="name" minlength="2" maxlength="30"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" autocomplete="name" required>
                        @error('name')
                            <span class="registration-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="registration-field">
                        <label for="mobile">Mobile number <span>*</span></label>
                        <input type="tel" name="mobile" id="mobile" minlength="10" maxlength="15"
                            class="form-control @error('mobile') is-invalid @enderror"
                            value="{{ old('mobile') }}" autocomplete="tel" inputmode="numeric" required>
                        @error('mobile')
                            <span class="registration-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="registration-field">
                        <label for="sponsor_ref">Sponsor ID <span class="registration-optional">Optional</span></label>
                        <input type="text" name="sponsor_ref" id="sponsor_ref" maxlength="20"
                            class="form-control @error('sponsor_ref') is-invalid @enderror"
                            value="{{ old('sponsor_ref', request('ref')) }}" inputmode="numeric" autocomplete="off">
                        @error('sponsor_ref')
                            <span class="registration-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="registration-field">
                        <label for="email">Email address <span>*</span></label>
                        <input type="email" name="email" id="email" maxlength="100"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" autocomplete="email" required>
                        @error('email')
                            <span class="registration-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="registration-field">
                        <label for="password">Password <span>*</span></label>
                        <input type="password" name="password" id="password" minlength="8"
                            class="form-control @error('password') is-invalid @enderror"
                            autocomplete="new-password" required>
                        @error('password')
                            <span class="registration-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="registration-field">
                        <label for="password_confirmation">Confirm password <span>*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" minlength="8"
                            class="form-control" autocomplete="new-password" required>
                    </div>

                    <div class="registration-field">
                        <label for="gender">Gender <span>*</span></label>
                        <select name="gender" id="gender"
                            class="form-select @error('gender') is-invalid @enderror" required>
                            <option value="">Choose an option</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <span class="registration-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="registration-field registration-field-full">
                        <label for="address">Street address <span>*</span></label>
                        <input type="text" name="address" id="address" maxlength="255"
                            class="form-control @error('address') is-invalid @enderror"
                            value="{{ old('address') }}" autocomplete="street-address" required>
                        @error('address')
                            <span class="registration-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="registration-field">
                        <label for="state">State <span>*</span></label>
                        <input type="text" name="state" id="state" maxlength="50"
                            class="form-control @error('state') is-invalid @enderror"
                            value="{{ old('state') }}" autocomplete="address-level1" required>
                        @error('state')
                            <span class="registration-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="registration-field">
                        <label for="city">City <span>*</span></label>
                        <input type="text" name="city" id="city" maxlength="50"
                            class="form-control @error('city') is-invalid @enderror"
                            value="{{ old('city') }}" autocomplete="address-level2" required>
                        @error('city')
                            <span class="registration-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="registration-field">
                        <label for="post">Post / ZIP code <span>*</span></label>
                        <input type="text" name="post" id="post" maxlength="20"
                            class="form-control @error('post') is-invalid @enderror"
                            value="{{ old('post') }}" autocomplete="postal-code" required>
                        @error('post')
                            <span class="registration-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="registration-field">
                        <label for="image">Profile image <span class="registration-optional">Optional</span></label>
                        <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/gif,image/webp"
                            class="form-control @error('image') is-invalid @enderror">
                        @error('image')
                            <span class="registration-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="registration-terms">
                    <input type="checkbox" name="terms" id="supportCheckbox"
                        class="@error('terms') is-invalid @enderror" required>
                    <label for="supportCheckbox">
                        I agree to the <a href="{{ route('frontend.termCondition') }}">Terms &amp; Conditions</a>
                    </label>
                    @error('terms')
                        <span class="registration-error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="registration-submit">Create account</button>
                <p class="registration-signin">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
            </form>
        </section>
    </div>

    <style>
        .registration-page {
            --registration-ink: #172033;
            --registration-muted: #526174;
            --registration-primary: #0759a6;
            min-height: calc(100vh - 2rem);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            background: linear-gradient(145deg, #eef5fb 0%, #f8fafc 50%, #e8f1fa 100%);
            color: var(--registration-ink);
        }

        .registration-card {
            width: min(100%, 760px);
            overflow: hidden;
            border: 1px solid #d7e1ec;
            border-radius: 1.25rem;
            background: #fff;
            box-shadow: 0 1.25rem 3.5rem rgba(23, 32, 51, .12);
        }

        .registration-header {
            padding: clamp(1.5rem, 5vw, 2.5rem);
            background: #172033;
            color: #fff;
        }

        .registration-eyebrow {
            display: block;
            margin-bottom: .45rem;
            color: #c9ddf3;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .registration-header h1 {
            margin: 0;
            color: #fff !important;
            font-size: clamp(1.65rem, 5vw, 2.15rem);
            font-weight: 750;
            letter-spacing: -.03em;
        }

        .registration-header p {
            margin: .5rem 0 0;
            color: #e1eaf4;
            font-size: .98rem;
        }

        .registration-form {
            padding: clamp(1.25rem, 4vw, 2.25rem);
        }

        .registration-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.15rem 1.25rem;
        }

        .registration-field {
            min-width: 0;
        }

        .registration-field-full {
            grid-column: 1 / -1;
        }

        .registration-field label {
            display: block;
            margin-bottom: .4rem;
            color: #263449;
            font-size: .9rem;
            font-weight: 650;
        }

        .registration-field label span:not(.registration-optional) {
            color: #b42318;
        }

        .registration-optional {
            margin-left: .3rem;
            color: #526174;
            font-size: .78rem;
            font-weight: 500;
        }

        .registration-field .form-control,
        .registration-field .form-select {
            min-height: 3rem;
            border-color: #bac7d5;
            border-radius: .65rem;
            color: #172033;
            font-size: 1rem;
            text-align: left;
        }

        .registration-field .form-control:focus,
        .registration-field .form-select:focus {
            border-color: #0759a6;
            box-shadow: 0 0 0 .2rem rgba(7, 89, 166, .16);
        }

        .registration-field input[type="file"] {
            padding: .65rem .75rem;
        }

        .registration-error {
            display: block;
            margin-top: .35rem;
            color: #b42318;
            font-size: .84rem;
            font-weight: 550;
        }

        .registration-referral {
            display: flex;
            align-items: center;
            gap: .7rem;
            margin-bottom: 1.4rem;
            padding: .9rem 1rem;
            border: 1px solid #a9d7bd;
            border-radius: .75rem;
            background: #edf8f1;
            color: #174c2e;
            font-size: .94rem;
        }

        .registration-referral-icon {
            display: grid;
            width: 1.5rem;
            height: 1.5rem;
            flex: 0 0 1.5rem;
            place-items: center;
            border-radius: 50%;
            background: #176b3a;
            color: #fff;
            font-weight: 700;
        }

        .registration-terms {
            display: grid;
            grid-template-columns: auto 1fr;
            align-items: start;
            column-gap: .65rem;
            margin-top: 1.5rem;
            color: #344256;
            font-size: .92rem;
        }

        .registration-terms input {
            width: 1.05rem;
            height: 1.05rem;
            margin-top: .2rem;
            accent-color: var(--registration-primary);
        }

        .registration-terms a,
        .registration-signin a {
            color: #064f91;
            font-weight: 650;
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        .registration-terms .registration-error {
            grid-column: 2;
        }

        .registration-submit {
            width: 100%;
            min-height: 3.15rem;
            margin-top: 1.5rem;
            border: 0;
            border-radius: .7rem;
            background: #0759a6;
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            transition: background-color .15s ease, transform .15s ease;
        }

        .registration-submit:hover {
            transform: translateY(-1px);
            background: #064780;
        }

        .registration-signin {
            margin: 1rem 0 0;
            color: #526174;
            text-align: center;
            font-size: .92rem;
        }

        @media (max-width: 575.98px) {
            .registration-page {
                min-height: auto;
                padding: .75rem;
            }

            .registration-card {
                border-radius: .9rem;
            }

            .registration-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
    </style>
@endsection
