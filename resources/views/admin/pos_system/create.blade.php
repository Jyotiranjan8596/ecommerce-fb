@extends('layouts.master')
@section('content')
    <div class="admin-pos-create-page">
        <header class="admin-pos-create-hero">
            <div>
                <span class="admin-pos-create-eyebrow">POS management</span>
                <h1>Add New POS</h1>
                <p>Set up a point-of-sale location, payment details, and its map position.</p>
            </div>
            <span class="admin-pos-create-hero-icon" aria-hidden="true"><i class="fas fa-store"></i></span>
        </header>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Please check the highlighted fields.</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.pos_system.store') }}" method="post" enctype="multipart/form-data" class="admin-pos-create-card">
            @csrf
            <input type="hidden" id="password" name="password">
            <div class="admin-pos-create-form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="admin-pos-create-section">
                            <div class="admin-pos-create-section-heading">
                                <span class="admin-pos-create-section-icon" aria-hidden="true"><i class="fas fa-store-alt"></i></span>
                                <div><h2>POS details</h2><p>Contact and transaction setup.</p></div>
                            </div>
                            <div class="mb-2">
                                <label for="name">POS name <span>*</span></label>
                                <input type="text" id="name" name="name"
                                    value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="delivery_orders_enabled"
                                    name="delivery_orders_enabled" value="1" @checked(old('delivery_orders_enabled'))>
                                <label class="form-check-label" for="delivery_orders_enabled">
                                    This POS offers customer delivery
                                </label>
                            </div>
                            <div class="mb-2">
                                <label for="image">POS image <span class="admin-pos-optional">Optional</span></label>
                                <input type="file" id="image" name="image"
                                    class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label for="mobilenumber">Phone <span>*</span></label>
                                <input type="tel" id="mobilenumber" name="mobilenumber" value="{{ old('mobilenumber') }}"
                                    class="form-control @error('mobilenumber') is-invalid @enderror" inputmode="numeric" autocomplete="tel" required>
                                @error('mobilenumber')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="upi_id">UPI QR image <span>*</span></label>
                                <input type="file" id="upi_id" name="upi" accept="image/*"
                                    class="form-control @error('upi') is-invalid @enderror" required>
                                @error('upi')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="email">Email address <span>*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror" autocomplete="email" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="transaction_charge">Transaction charge (%) <span>*</span></label>
                                <input type="number" id="transaction_charge" name="transaction_charge"
                                    value="{{ old('transaction_charge') }}" min="0" step="0.01" class="form-control @error('transaction_charge') is-invalid @enderror" required>
                                @error('transaction_charge')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="min_charge">Minimum charge <span>*</span></label>
                                <input type="number" id="min_charge" name="min_charge"
                                    value="{{ old('min_charge') }}" min="0" step="0.01" class="form-control @error('min_charge') is-invalid @enderror" required>
                                @error('min_charge')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="max_charge">Maximum charge <span>*</span></label>
                                <input type="number" id="max_charge" name="max_charge"
                                    value="{{ old('max_charge') }}" min="0" step="0.01" class="form-control @error('max_charge') is-invalid @enderror" required>
                                @error('max_charge')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="initial_letter_of_invoice">Invoice prefix <span>*</span></label>
                                <input type="text" id="initial_letter_of_invoice" name="initial_letter_of_invoice"
                                    value="{{ old('initial_letter_of_invoice') }}" class="form-control @error('initial_letter_of_invoice') is-invalid @enderror" required>
                                @error('initial_letter_of_invoice')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="entity_name">Owner name <span>*</span></label>
                                <input type="text" id="entity_name" name="entity_name"
                                    value="{{ old('entity_name') }}" class="form-control @error('entity_name') is-invalid @enderror" required>
                                @error('entity_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="admin-pos-create-section">
                            <div class="admin-pos-create-section-heading">
                                <span class="admin-pos-create-section-icon admin-pos-create-section-icon--blue" aria-hidden="true"><i class="fas fa-map-marker-alt"></i></span>
                                <div><h2>Owner &amp; location</h2><p>Business details and physical address.</p></div>
                            </div>
                            <div class="mb-2">
                                <label for="entity_address">Owner address <span>*</span></label>
                                <input type="text" id="entity_address" name="entity_address"
                                    value="{{ old('entity_address') }}" class="form-control @error('entity_address') is-invalid @enderror" required>
                                @error('entity_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="entity_contact">Owner contact <span>*</span></label>
                                <input type="tel" id="entity_contact" name="entity_contact" value="{{ old('entity_contact') }}"
                                    class="form-control @error('entity_contact') is-invalid @enderror" autocomplete="tel" required>
                                @error('entity_contact')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="comment">Notes <span>*</span></label>
                                <input type="text" id="comment" name="comment"
                                    value="{{ old('comment') }}" class="form-control @error('comment') is-invalid @enderror" required>
                                @error('comment')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="address">Store address <span>*</span></label>
                                <input type="text" id="address" name="address"
                                    value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror" autocomplete="street-address" required>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="city">City <span>*</span></label>
                                <input type="text" id="city" name="city"
                                    value="{{ old('city') }}" class="form-control @error('city') is-invalid @enderror" autocomplete="address-level2" required>
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="state">State <span>*</span></label>
                                <input type="text" id="state" name="state"
                                    class="form-control @error('state') is-invalid @enderror"
                                    value="{{ old('state') }}" autocomplete="address-level1" required>
                                @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label for="zip">PIN code <span>*</span></label>
                                <input type="text" id="zip" name="zip"
                                    value="{{ old('zip') }}" class="form-control @error('zip') is-invalid @enderror" inputmode="numeric" autocomplete="postal-code" required>
                                @error('zip')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="latitude">Latitude <span class="admin-pos-optional">Optional</span></label>
                                <input type="number" step="any" id="latitude" name="latitude"
                                    class="form-control @error('latitude') is-invalid @enderror"
                                    value="{{ old('latitude') }}">
                                @error('latitude')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="longitude">Longitude <span class="admin-pos-optional">Optional</span></label>
                                <input type="number" step="any" id="longitude" name="longitude"
                                    class="form-control @error('longitude') is-invalid @enderror"
                                    value="{{ old('longitude') }}">
                                @error('longitude')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                @include('shared.pos-location-map', [
                    'mapId' => 'admin-pos-create-map',
                    'mapTitle' => 'Choose the POS location',
                    'mapDescription' => 'Click the map to select a location. The latitude and longitude fields will update automatically.',
                    'coordinatePicker' => true,
                    'latitudeInputId' => 'latitude',
                    'longitudeInputId' => 'longitude',
                    'locations' => old('latitude') && old('longitude') ? [[
                        'name' => 'Selected POS location',
                        'latitude' => old('latitude'),
                        'longitude' => old('longitude'),
                    ]] : [],
                ])

                <div class="admin-pos-create-terms">
                    <div class="form-check">
                        <input type="checkbox" id="terms" name="terms"
                            class="form-check-input @error('terms') is-invalid @enderror" value="1" @checked(old('terms')) required>
                        <label class="form-check-label" for="terms">
                            I agree to the
                            <a href="{{ route('terms.conditions') }}" class="text-primary"
                                style="text-decoration: underline;">
                                terms and conditions
                            </a>.
                        </label>
                        @error('terms')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

            </div>
            <div class="admin-pos-create-actions">
                <a class="btn btn-outline-secondary" href="{{ route('admin.pos_system.index') }}">Cancel</a>
                <button class="btn btn-primary" type="submit"><i class="fas fa-store me-2" aria-hidden="true"></i>Create POS</button>
            </div>
        </form>
    </div>

    <style>
        .admin-pos-create-page { display: grid; gap: 1.25rem; max-width: 1320px; margin: 0 auto; padding: 1rem 0 2rem; color: #172b4d; }
        .admin-pos-create-hero { display: flex; align-items: center; justify-content: space-between; gap: 1rem; overflow: hidden; padding: clamp(1.4rem, 3vw, 2.2rem); border-radius: 24px; color: #fff; background: linear-gradient(120deg, #123b72, #1769aa 62%, #2387bf); box-shadow: 0 16px 34px rgba(18,59,114,.18); }
        .admin-pos-create-eyebrow { color: #c8e6ff; font-size: .72rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
        .admin-pos-create-hero h1 { margin: .35rem 0 .45rem; color: #fff; font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; }
        .admin-pos-create-hero p { margin: 0; color: rgba(255,255,255,.82); }
        .admin-pos-create-hero-icon { display: grid; width: 4rem; height: 4rem; flex: 0 0 4rem; place-items: center; border: 1px solid rgba(255,255,255,.3); border-radius: 18px; background: rgba(255,255,255,.13); color: #fff; font-size: 1.5rem; }
        .admin-pos-create-card { overflow: hidden; border: 1px solid #e5edf6; border-radius: 20px; background: #fff; box-shadow: 0 12px 32px rgba(24,54,91,.08); }
        .admin-pos-create-form-body { padding: clamp(.75rem, 2vw, 1.5rem); background: #f7f9fc; }
        .admin-pos-create-form-body > .row { --bs-gutter-x: 1rem; --bs-gutter-y: 1rem; }
        .admin-pos-create-form-body > .row > div { display: flex; }
        .admin-pos-create-section { width: 100%; padding: clamp(1rem, 2vw, 1.5rem); border: 1px solid #e5edf6; border-radius: 16px; background: #fff; }
        .admin-pos-create-section-heading { display: flex; align-items: center; gap: .8rem; margin-bottom: 1.25rem; padding-bottom: .9rem; border-bottom: 1px solid #edf1f6; }
        .admin-pos-create-section-icon { display: grid; width: 2.65rem; height: 2.65rem; flex: 0 0 2.65rem; place-items: center; border-radius: 13px; background: #eef2ff; color: #3755a4; }
        .admin-pos-create-section-icon--blue { background: #e8f3ff; color: #1769aa; }
        .admin-pos-create-section-heading h2 { margin: 0; color: #183b65; font-size: 1.02rem; font-weight: 800; }
        .admin-pos-create-section-heading p { margin: .2rem 0 0; color: #718198; font-size: .82rem; }
        .admin-pos-create-section .mb-2 { margin-bottom: .95rem !important; }
        .admin-pos-create-section label:not(.form-check-label) { display: block; margin-bottom: .4rem; color: #465a74; font-size: .81rem; font-weight: 750; }
        .admin-pos-create-section label > span:first-child { color: #d14343; }
        .admin-pos-optional { margin-left: .3rem; color: #8290a3 !important; font-size: .7rem; font-weight: 600; }
        .admin-pos-create-section .form-control { min-height: 44px; border-color: #d8e2ee; border-radius: 10px; color: #243b58; }
        .admin-pos-create-section .form-control:focus { border-color: #4784c2; box-shadow: 0 0 0 .2rem rgba(38,112,184,.14); }
        .admin-pos-create-section input[type="file"].form-control { min-height: 46px; padding: .45rem .6rem; }
        .admin-pos-create-section .form-check { padding: .8rem .9rem .8rem 2.4rem; border: 1px solid #dce8f5; border-radius: 10px; background: #f5f9ff; }
        .admin-pos-create-form-body > .pos-map-panel { margin: 1.25rem 0; }
        .admin-pos-create-terms { padding: 1rem 1.1rem; border: 1px solid #dce8f5; border-radius: 12px; background: #fff; }
        .admin-pos-create-terms .form-check-label { color: #52657e; }
        .admin-pos-create-actions { display: flex; justify-content: flex-end; gap: .65rem; padding: 1rem clamp(1rem, 2vw, 1.5rem); border-top: 1px solid #eaf0f6; background: #fff; }
        .admin-pos-create-actions .btn { min-height: 44px; padding: .65rem 1rem; border-radius: 10px; font-weight: 750; }
        .admin-pos-create-actions .btn-primary { box-shadow: 0 6px 14px rgba(21,82,153,.18); }
        @media (max-width: 640px) { .admin-pos-create-page { padding: .5rem 0 1.5rem; } .admin-pos-create-hero-icon { width: 3.25rem; height: 3.25rem; flex-basis: 3.25rem; font-size: 1.2rem; } .admin-pos-create-actions { flex-direction: column-reverse; } .admin-pos-create-actions .btn { width: 100%; } }
    </style>
@endsection
{{-- @section('scripts')
    <script type="text/javascript" src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('Editor');
    </script>
@endsection --}}
