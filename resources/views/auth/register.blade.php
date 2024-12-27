@extends('layouts.app')

@section('content')
    <style>
        .card-header {
            text-align: center;
            font-size: 1.5rem;
            background-color: orange;
        }
        
        @media (max-width: 576px) {
            .container {
                padding: 0 10px;
            }
        }
    </style>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card mt-4">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Name -->
                            <div class="mb-3 row">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
                                <div class="col-md-8">
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           name="name" value="{{ old('name') }}" required autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email Address -->
                            <div class="mb-3 row">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                                <div class="col-md-8">
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Gender -->
                            <div class="mb-3 row">
                                <label for="gender" class="col-md-4 col-form-label text-md-end">{{ __('Gender') }}</label>
                                <div class="col-md-8">
                                    <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('gender')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Mobile Number -->
                            <div class="mb-3 row">
                                <label for="mobilenumber" class="col-md-4 col-form-label text-md-end">{{ __('Mobile Number') }}</label>
                                <div class="col-md-8">
                                    <input id="mobilenumber" type="text"
                                           class="form-control @error('mobilenumber') is-invalid @enderror"
                                           name="mobilenumber" value="{{ old('mobilenumber') }}" required>
                                    @error('mobilenumber')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Sponsor ID (Optional) -->
                            <div class="mb-3 row">
                                <label for="sponsor_id" class="col-md-4 col-form-label text-md-end">{{ __('Sponsor ID') }}</label>
                                <div class="col-md-8">
                                    <input id="sponsor_id" type="text"
                                           class="form-control @error('sponsor_id') is-invalid @enderror"
                                           name="sponsor_id" value="{{ old('sponsor_id') }}">
                                    @error('sponsor_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Parent Level -->
                            <div class="mb-3 row">
                                <label for="parent_level" class="col-md-4 col-form-label text-md-end">{{ __('Parent for Level') }}</label>
                                <div class="col-md-8">
                                    <input id="parent_level" type="text"
                                           class="form-control @error('parent_level') is-invalid @enderror"
                                           name="parent_level" value="{{ old('parent_level') }}" required>
                                    @error('parent_level')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="mb-3 row">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                                <div class="col-md-8">
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           name="password" required>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-warning w-100">
                                        {{ __('Register') }}
                                    </button>
                                    <a class="btn btn-link" href="{{ route('login') }}" style="text-decoration: none;color:red;">
                                        {{ __('Already registered? Sign In') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
