@extends('pos.layouts.master')

@section('content')
    <div class="pos-profile-page">
        <section class="pos-profile-hero">
            <div class="pos-profile-hero__content">
                <span class="pos-profile-hero__eyebrow">Partner account</span>
                <h1>My Profile</h1>
                <p>Keep your contact and store details up to date.</p>
            </div>
            <div class="pos-profile-hero__identity">
                @if ($user_profile->image)
                    <img src="{{ asset('images/' . $user_profile->image) }}" alt="{{ $user_profile->name }}">
                @else
                    <span aria-hidden="true">{{ strtoupper(substr($user_profile->name, 0, 1)) }}</span>
                @endif
                <div>
                    <strong>{{ $user_profile->name }}</strong>
                    <small>POS Partner</small>
                </div>
            </div>
        </section>

        @if (session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <form class="pos-profile-form" action="{{ route('pos.update.profile') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <section class="pos-profile-card">
                <header class="pos-profile-card__header">
                    <span class="pos-profile-card__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"
                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </span>
                    <div>
                        <h2>Personal details</h2>
                        <p>Your account name and phone are managed by Freebazar.</p>
                    </div>
                </header>

                <div class="pos-profile-grid">
                    <div class="pos-profile-field">
                        <label for="name">Partner name</label>
                        <input type="text" id="name" name="name" class="form-control"
                            value="{{ old('name', $user_profile->name) }}" readonly>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="pos-profile-field">
                        <label for="mobilenumber">Phone number</label>
                        <input type="tel" id="mobilenumber" name="mobilenumber" class="form-control"
                            value="{{ old('mobilenumber', $user_profile->mobilenumber) }}" readonly>
                        @error('mobilenumber') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="pos-profile-field">
                        <label for="email">Email address</label>
                        <input type="email" id="email" name="email" class="form-control" data-profile-editable readonly
                            value="{{ old('email', $user_profile->email) }}" autocomplete="email">
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="pos-profile-field">
                        <label for="image">Profile photo <span>Optional</span></label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*" data-profile-editable disabled>
                        @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </section>

            <section class="pos-profile-card">
                <header class="pos-profile-card__header">
                    <span class="pos-profile-card__icon pos-profile-card__icon--blue" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"
                                stroke="currentColor" stroke-width="1.8" />
                            <circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="1.8" />
                        </svg>
                    </span>
                    <div>
                        <h2>Store location</h2>
                        <p>Help customers and our team find your POS.</p>
                    </div>
                </header>

                <div class="pos-profile-grid">
                    <div class="pos-profile-field pos-profile-field--wide">
                        <label for="address">Street address</label>
                        <input type="text" id="address" name="address" class="form-control" data-profile-editable readonly
                            value="{{ old('address', $user_profile->address) }}" autocomplete="street-address">
                        @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="pos-profile-field">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" class="form-control" data-profile-editable readonly
                            value="{{ old('city', $user_profile->city) }}" autocomplete="address-level2">
                        @error('city') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="pos-profile-field">
                        <label for="state">State</label>
                        <input type="text" id="state" name="state" class="form-control" data-profile-editable readonly
                            value="{{ old('state', $user_profile->state) }}" autocomplete="address-level1">
                        @error('state') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="pos-profile-field">
                        <label for="zip">PIN code</label>
                        <input type="text" id="zip" name="zip" class="form-control" data-profile-editable readonly
                            value="{{ old('zip', $user_profile->zip) }}" autocomplete="postal-code">
                        @error('zip') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="pos-profile-field">
                        <label for="latitude">Latitude</label>
                        <input type="number" step="any" id="latitude" name="latitude" class="form-control" data-profile-editable readonly
                            value="{{ old('latitude', $user_profile->latitude) }}">
                        @error('latitude') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="pos-profile-field">
                        <label for="longitude">Longitude</label>
                        <input type="number" step="any" id="longitude" name="longitude" class="form-control" data-profile-editable readonly
                            value="{{ old('longitude', $user_profile->longitude) }}">
                        @error('longitude') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </section>

            <div class="pos-profile-actions">
                <a class="btn btn-outline-secondary" href="{{ route('pos.index') }}">Back to dashboard</a>
                <button class="btn btn-outline-primary" type="button" id="pos-profile-edit">Edit profile</button>
                <button class="btn btn-primary" type="submit" id="pos-profile-save" hidden>Save profile</button>
                <button class="btn btn-outline-secondary" type="button" id="pos-profile-cancel" hidden>Cancel</button>
            </div>

            @include('shared.pos-location-map', [
                'mapId' => 'pos-profile-location-picker',
                'mapTitle' => 'Set your POS map location',
                'mapDescription' => 'Click the map to set or adjust your location. Coordinates update automatically.',
                'coordinatePicker' => true,
                'pickerEnabled' => false,
                'latitudeInputId' => 'latitude',
                'longitudeInputId' => 'longitude',
                'locations' => old('latitude', $user_profile->latitude) && old('longitude', $user_profile->longitude) ? [[
                    'name' => $user_profile->name,
                    'address' => $user_profile->address,
                    'city' => $user_profile->city,
                    'state' => $user_profile->state,
                    'zip' => $user_profile->zip,
                    'latitude' => old('latitude', $user_profile->latitude),
                    'longitude' => old('longitude', $user_profile->longitude),
                ]] : [],
            ])

            <section class="pos-profile-card">
                <header class="pos-profile-card__header">
                    <span class="pos-profile-card__icon pos-profile-card__icon--green" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none">
                            <rect x="3" y="5" width="18" height="14" rx="2"
                                stroke="currentColor" stroke-width="1.8" />
                            <path d="M3 10h18M7 15h3" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" />
                        </svg>
                    </span>
                    <div>
                        <h2>Payments &amp; delivery</h2>
                        <p>Manage your payment details and delivery-order availability.</p>
                    </div>
                </header>

                <div class="pos-profile-grid">
                    <div class="pos-profile-field">
                        <label for="upi_id">UPI ID</label>
                        <input type="text" id="upi_id" name="upi_id" class="form-control" data-profile-editable readonly
                            value="{{ old('upi_id', $user_profile->upi_id) }}" autocomplete="off">
                        @error('upi_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="pos-delivery-setting">
                        <div class="pos-delivery-setting__copy">
                            <strong>Customer delivery orders</strong>
                            @if ($user_profile->delivery_orders_enabled)
                                <span class="pos-delivery-status pos-delivery-status--approved">Approved &amp; active</span>
                                <p>Customers can select your POS for delivery orders.</p>
                            @else
                                <p>Your POS appears to customers only after admin approval.</p>
                            @endif
                        </div>
                        @unless ($user_profile->delivery_orders_enabled)
                            <input type="hidden" id="delivery_orders_requested_locked"
                                name="delivery_orders_requested"
                                value="{{ old('delivery_orders_requested', $user_profile->delivery_orders_requested) ? 1 : 0 }}">
                            <label class="pos-delivery-toggle" for="delivery_orders_requested">
                                <input type="checkbox" id="delivery_orders_requested" name="delivery_orders_requested" data-profile-editable disabled
                                    value="1"
                                    @checked(old('delivery_orders_requested', $user_profile->delivery_orders_requested))>
                                <span>Request approval</span>
                            </label>
                            @if ($user_profile->delivery_orders_requested)
                                <div class="pos-delivery-status pos-delivery-status--pending">
                                    Request pending — uncheck to cancel
                                </div>
                            @endif
                        @endunless
                    </div>
                </div>
            </section>

        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('.pos-profile-form');
            const editButton = document.getElementById('pos-profile-edit');
            const saveButton = document.getElementById('pos-profile-save');
            const cancelButton = document.getElementById('pos-profile-cancel');
            if (!form || !editButton || !saveButton || !cancelButton) return;

            const setEditing = editing => {
                form.classList.toggle('is-editing', editing);
                form.querySelectorAll('[data-profile-editable]').forEach(field => {
                    if (field.type === 'file' || field.type === 'checkbox') {
                        field.disabled = !editing;
                    } else {
                        field.readOnly = !editing;
                    }
                });
                const lockedDeliveryValue = document.getElementById('delivery_orders_requested_locked');
                if (lockedDeliveryValue) lockedDeliveryValue.disabled = editing;
                editButton.hidden = editing;
                saveButton.hidden = !editing;
                cancelButton.hidden = !editing;
                window.FreebazarPosMaps?.['pos-profile-location-picker']?.setPickerEnabled(editing);
            };

            editButton.addEventListener('click', () => setEditing(true));
            cancelButton.addEventListener('click', () => {
                form.reset();
                setEditing(false);
            });
            form.addEventListener('submit', () => setEditing(false));
        });
    </script>
@endpush
