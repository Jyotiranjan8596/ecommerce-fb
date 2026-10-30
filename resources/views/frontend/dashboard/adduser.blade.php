@extends('frontend.dashboard.layouts.master')

@section('content')
    <div class="container-fluid px-2 px-md-3 py-3">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header border-0 bg-primary text-white px-4 py-3 user-add-header">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h4 class="mb-0 fw-bold user-add-title">Add User</h4>
                </div>
                    <span class="badge rounded-pill bg-white text-primary px-3 py-2 fs-6">Sponsor ID: {{ $user_id }}</span>
                </div>
            </div>

            <form action="{{ route('user.add.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf

                <div class="card-body p-3 p-lg-4">
                    <div class="alert alert-light border rounded-4 mb-4 shadow-sm">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                            <div>
                                <div class="fw-semibold text-dark">Referral Link</div>
                                <small class="text-muted">Invite a new customer to register themselves.</small>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill copy-referral-link">
                                <i class="bi bi-clipboard-plus me-1"></i>Copy link
                            </button>
                        </div>
                        <div class="input-group mt-3">
                            <input id="referralLinkInput" type="text" class="form-control border-0 bg-white shadow-none" value="{{ $referral_link }}" readonly>
                            <span class="input-group-text bg-white border-0">
                                <i class="bi bi-link-45deg text-primary"></i>
                            </span>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="text" id="name" name="name" minlength="5" maxlength="30"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Name" required>
                                <label for="name">Name*</label>
                                @error('name')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <input type="hidden" name="password">

                            <div class="form-floating mb-3">
                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Email" required>
                                <label for="email">Email*</label>
                                @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="tel" id="mobilenumber" name="mobilenumber" minlength="10" maxlength="10"
                                    class="form-control @error('mobilenumber') is-invalid @enderror" value="{{ old('mobilenumber') }}" placeholder="Phone" required>
                                <label for="mobilenumber">Phone*</label>
                                @error('mobilenumber')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <select id="gender" name="gender"
                                    class="form-select @error('gender') is-invalid @enderror" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                <label for="gender">Gender*</label>
                                @error('gender')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" id="sponsor_id" name="sponsor_id"
                                    class="form-control @error('sponsor_id') is-invalid @enderror" value="{{ $user_id }}" placeholder="Sponsor Id" readonly>
                                <label for="sponsor_id">Sponsor Id*</label>
                                <input type="hidden" name="hidden_user_id" value="{{ auth()->user()->id }}">
                                @error('sponsor_id')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" id="address" name="address"
                                    class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" placeholder="Address" required>
                                <label for="address">Address*</label>
                                @error('address')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="text" id="city" name="city"
                                    class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" placeholder="City" required>
                                <label for="city">City*</label>
                                @error('city')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" id="state" name="state"
                                    class="form-control @error('state') is-invalid @enderror" value="{{ old('state') }}" placeholder="State" required>
                                <label for="state">State*</label>
                                @error('state')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" id="zip" name="zip"
                                    class="form-control @error('zip') is-invalid @enderror" value="{{ old('zip') }}" placeholder="Zip" required>
                                <label for="zip">Zip*</label>
                                @error('zip')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label fw-semibold text-dark">Profile Image</label>
                                <input type="file" id="image" name="image"
                                    class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                @error('image')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 px-3 px-lg-4 py-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <p class="mb-0 text-muted small">
                            By registering, you agree to our
                            <a href="{{ route('user.term.condition') }}" class="text-danger fw-semibold text-decoration-none">Terms & Conditions</a>
                        </p>

                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-primary px-4 rounded-pill" type="submit">
                                <i class="bi bi-person-plus me-1"></i>Register
                            </button>
                            <a class="btn btn-outline-secondary px-4 rounded-pill" href="{{ route('user.index') }}">
                                Back to list
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const copyButton = document.querySelector('.copy-referral-link');
            const referralInput = document.getElementById('referralLinkInput');

            if (copyButton && referralInput) {
                copyButton.addEventListener('click', async function () {
                    try {
                        await navigator.clipboard.writeText(referralInput.value);
                        const originalText = copyButton.innerHTML;
                        copyButton.innerHTML = '<i class="bi bi-check2-circle me-1"></i>Copied';
                        copyButton.classList.add('btn-success');
                        copyButton.classList.remove('btn-outline-primary');

                        setTimeout(function () {
                            copyButton.innerHTML = originalText;
                            copyButton.classList.remove('btn-success');
                            copyButton.classList.add('btn-outline-primary');
                        }, 1500);
                    } catch (error) {
                        referralInput.focus();
                        referralInput.select();
                        document.execCommand('copy');
                    }
                });
            }
        });
    </script>
    <style>
        .user-add-header {
            background: linear-gradient(115deg, #0f766e 0%, #087f8c 55%, #145da0 100%) !important;
        }

        .user-add-header .user-add-title {
            color: #fff !important;
            text-shadow: 0 1px 2px rgba(0, 0, 0, .18);
        }

        .user-add-header .user-add-eyebrow {
            color: #d5fff5 !important;
            letter-spacing: .08em;
        }

        .user-add-header .badge {
            color: #075e59 !important;
        }
    </style>
@endsection
