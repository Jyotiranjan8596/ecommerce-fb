@extends('pos.layouts.master')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h4><b>My Profile</b></h4>
        </div>
        <form action="{{ route('pos.update.profile') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="name">Name*</label>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user_profile->name) }}" readonly
                                style="background-color: rgb(218, 211, 211);">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="mobilenumber">Phone*</label>
                            <input type="number" id="mobilenumber" name="mobilenumber"
                                class="form-control @error('mobilenumber') is-invalid @enderror"
                                value="{{ old('mobilenumber', $user_profile->mobilenumber) }}" readonly
                                style="background-color: rgb(218, 211, 211);">
                            @error('mobilenumber')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="state">State*</label>
                            <input type="text" id="state" name="state"
                                class="form-control @error('state') is-invalid @enderror"
                                value="{{ old('state', $user_profile->state) }}">
                            @error('state')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="upi_id">UPI Id*</label>
                            <input type="upi_id" id="upi_id" name="upi_id"
                                class="form-control @error('upi_id') is-invalid @enderror"
                                value="{{ old('upi_id', $user_profile->upi_id) }}">
                            @error('city')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="email">Email*</label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user_profile->email) }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="image">Profile Image*</label>
                            <input type="file" id="image" name="image"
                                class="form-control @error('image') is-invalid @enderror">
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @if ($user_profile->image)
                                <img src="{{ asset('images/' . $user_profile->image) }}" alt="Profile Image"
                                    width="40">
                            @endif
                        </div>
                        <div class="mb-2">
                            <label for="address">Address*</label>
                            <input type="text" id="address" name="address"
                                class="form-control @error('address') is-invalid @enderror"
                                value="{{ old('address', $user_profile->address) }}">
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="city">City*</label>
                            <input type="city" id="city" name="city"
                                class="form-control @error('city') is-invalid @enderror"
                                value="{{ old('city', $user_profile->city) }}">
                            @error('city')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="zip">Zip*</label>
                            <input type="text" id="zip" name="zip"
                                class="form-control @error('zip') is-invalid @enderror"
                                value="{{ old('zip', $user_profile->zip) }}">
                            @error('zip')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">UPDATE</button>
                {{-- <button class="btn btn-warning me-2" type="submit">Validate</button> --}}
                <a class="btn btn-secondary" href="{{ route('user.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection
