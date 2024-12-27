@extends('pos.layouts.master')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h4><b>Change Password</b></h4>
        </div>
        <form action="{{ route('pos.new.password') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="password">New Password*</label>
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>                                                               
                        <div class="mb-2">
                            <label for="password_confirmation">Repeat Password*</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror">
                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                                                                                  
                    </div>
                  
                </div>
            </div>

            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">CHANGE PASSWORD</button>               
                <a class="btn btn-secondary" href="{{ route('pos.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection