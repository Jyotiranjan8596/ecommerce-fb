@extends(request()->routeIs('user.pos.list.index') ? 'frontend.dashboard.layouts.master' : 'ui.layout.master')

@section('content')
    <div class="container-fluid px-2 px-md-3 py-3 pos-directory">
        <section class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header border-0 px-4 py-4 pos-directory-header">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <span class="pos-directory-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M3.75 10.25 5.2 4.5h13.6l1.45 5.75M4.5 10v9.5h15V10M3.75 10.25a2.25 2.25 0 0 0 4.5 0 2.25 2.25 0 0 0 4.5 0 2.25 2.25 0 0 0 4.5 0 2.25 2.25 0 0 0 4.5 0M9 19.5v-5h6v5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <div>
                            <div class="text-uppercase small fw-semibold mb-1 pos-directory-eyebrow">FreeBazar network</div>
                            <h1 class="h4 mb-1 fw-bold pos-directory-title">Find a POS near you</h1>
                            <p class="mb-0 pos-directory-subtitle">Explore locations on the map or search by city and ZIP code.</p>
                        </div>
                    </div>

                    <form id="srch-form" class="pos-directory-search" role="search">
                        <label class="visually-hidden" for="pos-search">Search by city or ZIP</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <circle cx="10.8" cy="10.8" r="6.3" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="m15.5 15.5 4.2 4.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <input id="pos-search" type="search" name="search" class="form-control"
                                placeholder="Search by city or ZIP" autocomplete="off">
                            <button class="btn btn-pos-search" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card-body p-3 p-md-4">
                @include('shared.pos-location-map', [
                    'mapId' => 'user-pos-locations-map',
                    'mapTitle' => 'Find a POS on the map',
                    'mapDescription' => 'Explore listed POS locations, view their details, and open directions.',
                    'locations' => [],
                ])

                <div class="pos-directory-list-heading">
                    <div>
                        <span class="pos-directory-section-kicker">Browse the network</span>
                        <h2>POS locations</h2>
                        <p>Contact a location or select a map marker for directions.</p>
                    </div>
                    <div id="pos-list-summary" class="pos-directory-summary" aria-live="polite"></div>
                </div>

                <div class="table-responsive pos-directory-table-wrap">
                    <table class="table align-middle mb-0 pos-directory-table" id="posList-table">
                        <thead>
                            <tr>
                                <th scope="col">Sl.No</th>
                                <th scope="col">POS Name</th>
                                <th scope="col">Address</th>
                                <th scope="col">City</th>
                                <th scope="col">ZIP</th>
                                <th scope="col">Phone</th>
                            </tr>
                        </thead>
                        <tbody id="pos-list-body">
                            <tr>
                                <td colspan="6">
                                    <div class="pos-list-state" role="status">
                                        <span class="spinner-border spinner-border-sm text-primary" aria-hidden="true"></span>
                                        <span>Loading POS locations…</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    <nav id="pagination-container" class="pos-directory-pagination" aria-label="POS list pages"></nav>
                </div>
            </div>
        </section>
    </div>

    <style>
        .pos-directory {
            color: #172033;
            background: linear-gradient(180deg, #f3f7fb 0%, #eef8f8 100%);
            min-height: 100vh;
            padding: 1rem !important;
        }

        .pos-directory .card {
            border: 1px solid rgba(14, 52, 84, 0.08);
            background: #fff;
            box-shadow: 0 1rem 2.5rem rgba(21, 47, 73, .08) !important;
        }

        .pos-directory .pos-directory-header {
            position: relative;
            overflow: hidden;
            padding: 1.5rem 1.5rem 1.3rem !important;
            color: #fff;
            background: radial-gradient(circle at top left, rgba(255,255,255,0.17), transparent 30%),
                linear-gradient(115deg, #0f766e 0%, #0d7a8a 42%, #145da0 100%);
        }

        .pos-directory .pos-directory-header::after {
            content: "";
            position: absolute;
            inset: auto -8% -35% auto;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            pointer-events: none;
        }

        .pos-directory .pos-directory-icon {
            position: relative;
            z-index: 1;
            display: grid;
            width: 3.25rem;
            height: 3.25rem;
            flex: 0 0 3.25rem;
            place-items: center;
            border: 1px solid rgba(255, 255, 255, 0.38);
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
            box-shadow: 0 0.5rem 1.25rem rgba(10, 64, 82, 0.25);
        }

        .pos-directory .pos-directory-icon svg {
            width: 1.8rem;
            height: 1.8rem;
        }

        .pos-directory .pos-directory-eyebrow {
            position: relative;
            z-index: 1;
            color: #d5fff5;
            letter-spacing: .085em;
        }

        .pos-directory .pos-directory-title {
            position: relative;
            z-index: 1;
            color: #fff;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.18);
        }

        .pos-directory .pos-directory-subtitle {
            position: relative;
            z-index: 1;
            color: #e3f4f4;
            font-size: .92rem;
        }

        .pos-directory .pos-directory-search {
            position: relative;
            z-index: 1;
            width: min(100%, 430px);
        }

        .pos-directory .pos-directory-search .input-group {
            min-height: 52px;
            padding: .25rem;
            border: 1px solid rgba(255, 255, 255, .6);
            border-radius: 0.9rem;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 .5rem 1.15rem rgba(4, 43, 65, 0.2);
        }

        .pos-directory .pos-directory-search .input-group-text {
            border: 0;
            background: transparent;
            color: #087f8c;
        }

        .pos-directory .pos-directory-search .input-group-text svg {
            width: 1.2rem;
            height: 1.2rem;
        }

        .pos-directory .pos-directory-search .form-control {
            min-width: 0;
            border: 0;
            box-shadow: none;
            color: #172033;
            background: transparent;
        }

        .pos-directory .pos-directory-search .form-control:focus {
            box-shadow: none;
        }

        .pos-directory .btn-pos-search {
            padding-inline: 1.15rem;
            border: 0;
            border-radius: .7rem !important;
            background: linear-gradient(135deg, #0e8c9a 0%, #146f9c 100%);
            color: #fff;
            font-weight: 800;
            transition: transform .2s ease, filter .2s ease;
        }

        .pos-directory .btn-pos-search:hover,
        .pos-directory .btn-pos-search:focus-visible {
            filter: brightness(1.04);
            color: #fff;
            transform: translateY(-1px);
        }

        .pos-directory .card-body {
            background: linear-gradient(180deg, #ffffff 0%, #f9fbff 100%);
        }

        .pos-directory .pos-map-panel {
            margin-bottom: 1.75rem;
        }

        .pos-directory .pos-map-canvas {
            height: clamp(250px, 30vw, 360px);
        }

        .pos-directory-list-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
            margin: 0 0 .85rem;
        }

        .pos-directory-section-kicker {
            display: block;
            margin-bottom: .2rem;
            color: #0d7a8a;
            font-size: .68rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .pos-directory-list-heading h2 {
            margin: 0;
            color: #172b3a;
            font-size: 1.1rem;
            font-weight: 800;
        }

        .pos-directory-list-heading p {
            margin: .25rem 0 0;
            color: #64748b;
            font-size: .84rem;
        }

        .pos-directory-summary {
            flex: 0 0 auto;
            padding: .45rem .75rem;
            border: 1px solid #d5e9e8;
            border-radius: 999px;
            background: #f0f9f8;
            color: #12616a;
            font-size: .78rem;
            font-weight: 750;
        }

        .pos-directory-summary:empty {
            display: none;
        }

        .pos-directory .pos-directory-table-wrap {
            max-height: min(65vh, 560px);
            max-height: min(65dvh, 560px);
            border: 1px solid #dfeaf2;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 0.75rem 1.5rem rgba(17, 24, 39, 0.04);
            overflow-x: auto;
            overflow-y: auto;
            overscroll-behavior: contain;
            scrollbar-color: #94a3b8 #f1f5f9;
            scrollbar-width: thin;
            -webkit-overflow-scrolling: touch;
        }

        .pos-directory .pos-directory-table {
            min-width: 720px;
            color: #243448;
            margin-bottom: 0;
        }

        .pos-directory .pos-directory-table thead th {
            position: sticky;
            top: 0;
            z-index: 1;
            padding: 1rem 1rem;
            border-bottom: 1px solid #dfe9f1;
            background: linear-gradient(180deg, #f6fbfb 0%, #eef8f8 100%);
            color: #174b54;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .075em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .pos-directory .pos-directory-table tbody td {
            padding: .95rem 1rem;
            border-color: #edf2f7;
            font-size: .92rem;
            vertical-align: middle;
            color: #2f3d52;
        }

        .pos-directory .pos-directory-table tbody td[data-label="POS Name"] {
            color: #17324d;
            font-weight: 750;
        }

        .pos-directory .pos-directory-table tbody td[data-label="Phone"] a {
            display: inline-flex;
            align-items: center;
            color: #0d7180;
            font-weight: 700;
            text-decoration: none;
        }

        .pos-directory .pos-directory-table tbody td[data-label="Phone"] a:hover,
        .pos-directory .pos-directory-table tbody td[data-label="Phone"] a:focus-visible {
            color: #145da0;
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        .pos-directory .pos-directory-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .pos-directory .pos-directory-table tbody tr:not(.pos-state-row):hover {
            background: linear-gradient(90deg, #f7fbfc 0%, #f3f9ff 100%);
        }

        .pos-directory .pos-list-state {
            display: flex;
            min-height: 110px;
            align-items: center;
            justify-content: center;
            gap: .7rem;
            color: #526174;
            font-weight: 600;
        }

        .pos-directory .pos-state-row td {
            border: 0;
        }

        .pos-directory-pagination {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: .4rem;
        }

        .pos-directory-pagination .page-link {
            min-width: 2.4rem;
            min-height: 2.4rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: .38rem .7rem;
            border: 1px solid #d9e5e9;
            border-radius: .7rem;
            background: #fff;
            color: #0d7a8a;
            font-size: .87rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 0.15rem 0.5rem rgba(15, 118, 110, 0.04);
        }

        .pos-directory-pagination .page-link:hover {
            border-color: #0d7a8a;
            background: #edf9f8;
        }

        .pos-directory-pagination .page-link.active {
            border-color: #0d7a8a;
            background: linear-gradient(135deg, #0d7a8a 0%, #145da0 100%);
            color: #fff;
        }

        .pos-directory-pagination .page-link:disabled,
        .pos-directory-pagination .page-link.disabled {
            cursor: not-allowed;
            opacity: .45;
        }

        @media (max-width: 767.98px) {
            .pos-directory {
                padding: .5rem !important;
            }

            .pos-directory .pos-directory-header {
                padding: 1.15rem !important;
            }

            .pos-directory-list-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .pos-directory .pos-directory-search {
                width: 100%;
            }

            .pos-directory .pos-directory-table-wrap {
                overflow-x: hidden;
                overflow-y: auto;
                border: 0;
                box-shadow: none;
                background: transparent;
            }

            .pos-directory .pos-directory-table {
                min-width: 0;
                border-collapse: separate;
                border-spacing: 0 .7rem;
            }

            .pos-directory .pos-directory-table thead {
                display: none;
            }

            .pos-directory .pos-directory-table tbody {
                display: block;
            }

            .pos-directory .pos-directory-table tbody tr:not(.pos-state-row) {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                overflow: hidden;
                border: 1px solid #e3ebf0;
                border-radius: .85rem;
                background: #fff;
                box-shadow: 0 .25rem .8rem rgba(23, 32, 51, .045);
            }

            .pos-directory .pos-directory-table tbody tr:not(.pos-state-row) td {
                min-width: 0;
                padding: .75rem .85rem;
                border: 0;
                overflow-wrap: anywhere;
            }

            .pos-directory .pos-directory-table tbody tr:not(.pos-state-row) td::before {
                display: block;
                margin-bottom: .22rem;
                color: #64748b;
                content: attr(data-label);
                font-size: .68rem;
                font-weight: 800;
                letter-spacing: .045em;
                text-transform: uppercase;
            }

            .pos-directory .pos-directory-table tbody tr:not(.pos-state-row) td[data-label="POS Name"],
            .pos-directory .pos-directory-table tbody tr:not(.pos-state-row) td[data-label="Address"] {
                grid-column: 1 / -1;
            }

            .pos-directory .pos-directory-table tbody tr:not(.pos-state-row) td[data-label="Sl.No"] {
                display: none;
            }

            .pos-directory .pos-directory-table tbody tr:not(.pos-state-row) td[data-label="POS Name"] {
                background: linear-gradient(135deg, #eefaf9 0%, #f0f7ff 100%);
                color: #17324d;
                font-size: 1rem;
                font-weight: 800;
            }

            .pos-directory .pos-directory-table tbody tr:not(.pos-state-row) td[data-label="POS Name"]::before {
                color: #0d7a8a;
            }

            .pos-directory .pos-directory-table tbody tr.pos-state-row {
                display: table-row;
            }

            .pos-directory .pos-directory-table tbody tr.pos-state-row td {
                display: table-cell;
            }

            .pos-directory-pagination {
                justify-content: flex-start;
            }
        }

        @media (max-width: 420px) {
            .pos-directory .card-body {
                padding: 1rem !important;
            }

            .pos-directory .pos-directory-summary {
                max-width: 100%;
                white-space: normal;
            }
        }
    </style>

    <script>
        $(document).ready(function () {
            const $form = $('#srch-form');
            const $table = $('#pos-list-body');
            const $pagination = $('#pagination-container');
            const $summary = $('#pos-list-summary');
            let request = null;
            let mappedLocations = [];

            const scrollList = window.FreebazarScrollList && window.FreebazarScrollList.mount
                ? window.FreebazarScrollList.mount($pagination[0], function (page) {
                    return getPosList(page, true);
                })
                : {
                    reset: function () {},
                    update: function () {},
                };

            function showState(message, isError = false) {
                const $row = $('<tr>').addClass('pos-state-row');
                const $cell = $('<td>').attr('colspan', 6);
                const $state = $('<div>').addClass('pos-list-state').attr('role', isError ? 'alert' : 'status');
                $state.text(message);
                $cell.append($state);
                $row.append($cell);
                $table.empty().append($row);
            }

            function addCell($row, label, value) {
                $('<td>').attr('data-label', label).text(value ?? '—').appendTo($row);
            }

            function getPosList(page = 1, append = false) {
                if (request) {
                    request.abort();
                }

                if (!append) {
                    showState('Loading POS locations…');
                    $summary.text('');
                    mappedLocations = [];
                    scrollList.reset();
                }

                const formData = new FormData($form[0]);
                formData.append('page', page);
                const params = new URLSearchParams();
                params.set('page', page);
                if (formData.get('search')) {
                    params.set('search', formData.get('search'));
                }

                request = $.ajax({
                    type: 'GET',
                    url: @json(route('frontend.pos.list.data')) + (params.toString() ? '?' + params.toString() : ''),
                    dataType: 'json'
                }).done(function (response) {
                    if (!response || response.code !== 200 || !response.data) {
                        showState('Unable to load POS locations. Please try again.', true);
                        return;
                    }

                    const pagination = response.data;
                    const posList = Array.isArray(pagination.data) ? pagination.data : [];
                    mappedLocations = append ? mappedLocations.concat(posList) : posList;
                    if (window.FreebazarPosMaps && window.FreebazarPosMaps['user-pos-locations-map']) {
                        window.FreebazarPosMaps['user-pos-locations-map'].update(mappedLocations);
                    }
                    if (!append) {
                        $table.empty();
                    }

                    if (posList.length === 0) {
                        if (!append) {
                            showState('No POS locations found. Try another city or ZIP code.');
                            $summary.text('No locations found');
                        }
                        scrollList.update(pagination.current_page, pagination.last_page);
                        return;
                    }

                    const firstItem = pagination.from ?? ((pagination.current_page - 1) * pagination.per_page + 1);
                    posList.forEach(function (item, index) {
                        const $row = $('<tr>');
                        addCell($row, 'Sl.No', String(firstItem + index));
                        addCell($row, 'POS Name', item.name || 'N/A');
                        addCell($row, 'Address', item.address || 'N/A');
                        addCell($row, 'City', item.city || 'N/A');
                        addCell($row, 'ZIP', item.zip || 'N/A');
                        const $phoneCell = $('<td>').attr('data-label', 'Phone');
                        if (item.mobilenumber) {
                            $('<a>').attr('href', 'tel:' + item.mobilenumber).text(item.mobilenumber).appendTo($phoneCell);
                        } else {
                            $phoneCell.text('N/A');
                        }
                        $row.append($phoneCell);
                        $table.append($row);
                    });

                    const loadedCount = $table.children('tr').not('.pos-state-row').length;
                    $summary.text('Showing 1–' + loadedCount + ' of ' + pagination.total + ' locations');
                    scrollList.update(pagination.current_page, pagination.last_page);
                }).fail(function (xhr, status) {
                    if (status === 'abort') {
                        return;
                    }
                    if (!append) {
                        showState('Could not load POS locations. Check your connection and try again.', true);
                    }
                });
                return request;
            }

            $form.on('submit', function (event) {
                event.preventDefault();
                getPosList(1);
            });

            getPosList();
        });
    </script>
@endsection
