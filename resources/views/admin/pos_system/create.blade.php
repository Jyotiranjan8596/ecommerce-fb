@extends('layouts.master')
@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            Add New POS
        </div>
        <form action="{{ route('admin.pos_system.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-body">
                            <div class="mb-2">
                                <label for="name">POS NAME*</label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <input type="hidden" id="password" name="password">
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
                                <label for="email">Email Address*</label>
                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="transaction_charge">Transaction Charge*</label>
                                <input type="number" id="transaction_charge" name="transaction_charge"
                                    class="form-control @error('transaction_charge') is-invalid @enderror">
                                @error('transaction_charge')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="min_charge">Min Charge*</label>
                                <input type="number" id="min_charge" name="min_charge"
                                    class="form-control @error('min_charge') is-invalid @enderror">
                                @error('min_charge')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="max_charge">Max Charge*</label>
                                <input type="number" id="max_charge" name="max_charge"
                                    class="form-control @error('max_charge') is-invalid @enderror">
                                @error('max_charge')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="initial_letter_of_invoice">Initial Letter Of Invoice</label>
                                <input type="text" id="initial_letter_of_invoice" name="initial_letter_of_invoice"
                                    class="form-control @error('initial_letter_of_invoice') is-invalid @enderror">
                                @error('initial_letter_of_invoice')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="pos_code">POS CODE (To show In transaction)</label>
                                <input type="text" id="pos_code" name="pos_code"
                                    class="form-control @error('pos_code') is-invalid @enderror">
                                @error('pos_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="entity_name">Entity name*</label>
                                <input type="text" id="entity_name" name="entity_name"
                                    class="form-control @error('entity_name') is-invalid @enderror">
                                @error('entity_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-body">                         
                            <div class="mb-2">
                                <label for="entity_address">Entity Address*</label>
                                <input type="text" id="entity_address" name="entity_address"
                                    class="form-control @error('entity_address') is-invalid @enderror">
                                @error('entity_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="entity_contact">Entity Contact*</label>
                                <input type="text" id="entity_contact" name="entity_contact"
                                    class="form-control @error('entity_contact') is-invalid @enderror">
                                @error('entity_contact')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="comment">Comment*</label>
                                <input type="text" id="comment" name="comment"
                                    class="form-control @error('comment') is-invalid @enderror">
                                @error('comment')
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
                            <div class="mb-2">
                                <label for="city">City*</label>
                                <input type="text" id="city" name="city"
                                    class="form-control @error('city') is-invalid @enderror">
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="state">State*</label>
                                <select id="state" name="state"
                                    class="form-control @error('state') is-invalid @enderror">
                                    <option value="">Select a state</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state }}"
                                            {{ old('state') == $state ? 'selected' : '' }}>
                                            {{ $state }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="zip">Zip*</label>
                                <input type="text" id="zip" name="zip"
                                    class="form-control @error('zip') is-invalid @enderror">
                                @error('zip')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="latitude">Latitude*</label>
                                <input type="text" id="latitude" name="latitude"
                                    class="form-control @error('latitude') is-invalid @enderror">
                                @error('latitude')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="longitude">Longitude*</label>
                                <input type="text" id="longitude" name="longitude"
                                    class="form-control @error('longitude') is-invalid @enderror">
                                @error('longitude')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Register</button>
                <a class="btn btn-secondary" href="{{ route('admin.pos_system.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection
{{-- @section('scripts')
    <script type="text/javascript" src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('Editor');
    </script>
@endsection --}}
