@extends('layouts.master')

@section('content')
    <style>
        .offer-admin-page { max-width: 1200px; margin: 0 auto; padding: 1rem 1rem 2.5rem; color: #18324d; }
        .offer-admin-hero { display:flex; align-items:center; justify-content:space-between; gap:1.5rem; margin-bottom:1.25rem; padding:1.5rem 1.7rem; border-radius:20px; background:linear-gradient(120deg,#123b72,#1769aa 68%,#2783bd); color:#fff; box-shadow:0 14px 30px rgba(18,59,114,.15); }
        .offer-admin-hero h1 { margin:.25rem 0 .4rem; color:#fff; font-size:clamp(1.45rem,3vw,2rem); font-weight:800; }
        .offer-admin-hero p { margin:0; color:rgba(255,255,255,.82); }
        .offer-admin-eyebrow { font-size:.72rem; font-weight:800; letter-spacing:.12em; text-transform:uppercase; color:#b9e2ff; }
        .offer-hero-action { display:inline-flex; align-items:center; gap:.5rem; padding:.7rem 1rem; border-radius:10px; background:#fff; color:#145183; font-weight:800; text-decoration:none; white-space:nowrap; }
        .offer-hero-action:hover { background:#eaf5ff; color:#123b72; }
        .offer-stats { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:.9rem; margin-bottom:1.2rem; }
        .offer-stat { display:flex; align-items:center; gap:.85rem; padding:1rem 1.1rem; border:1px solid #e0eaf3; border-radius:15px; background:#fff; box-shadow:0 8px 20px rgba(25,55,85,.04); }
        .offer-stat-icon { display:grid; width:42px; height:42px; place-items:center; border-radius:12px; background:#edf6ff; color:#2474b8; }
        .offer-stat strong { display:block; color:#18324d; font-size:1.25rem; line-height:1.1; }
        .offer-stat span:last-child { display:block; margin-top:.2rem; color:#708399; font-size:.78rem; }
        .offer-table-panel { overflow:hidden; border:1px solid #e0eaf3; border-radius:17px; background:#fff; box-shadow:0 10px 28px rgba(25,55,85,.05); }
        .offer-table-heading { display:flex; align-items:center; justify-content:space-between; padding:1.1rem 1.3rem; border-bottom:1px solid #eaf0f5; }
        .offer-table-heading h2 { margin:0; color:#18324d; font-size:1rem; font-weight:800; }
        .offer-table-heading p { margin:.2rem 0 0; color:#718398; font-size:.82rem; }
        .offer-table { margin:0; }
        .offer-table th { padding:.8rem 1rem; border-bottom:1px solid #e8eef4; background:#f7fafc; color:#6c7f92; font-size:.72rem; letter-spacing:.06em; text-transform:uppercase; white-space:nowrap; }
        .offer-table td { padding:1rem; border-color:#eef2f6; color:#344b62; vertical-align:middle; }
        .offer-title-cell { max-width:310px; }
        .offer-title-cell strong { display:block; color:#193b5a; font-size:.92rem; }
        .offer-title-cell span { display:-webkit-box; overflow:hidden; margin-top:.25rem; color:#718398; font-size:.8rem; -webkit-box-orient:vertical; -webkit-line-clamp:2; }
        .offer-audience-badge { display:inline-block; margin:.12rem .15rem .12rem 0; padding:.28rem .5rem; border-radius:20px; background:#eff6fc; color:#36627f; font-size:.7rem; font-weight:750; white-space:nowrap; }
        .offer-status { display:inline-flex; align-items:center; gap:.35rem; padding:.32rem .55rem; border-radius:20px; font-size:.72rem; font-weight:800; }
        .offer-status-live { background:#e8f7ef; color:#16804b; }
        .offer-status-off { background:#f0f2f5; color:#6f7d8b; }
        .offer-date { min-width:120px; color:#536a80; font-size:.8rem; white-space:nowrap; }
        .offer-row-actions { display:flex; align-items:center; gap:.4rem; }
        .offer-row-actions .btn { display:inline-flex; align-items:center; gap:.35rem; border-radius:8px; font-size:.78rem; font-weight:700; }
        .offer-empty { padding:3.3rem 1.5rem; text-align:center; }
        .offer-empty i { display:grid; width:58px; height:58px; margin:0 auto .9rem; place-items:center; border-radius:18px; background:#edf6ff; color:#2474b8; font-size:1.3rem; }
        .offer-empty h3 { color:#193b5a; font-size:1.05rem; font-weight:800; }
        .offer-empty p { max-width:420px; margin:.3rem auto 1.1rem; color:#718398; font-size:.87rem; }
        @media(max-width:700px) { .offer-admin-hero { align-items:flex-start; flex-direction:column; padding:1.2rem; } .offer-stats { grid-template-columns:1fr; gap:.55rem; } .offer-stat { padding:.75rem .9rem; } .offer-table-heading { align-items:flex-start; flex-direction:column; gap:.6rem; } .offer-row-actions { flex-direction:column; align-items:stretch; } }
    </style>
    <main class="offer-admin-page">
        <header class="offer-admin-hero">
            <div><span class="offer-admin-eyebrow">Campaigns &amp; promotions</span><h1>Offer Management</h1><p>Create flexible business offers and publish them to selected customer and POS groups.</p></div>
            <a class="offer-hero-action" href="{{ route('admin.offers.create') }}"><i class="fas fa-plus" aria-hidden="true"></i>Create offer</a>
        </header>

        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif

        <section class="offer-stats" aria-label="Offer summary">
            <div class="offer-stat"><span class="offer-stat-icon"><i class="fas fa-tags" aria-hidden="true"></i></span><div><strong>{{ $offers->total() }}</strong><span>Total offers</span></div></div>
            <div class="offer-stat"><span class="offer-stat-icon"><i class="fas fa-bullhorn" aria-hidden="true"></i></span><div><strong>{{ number_format($activeCount) }}</strong><span>Published offers</span></div></div>
            <div class="offer-stat"><span class="offer-stat-icon"><i class="fas fa-calendar-alt" aria-hidden="true"></i></span><div><strong>{{ number_format($scheduledCount) }}</strong><span>Scheduled to start</span></div></div>
        </section>

        <section class="offer-table-panel">
            <header class="offer-table-heading"><div><h2>All offers</h2><p>Manage offer details, audiences, timing and publishing status.</p></div></header>
            @if ($offers->isNotEmpty())
                <div class="table-responsive">
                    <table class="table offer-table">
                        <thead><tr><th>Offer</th><th>Audience</th><th>Schedule</th><th>Status</th><th>Actions</th></tr></thead>
                        <tbody>
                            @foreach ($offers as $offer)
                                <tr>
                                    <td class="offer-title-cell"><strong>{{ $offer->title }}</strong><span>{{ $offer->description }}</span></td>
                                    <td>
                                        @foreach ($offer->audiences ?? [] as $audience)
                                            <span class="offer-audience-badge">{{ \App\Models\Offer::AUDIENCES[$audience] ?? $audience }}</span>
                                        @endforeach
                                    </td>
                                    <td class="offer-date">
                                        @if ($offer->starts_at || $offer->ends_at)
                                            {{ $offer->starts_at?->format('M j, Y') ?? 'Now' }}<br><span class="text-muted">to</span> {{ $offer->ends_at?->format('M j, Y') ?? 'No end date' }}
                                        @else
                                            Anytime
                                        @endif
                                    </td>
                                    <td>
                                        <span class="offer-status {{ $offer->is_active ? 'offer-status-live' : 'offer-status-off' }}">
                                            <i class="fas {{ $offer->is_active ? 'fa-circle-check' : 'fa-circle-pause' }}" aria-hidden="true"></i>
                                            {{ $offer->is_active ? 'Published' : 'Unpublished' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="offer-row-actions">
                                            <a href="{{ route('admin.offers.edit', $offer) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-pen" aria-hidden="true"></i>Edit</a>
                                            <form action="{{ route('admin.offers.destroy', $offer) }}" method="POST" onsubmit="return confirm('Delete this offer? This cannot be undone.');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash" aria-hidden="true"></i>Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-3 py-3">{{ $offers->links() }}</div>
            @else
                <div class="offer-empty">
                    <i class="fas fa-tags" aria-hidden="true"></i>
                    <h3>No offers yet</h3>
                    <p>Create your first offer and choose whether it should be available to POS, Prime customers, non-Prime customers, or a combination.</p>
                    <a class="btn offer-primary-button" href="{{ route('admin.offers.create') }}">Create your first offer</a>
                </div>
            @endif
        </section>
    </main>
@endsection
