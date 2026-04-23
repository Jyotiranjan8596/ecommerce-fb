@extends('layouts.master')

@section('content')
    <style>
        .pagination-wrapper {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 1rem;
            padding: 0.5rem 0;
        }

        .pagination-info {
            font-size: 0.85rem;
            color: #6c757d;
            margin-right: 8px;
        }

        .pagination-buttons {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px;
        }

        .pagination-btn {
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            border: 1.5px solid #dee2e6;
            border-radius: 8px;
            background: #fff;
            color: #495057;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .pagination-btn:hover:not(.disabled):not(.active) {
            background: #f0f4ff;
            border-color: #4f46e5;
            color: #4f46e5;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(79, 70, 229, 0.15);
        }

        .pagination-btn.active {
            background: #4f46e5;
            border-color: #4f46e5;
            color: #fff;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.35);
            cursor: default;
        }

        .pagination-btn.nav-btn {
            background: #f8f9fa;
            color: #495057;
        }

        .pagination-btn.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }

        .pagination-ellipsis {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            color: #6c757d;
            font-size: 1rem;
            letter-spacing: 1px;
        }

        /* Mobile responsive */
        @media (max-width: 576px) {
            .pagination-wrapper {
                justify-content: center;
            }

            .pagination-info {
                width: 100%;
                text-align: center;
                margin-right: 0;
            }

            .pagination-buttons {
                justify-content: center;
            }

            .pagination-btn {
                min-width: 32px;
                height: 32px;
                font-size: 0.8rem;
            }
        }
    </style>
    <div class="container" style="margin-top: 20px;">
        <h3 class="text-center"><b style="color: rgb(8, 7, 20)">WALLET DETAILS</b></h3>

        <div class="row g-3 mb-3">

            {{-- File Upload --}}
            <div class="col-12 col-md-4">
                <form action="{{ route('admin.wallet.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label class="form-label small text-muted mb-1">Upload Wallet</label>
                    <div class="input-group">
                        <input type="file" class="form-control form-control-sm" name="file" accept=".xlsx" required>
                        <button class="btn btn-info btn-sm fw-semibold" type="submit">
                            <i class="bi bi-upload me-1"></i> UPLOAD
                        </button>
                    </div>
                    @if ($errors->has('file'))
                        <div class="text-danger mt-1 small">{{ $errors->first('file') }}</div>
                    @endif
                </form>
            </div>

            {{-- Filters --}}
            <div class="col-12 col-md-6">
                <form id="wallet-form">
                    @csrf
                    <label class="form-label small text-muted mb-1">Filter Records</label>
                    <div class="row g-2">
                        <div class="col-6 col-sm-4 col-md-4">
                            <select name="month" id="month" class="form-select form-select-sm w-100">
                                <option value="">Month</option>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6 col-sm-3 col-md-3">
                            <select name="year" id="year" class="form-select form-select-sm w-100">
                                <option value="">Year</option>
                                @foreach (range(now()->year, 2020) as $y)
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6 col-sm-3 col-md-3">
                            <select name="type" id="trans_type" class="form-select form-select-sm w-100">
                                <option value="all">All Types</option>
                                <option value="credit">Credit</option>
                                <option value="debit">Debit</option>
                            </select>
                        </div>

                        <div class="col-6 col-sm-2 col-md-2 d-flex gap-1">
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold flex-fill">
                                <i class="bi bi-funnel me-1"></i> FILTER
                            </button>
                        </div>

                        {{-- <div class="col-12 col-sm-auto">
                                <a href="" class="btn btn-outline-secondary btn-sm w-100">
                                    <i class="bi bi-x-circle me-1"></i> RESET
                                </a>
                            </div> --}}

                    </div>
                </form>
            </div>

            {{-- Export --}}
            <div class="col-12 col-md-2 d-flex align-items-end justify-content-md-end">
                <form method="GET" action="{{ route('admin.wallet.export') }}" class="w-100">
                    @csrf
                    <button class="btn btn-danger btn-sm fw-semibold w-100" type="submit">
                        <i class="bi bi-download me-1"></i> EXPORT
                    </button>
                </form>
            </div>

        </div>
        <hr class="my-4">

        <!-- Data Table -->
        <div class="table-responsive">
            <table class="table table-striped" id="wallet-tbl">
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>Month</th>
                        <th>USER ID</th>
                        <th>USER NAME</th>
                        <th>MOBILE NUMBER</th>
                        <th>PAYMENT MODE</th>
                        <th>WALLET AMOUNT</th>
                        <th>REWARD POINTS</th>

                    </tr>
                </thead>
                <tbody id="wlt-tbl-bdy">
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div id="pagination-container" class="pagination-wrapper"></div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {

            // Initial load
            walletLoad(1);

            function walletLoad(page = 1, formElement) {

                let formData = new FormData(formElement); // Capital 'F'
                formData.append('page', page);
                $.ajax({
                    url: "{{ route('admin.wallet.index') }}",
                    type: "POST",
                    data: formData,
                    processData: false, // Important when using FormData
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        let rows = '';
                        let index = response.data.from ?? 1;

                        if (response.data.data.length > 0) {
                            const pagination = response.data;
                            let paginationHtml = '';
                            response.data.data.forEach(function(item) {
                                
                                rows += `
                                        <tr>
                                            <td>${index++}</td>
                                            <td>${item.month ?? ''}</td>
                                            <td>${item.user_data?.user_id ?? ''}</td>
                                            <td>${item.user_data?.name ?? ''}</td>
                                            <td>${item.mobilenumber ?? ''}</td>
                                            <td>${item.trans_type ?? ''}</td>
                                            <td>₹${item.rounded_wallet_amount ?? 0}/-</td>
                                            <td>₹${item.rounded_reward_point ?? 0}/-</td>
                                        </tr>
                                    `;
                            });
                            $('#pagination-container').html(buildPagination(pagination));

                        } else {

                            rows = `
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            No data found
                                        </td>
                                    </tr>
                                `;
                        }

                        $('#wlt-tbl-bdy').html(rows);

                        // Pagination HTML
                        $('#pagination-link').html(response.pagination);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('Something went wrong');
                    }
                });
            }

            // Filter submit
            $('#wallet-form').on('submit', function(e) {
                e.preventDefault();
                walletLoad(1,this);
            });

            // Pagination click
            $(document).on('click', '.pagination-btn', function() {
                const page = $(this).data('page');
                walletLoad(page, $('#srch-form')[0]); // Reuse the same form
            });

            function buildPagination(pagination) {
                const {
                    current_page,
                    last_page
                } = pagination;
                let paginationHtml = '';

                const maxVisible = 5; // Max page buttons to show
                let startPage = Math.max(1, current_page - Math.floor(maxVisible / 2));
                let endPage = Math.min(last_page, startPage + maxVisible - 1);

                // Adjust start if end hits the limit
                if (endPage - startPage < maxVisible - 1) {
                    startPage = Math.max(1, endPage - maxVisible + 1);
                }

                // First + Ellipsis
                if (startPage > 1) {
                    paginationHtml += pageBtn(1, current_page);
                    if (startPage > 2) {
                        paginationHtml += `<span class="pagination-ellipsis">…</span>`;
                    }
                }

                // Page Numbers
                for (let i = startPage; i <= endPage; i++) {
                    paginationHtml += pageBtn(i, current_page);
                }

                // Ellipsis + Last
                if (endPage < last_page) {
                    if (endPage < last_page - 1) {
                        paginationHtml += `<span class="pagination-ellipsis">…</span>`;
                    }
                    paginationHtml += pageBtn(last_page, current_page);
                }

                // Wrap with Prev/Next
                const prevBtn = current_page > 1 ?
                    `<button class="pagination-btn nav-btn pagination-btn" data-page="${current_page - 1}" title="Previous">
                    <i class="fas fa-chevron-left"></i>
                    </button>` :
                    `<button class="pagination-btn nav-btn disabled" disabled title="Previous">
                        <i class="fas fa-chevron-left"></i>
                    </button>`;

                const nextBtn = current_page < last_page ?
                    `<button class="pagination-btn nav-btn pagination-btn" data-page="${current_page + 1}" title="Next">
                        <i class="fas fa-chevron-right"></i>
                    </button>` :
                    `<button class="pagination-btn nav-btn disabled" disabled title="Next">
                        <i class="fas fa-chevron-right"></i>
                    </button>`;

                return `
                    <div class="pagination-info">
                        Page <strong>${current_page}</strong> of <strong>${last_page}</strong>
                    </div>
                    <div class="pagination-buttons">
                        ${prevBtn}
                        ${paginationHtml}
                        ${nextBtn}
                    </div>
                `;
            }

            function pageBtn(i, current) {
                const isActive = i === current;
                return `<button 
                class="pagination-btn ${isActive ? 'active' : ''}" 
                data-page="${i}"
                ${isActive ? 'aria-current="page"' : ''}
            >${i}</button>`;
            }

        });
    </script>
@endsection
