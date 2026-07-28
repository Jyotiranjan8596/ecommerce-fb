@extends('pos.layouts.master')

@section('content')
    <style>
        #wallet-tbl thead th {
            position: sticky;
            top: 0;
            background-color: #000;
            color: #fff;
            z-index: 10;
        }

        .table-responsive {
            max-height: 500px;
            overflow-y: auto;
        }

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

        #form-loader-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.6);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        #form-loader-overlay .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
    <div id="form-loader-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="container" style="margin-top: 20px;">
        <h3 class="text-center"><b style="color: rgb(8, 7, 20)">Account Ledger</b></h3>

        <div class="row g-3 mb-3">
            {{-- Filters --}}
            <div class="col-12 col-md-6">
                <form id="ledger-form">
                    @csrf
                    <label class="form-label small text-muted mb-1">Search Types</label>
                    <div class="row g-2 align-items-center">
                        <!-- Search Type Dropdown -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <select name="search_type" id="search_type" class="form-select form-select-sm w-100">
                                <option value="voucher">Voucher No</option>
                                <option value="date">Date</option>
                                <option value="ref">Ref No</option>
                            </select>
                        </div>

                        <!-- Search Value Input -->
                        <div class="col-8 col-sm-4 col-md-5">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" id="trans_value" name="value" class="form-control"
                                    placeholder="Search...">
                            </div>
                        </div>

                        <!-- Filter Button -->
                        <div class="col-4 col-sm-2 col-md-3 d-grid">
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold">
                                <i class="bi bi-funnel me-1"></i> FILTER
                            </button>
                        </div>

                    </div>
                </form>
            </div>

            {{-- Export --}}
            <div class="col-12 col-md-2 d-flex align-items-end justify-content-md-end">
                <form method="post" action="{{ route('pos.ledger.export') }}" class="w-100">
                    @csrf
                    <input id="hidden_search_type" type="hidden" name="hsearch_type">
                    <input id="hidden_value" type="hidden" name="hvalue">
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
                        <th>Date</th>
                        <th>Voucher No</th>
                        <th>Ref No</th>
                        {{-- <th>Account</th> --}}
                        <th>Debit</th>
                        <th>Credit</th>
                    </tr>
                </thead>

                <tbody id="ldg-tbl-bdy">
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div id="pagination-container" class="pagination-wrapper"></div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            function showFormLoader() {
                $('#form-loader-overlay').css('display', 'flex');
            }

            function hideFormLoader() {
                $('#form-loader-overlay').css('display', 'none');
            }
            // Initial load
            loadLedger(1);


            const typeSelect = document.getElementById('search_type');
            const valueInput = document.getElementById('trans_value');
            $('#search_type').on('change', function() {
                $('#hidden_search_type').val(this.value);
            });

            typeSelect.addEventListener('change', function() {
                if (this.value === 'date') {
                    valueInput.type = 'date';
                    valueInput.placeholder = '';
                } else {
                    valueInput.type = 'text';
                    valueInput.placeholder = 'Search...';
                }
            });

            function loadLedger(page = 1, formElement) {

                let formData = new FormData(formElement); // Capital 'F'
                formData.append('page', page);
                $.ajax({
                    url: "{{ route('pos.get.ledger') }}",
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
                                            <td>${item.date ?? ''}</td>
                                            <td>${item.voucher_number ?? ''}</td>
                                            <td>${item.reference_number ?? ''}</td>
                                            <td>${item.debit ?? ''}</td>
                                            <td>${item.credit ?? ''}</td>
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

                        $('#ldg-tbl-bdy').html(rows);

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
            $('#ledger-form').on('submit', function(e) {
                e.preventDefault();
                $('#hidden_search_type').val(typeSelect);
                $('#hidden_value').val(valueInput);
                loadLedger(1, this);
            });

            // Pagination click
            $(document).on('click', '.pagination-btn', function() {
                const page = $(this).data('page');
                loadLedger(page, $('#srch-form')[0]); // Reuse the same form
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
