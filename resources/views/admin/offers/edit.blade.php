@extends('layouts.master')

@section('content')
    <style>
        .offer-admin-page { max-width: 1100px; margin: 0 auto; padding: 1rem 1rem 2.5rem; color: #18324d; }
        .offer-admin-hero { display:flex; align-items:center; justify-content:space-between; gap:1.5rem; margin-bottom:1.25rem; padding:1.5rem 1.7rem; border-radius:20px; background:linear-gradient(120deg,#123b72,#1769aa 68%,#2783bd); color:#fff; box-shadow:0 14px 30px rgba(18,59,114,.15); }
        .offer-admin-hero h1 { margin:.25rem 0 .4rem; color:#fff; font-size:clamp(1.45rem,3vw,2rem); font-weight:800; }
        .offer-admin-hero p { margin:0; color:rgba(255,255,255,.82); }
        .offer-admin-eyebrow { font-size:.72rem; font-weight:800; letter-spacing:.12em; text-transform:uppercase; color:#b9e2ff; }
        .offer-admin-panel { padding:1.5rem; border:1px solid #e0eaf3; border-radius:18px; background:#fff; box-shadow:0 10px 28px rgba(25,55,85,.06); }
        .offer-form-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:1.15rem; }
        .offer-field-wide { grid-column:1/-1; }
        .offer-form .form-label { margin-bottom:.45rem; color:#28445f; font-size:.88rem; font-weight:750; }
        .offer-form .form-control { min-height:44px; border-color:#d9e4ee; border-radius:10px; }
        .offer-form textarea.form-control { min-height:unset; }
        .offer-field-hint,.offer-form .form-text { color:#728398; font-size:.8rem; }
        .offer-field-hint { margin:-.15rem 0 .75rem; }
        .offer-audience-options { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:.65rem; }
        .offer-audience-option { cursor:pointer; }
        .offer-audience-option input { position:absolute; opacity:0; }
        .offer-audience-option span { display:flex; align-items:center; gap:.55rem; min-height:50px; padding:.7rem .8rem; border:1px solid #d9e4ee; border-radius:11px; color:#536a80; font-size:.87rem; font-weight:700; transition:.16s ease; }
        .offer-audience-option i { color:#2474b8; }
        .offer-audience-option input:checked + span { border-color:#287ab6; background:#eef7ff; color:#145183; box-shadow:0 0 0 2px rgba(40,122,182,.08); }
        .offer-audience-option input:focus-visible + span { outline:3px solid rgba(40,122,182,.25); }
        .offer-active-toggle { display:flex; align-items:center; gap:.75rem; padding:1rem; border:1px solid #d9e9df; border-radius:12px; background:#f5fbf7; cursor:pointer; }
        .offer-active-toggle input { width:1.1rem; height:1.1rem; accent-color:#16834f; }
        .offer-active-toggle span { display:flex; flex-direction:column; color:#244a37; }
        .offer-active-toggle small { margin-top:.15rem; color:#728398; }
        .offer-form-actions { display:flex; justify-content:flex-end; gap:.65rem; margin-top:1.4rem; padding-top:1.1rem; border-top:1px solid #edf1f5; }
        .offer-primary-button { border:0; border-radius:9px; background:#1769aa; color:#fff; font-weight:750; }
        .offer-primary-button:hover { background:#12558d; color:#fff; }
        @media(max-width:650px) { .offer-admin-hero { align-items:flex-start; padding:1.2rem; } .offer-admin-panel { padding:1rem; } .offer-form-grid,.offer-audience-options { grid-template-columns:1fr; } .offer-field-wide { grid-column:auto; } }
    </style>
    <main class="offer-admin-page">
        <header class="offer-admin-hero">
            <div><span class="offer-admin-eyebrow">Offer management</span><h1>Edit offer</h1><p>Update “{{ $offer->title }}” and choose who can see it.</p></div>
            <i class="fas fa-pen-to-square fa-2x d-none d-sm-block" aria-hidden="true"></i>
        </header>
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">Please review the highlighted fields and try again.</div>
        @endif
        <section class="offer-admin-panel">
            @include('admin.offers._form', [
                'offer' => $offer,
                'action' => route('admin.offers.update', $offer),
                'method' => 'PUT',
                'submitLabel' => 'Save changes',
            ])
        </section>
    </main>
@endsection
