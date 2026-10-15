@extends('frontend.dashboard.layouts.master')

@section('content')
    <div class="prime-profile-page">
        <section class="prime-profile-hero">
            <a class="prime-profile-back" href="{{ route('user.prime.dashboard') }}"><i class="fas fa-arrow-left" aria-hidden="true"></i> Prime dashboard</a>
            <span class="prime-badge prime-badge--large"><i class="fas fa-crown" aria-hidden="true"></i> Prime member</span>
            <div class="prime-profile-identity">
                <span class="prime-profile-avatar" role="img" aria-label="Profile photo of {{ $user->name }}">
                    @if ($user->image)
                        <img src="{{ asset('images/' . $user->image) }}" alt="Profile photo of {{ $user->name }}"
                            onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
                        <span hidden aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user->name ?: '?', 0, 1)) }}</span>
                    @else
                        <span aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user->name ?: '?', 0, 1)) }}</span>
                    @endif
                </span>
                <div>
                    <h1>{{ \Illuminate\Support\Str::title($user->name) }}</h1>
                    <p>Prime customer profile · User ID {{ $user->user_id ?: '—' }}</p>
                </div>
            </div>
            <a class="prime-button" href="{{ route('user.edit.profile') }}">Edit account details</a>
        </section>

        <section class="prime-profile-grid">
            <article class="prime-profile-card prime-profile-card--membership">
                <span class="prime-panel__eyebrow">Membership</span>
                <h2>Prime member</h2>
                <p>You qualify with {{ number_format($user->prime_referral_count) }} direct customer referrals.</p>
                <dl>
                    <div><dt>Prime since</dt><dd>{{ $primeSince?->format('d M Y') ?? '—' }}</dd></div>
                    <div><dt>Referral threshold</dt><dd>{{ \App\Models\User::PRIME_REFERRAL_THRESHOLD }} customers</dd></div>
                    <div><dt>Membership</dt><dd><span class="prime-status-pill">Active</span></dd></div>
                </dl>
            </article>

            <article class="prime-profile-card">
                <span class="prime-panel__eyebrow">Personal details</span>
                <h2>Profile information</h2>
                <dl>
                    <div><dt>Name</dt><dd>{{ $user->name ?: 'Not provided' }}</dd></div>
                    <div><dt>Sponsor user ID</dt><dd>{{ $user->sponsorUser?->user_id ?: '—' }}</dd></div>
                    <div><dt>Email</dt><dd>{{ $user->email ?: 'Not provided' }}</dd></div>
                    <div><dt>Mobile number</dt><dd>{{ $user->mobilenumber ?: 'Not provided' }}</dd></div>
                    <div><dt>Address</dt><dd>{{ $user->address ?: 'Not provided' }}</dd></div>
                    <div><dt>City and state</dt><dd>{{ collect([$user->city, $user->state])->filter()->implode(', ') ?: 'Not provided' }}</dd></div>
                </dl>
            </article>

            <article class="prime-profile-card prime-profile-card--network">
                <span class="prime-panel__eyebrow">Customer family</span>
                <h2>Your referrals</h2>
                <p>{{ number_format($user->prime_referral_count) }} direct customers are linked to your account.</p>
                <a class="prime-button" href="{{ route('user.sponsor.list') }}">Open referral list <span aria-hidden="true">&rarr;</span></a>
            </article>
        </section>
    </div>
@endsection
