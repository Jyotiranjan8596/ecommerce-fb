@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Reset Password via OTP
                </div>

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('password.reset.otp') }}">
                        @csrf

                        {{-- Phone --}}
                        <div class="mb-3">
                            <label>Phone Number</label>
                            <input type="tel"
                                   class="form-control @error('mobilenumber') is-invalid @enderror"
                                   name="mobilenumber"
                                   placeholder="Enter Number"
                                   value="{{ session('mobilenumber') ?? old('mobilenumber') }}"
                                   {{ session('otp_sent') ? 'readonly' : '' }}
                                   required>

                            @error('mobilenumber')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        @if(session('otp_sent'))
                            {{-- OTP --}}
                            <div class="mb-3">
                                <label>OTP</label>
                                <input type="text"
                                       class="form-control @error('otp') is-invalid @enderror"
                                       name="otp"
                                       inputmode="numeric"
                                       required>

                                @error('otp')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- New Password --}}
                            <div class="mb-3">
                                <label>New Password</label>
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       name="password"
                                       required>
                            </div>

                            {{-- Confirm Password --}}
                            <div class="mb-3">
                                <label>Confirm Password</label>
                                <input type="password"
                                       class="form-control"
                                       name="password_confirmation"
                                       required>
                            </div>
                        @endif

                        <button class="btn btn-primary w-100">
                            {{ session('otp_sent') ? 'Verify OTP & Reset Password' : 'Send OTP' }}
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
