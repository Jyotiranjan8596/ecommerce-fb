@extends('frontend.dashboard.layouts.master')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h4><b>Add User</b></h4>
        </div>
        <form action="{{ route('user.add.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="name">Name*</label>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <input type="hidden" name="password">
                        <div class="mb-2">
                            <label for="email">Email*</label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="mobilenumber">Phone*</label>
                            <input type="number" id="mobilenumber" name="mobilenumber"
                                class="form-control @error('mobilenumber') is-invalid @enderror" required>
                            @error('mobilenumber')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="gender">Gender*</label>
                            <select id="gender" name="gender"
                                class="form-control @error('gender') is-invalid @enderror">
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
                        <div class="mb-2">
                            <label for="sponsor_id">Sponsor Id*</label>
                            <input type="hidden" name="hidden_user_id" value="{{ auth()->user()->id }}">
                            <input type="text" id="sponsor_id" name="sponsor_id"
                                class="form-control @error('sponsor_id') is-invalid @enderror" value="{{ $user_id }}"
                                style="background-color: rgb(218, 211, 211)" readonly>
                            @error('sponsor_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="address">Address*</label>
                            <input type="text" id="address" name="address"
                                class="form-control @error('address') is-invalid @enderror">
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                    </div>
                    <div class="col-md-6">

                        <div class="mb-2">
                            <label for="city">City*</label>
                            <input type="city" id="city" name="city"
                                class="form-control @error('city') is-invalid @enderror">
                            @error('city')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="state">State*</label>
                            <input type="text" id="state" name="state"
                                class="form-control @error('state') is-invalid @enderror" value="{{ old('state') }}">
                            @error('state')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- <div class="mb-2">
                            <label for="parent_level">Parent FOR LEVEL*</label>
                            <input type="text" id="parent_level" name="parent_level"
                                class="form-control @error('parent_level') is-invalid @enderror" >
                            @error('parent_level')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> --}}
                        <div class="mb-2">
                            <label for="image">Image*</label>
                            <input type="file" id="image" name="image"
                                class="form-control @error('image') is-invalid @enderror">
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div style="margin-left: 15px;">
                <h3><b>NB: Provide valid email for further communications.</b></h3>
            </div> --}}
            {{-- <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                       
                        <div class="mb-2">
                            <label for="zip">Zip*</label>
                            <input type="text" id="zip" name="zip"
                                class="form-control @error('zip') is-invalid @enderror" >
                            @error('zip')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="pan_no">PAN NO*</label>
                            <input type="text" id="pan_no" name="pan_no"
                                class="form-control @error('pan_no') is-invalid @enderror" >
                            @error('pan_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="bank">BANK*</label>
                            <input type="text" id="bank" name="bank"
                                class="form-control @error('bank') is-invalid @enderror">
                            @error('bank')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="account_no">ACCOUNT NO*</label>
                            <input type="text" id="account_no" name="account_no"
                                class="form-control @error('account_no') is-invalid @enderror" >
                            @error('account_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="ifsc">IFSC*</label>
                            <input type="text" id="ifsc" name="ifsc"
                                class="form-control @error('ifsc') is-invalid @enderror" >
                            @error('ifsc')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="nominee_name">NOMINEE NAME*</label>
                            <input type="text" id="nominee_name" name="nominee_name"
                                class="form-control @error('nominee_name') is-invalid @enderror" >
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
                                <option value="relation_user">Select</option>
                                @foreach ($relation_user as $user)
                            <option value="{{ $user }}" {{ old('relation_user') == $user ? 'selected' : '' }}>
                                {{ $user }}
                            </option>
                            @endforeach
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
            <div style="margin-left: 15px;">
                <p>By Clicking below register button you are agreing to our <span><a
                            href="{{ route('user.term.condition') }}" style="color: red">TERMS AND CONDITION</a></span>
                </p>
            </div> --}}
            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Register</button>
                {{-- <button class="btn btn-warning me-2" type="submit">Validate</button> --}}
                <a class="btn btn-secondary" href="{{ route('user.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection
