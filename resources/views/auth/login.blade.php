@extends('layouts.app')

@section('content')
    <style>
        .card-header {
            text-align: center;
            font-size: 25px;
            background-color: orange;
        }

        @media (max-width: 576px) {
            .card-header {
                font-size: 20px;
            }

            .col-form-label {
                font-size: 14px;
            }
        }
    </style>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5"> 
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3 row">
                                <label for="mobile_or_user_id" class="col-md-4 col-form-label text-md-end">
                                    {{ __('Mobile / User ID / Email') }}
                                </label>

                                <div class="col-md-6">
                                    <input id="mobile_or_user_id" type="text"
                                           class="form-control @error('mobile_or_user_id') is-invalid @enderror"
                                           name="mobile_or_user_id" value="{{ old('mobile_or_user_id') }}"
                                           autocomplete="mobile_or_user_id" autofocus>

                                    @error('mobile_or_user_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="password" class="col-md-4 col-form-label text-md-end">
                                    {{ __('Password') }}
                                </label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- <div class="mb-3 row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                               {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="mb-0 row">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-warning">
                                        {{ __('Login') }}
                                    </button>

                                    {{-- @if (Route::has('password.request')) --}}
                                        <a class="btn btn-link" href="{{ route('register') }}" style="text-decoration: none;color:red;">
                                            {{ __('Not registered? Create an account') }}
                                        </a>
                                    {{-- @endif --}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
