@extends('layouts.master')

@section('content')
    <style>
        .all-user-transactions { max-width: 1600px; margin: 1.25rem auto; padding: 0 1rem 2rem; }
        .all-user-transactions__hero { display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:1.15rem; padding:1.45rem 1.65rem; border:1px solid #d7e7f6; border-radius:20px; background:linear-gradient(120deg,#123b72,#1769aa 68%,#2783bd); color:#fff; box-shadow:0 14px 32px rgba(18,59,114,.14); }
        .all-user-transactions__eyebrow { display:inline-flex; align-items:center; gap:.45rem; color:#c6e4ff; font-size:.72rem; font-weight:800; letter-spacing:.12em; text-transform:uppercase; }
        .all-user-transactions__hero h1 { margin:.35rem 0 .2rem; color:#fff; font-size:clamp(1.5rem,2.6vw,2rem); font-weight:850; }
        .all-user-transactions__hero p { margin:0; color:rgba(255,255,255,.78); font-size:.88rem; }
        .all-user-transactions__hero-icon { display:grid; width:58px; height:58px; flex:0 0 58px; place-items:center; border:1px solid rgba(255,255,255,.2); border-radius:17px; background:rgba(255,255,255,.12); color:#fff; font-size:1.35rem; }
        .all-user-transactions__toolbar { display:flex; align-items:flex-end; justify-content:space-between; gap:1rem; margin-bottom:1rem; padding:1.1rem 1.2rem; border:1px solid #e0e8f1; border-radius:16px; background:#fff; box-shadow:0 8px 22px rgba(19,48,79,.055); }
        .all-user-transactions__filters { display:flex; flex:1 1 auto; align-items:flex-end; gap:.75rem; }
        .all-user-transactions__field { min-width:190px; }
        .all-user-transactions__field label { display:block; margin-bottom:.35rem; color:#52657a; font-size:.76rem; font-weight:750; }
        .all-user-transactions__field input,.all-user-transactions__field select { min-height:42px; border-color:#d9e3ed; border-radius:9px; }
        .all-user-transactions__filters .btn,.all-user-transactions__export .btn { min-height:42px; border-radius:9px; font-size:.82rem; font-weight:750; }
        .all-user-transactions__toolbar .toolbar-icon-button { display:inline-grid; width:46px; min-width:46px; height:46px; padding:0; place-items:center; border:0; border-radius:14px; color:#fff; box-shadow:0 5px 12px rgba(29,57,91,.2); transition:transform .18s ease,box-shadow .18s ease,filter .18s ease; }
        .all-user-transactions__toolbar .toolbar-icon-button:hover { color:#fff; transform:translateY(-2px); filter:saturate(1.12); box-shadow:0 8px 16px rgba(29,57,91,.25); }
        .all-user-transactions__toolbar .toolbar-icon-button:focus-visible { outline:3px solid #94c5ff; outline-offset:3px; }
        .toolbar-icon-button__logo { display:block; width:23px; height:23px; }
        .toolbar-icon-button--date { background:linear-gradient(145deg,#3478f6,#1553c7); }
        .toolbar-icon-button--search { background:linear-gradient(145deg,#16b89a,#07846e); }
        .toolbar-icon-button--export { background:linear-gradient(145deg,#9b61ed,#6b36c5); }
        .all-user-transactions__export .btn { border-color:#1769aa; background:#1769aa; color:#fff; }
        .all-user-transactions__export .btn:hover { border-color:#123b72; background:#123b72; }
        .all-user-transactions__table-card { overflow:hidden; border:1px solid #e0e8f1; border-radius:16px; background:#fff; box-shadow:0 8px 22px rgba(19,48,79,.055); }
        .user-transactions-table-wrap { max-height:620px; overflow:auto; }
        #utrans-tbl { min-width:1000px; margin:0; }
        #utrans-tbl thead th { position:sticky; top:0; z-index:2; padding:.85rem .75rem; border:0; background:#123b72; color:#fff; font-size:.7rem; font-weight:800; letter-spacing:.04em; white-space:nowrap; }
        #utrans-tbl tbody td { padding:.78rem .75rem; border-color:#edf1f6; color:#40546a; font-size:.81rem; vertical-align:middle; white-space:nowrap; }
        #utrans-tbl tbody tr:nth-child(even) { background:#f8fbff; }
        #utrans-tbl tbody tr:hover { background:#eef6ff; }

        .pagination-wrapper {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 8px;
            margin: 0;
            padding: 1rem 1.2rem;
            border-top: 1px solid #edf1f6;
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
            border: 1px solid #d9e3ed;
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
            background: #eff6ff;
            border-color: #1769aa;
            color: #1769aa;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(23, 105, 170, 0.15);
        }

        .pagination-btn.active {
            background: #1769aa;
            border-color: #1769aa;
            color: #fff;
            box-shadow: 0 2px 8px rgba(23, 105, 170, 0.25);
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
            .all-user-transactions { margin:.5rem auto; padding:0 .65rem 1rem; }
            .all-user-transactions__hero { padding:1.1rem; }
            .all-user-transactions__hero-icon { display:none; }
            .all-user-transactions__toolbar { align-items:stretch; flex-direction:column; padding:.9rem; }
            .all-user-transactions__filters { align-items:stretch; flex-direction:column; }
            .all-user-transactions__field { min-width:0; }
            .all-user-transactions__toolbar .toolbar-icon-button { width:42px; }
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
    <main class="all-user-transactions">
        <header class="all-user-transactions__hero">
            <div>
                <span class="all-user-transactions__eyebrow"><i class="fas fa-chart-line" aria-hidden="true"></i> Sales report</span>
                <h1>Today's Sales</h1>
                <p>Review customer sales and payment activity for a selected date and POS.</p>
            </div>
            <span class="all-user-transactions__hero-icon" aria-hidden="true"><i class="fas fa-receipt"></i></span>
        </header>

        <section class="all-user-transactions__toolbar">
            <form id="trans-form" class="all-user-transactions__filters">
                @csrf
                <div class="all-user-transactions__field">
                    <label for="transaction_date">Date</label>
                    <input required id="transaction_date" name="transaction_date" class="form-control" type="date"
                        value="{{ request('transaction_date', today()->toDateString()) }}">
                </div>
                <button type="submit" class="btn toolbar-icon-button toolbar-icon-button--date" title="Filter by date"
                    aria-label="Filter by date">
                    <svg class="toolbar-icon-button__logo" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <rect x="3.5" y="5" width="17" height="15.5" rx="3" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M7.5 3.5v3M16.5 3.5v3M4 9.5h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="m8.3 14 2.1 2.1 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="all-user-transactions__field">
                    <label for="user_search">Search POS or Customer User ID / Mobile</label>
                    <input id="user_search" name="user_search" class="form-control" type="search"
                        placeholder="Enter POS or customer User ID / mobile"
                        value="{{ request('user_search') }}">
                </div>
                <button type="submit" class="btn toolbar-icon-button toolbar-icon-button--search" title="Search POS and customer"
                    aria-label="Search POS and customer">
                    <svg class="toolbar-icon-button__logo" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="10.8" cy="10.8" r="6.3" stroke="currentColor" stroke-width="2"/>
                        <path d="m15.5 15.5 4.2 4.2" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                        <path d="M8.5 10.8h4.6M10.8 8.5v4.6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </button>
            </form>
            <form method="POST" action="{{ route('admin.all.users.transactions.export') }}" class="all-user-transactions__export">
                @csrf
                <input id="hidden_transaction_date" type="hidden" name="transaction_date"
                    value="{{ request('transaction_date', today()->toDateString()) }}">
                <input id="hidden_user_search" type="hidden" name="user_search" value="{{ request('user_search') }}">
                <button class="btn toolbar-icon-button toolbar-icon-button--export" type="submit" title="Export sales"
                    aria-label="Export sales">
                    <svg class="toolbar-icon-button__logo" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M7 3.5h7l4.5 4.6v11.4a1.5 1.5 0 0 1-1.5 1.5H7A1.5 1.5 0 0 1 5.5 19.5v-14A2 2 0 0 1 7.5 3.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        <path d="M14 3.8v4.7h4.2M12 10v6m-2.5-2.5L12 16l2.5-2.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </form>
        </section>

        <!-- Data Table -->
        <div class="table-responsive">
            <table class="table table-striped" id="utrans-tbl">
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>POS</th>
                        <th>Invoice</th>
                        <th>Billing Amount</th>
                        <th>By Wallet</th>
                        <th>By Reward</th>
                        <th>Net Pay</th>
                        <th>Wallet Remain</th>
                        <th>Reward Remain</th>
                        <th>Transaction Date</th>
                        <th>Action</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody id="wlt-tbl-bdy">
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div id="pagination-container" class="pagination-wrapper"></div>
    </div>
    <div class="modal fade" id="editTransactionModal" tabindex="-1" aria-labelledby="editTransactionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="edit-transaction-form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTransactionModalLabel">Edit Transaction Amounts</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_billing_amount" class="form-label">Billing Amount</label>
                            <input required type="number" min="0" step="0.01" class="form-control"
                                id="edit_billing_amount" name="billing_amount">
                        </div>
                        <div class="mb-3">
                            <label for="edit_amount_wallet" class="form-label">By Wallet</label>
                            <input required type="number" min="0" step="1" class="form-control"
                                id="edit_amount_wallet" name="amount_wallet">
                        </div>
                        <div class="mb-3">
                            <label for="edit_reward_amount" class="form-label">By Reward</label>
                            <input required type="number" min="0" step="0.01" class="form-control"
                                id="edit_reward_amount" name="reward_amount">
                        </div>
                        <div id="edit-transaction-error" class="alert alert-danger d-none" role="alert"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            const escapeHtml = value => $('<div>').text(value ?? '').html();
            const scrollList = window.FreebazarScrollList.mount(
                document.getElementById('pagination-container'),
                function(page) {
                    return transactionsLoad(page, $('#trans-form')[0], true);
                }
            );

            $('#transaction_date').on('change', function() {
                $('#hidden_transaction_date').val(this.value);
            });

            $('#user_search').on('input', function() {
                $('#hidden_user_search').val(this.value);
            });
            // Initial load
            transactionsLoad(1, $('#trans-form')[0]);

            function transactionsLoad(page = 1, formElement, append = false) {
                let formData = new FormData(formElement || document.createElement('form'));
                formData.append('page', page);
                return $.ajax({
                    url: "{{ route('admin.all.users.transactions') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        let rows = '';
                        let index = response.data.from ?? 1;

                        if (response.data.data.length > 0) {
                            response.data.data.forEach(function(item) {
                        const auditHistory = (item.status_audit ?? []).map(change =>
                            `<div>${escapeHtml(change.description)} by ${escapeHtml(change.actor)} · ${escapeHtml(change.at)}</div>`
                        ).join('');
                        const auditSummary = auditHistory ||
                            '<div class="text-muted small">Actor history unavailable for this older record.</div>';

                        rows += `
                                        <tr>
                                            <td>${index++}</td>
                                            <td>${item.user_name ?? ''}</td>
                                            <td>${item.mobile ?? ''}</td>
                                            <td>${item.pos_name ?? ''}</td>
                                            <td>${item.invoice ?? ''}</td>
                                            <td>${item.billing_amount ?? ''}</td>
                                            <td>${item.amount_wallet ?? ''}</td>
                                            <td>${item.reward_amount ?? ''}</td>
                                            <td>${item.amount ?? ''}</td>
                                            <td>₹${item.remaining_amount ?? 0}/-</td>
                                            <td>₹${item.remaining_reward ?? 0}/-</td>
                                            <td>${item.trans_date ?? 0}</td>
                                            <td class="text-nowrap">
                                                <button type="button" class="btn btn-sm btn-outline-primary edit-transaction"
                                                    data-id="${item.id}" data-billing-amount="${item.billing_amount ?? 0}"
                                                    data-amount-wallet="${item.amount_wallet ?? 0}" data-reward-amount="${item.reward_amount ?? 0}">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-transaction"
                                                    data-id="${item.id}">
                                                    Delete
                                                </button>
                                            </td>
                                            <td>
                                                <span class="badge ${item.status_label === 'Verified' ? 'bg-success' : 'bg-warning text-dark'}">${escapeHtml(item.status_label)}</span>
                                                <div class="small text-muted mt-1">${auditSummary}</div>
                                            </td>
                                        </tr>
                                    `;
                            });
                        } else {

                            rows = append ? '' : `
                                    <tr>
                                        <td colspan="14" class="text-center">
                                            No data found
                                        </td>
                                    </tr>
                                `;

                        }

                        if (append) {
                            $('#wlt-tbl-bdy').append(rows);
                        } else {
                            $('#wlt-tbl-bdy').html(rows);
                        }

                        scrollList.update(
                            response.data.current_page ?? page,
                            response.data.last_page ?? page
                        );
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('Something went wrong');
                    }
                });
            }

            // Filter submit
            $('#trans-form').on('submit', function(e) {
                e.preventDefault();
                scrollList.reset();
                transactionsLoad(1, this);
            });

            const editTransactionModal = new bootstrap.Modal(document.getElementById('editTransactionModal'));
            const updateTransactionUrl = @json(route('admin.all.users.transactions.update', ['wallet' => '__WALLET_ID__']));
            const deleteTransactionUrl = @json(route('admin.all.users.transactions.delete', ['wallet' => '__WALLET_ID__']));

            $('#wlt-tbl-bdy').on('click', '.edit-transaction', function() {
                const button = $(this);
                $('#edit-transaction-form').data('id', button.data('id'));
                $('#edit_billing_amount').val(button.data('billing-amount'));
                $('#edit_amount_wallet').val(button.data('amount-wallet'));
                $('#edit_reward_amount').val(button.data('reward-amount'));
                $('#edit-transaction-error').addClass('d-none').text('');
                editTransactionModal.show();
            });

            $('#edit-transaction-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                const walletId = $(form).data('id');
                const submitButton = $(form).find('[type="submit"]');
                submitButton.prop('disabled', true);

                $.ajax({
                    url: updateTransactionUrl.replace('__WALLET_ID__', walletId),
                    type: 'PATCH',
                    data: $(form).serialize(),
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        editTransactionModal.hide();
                        scrollList.reset();
                        transactionsLoad(1, $('#trans-form')[0]);
                        Swal.fire('Saved', response.message, 'success');
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Unable to update transaction amounts.';
                        $('#edit-transaction-error').removeClass('d-none').text(message);
                    },
                    complete: function() {
                        submitButton.prop('disabled', false);
                    }
                });
            });

            $('#wlt-tbl-bdy').on('click', '.delete-transaction', function() {
                const walletId = $(this).data('id');

                Swal.fire({
                    title: 'Delete this transaction?',
                    text: 'Its linked wallet record will also be deleted. This cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Delete'
                }).then(function(result) {
                    if (!result.isConfirmed) {
                        return;
                    }

                    $.ajax({
                        url: deleteTransactionUrl.replace('__WALLET_ID__', walletId),
                        type: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function(response) {
                            scrollList.reset();
                            transactionsLoad(1, $('#trans-form')[0]);
                            Swal.fire('Deleted', response.message, 'success');
                        },
                        error: function() {
                            Swal.fire('Error', 'Unable to delete the transaction.', 'error');
                        }
                    });
                });
            });

        });
    </script>
@endsection
