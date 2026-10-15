@extends('frontend.dashboard.layouts.master')

@section('content')
    <div class="prime-dashboard-page">
        <section class="prime-hero">
            <div class="prime-hero__copy">
                <span class="prime-badge prime-badge--large"><i class="fas fa-crown" aria-hidden="true"></i> Prime member</span>
                <h1>Welcome to your Prime dashboard, {{ \Illuminate\Support\Str::title($user->name) }}</h1>
                <p>Your customer network has reached the Prime milestone. Review your profile and referral activity here.</p>
                <div class="prime-hero__actions">
                    <a class="prime-button" href="{{ route('user.prime.profile') }}">View Prime profile <span aria-hidden="true">&rarr;</span></a>
                    <a class="prime-button prime-button--light" href="{{ route('user.sponsor.list') }}">View all referrals</a>
                </div>
            </div>
            <div class="prime-hero__mark" aria-hidden="true"><i class="fas fa-crown"></i></div>
        </section>

        <section class="prime-stats" aria-label="Prime membership statistics">
            <article class="prime-stat-card">
                <span class="prime-stat-card__icon"><i class="fas fa-users" aria-hidden="true"></i></span>
                <span class="prime-stat-card__label">Direct referrals</span>
                <strong>{{ number_format($user->prime_referral_count) }}</strong>
                <small>Prime threshold: {{ \App\Models\User::PRIME_REFERRAL_THRESHOLD }}</small>
            </article>
            <article class="prime-stat-card">
                <span class="prime-stat-card__icon"><i class="fas fa-calendar-check" aria-hidden="true"></i></span>
                <span class="prime-stat-card__label">Prime since</span>
                <strong class="prime-stat-card__date">{{ $primeSince?->format('d M Y') ?? '—' }}</strong>
                <small>When your tenth direct referral joined</small>
            </article>
            <article class="prime-stat-card prime-stat-card--status">
                <span class="prime-stat-card__icon"><i class="fas fa-award" aria-hidden="true"></i></span>
                <span class="prime-stat-card__label">Membership status</span>
                <strong>Active</strong>
                <small>Prime member</small>
            </article>
        </section>

        <section class="prime-panel">
            <div class="prime-panel__heading">
                <div>
                    <span class="prime-panel__eyebrow">Your network</span>
                    <h2>Recent referrals</h2>
                </div>
                <a href="{{ route('user.sponsor.list') }}">See all <span aria-hidden="true">&rarr;</span></a>
            </div>
            @if ($referrals->isNotEmpty())
                <div class="prime-referral-list">
                    @foreach ($referrals as $referral)
                        <div class="prime-referral-row">
                            <span class="prime-referral-avatar" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($referral->name ?: '?', 0, 1)) }}</span>
                            <div class="prime-referral-row__person">
                                <strong>{{ \Illuminate\Support\Str::title($referral->name ?: 'Name unavailable') }}
                                    @if ($referral->is_prime_member)
                                        <span class="prime-badge ms-1">Prime</span>
                                    @endif
                                </strong>
                                <small>User ID: {{ $referral->user_id ?: '—' }}</small>
                            </div>
                            <time datetime="{{ optional($referral->created_at)->toDateString() }}">{{ optional($referral->created_at)->format('d M Y') ?: '—' }}</time>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="prime-empty">Your referral activity will appear here.</div>
            @endif
        </section>
    </div>
@endsection
