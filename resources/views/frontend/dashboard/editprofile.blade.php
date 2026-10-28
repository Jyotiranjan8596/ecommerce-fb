@extends('frontend.dashboard.layouts.master')

@section('content')
    <div class="container-fluid px-2 px-md-3 py-3">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden profile-card {{ $user_profile->is_prime_member ? 'profile-card--prime' : '' }}">
            <div class="card-header border-0 px-4 py-3 profile-header">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <div class="text-uppercase small fw-semibold mb-1 profile-eyebrow">Account settings</div>
                        <h4 class="mb-0 fw-bold profile-title">My Profile</h4>
                        <p class="mb-0 mt-1 profile-subtitle">Your personal and account information.</p>
                    </div>
                    @unless ($user_profile->is_prime_member)
                        <span class="badge rounded-pill bg-white px-3 py-2 fs-6 profile-badge">Profile details</span>
                    @endunless
                </div>
            </div>

            <div class="card-body p-3 p-lg-4">
                @if (session('success'))
                    <div class="alert alert-success profile-photo-success" role="status">{{ session('success') }}</div>
                @endif

                <div class="profile-overview">
                    <div class="profile-person">
                        <div class="profile-avatar-wrap {{ $user_profile->is_prime_member ? 'prime-profile-image-frame' : '' }}">
                            <img id="profile-avatar-preview"
                                @if ($user_profile->image) src="{{ asset('images/' . $user_profile->image) }}" @endif
                                alt="Profile photo of {{ $user_profile->name }}" class="profile-avatar"
                                @if (!$user_profile->image) hidden @endif
                                onerror="this.hidden = true; document.getElementById('profile-avatar-fallback').hidden = false;">
                            <div id="profile-avatar-fallback" class="profile-avatar profile-avatar-placeholder"
                                aria-hidden="true" @if ($user_profile->image) hidden @endif>
                                {{ strtoupper(substr($user_profile->name ?? '?', 0, 1)) }}
                            </div>
                        </div>
                        <div class="min-w-0">
                            @if ($user_profile->is_prime_member)
                                <span class="prime-badge mb-2">
                                    <i class="fas fa-crown" aria-hidden="true"></i> PRIME MEMBER
                                </span>
                            @endif
                            <h5 class="mb-1 profile-person-name">{{ $user_profile->name ?: 'Name not provided' }}</h5>
                            <div class="profile-person-contact">{{ $user_profile->email ?: 'Email not provided' }}</div>
                            <div class="profile-person-contact">{{ $user_profile->mobilenumber ?: 'Phone not provided' }}</div>
                            <form action="{{ route('user.profile.image.update') }}" method="POST"
                                enctype="multipart/form-data" class="profile-photo-form mt-3">
                                @csrf
                                <input type="file" id="profile-photo-input" name="image"
                                    class="visually-hidden @error('image') is-invalid @enderror"
                                    accept="image/jpeg,image/png,image/gif,image/webp" required>
                                <label for="profile-photo-input" class="btn btn-outline-primary btn-sm profile-photo-choose">
                                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M4 8a2 2 0 0 1 2-2h2l1.5-2h5L16 6h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
                                        <circle cx="12" cy="13" r="3.5" stroke="currentColor" stroke-width="1.7" />
                                    </svg>
                                    Change profile image
                                </label>
                                <button type="submit" class="btn btn-primary btn-sm profile-photo-upload" disabled>Upload photo</button>
                                <span id="profile-photo-filename" class="profile-photo-filename" aria-live="polite"></span>
                                <small class="profile-photo-help">JPG, PNG, GIF, or WEBP · Up to 40 MB</small>
                                @error('image')
                                    <span class="profile-photo-error" role="alert">{{ $message }}</span>
                                @enderror
                            </form>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-12 col-lg-6">
                            <section class="profile-details-section h-100" aria-labelledby="profile-personal-heading">
                                <h6 id="profile-personal-heading" class="profile-section-heading">Personal details</h6>
                                <dl class="profile-details-list">
                                    <div class="profile-detail">
                                        <dt>Full name</dt>
                                        <dd>{{ $user_profile->name ?: 'Not provided' }}</dd>
                                    </div>
                                    @if ($user_profile->is_prime_member)
                                        <div class="profile-detail">
                                            <dt>User ID</dt>
                                            <dd>{{ $user_profile->user_id ?: 'Not provided' }}</dd>
                                        </div>
                                        <div class="profile-detail">
                                            <dt>Sponsor user ID</dt>
                                            <dd>{{ $user_profile->sponsorUser?->user_id ?: 'Not provided' }}</dd>
                                        </div>
                                    @endif
                                    <div class="profile-detail">
                                        <dt>Email address</dt>
                                        <dd>{{ $user_profile->email ?: 'Not provided' }}</dd>
                                    </div>
                                    <div class="profile-detail">
                                        <dt>Phone number</dt>
                                        <dd>
                                            {{ $user_profile->mobilenumber ?: 'Not provided' }}
                                            <span class="profile-note">Changes require admin approval</span>
                                        </dd>
                                    </div>
                                    <div class="profile-detail">
                                        <dt>Address</dt>
                                        <dd>{{ $user_profile->address ?: 'Not provided' }}</dd>
                                    </div>
                                    <div class="profile-detail">
                                        <dt>City</dt>
                                        <dd>{{ $user_profile->city ?: 'Not provided' }}</dd>
                                    </div>
                                    <div class="profile-detail">
                                        <dt>State</dt>
                                        <dd>{{ $user_profile->state ?: 'Not provided' }}</dd>
                                    </div>
                                    <div class="profile-detail">
                                        <dt>ZIP code</dt>
                                        <dd>{{ $user_profile->zip ?: 'Not provided' }}</dd>
                                    </div>
                                </dl>
                            </section>
                        </div>

                        <div class="col-12 col-lg-6">
                            <section class="profile-details-section h-100" aria-labelledby="profile-bank-heading">
                                <h6 id="profile-bank-heading" class="profile-section-heading">Bank &amp; nominee details</h6>
                                <dl class="profile-details-list">
                                    <div class="profile-detail">
                                        <dt>PAN number</dt>
                                        <dd>{{ $user_profile->pan_no ?: 'Not provided' }}</dd>
                                    </div>
                                    <div class="profile-detail">
                                        <dt>Bank</dt>
                                        <dd>{{ $user_profile->bank ?: 'Not provided' }}</dd>
                                    </div>
                                    <div class="profile-detail">
                                        <dt>Account number</dt>
                                        <dd>{{ $user_profile->account_no ?: 'Not provided' }}</dd>
                                    </div>
                                    <div class="profile-detail">
                                        <dt>IFSC</dt>
                                        <dd>{{ $user_profile->ifsc ?: 'Not provided' }}</dd>
                                    </div>
                                    <div class="profile-detail">
                                        <dt>Nominee name</dt>
                                        <dd>{{ $user_profile->nominee_name ?: 'Not provided' }}</dd>
                                    </div>
                                    <div class="profile-detail">
                                        <dt>Relation to nominee</dt>
                                        <dd>{{ $user_profile->relation_user ?: 'Not provided' }}</dd>
                                    </div>
                                </dl>
                            </section>
                        </div>
                    </div>
                </div>

                @if ($user_profile->is_prime_member)
                    <section class="prime-profile-summary mt-4" aria-label="Prime membership details">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div>
                                <span class="prime-profile-summary__eyebrow"><i class="fas fa-crown" aria-hidden="true"></i> Membership</span>
                                <h5 class="mb-1">Prime member</h5>
                                <p class="mb-0">Your customer family has {{ number_format($user_profile->prime_referral_count) }} direct referrals.</p>
                            </div>
                            <a class="btn btn-outline-primary rounded-pill px-4" href="{{ route('user.sponsor.list') }}">View referrals</a>
                        </div>
                        <div class="prime-profile-summary__facts">
                            <div><span>Membership status</span><strong>Active</strong></div>
                            <div><span>Prime since</span><strong>{{ $primeSince?->format('d M Y') ?? '—' }}</strong></div>
                            <div><span>Referral threshold</span><strong>{{ \App\Models\User::PRIME_REFERRAL_THRESHOLD }} customers</strong></div>
                        </div>
                    </section>
                @endif

                <details class="profile-edit-details mt-4" @if ($errors->any()) open @endif>
                    <summary class="btn btn-primary rounded-pill px-4 profile-edit-toggle">
                        <i class="bi bi-pencil-square me-1"></i>Edit personal details
                    </summary>

                    <form action="{{ route('user.update.profile') }}" method="POST" class="profile-edit-form mt-3">
                        @csrf
                        <div class="profile-edit-panel">
                            <div class="profile-section-heading">Update personal details</div>
                            <p class="profile-edit-help">Phone number and bank information are managed separately and can’t be edited here.</p>

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="name" class="form-label">Full name</label>
                                    <input type="text" id="name" name="name" maxlength="30"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $user_profile->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" id="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user_profile->email) }}">
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" id="address" name="address"
                                        class="form-control @error('address') is-invalid @enderror"
                                        value="{{ old('address', $user_profile->address) }}">
                                    @error('address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" id="city" name="city"
                                        class="form-control @error('city') is-invalid @enderror"
                                        value="{{ old('city', $user_profile->city) }}">
                                    @error('city')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="state" class="form-label">State</label>
                                    <input type="text" id="state" name="state"
                                        class="form-control @error('state') is-invalid @enderror"
                                        value="{{ old('state', $user_profile->state) }}">
                                    @error('state')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="zip" class="form-label">ZIP code</label>
                                    <input type="text" id="zip" name="zip"
                                        class="form-control @error('zip') is-invalid @enderror"
                                        value="{{ old('zip', $user_profile->zip) }}">
                                    @error('zip')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="nominee_name" class="form-label">Nominee name</label>
                                    <input type="text" id="nominee_name" name="nominee_name"
                                        class="form-control @error('nominee_name') is-invalid @enderror"
                                        value="{{ old('nominee_name', $user_profile->nominee_name) }}">
                                    @error('nominee_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="relation_user" class="form-label">Relation to nominee</label>
                                    <input type="text" id="relation_user" name="relation_user"
                                        class="form-control @error('relation_user') is-invalid @enderror"
                                        value="{{ old('relation_user', $user_profile->relation_user) }}">
                                    @error('relation_user')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-4">
                                <button class="btn btn-primary px-4 rounded-pill profile-save-button" type="submit">
                                    <i class="bi bi-check2-circle me-1"></i>Save changes
                                </button>
                                <a class="btn btn-outline-secondary px-4 rounded-pill" href="{{ route('user.edit.profile') }}">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </details>
            </div>

            <div class="card-footer bg-white border-0 px-3 px-lg-4 py-3">
                <div class="d-flex justify-content-end">
                    <a class="btn btn-outline-secondary px-4 rounded-pill" href="{{ route('user.index') }}">Back to dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const input = document.getElementById('profile-photo-input');
            const preview = document.getElementById('profile-avatar-preview');
            const fallback = document.getElementById('profile-avatar-fallback');
            const filename = document.getElementById('profile-photo-filename');
            const uploadButton = document.querySelector('.profile-photo-upload');

            if (!input || !preview || !fallback || !filename || !uploadButton) return;

            input.addEventListener('change', function () {
                const file = input.files && input.files[0];
                if (!file) return;

                filename.textContent = file.name;
                uploadButton.disabled = false;

                if (file.type.startsWith('image/')) {
                    preview.src = URL.createObjectURL(file);
                    preview.hidden = false;
                    fallback.hidden = true;
                }
            });
        })();
    </script>

    <style>
        .profile-header {
            background: linear-gradient(115deg, #0f766e 0%, #087f8c 55%, #145da0 100%) !important;
        }

        .profile-title {
            color: #fff !important;
        }

        .profile-eyebrow {
            color: #d5fff5;
            letter-spacing: .08em;
        }

        .profile-subtitle {
            color: #e5f5ff;
        }

        .profile-badge {
            color: #075e59;
        }

        .profile-person {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.1rem;
            border: 1px solid #dce6ee;
            border-radius: 1rem;
            background: linear-gradient(120deg, #f4fbfa, #f4f8fc);
        }

        .profile-avatar {
            width: 4.5rem;
            height: 4.5rem;
            flex: 0 0 4.5rem;
            border: 3px solid #fff;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 .2rem .65rem rgba(23, 32, 51, .16);
        }

        .profile-avatar-wrap {
            position: relative;
            width: 4.5rem;
            height: 4.5rem;
            flex: 0 0 4.5rem;
        }

        .profile-avatar-wrap .profile-avatar {
            position: absolute;
            inset: 0;
        }

        .profile-avatar[hidden] {
            display: none !important;
        }

        .profile-avatar-placeholder {
            display: grid;
            place-items: center;
            background: #087f8c;
            color: #fff;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .profile-avatar-placeholder[hidden] {
            display: none !important;
        }

        .profile-photo-form {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .45rem;
        }

        .profile-photo-form .btn {
            border-radius: 999px;
            font-size: .78rem;
            font-weight: 700;
        }

        .profile-photo-choose {
            display: inline-flex;
            align-items: center;
            cursor: pointer;
        }

        .profile-photo-choose svg {
            width: 1rem;
            height: 1rem;
            margin-right: .35rem;
        }

        .profile-photo-upload:disabled {
            cursor: not-allowed;
            opacity: .55;
        }

        .profile-photo-help,
        .profile-photo-filename,
        .profile-photo-error {
            flex-basis: 100%;
            font-size: .78rem;
        }

        .profile-photo-help {
            color: #64748b;
        }

        .profile-photo-filename {
            color: #087f8c;
            font-weight: 650;
            overflow-wrap: anywhere;
        }

        .profile-photo-error {
            color: #b42332;
        }

        .profile-photo-success {
            border-radius: 14px;
        }

        .profile-person-name {
            overflow-wrap: anywhere;
            color: #172033;
            font-weight: 750;
        }

        .profile-person-contact {
            overflow-wrap: anywhere;
            color: #46576b;
            font-size: .92rem;
        }

        .profile-details-section {
            padding: 1.15rem;
            border: 1px solid #e0e8ef;
            border-radius: .9rem;
            background: #fff;
        }

        .profile-section-heading {
            margin: 0 0 1rem;
            padding-bottom: .65rem;
            border-bottom: 1px solid #e3eaf1;
            color: #17324d;
            font-size: 1rem;
            font-weight: 700;
        }

        .profile-details-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem 1.25rem;
            margin: 0;
        }

        .profile-detail {
            min-width: 0;
        }

        .profile-detail dt {
            margin-bottom: .22rem;
            color: #526174;
            font-size: .78rem;
            font-weight: 650;
            letter-spacing: .025em;
        }

        .profile-detail dd {
            margin: 0;
            color: #172033;
            font-size: .95rem;
            font-weight: 550;
            overflow-wrap: anywhere;
        }

        .profile-note {
            display: block;
            margin-top: .15rem;
            color: #526174;
            font-size: .78rem;
            font-weight: 400;
        }

        .profile-edit-details > summary {
            display: inline-flex;
            align-items: center;
            list-style: none;
            cursor: pointer;
        }

        .profile-edit-details > summary::-webkit-details-marker {
            display: none;
        }

        .profile-edit-panel {
            padding: clamp(1rem, 3vw, 1.5rem);
            border: 1px solid #dce6ee;
            border-radius: .9rem;
            background: #f8fafc;
        }

        .profile-edit-help {
            margin: -.2rem 0 1.1rem;
            color: #526174;
            font-size: .9rem;
        }

        .profile-edit-form .form-label {
            color: #263449;
            font-size: .9rem;
            font-weight: 650;
        }

        .profile-edit-form .form-control {
            min-height: 2.9rem;
            border-color: #bac7d5;
            border-radius: .65rem;
            color: #172033;
        }

        .profile-edit-form .form-control:focus {
            border-color: #087f8c;
            box-shadow: 0 0 0 .2rem rgba(8, 127, 140, .16);
        }

        .profile-save-button {
            background-color: #087f8c;
            border-color: #087f8c;
        }

        .profile-save-button:hover,
        .profile-save-button:focus {
            background-color: #075e68;
            border-color: #075e68;
        }

        @media (max-width: 575.98px) {
            .profile-details-list {
                grid-template-columns: minmax(0, 1fr);
                gap: .85rem;
            }
        }
    </style>
@endsection
