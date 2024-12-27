@extends('frontend.dashboard.layouts.master')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h4><b>Edit Profile</b></h4>
        </div>
        <form action="{{ route('user.update.profile') }}" method="POST" enctype="multipart/form-data">
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
                            <label for="pan_no">PAN NO*</label>
                            <input type="text" id="pan_no" name="pan_no"
                                class="form-control @error('pan_no') is-invalid @enderror"
                                value="{{ old('pan_no', $user_profile->pan_no) }}" readonly
                                style="background-color: rgb(218, 211, 211);">
                            @error('pan_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="bank">BANK*</label>
                            <input type="text" id="bank" name="bank"
                                class="form-control @error('bank') is-invalid @enderror"
                                value="{{ old('bank', $user_profile->bank) }}" readonly
                                style="background-color: rgb(218, 211, 211);">
                            @error('bank')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="account_no">ACCOUNT NO*</label>
                            <input type="text" id="account_no" name="account_no"
                                class="form-control @error('account_no') is-invalid @enderror"
                                value="{{ old('account_no', $user_profile->account_no) }}" readonly
                                style="background-color: rgb(218, 211, 211);">
                            @error('account_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="ifsc">IFSC*</label>
                            <input type="text" id="ifsc" name="ifsc"
                                class="form-control @error('ifsc') is-invalid @enderror"
                                value="{{ old('ifsc', $user_profile->ifsc) }}" readonly
                                style="background-color: rgb(218, 211, 211);">
                            @error('ifsc')
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
                            <label for="image">Image*</label>
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

                        <div class="mb-2">
                            <label for="nominee_name">NOMINEE NAME*</label>
                            <input type="text" id="nominee_name" name="nominee_name"
                                class="form-control @error('nominee_name') is-invalid @enderror"
                                value="{{ old('nominee_name', $user_profile->nominee_name) }}">
                            @error('nominee_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="relation_user">RELATION WITH USER*</label>
                            <select id="relation_user" name="relation_user"
                                class="form-control @error('relation_user') is-invalid @enderror">
                                <option value="">Select</option>
                                {{-- @foreach ($states as $state)
                            <option value="{{ $state }}" {{ old('state') == $state ? 'selected' : '' }}>
                                {{ $state }}
                            </option>
                            @endforeach --}}
                            </select>
                            @error('relation_user')
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
