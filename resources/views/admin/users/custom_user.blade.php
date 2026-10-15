@extends('layouts.master')

@section('content')
    <div class="admin-customer-create">
        <header class="admin-customer-hero">
            <div>
                <span class="admin-customer-eyebrow">Customer management</span>
                <h1>Add Customer</h1>
                <p>Create a customer account and assign its sponsor.</p>
            </div>
            <span class="admin-customer-hero-icon" aria-hidden="true"><i class="fas fa-user-plus"></i></span>
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

        <form action="{{ route('admin.user.customer-store') }}" method="POST" class="admin-customer-card">
            @csrf
            <input type="hidden" name="password" value="{{ old('password') }}">

            <section class="admin-customer-section" aria-labelledby="customer-personal-heading">
                <div class="admin-customer-section-heading">
                    <span class="admin-customer-section-icon" aria-hidden="true"><i class="fas fa-user"></i></span>
                    <div>
                        <h2 id="customer-personal-heading">Personal details</h2>
                        <p>Enter the customer’s contact information.</p>
                    </div>
                </div>

                <div class="admin-customer-fields">
                    <div class="admin-customer-field">
                        <label for="name">Customer name <span>*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" minlength="5" maxlength="30"
                            class="form-control @error('name') is-invalid @enderror" autocomplete="name" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="admin-customer-field">
                        <label for="email">Email address <span>*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" autocomplete="email" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="admin-customer-field">
                        <label for="mobilenumber">Mobile number <span>*</span></label>
                        <input type="tel" id="mobilenumber" name="mobilenumber" value="{{ old('mobilenumber') }}"
                            class="form-control @error('mobilenumber') is-invalid @enderror" inputmode="numeric"
                            autocomplete="tel" minlength="10" maxlength="10" required>
                        @error('mobilenumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="admin-customer-field">
                        <label for="gender">Gender <span>*</span></label>
                        <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                            <option value="">Select gender</option>
                            <option value="male" @selected(old('gender') === 'male')>Male</option>
                            <option value="female" @selected(old('gender') === 'female')>Female</option>
                        </select>
                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="admin-customer-field admin-customer-field--wide">
                        <label for="address">Address <span>*</span></label>
                        <input type="text" id="address" name="address" value="{{ old('address') }}"
                            class="form-control @error('address') is-invalid @enderror" autocomplete="street-address" required>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </section>

            <section class="admin-customer-section" aria-labelledby="customer-location-heading">
                <div class="admin-customer-section-heading">
                    <span class="admin-customer-section-icon admin-customer-section-icon--blue" aria-hidden="true"><i class="fas fa-map-marker-alt"></i></span>
                    <div>
                        <h2 id="customer-location-heading">Location &amp; sponsor</h2>
                        <p>Add the customer’s location and referral sponsor.</p>
                    </div>
                </div>

                <div class="admin-customer-fields">
                    <div class="admin-customer-field">
                        <label for="city">City <span>*</span></label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}"
                            class="form-control @error('city') is-invalid @enderror" autocomplete="address-level2" required>
                        @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="admin-customer-field">
                        <label for="state">State <span>*</span></label>
                        <input type="text" id="state" name="state" value="{{ old('state') }}"
                            class="form-control @error('state') is-invalid @enderror" autocomplete="address-level1" required>
                        @error('state') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="admin-customer-field">
                        <label for="zip">PIN code <span>*</span></label>
                        <input type="text" id="zip" name="zip" value="{{ old('zip') }}"
                            class="form-control @error('zip') is-invalid @enderror" inputmode="numeric" autocomplete="postal-code" required>
                        @error('zip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="admin-customer-field admin-customer-field--wide">
                        <label for="search_user">Sponsor <span>*</span></label>
                        <div class="admin-customer-sponsor-search">
                            <i class="fas fa-search" aria-hidden="true"></i>
                            <input type="search" id="search_user" name="sponsor" class="form-control"
                                value="{{ old('sponsor') }}" placeholder="Search by sponsor mobile number or user ID"
                                autocomplete="off" aria-autocomplete="list" aria-controls="userList" required>
                            <input type="hidden" id="hidden_sponsor_id" name="sponsor_id" value="{{ old('sponsor_id') }}">
                            <div id="userList" class="admin-customer-sponsor-results" role="listbox" hidden></div>
                        </div>
                        <small class="admin-customer-help">Choose a matching account from the results to set the sponsor.</small>
                        @error('sponsor_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </section>

            <footer class="admin-customer-actions">
                <a class="btn btn-outline-secondary" href="{{ route('admin.users.index') }}">Cancel</a>
                <button class="btn btn-primary admin-customer-submit" type="submit">
                    <i class="fas fa-user-plus me-2" aria-hidden="true"></i>Create customer
                </button>
            </footer>
        </form>
    </div>

    <style>
        .admin-customer-create { display: grid; gap: 1.25rem; max-width: 1100px; margin: 0 auto; padding: 1rem 0 2rem; color: #172b4d; }
        .admin-customer-hero { display: flex; align-items: center; justify-content: space-between; gap: 1rem; overflow: hidden; padding: clamp(1.4rem, 3vw, 2.2rem); border-radius: 24px; color: #fff; background: linear-gradient(120deg, #123b72, #1769aa 62%, #2387bf); box-shadow: 0 16px 34px rgba(18, 59, 114, .18); }
        .admin-customer-eyebrow { color: #c8e6ff; font-size: .72rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
        .admin-customer-hero h1 { margin: .35rem 0 .45rem; color: #fff; font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; }
        .admin-customer-hero p { margin: 0; color: rgba(255,255,255,.82); }
        .admin-customer-hero-icon { display: grid; width: 4rem; height: 4rem; flex: 0 0 4rem; place-items: center; border: 1px solid rgba(255,255,255,.3); border-radius: 18px; background: rgba(255,255,255,.13); color: #fff; font-size: 1.5rem; }
        .admin-customer-card { overflow: hidden; border: 1px solid #e5edf6; border-radius: 20px; background: #fff; box-shadow: 0 12px 32px rgba(24,54,91,.08); }
        .admin-customer-section { padding: clamp(1.1rem, 2.5vw, 1.75rem); }
        .admin-customer-section + .admin-customer-section { border-top: 1px solid #eaf0f6; }
        .admin-customer-section-heading { display: flex; align-items: center; gap: .85rem; margin-bottom: 1.25rem; }
        .admin-customer-section-icon { display: grid; width: 2.75rem; height: 2.75rem; flex: 0 0 2.75rem; place-items: center; border-radius: 13px; background: #eef2ff; color: #3755a4; }
        .admin-customer-section-icon--blue { background: #e8f3ff; color: #1769aa; }
        .admin-customer-section-heading h2 { margin: 0; color: #183b65; font-size: 1.05rem; font-weight: 800; }
        .admin-customer-section-heading p { margin: .2rem 0 0; color: #718198; font-size: .84rem; }
        .admin-customer-fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem 1.2rem; }
        .admin-customer-field { min-width: 0; }
        .admin-customer-field--wide { grid-column: 1 / -1; }
        .admin-customer-field label { display: block; margin-bottom: .4rem; color: #465a74; font-size: .82rem; font-weight: 750; }
        .admin-customer-field label > span:first-child { color: #d14343; }
        .admin-customer-field .form-control, .admin-customer-field .form-select { min-height: 46px; border-color: #d8e2ee; border-radius: 10px; color: #243b58; }
        .admin-customer-field .form-control:focus, .admin-customer-field .form-select:focus { border-color: #4784c2; box-shadow: 0 0 0 .2rem rgba(38, 112, 184, .14); }
        .admin-customer-optional { margin-left: .35rem; color: #8290a3; font-size: .72rem; font-weight: 600; }
        .admin-customer-sponsor-search { position: relative; }
        .admin-customer-sponsor-search > i { position: absolute; top: 50%; left: .9rem; z-index: 1; color: #8694a8; transform: translateY(-50%); }
        .admin-customer-sponsor-search input[type="search"] { padding-left: 2.4rem; }
        .admin-customer-sponsor-results { position: absolute; top: calc(100% + .35rem); right: 0; left: 0; z-index: 20; max-height: 230px; overflow-y: auto; padding: .35rem; border: 1px solid #dce5ef; border-radius: 12px; background: #fff; box-shadow: 0 14px 28px rgba(24,54,91,.16); }
        .admin-customer-sponsor-results button { display: block; width: 100%; padding: .65rem .75rem; border: 0; border-radius: 8px; background: transparent; color: #344761; text-align: left; }
        .admin-customer-sponsor-results button:hover, .admin-customer-sponsor-results button:focus { background: #eff6ff; color: #153e75; }
        .admin-customer-help { display: block; margin-top: .4rem; color: #718198; }
        .admin-customer-actions { display: flex; justify-content: flex-end; gap: .65rem; padding: 1rem clamp(1.1rem, 2.5vw, 1.75rem); border-top: 1px solid #eaf0f6; background: #f8fafc; }
        .admin-customer-actions .btn { min-height: 44px; padding: .65rem 1rem; border-radius: 10px; font-weight: 750; }
        .admin-customer-submit { box-shadow: 0 6px 14px rgba(21, 82, 153, .18); }
        @media (max-width: 640px) { .admin-customer-create { padding-top: .5rem; } .admin-customer-fields { grid-template-columns: 1fr; gap: .85rem; } .admin-customer-field--wide { grid-column: auto; } .admin-customer-hero-icon { width: 3.25rem; height: 3.25rem; flex-basis: 3.25rem; font-size: 1.2rem; } .admin-customer-actions { flex-direction: column-reverse; } .admin-customer-actions .btn { width: 100%; } }
    </style>
@endsection

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function () {
            const $search = $('#search_user');
            const $results = $('#userList');
            let request;

            $search.on('input', function () {
                const query = $(this).val().trim();
                $('#hidden_sponsor_id').val('');
                if (request) request.abort();
                if (!query) {
                    $results.prop('hidden', true).empty();
                    return;
                }

                request = $.get("{{ route('admin.search.sponsor') }}", { query: query })
                    .done(function (users) {
                        $results.empty();
                        if (users.length) {
                            users.forEach(function (user) {
                                const $option = $('<button>', { type: 'button', role: 'option', class: 'sponsor-result' })
                                    .text(user.mobilenumber + ' (' + user.user_id + ')')
                                    .attr('data-id', user.id);
                                $results.append($option);
                            });
                        } else {
                            $results.append($('<div>', { class: 'px-3 py-2 text-secondary' }).text('No matching sponsors found'));
                        }
                        $results.prop('hidden', false);
                    })
                    .fail(function (xhr, status) {
                        if (status !== 'abort') {
                            $results.empty().append($('<div>', { class: 'px-3 py-2 text-danger' }).text('Sponsor search is temporarily unavailable.'));
                            $results.prop('hidden', false);
                        }
                    });
            });

            $results.on('click', '.sponsor-result', function () {
                $search.val($(this).text());
                $('#hidden_sponsor_id').val($(this).data('id'));
                $results.prop('hidden', true).empty();
            });

            $(document).on('click', function (event) {
                if (!$(event.target).closest('.admin-customer-sponsor-search').length) {
                    $results.prop('hidden', true);
                }
            });

            $('.admin-customer-card').on('submit', function (event) {
                const sponsorSelected = Boolean($('#hidden_sponsor_id').val());
                $search[0].setCustomValidity(sponsorSelected ? '' : 'Select a sponsor from the matching results.');
                if (!sponsorSelected) {
                    event.preventDefault();
                    $search[0].reportValidity();
                }
            });

            $search.on('input', function () {
                this.setCustomValidity('');
            });
        });
    </script>
