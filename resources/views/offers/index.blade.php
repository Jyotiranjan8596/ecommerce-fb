@extends($layout)

@section('content')
    <style>
        .available-offers { max-width:1120px; margin:0 auto; padding:1rem 1rem 2.5rem; color:#193b5a; }
        .available-offers-hero { position:relative; overflow:hidden; margin-bottom:1.3rem; padding:1.7rem; border:1px solid #d8e7f5; border-radius:20px; background:linear-gradient(120deg,#123b72,#1769aa 70%,#2783bd); color:#fff; box-shadow:0 14px 30px rgba(18,59,114,.14); }
        .available-offers-hero::after { position:absolute; top:-55px; right:-25px; width:210px; height:210px; border:30px solid rgba(255,255,255,.08); border-radius:50%; content:""; }
        .available-offers-hero h1 { position:relative; z-index:1; margin:.3rem 0 .4rem; color:#fff; font-size:clamp(1.55rem,3vw,2.15rem); font-weight:850; }
        .available-offers-hero p { position:relative; z-index:1; max-width:620px; margin:0; color:rgba(255,255,255,.82); }
        .available-offers-kicker { position:relative; z-index:1; font-size:.72rem; font-weight:800; letter-spacing:.12em; text-transform:uppercase; color:#b9e2ff; }
        .available-offers-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:1rem; }
        .available-offer-card { overflow:hidden; border:1px solid #e0eaf3; border-radius:17px; background:#fff; box-shadow:0 9px 24px rgba(25,55,85,.055); }
        .available-offer-card__top { display:flex; align-items:flex-start; justify-content:space-between; gap:.8rem; padding:1.15rem 1.2rem .9rem; border-bottom:1px solid #edf2f6; }
        .available-offer-card__icon { display:grid; flex:0 0 44px; width:44px; height:44px; place-items:center; border-radius:13px; background:#eef7ff; color:#2075b5; font-size:1.1rem; }
        .available-offer-card__title { flex:1; min-width:0; }
        .available-offer-card__title h2 { margin:0; color:#193b5a; font-size:1.05rem; font-weight:820; overflow-wrap:anywhere; }
        .available-offer-card__date { display:block; margin-top:.28rem; color:#788a9c; font-size:.75rem; }
        .available-offer-card__body { padding:1rem 1.2rem 1.2rem; }
        .available-offer-card__description { color:#526a80; font-size:.9rem; line-height:1.65; white-space:pre-line; }
        .available-offer-card__section { margin-top:.9rem; padding:.8rem .9rem; border-radius:11px; background:#f7fafc; }
        .available-offer-card__section strong { display:block; margin-bottom:.25rem; color:#36566f; font-size:.76rem; font-weight:800; letter-spacing:.03em; text-transform:uppercase; }
        .available-offer-card__section p { margin:0; color:#5d7185; font-size:.84rem; line-height:1.55; white-space:pre-line; }
        .available-offer-card__terms { border:1px solid #f0f1e9; background:#fbfbf6; }
        .available-offer-cta { display:inline-flex; align-items:center; gap:.45rem; margin-top:1rem; padding:.65rem .9rem; border-radius:9px; background:#1769aa; color:#fff; font-size:.84rem; font-weight:750; text-decoration:none; }
        .available-offer-cta:hover { background:#12558d; color:#fff; }
        .available-offers-empty { padding:3.3rem 1.5rem; border:1px solid #e0eaf3; border-radius:18px; background:#fff; text-align:center; box-shadow:0 9px 24px rgba(25,55,85,.045); }
        .available-offers-empty i { display:grid; width:62px; height:62px; margin:0 auto .95rem; place-items:center; border-radius:19px; background:#eef7ff; color:#2474b8; font-size:1.35rem; }
        .available-offers-empty h2 { color:#193b5a; font-size:1.08rem; font-weight:820; }
        .available-offers-empty p { max-width:430px; margin:.3rem auto 0; color:#728398; font-size:.88rem; }
        @media(max-width:700px) { .available-offers-grid { grid-template-columns:1fr; } .available-offers-hero { padding:1.25rem; } }
    </style>
    <main class="available-offers">
        <header class="available-offers-hero">
            <span class="available-offers-kicker">Selected for you</span>
            <h1>Offers &amp; opportunities</h1>
            <p>Explore the latest benefits, promotions and business updates available to your account.</p>
        </header>

        @if ($offers->isNotEmpty())
            <section class="available-offers-grid" aria-label="Available offers">
                @foreach ($offers as $offer)
                    <article class="available-offer-card">
                        <header class="available-offer-card__top">
                            <span class="available-offer-card__icon"><i class="fas fa-sparkles" aria-hidden="true"></i></span>
                            <div class="available-offer-card__title">
                                <h2>{{ $offer->title }}</h2>
                                <span class="available-offer-card__date">
                                    @if ($offer->ends_at)
                                        Available through {{ $offer->ends_at->format('M j, Y') }}
                                    @elseif ($offer->starts_at)
                                        Available since {{ $offer->starts_at->format('M j, Y') }}
                                    @else
                                        Available now
                                    @endif
                                </span>
                            </div>
                        </header>
                        <div class="available-offer-card__body">
                            <div class="available-offer-card__description">{{ $offer->description }}</div>
                            @if ($offer->redemption_instructions)
                                <section class="available-offer-card__section">
                                    <strong>How to claim</strong><p>{{ $offer->redemption_instructions }}</p>
                                </section>
                            @endif
                            @if ($offer->terms)
                                <section class="available-offer-card__section available-offer-card__terms">
                                    <strong>Terms &amp; conditions</strong><p>{{ $offer->terms }}</p>
                                </section>
                            @endif
                            @if ($offer->cta_url && $offer->cta_label)
                                <a class="available-offer-cta" href="{{ $offer->cta_url }}" target="_blank" rel="noopener noreferrer">
                                    {{ $offer->cta_label }} <i class="fas fa-arrow-up-right-from-square" aria-hidden="true"></i>
                                </a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </section>
            <div class="mt-4">{{ $offers->links() }}</div>
        @else
            <section class="available-offers-empty">
                <i class="fas fa-tags" aria-hidden="true"></i>
                <h2>No current offers</h2>
                <p>There are no published offers available for your account right now. Check back soon for new benefits and opportunities.</p>
            </section>
        @endif
    </main>
@endsection
