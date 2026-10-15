@extends('layouts.master')

@section('content')
    <div class="admin-profile-page">
        <header class="admin-profile-hero">
            <div class="admin-profile-identity">
                <div class="admin-profile-avatar">
                    @if ($profile->image)
                        <img id="admin-profile-avatar-preview" src="{{ asset('images/' . $profile->image) }}" alt="Profile photo of {{ $profile->name }}">
                    @else
                        <img id="admin-profile-avatar-preview" src="" alt="Profile photo of {{ $profile->name }}" hidden>
                        <span id="admin-profile-avatar-fallback" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($profile->name ?: 'A', 0, 1)) }}</span>
                    @endif
                </div>
                <div>
                    <span class="admin-profile-eyebrow">Account settings</span>
                    <h1>Admin Profile</h1>
                    <p>Update the name, email, and profile photo for your admin account.</p>
                </div>
            </div>
            <a class="btn btn-light admin-profile-password-link" href="{{ route('admin.password.index') }}">
                <i class="fas fa-lock me-2" aria-hidden="true"></i>Change password
            </a>
        </header>

        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif
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

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="admin-profile-card">
            @csrf
            @method('PUT')
            <div class="admin-profile-card-heading">
                <span class="admin-profile-card-icon" aria-hidden="true"><i class="fas fa-user-edit"></i></span>
                <div>
                    <h2>Profile details</h2>
                    <p>Your profile information is used across the admin panel.</p>
                </div>
            </div>

            <div class="admin-profile-fields">
                <div class="admin-profile-field">
                    <label for="title">Name <span>*</span></label>
                    <input type="text" id="title" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $profile->name) }}" autocomplete="name" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="admin-profile-field">
                    <label for="email">Email address <span>*</span></label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $profile->email) }}" autocomplete="email" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="admin-profile-field admin-profile-field--wide">
                    <label for="image">Profile photo <span class="admin-profile-optional">Optional</span></label>
                    <div class="admin-profile-upload">
                        <div class="admin-profile-upload-preview" aria-hidden="true">
                            @if ($profile->image)
                                <img id="admin-profile-upload-preview" src="{{ asset('images/' . $profile->image) }}" alt="">
                            @else
                                <img id="admin-profile-upload-preview" src="" alt="" hidden>
                                <i id="admin-profile-upload-fallback" class="fas fa-user"></i>
                            @endif
                        </div>
                        <div class="admin-profile-upload-input">
                            <input type="file" id="image" name="image" accept="image/*"
                                class="form-control @error('image') is-invalid @enderror">
                            <small>Choose an image to update your profile photo.</small>
                            @error('image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <footer class="admin-profile-actions">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-save me-2" aria-hidden="true"></i>Save profile
                </button>
            </footer>
        </form>
    </div>

    <style>
        .admin-profile-page { display: grid; gap: 1.25rem; max-width: 1100px; margin: 0 auto; padding: 1rem 0 2rem; color: #172b4d; }
        .admin-profile-hero { display: flex; align-items: center; justify-content: space-between; gap: 1.25rem; padding: clamp(1.3rem, 3vw, 2rem); border-radius: 24px; color: #fff; background: linear-gradient(120deg, #123b72, #1769aa 62%, #2387bf); box-shadow: 0 16px 34px rgba(18,59,114,.18); }
        .admin-profile-identity { display: flex; min-width: 0; align-items: center; gap: 1rem; }
        .admin-profile-avatar { display: grid; width: 5rem; height: 5rem; flex: 0 0 5rem; overflow: hidden; place-items: center; border: 3px solid rgba(255,255,255,.78); border-radius: 50%; background: rgba(255,255,255,.16); color: #fff; font-size: 1.8rem; font-weight: 800; box-shadow: 0 5px 18px rgba(7,29,57,.18); }
        .admin-profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .admin-profile-page [hidden] { display: none !important; }
        .admin-profile-eyebrow { color: #c8e6ff; font-size: .72rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
        .admin-profile-hero h1 { margin: .3rem 0 .35rem; color: #fff; font-size: clamp(1.55rem, 3vw, 2.1rem); font-weight: 800; }
        .admin-profile-hero p { margin: 0; color: rgba(255,255,255,.82); }
        .admin-profile-password-link { flex: 0 0 auto; min-height: 44px; padding: .65rem 1rem; border: 1px solid rgba(255,255,255,.85); border-radius: 10px; color: #183b65; font-weight: 750; box-shadow: 0 7px 16px rgba(8,19,45,.18); }
        .admin-profile-password-link:hover { background: #eaf3ff; color: #102e54; }
        .admin-profile-card { overflow: hidden; border: 1px solid #e5edf6; border-radius: 20px; background: #fff; box-shadow: 0 12px 32px rgba(24,54,91,.08); }
        .admin-profile-card-heading { display: flex; align-items: center; gap: .85rem; padding: 1.25rem clamp(1.1rem, 2.5vw, 1.75rem); border-bottom: 1px solid #eaf0f6; }
        .admin-profile-card-icon { display: grid; width: 2.75rem; height: 2.75rem; flex: 0 0 2.75rem; place-items: center; border-radius: 13px; background: #e8f3ff; color: #1769aa; }
        .admin-profile-card-heading h2 { margin: 0; color: #183b65; font-size: 1.05rem; font-weight: 800; }
        .admin-profile-card-heading p { margin: .2rem 0 0; color: #718198; font-size: .84rem; }
        .admin-profile-fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.1rem 1.25rem; padding: clamp(1.1rem, 2.5vw, 1.75rem); }
        .admin-profile-field { min-width: 0; }
        .admin-profile-field--wide { grid-column: 1 / -1; }
        .admin-profile-field label { display: block; margin-bottom: .4rem; color: #465a74; font-size: .82rem; font-weight: 750; }
        .admin-profile-field label > span:first-child { color: #d14343; }
        .admin-profile-optional { margin-left: .3rem; color: #8290a3 !important; font-size: .72rem; font-weight: 600; }
        .admin-profile-field .form-control { min-height: 46px; border-color: #d8e2ee; border-radius: 10px; color: #243b58; }
        .admin-profile-field .form-control:focus { border-color: #4784c2; box-shadow: 0 0 0 .2rem rgba(38,112,184,.14); }
        .admin-profile-upload { display: flex; align-items: center; gap: .9rem; padding: .9rem; border: 1px solid #e5edf6; border-radius: 14px; background: #f8fafc; }
        .admin-profile-upload-preview { display: grid; width: 3.75rem; height: 3.75rem; flex: 0 0 3.75rem; overflow: hidden; place-items: center; border-radius: 14px; background: #e8f3ff; color: #1769aa; font-size: 1.2rem; }
        .admin-profile-upload-preview img { width: 100%; height: 100%; object-fit: cover; }
        .admin-profile-upload-input { flex: 1; min-width: 0; }
        .admin-profile-upload-input small { display: block; margin-top: .35rem; color: #718198; }
        .admin-profile-actions { display: flex; justify-content: flex-end; padding: 1rem clamp(1.1rem, 2.5vw, 1.75rem); border-top: 1px solid #eaf0f6; background: #f8fafc; }
        .admin-profile-actions .btn { min-height: 44px; padding: .65rem 1rem; border-radius: 10px; font-weight: 750; box-shadow: 0 6px 14px rgba(21,82,153,.18); }
        @media (max-width: 640px) { .admin-profile-page { padding: .5rem 0 1.5rem; } .admin-profile-hero { align-items: stretch; flex-direction: column; } .admin-profile-identity { align-items: flex-start; } .admin-profile-avatar { width: 3.75rem; height: 3.75rem; flex-basis: 3.75rem; font-size: 1.4rem; } .admin-profile-password-link { width: 100%; } .admin-profile-fields { grid-template-columns: 1fr; gap: .9rem; } .admin-profile-field--wide { grid-column: auto; } .admin-profile-actions .btn { width: 100%; } }
    </style>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('image');
        const avatarPreview = document.getElementById('admin-profile-avatar-preview');
        const uploadPreview = document.getElementById('admin-profile-upload-preview');
        if (!input || !avatarPreview || !uploadPreview) return;

        let currentPreviewUrl = null;
        input.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (!file || !file.type.startsWith('image/')) return;

            if (currentPreviewUrl) URL.revokeObjectURL(currentPreviewUrl);
            currentPreviewUrl = URL.createObjectURL(file);
            [avatarPreview, uploadPreview].forEach(function (preview) {
                preview.src = currentPreviewUrl;
                preview.hidden = false;
            });

            document.getElementById('admin-profile-avatar-fallback')?.setAttribute('hidden', 'hidden');
            document.getElementById('admin-profile-upload-fallback')?.setAttribute('hidden', 'hidden');
        });
    });
</script>
