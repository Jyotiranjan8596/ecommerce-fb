@extends('frontend.dashboard.layouts.master')

@section('content')
    <div class="container-fluid px-2 px-md-3 py-3 sponsor-list-page">
        <section class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header border-0 px-4 py-4 sponsor-list-header">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <span class="sponsor-list-icon" aria-hidden="true">
                            <svg class="sponsor-list-referral-logo" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.5 12.5L15.5 8.5M8.5 11.5L15.5 15.5" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round"></path>
                                <circle cx="6" cy="12" r="3" stroke="currentColor" stroke-width="1.8"></circle>
                                <circle cx="18" cy="6" r="3" stroke="currentColor" stroke-width="1.8"></circle>
                                <circle cx="18" cy="18" r="3" stroke="currentColor" stroke-width="1.8"></circle>
                            </svg>
                        </span>
                        <div>
                            <h1 class="h4 mb-1 fw-bold sponsor-list-title">My Referral List</h1>
                            <p class="mb-0 sponsor-list-subtitle">Customers registered under your sponsorship.</p>
                        </div>
                    </div>
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-2 sponsor-list-actions">
                        <button type="button" class="btn btn-outline-light fw-bold rounded-pill px-3 flex-shrink-0 sponsor-list-share"
                            aria-label="Share referral link" title="Share referral link"
                            data-share-url="{{ $referral_link }}">
                            <svg class="sponsor-list-action-icon" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <circle cx="18" cy="5" r="3" stroke="currentColor" stroke-width="2"></circle>
                                <circle cx="6" cy="12" r="3" stroke="currentColor" stroke-width="2"></circle>
                                <circle cx="18" cy="19" r="3" stroke="currentColor" stroke-width="2"></circle>
                                <path d="M8.7 10.6l6.6-4.2M8.7 13.4l6.6 4.2" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round"></path>
                            </svg>
                            <span class="sponsor-list-action-label">Share Referral Link</span>
                        </button>
                        <a href="{{ route('user.add') }}" aria-label="Add User" title="Add User"
                            class="btn btn-primary fw-bold rounded-pill px-4 shadow flex-shrink-0 border border-white sponsor-list-add-user">
                            <svg class="sponsor-list-action-icon" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <circle cx="9" cy="8" r="4" stroke="currentColor" stroke-width="2"></circle>
                                <path d="M2.5 21v-2a6.5 6.5 0 0113 0v2h-13zM19 8v8M15 12h8" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span class="sponsor-list-action-label">Add User</span>
                        </a>
                        <span class="badge rounded-pill px-3 py-2 sponsor-list-count"
                            aria-label="{{ $sponcer->count() }} {{ \Illuminate\Support\Str::plural('member', $sponcer->count()) }}">
                            <svg class="sponsor-list-count-icon" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <circle cx="9" cy="8" r="3" stroke="currentColor" stroke-width="2"></circle>
                                <path d="M3 20v-1a6 6 0 0112 0v1H3zM16 5.5a3 3 0 010 5.8M18 14a4 4 0 013 4v1h-3"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span>{{ $sponcer->count() }}</span>
                            <span class="sponsor-list-count-label">{{ \Illuminate\Support\Str::plural('member', $sponcer->count()) }}</span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="sponsor-list-share-status px-4" role="status" aria-live="polite" hidden></div>

            <div class="card-body p-3 p-md-4">
                @if ($sponcer->isEmpty())
                    <div class="sponsor-list-empty">
                        <span class="sponsor-list-empty-icon" aria-hidden="true">
                            <i class="fa fa-user-plus"></i>
                        </span>
                        <h2 class="h5 mb-1">No sponsored members yet</h2>
                        <p class="mb-0">New members who register with your sponsorship will appear here.</p>
                    </div>
                @else
                    <div class="table-responsive sponsor-list-table-wrap">
                        <table class="table align-middle mb-0 sponsor-list-table">
                            <thead>
                                <tr>
                                    <th scope="col">Sl.No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Picture</th>
                                    <th scope="col">Mobile number</th>
                                    <th scope="col">Sponsor date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sponcer as $key => $data)
                                    @php
                                        $member = $data;
                                        $memberName = $member->name ?? 'Member';
                                    @endphp
                                    <tr>
                                        <td data-label="Sl.No">{{ $key + 1 }}</td>
                                        <td data-label="Name" class="sponsor-member-name">
                                            {{ $member->name ?? 'N/A' }}
                                            @if ($member->is_prime_member)
                                                <span class="prime-badge ms-2">Prime</span>
                                            @endif
                                        </td>
                                        <td data-label="Picture">
                                            @if ($member && $member->image)
                                                <img src="{{ asset('images/' . $member->image) }}"
                                                    alt="Profile picture of {{ $memberName }}" class="sponsor-member-avatar"
                                                    loading="lazy"
                                                    onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
                                                <span class="sponsor-member-avatar sponsor-member-initial" aria-hidden="true" hidden>
                                                    {{ strtoupper(substr($memberName, 0, 1)) }}
                                                </span>
                                            @else
                                                <span class="sponsor-member-avatar sponsor-member-initial" aria-hidden="true">
                                                    {{ strtoupper(substr($memberName, 0, 1)) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td data-label="Mobile number">
                                            @if ($member && $member->mobilenumber)
                                                <a class="sponsor-member-phone"
                                                    href="tel:{{ $member->mobilenumber }}">{{ $member->mobilenumber }}</a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td data-label="Sponsor date">{{ $data->created_on }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <style>
        .sponsor-list-page {
            color: #172033;
        }

        .sponsor-list-page .sponsor-list-add-user {
            color: #fff !important;
        }

        .sponsor-list-page .sponsor-list-share {
            color: #fff !important;
            border: 1px solid #fff !important;
        }

        .sponsor-list-page .sponsor-list-add-user:hover,
        .sponsor-list-page .sponsor-list-add-user:focus {
            color: #000 !important;
        }

        .sponsor-list-page .sponsor-list-share-status:not([hidden]) {
            padding-bottom: .75rem;
            color: #fff;
        }

        .sponsor-list-page .sponsor-list-header {
            color: #fff;
            background: linear-gradient(115deg, #0f766e 0%, #087f8c 55%, #145da0 100%);
        }

        .sponsor-list-page .sponsor-list-icon {
            display: grid;
            width: 3.25rem;
            height: 3.25rem;
            flex: 0 0 3.25rem;
            place-items: center;
            border: 1px solid rgba(255, 255, 255, .38);
            border-radius: 1rem;
            background: rgba(255, 255, 255, .15);
            color: #fff;
            font-size: 1.25rem;
        }

        .sponsor-list-page .sponsor-list-referral-logo {
            width: 1.65rem;
            height: 1.65rem;
        }

        .sponsor-list-page .sponsor-list-eyebrow {
            color: #d5fff5;
            letter-spacing: .08em;
        }

        .sponsor-list-page .sponsor-list-title {
            color: #fff;
            text-shadow: 0 1px 2px rgba(0, 0, 0, .18);
        }

        .sponsor-list-page .sponsor-list-subtitle {
            color: #e3f4f4;
            font-size: .92rem;
        }

        .sponsor-list-page .sponsor-list-count {
            border: 1px solid rgba(255, 255, 255, .45);
            background: rgba(255, 255, 255, .15);
            color: #fff;
            font-size: .88rem;
        }

        .sponsor-list-page .sponsor-list-count-icon {
            display: none;
        }

        .sponsor-list-page .sponsor-list-action-icon {
            width: 1.15rem;
            height: 1.15rem;
            flex: 0 0 auto;
            vertical-align: middle;
        }

        .sponsor-list-page .sponsor-list-table-wrap {
            overflow: auto;
            border: 1px solid #e4ebf1;
            border-radius: .85rem;
        }

        .sponsor-list-page .sponsor-list-table {
            min-width: 680px;
            color: #263449;
        }

        .sponsor-list-page .sponsor-list-table thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            padding: .9rem 1rem;
            border-bottom: 1px solid #dfe8ee;
            background: #f0f7f7;
            color: #174b54;
            font-size: .74rem;
            font-weight: 800;
            letter-spacing: .05em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .sponsor-list-page .sponsor-list-table tbody td {
            padding: .8rem 1rem;
            border-color: #edf1f5;
            font-size: .92rem;
            vertical-align: middle;
        }

        .sponsor-list-page .sponsor-list-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .sponsor-list-page .sponsor-list-table tbody tr:hover {
            background: #f8fbfc;
        }

        .sponsor-list-page .sponsor-member-name {
            color: #17324d;
            font-weight: 750;
        }

        .sponsor-list-page .sponsor-member-avatar {
            display: inline-grid;
            width: 2.8rem;
            height: 2.8rem;
            place-items: center;
            border: 2px solid #fff;
            border-radius: 50%;
            background: #eaf6f4;
            box-shadow: 0 0 0 1px #d7e8e7;
            color: #087f8c;
            font-size: 1rem;
            font-weight: 800;
            object-fit: cover;
        }

        .sponsor-list-page .sponsor-member-avatar[hidden] {
            display: none !important;
        }

        .sponsor-list-page .sponsor-member-phone {
            color: #075e59;
            font-weight: 700;
            text-decoration: none;
        }

        .sponsor-list-page .sponsor-member-phone:hover {
            color: #087f8c;
            text-decoration: underline;
            text-underline-offset: .2em;
        }

        .sponsor-list-page .sponsor-list-empty {
            display: flex;
            min-height: 250px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            color: #66768a;
            text-align: center;
        }

        .sponsor-list-page .sponsor-list-empty h2 {
            color: #17324d;
        }

        .sponsor-list-page .sponsor-list-empty-icon {
            display: grid;
            width: 3.5rem;
            height: 3.5rem;
            margin-bottom: 1rem;
            place-items: center;
            border-radius: 50%;
            background: #eaf6f4;
            color: #087f8c;
            font-size: 1.35rem;
        }

        @media (max-width: 767.98px) {
            .sponsor-list-page .sponsor-list-header {
                padding: 1.15rem !important;
            }

            .sponsor-list-page .sponsor-list-actions {
                flex-direction: row !important;
                justify-content: flex-start;
                gap: .65rem !important;
            }

            .sponsor-list-page .sponsor-list-share,
            .sponsor-list-page .sponsor-list-add-user {
                display: inline-flex;
                width: 2.85rem;
                height: 2.85rem;
                align-items: center;
                justify-content: center;
                padding: 0 !important;
                border-radius: 50% !important;
            }

            .sponsor-list-page .sponsor-list-share {
                color: #fff !important;
                border: 1px solid #fff !important;
                background: rgba(255, 255, 255, .16) !important;
            }

            .sponsor-list-page .sponsor-list-share .sponsor-list-action-icon,
            .sponsor-list-page .sponsor-list-add-user .sponsor-list-action-icon {
                width: 1.3rem;
                height: 1.3rem;
            }

            .sponsor-list-page .sponsor-list-action-label,
            .sponsor-list-page .sponsor-list-count-label {
                display: none;
            }

            .sponsor-list-page .sponsor-list-count {
                display: inline-flex;
                min-width: 2.85rem;
                height: 2.85rem;
                align-items: center;
                justify-content: center;
                gap: .2rem;
                padding: 0 .45rem !important;
                font-size: .82rem;
            }

            .sponsor-list-page .sponsor-list-count-icon {
                display: inline-block;
                width: .9rem;
                height: .9rem;
            }

            .sponsor-list-page .sponsor-list-table-wrap {
                overflow: visible;
                border: 0;
            }

            .sponsor-list-page .sponsor-list-table {
                min-width: 0;
                border-collapse: separate;
                border-spacing: 0 .7rem;
            }

            .sponsor-list-page .sponsor-list-table thead {
                display: none;
            }

            .sponsor-list-page .sponsor-list-table tbody {
                display: block;
            }

            .sponsor-list-page .sponsor-list-table tbody tr {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                overflow: hidden;
                border: 1px solid #e3ebf0;
                border-radius: .85rem;
                background: #fff;
                box-shadow: 0 .25rem .8rem rgba(23, 32, 51, .045);
            }

            .sponsor-list-page .sponsor-list-table tbody td {
                min-width: 0;
                padding: .75rem .85rem;
                border: 0;
                overflow-wrap: anywhere;
            }

            .sponsor-list-page .sponsor-list-table tbody td::before {
                display: block;
                margin-bottom: .22rem;
                color: #64748b;
                content: attr(data-label);
                font-size: .67rem;
                font-weight: 800;
                letter-spacing: .04em;
                text-transform: uppercase;
            }

            .sponsor-list-page .sponsor-list-table tbody td[data-label="Sl.No"] {
                display: none;
            }

            .sponsor-list-page .sponsor-list-table tbody td[data-label="Name"] {
                grid-column: 1 / -1;
                background: #f0f7f7;
                color: #17324d;
                font-size: 1rem;
            }

            .sponsor-list-page .sponsor-list-table tbody td[data-label="Name"]::before {
                color: #087f8c;
            }

            .sponsor-list-page .sponsor-list-table tbody td[data-label="Picture"] {
                display: flex;
                align-items: center;
                gap: .6rem;
            }

            .sponsor-list-page .sponsor-list-table tbody td[data-label="Picture"]::before {
                content: "Profile";
            }

            .sponsor-list-page .sponsor-member-avatar {
                width: 2.4rem;
                height: 2.4rem;
            }
        }

        @media (max-width: 420px) {
            .sponsor-list-page .sponsor-list-icon {
                width: 2.8rem;
                height: 2.8rem;
                flex-basis: 2.8rem;
            }

            .sponsor-list-page .sponsor-list-title {
                font-size: 1.2rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const shareButton = document.querySelector('.sponsor-list-share');
            const shareStatus = document.querySelector('.sponsor-list-share-status');

            if (!shareButton || !shareStatus) {
                return;
            }

            const referralUrl = shareButton.dataset.shareUrl;

            async function copyReferralLink() {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(referralUrl);
                    return;
                }

                const input = document.createElement('textarea');
                input.value = referralUrl;
                input.setAttribute('readonly', '');
                input.style.position = 'fixed';
                input.style.opacity = '0';
                document.body.appendChild(input);
                input.select();
                const copied = document.execCommand('copy');
                input.remove();

                if (!copied) {
                    throw new Error('Copy command failed');
                }
            }

            shareButton.addEventListener('click', async function () {
                shareStatus.hidden = true;

                try {
                    if (navigator.share) {
                        await navigator.share({
                            title: 'Join Freebazar',
                            text: 'Join Freebazar using my referral link:',
                            url: referralUrl
                        });
                        shareStatus.textContent = 'Referral link shared.';
                    } else {
                        await copyReferralLink();
                        shareStatus.textContent = 'Referral link copied to clipboard.';
                    }
                    shareStatus.hidden = false;
                } catch (error) {
                    if (error.name === 'AbortError') {
                        return;
                    }

                    try {
                        await copyReferralLink();
                        shareStatus.textContent = 'Sharing is unavailable. Referral link copied to clipboard.';
                    } catch (copyError) {
                        shareStatus.textContent = 'Unable to share or copy the referral link. Please try again.';
                    }
                    shareStatus.hidden = false;
                }
            });
        });
    </script>
@endsection
