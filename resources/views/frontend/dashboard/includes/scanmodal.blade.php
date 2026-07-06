{{-- Modals  --}}
<!-- QR Code Scanner Modal -->
<div class="modal fade qr-modal" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content qr-scan-content">
            <div class="modal-header qr-scan-header">
                <h5 class="modal-title" id="qrScannerModalLabel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    Scan QR Code
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body qr-scan-body">
                <div class="qr-viewport">
                    <div id="qr-reader"></div>
                    <div class="qr-frame">
                        <span class="corner tl"></span>
                        <span class="corner tr"></span>
                        <span class="corner bl"></span>
                        <span class="corner br"></span>
                        <span class="scan-line"></span>
                    </div>
                    <div class="qr-status" id="qrStatusText">Point your camera at a Freebazar QR code</div>
                </div>
                <div class="qr-controls">
                    <button type="button" class="qr-ctrl-btn" id="qrTorchBtn" title="Toggle flashlight">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path>
                            <line x1="12" y1="2" x2="12" y2="12"></line>
                        </svg>
                        <span>Flash</span>
                    </button>
                    <button type="button" class="qr-ctrl-btn" id="qrSwitchCamBtn" title="Switch camera">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <polyline points="17 1 21 5 17 9"></polyline>
                            <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                            <polyline points="7 23 3 19 7 15"></polyline>
                            <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                        </svg>
                        <span>Flip</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Details Modal -->
<div class="modal fade qr-modal" id="qrDetailsModal" tabindex="-1" aria-labelledby="qrDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content qr-details-content">
            <div class="modal-body qr-details-body text-center">
                <div class="qr-success-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                        stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <p class="qr-details-label">POS Verified</p>
                <h5 id="qr-details-text" class="qr-details-name"></h5>
                <button id="openBillingModal" class="btn qr-btn-primary w-100 mt-3">
                    Continue to Billing
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Billing Modal -->
<div class="modal fade qr-modal" id="billingModal" tabindex="-1" aria-labelledby="billingModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content billing-content">
            <div class="modal-header billing-header">
                <div>
                    <h5 class="modal-title" id="billingModalLabel">Billing Information</h5>
                    <p class="billing-pos-chip" id="billing-pos-name">Not Scanned</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body billing-body">
                <form id="qrForm" method="post" action="{{ route('user.payment') }}">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <!-- Discount toggle group -->
                    <div class="discount-switch-group">
                        <label class="switch-row">
                            <span class="switch-label">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="7"></circle>
                                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                                </svg>
                                Reward Points
                            </span>
                            <span class="toggle">
                                <input type="checkbox" name="reward_select" id="checked-reward" checked
                                    onchange="toggleExclusive(this, 'checked-wallet')">
                                <span class="slider"></span>
                            </span>
                        </label>
                        <label class="switch-row">
                            <span class="switch-label">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                                    </rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                Use Wallet
                            </span>
                            <span class="toggle">
                                <input type="checkbox" name="wallet_select" id="checked-wallet"
                                    onchange="toggleExclusive(this, 'checked-reward')">
                                <span class="slider"></span>
                            </span>
                        </label>
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" name="pos_id" id="qrDataId" />
                    <input type="hidden" name="upi_id" id="upi_ID" />
                    <input type="hidden" name="invoice" />
                    <input type="hidden" name="user_id" value="{{ $user_profile->id }}" />
                    <input type="hidden" name="mobilenumber" value="{{ $user_profile->mobilenumber }}" />
                    <input type="hidden" name="insert_date" id="insert_date" />
                    <input type="hidden" name="transaction_date" id="transaction_date"
                        value="{{ now()->format('Y-m-d') }}" />

                    <!-- Billing Amount -->
                    <div class="form-group mb-3">
                        <label for="billing_amount" class="modal-label">Billing Amount</label>
                        <div class="input-prefix-group readonly-group">
                            <span class="input-prefix">&#8377;</span>
                            <input type="number" class="form-control" id="billing_amount" name="billing_amount"
                                required min="0" step="any" placeholder="0.00"
                                oninput="checkWalletBalance()" />
                        </div>
                    </div>
                    <input id="sponsors_count" type="hidden" name="wallet_balance" value="{{ $sponsors_count }}" />

                    <!-- Paying Amount -->
                    <div class="form-group mb-3">
                        <label for="paying_amount" class="modal-label">Paying Amount</label>
                        <div class="input-prefix-group readonly-group">
                            <span class="input-prefix">&#8377;</span>
                            <input type="number" class="form-control" name="paying_amount" id="paying_amount"
                                required min="0" readonly />
                        </div>
                    </div>

                    <!-- Pay By -->
                    <div id="upi-options" class="form-group mb-3" style="display: none;">
                        <label class="modal-label">Select UPI Provider</label>
                        <div class="upi-grid">
                            <div class="upi-card">
                                <input type="radio" id="gpay" name="upi_provider" value="googlepay" hidden
                                    class="upi-radio">
                                <label for="gpay" class="upi-card-label" onclick="selectUPI('gpay')">
                                    <img src="https://img.icons8.com/fluency/48/google-pay-new.png" alt="googlePay" />
                                    <span>GPay</span>
                                </label>
                            </div>
                            <div class="upi-card">
                                <input type="radio" id="phonepe" name="upi_provider" value="phonepe" hidden
                                    class="upi-radio">
                                <label for="phonepe" class="upi-card-label" onclick="selectUPI('phonepe')">
                                    <img src="https://img.icons8.com/color/48/phone-pe.png" alt="phone-pe" />
                                    <span>PhonePe</span>
                                </label>
                            </div>
                            <div class="upi-card">
                                <input type="radio" id="paytm" name="upi_provider" value="paytm" hidden
                                    class="upi-radio">
                                <label for="paytm" class="upi-card-label" onclick="selectUPI('paytm')">
                                    <img src="https://img.icons8.com/fluency/48/paytm.png" alt="paytm" />
                                    <span>Paytm</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Balances -->
                    <div class="balance-cards">
                        <div class="balance-card">
                            <span class="balance-label">Wallet Balance</span>
                            <span class="balance-value">&#8377;<span
                                    id="wallet_balance">{{ $walletBalance }}</span></span>
                            <input type="hidden" name="wallet_balance" value="{{ $walletBalance }}" />
                        </div>
                        <div class="balance-card">
                            <span class="balance-label">Reward Points</span>
                            <span class="balance-value">&#8377;<span
                                    id="reward_balance">{{ $rewardBalance }}</span></span>
                            <input type="hidden" name="reward_balance" value="{{ $rewardBalance }}" />
                        </div>
                    </div>

                    <!-- Insufficient Balance -->
                    <div class="insufficient-balance mb-3" style="display: none">
                        <label class="modal-label">Alternative Payment Method for Remaining Amount</label>
                        <select name="alternative_pay_by" id="alternative_pay_by" class="form-control" required
                            style="display: none">
                            <option value="">Select Payment Method</option>
                            <option value="cash">Cash</option>
                            <option value="upi">UPI</option>
                        </select>
                    </div>

                    <!-- Remaining Balance -->
                    <div class="remaining-balance mb-3" style="display: none">
                        <label for="remaining_amount" class="modal-label">Remaining Amount to be Paid</label>
                        <input type="text" id="remaining_amount" class="form-control" readonly />
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" id="submitPaymentButton" class="btn qr-btn-primary w-100">
                            Submit Payment
                        </button>
                    </div>

                    <!-- Full Page Loader -->
                    <div id="fullPageLoader" class="full-page-loader">
                        <div class="text-center">
                            <div class="loader-spinner"></div>
                            <p class="loader-text mt-3">Processing Payment...</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --qr-accent: #0f9d8a;
        --qr-accent-dark: #0b7d6e;
        --qr-ink: #0f1b1a;
        --qr-muted: #6b7876;
        --qr-bg-soft: #f3f8f7;
        --qr-border: #e1e9e7;
        --qr-danger: #e0563f;
        --qr-radius: 18px;
    }

    /* ---------- Shared modal shell ---------- */
    .qr-modal .modal-content {
        border: none;
        border-radius: var(--qr-radius);
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(15, 27, 26, 0.25);
    }

    .qr-modal .modal-dialog {
        max-width: 420px;
        margin: 1rem auto;
    }

    /* ---------- Scanner modal ---------- */
    .qr-scan-header {
        background: linear-gradient(135deg, var(--qr-accent), var(--qr-accent-dark));
        color: #fff;
        border: none;
        padding: 1rem 1.25rem;
    }

    .qr-scan-header .modal-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        font-size: 1.05rem;
    }

    .qr-scan-body {
        background: #0c1615;
        padding: 0;
    }

    .qr-viewport {
        position: relative;
        width: 100%;
        aspect-ratio: 1 / 1;
        background: #000;
        overflow: hidden;
    }

    #qr-reader {
        width: 100% !important;
        height: 100% !important;
    }

    #qr-reader video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
    }

    /* hide the library's default chrome (camera dropdown, borders, links) so our own UI takes over */
    #qr-reader img,
    #qr-reader__dashboard_section_csr>div:not(:first-child),
    #qr-reader__header_message,
    #qr-reader__status_span,
    #qr-reader__camera_selection {
        display: none !important;
    }

    #qr-reader__scan_region {
        border: none !important;
        background: transparent !important;
        position: absolute !important;
        inset: 0 !important;
    }

    #qr-reader__dashboard {
        display: none !important;
    }

    .qr-frame {
        position: absolute;
        inset: 12%;
        pointer-events: none;
    }

    .corner {
        position: absolute;
        width: 32px;
        height: 32px;
        border: 4px solid var(--qr-accent);
        opacity: 0.95;
    }

    .corner.tl {
        top: 0;
        left: 0;
        border-right: none;
        border-bottom: none;
        border-radius: 8px 0 0 0;
    }

    .corner.tr {
        top: 0;
        right: 0;
        border-left: none;
        border-bottom: none;
        border-radius: 0 8px 0 0;
    }

    .corner.bl {
        bottom: 0;
        left: 0;
        border-right: none;
        border-top: none;
        border-radius: 0 0 0 8px;
    }

    .corner.br {
        bottom: 0;
        right: 0;
        border-left: none;
        border-top: none;
        border-radius: 0 0 8px 0;
    }

    .scan-line {
        position: absolute;
        left: 4%;
        right: 4%;
        top: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--qr-accent), transparent);
        box-shadow: 0 0 8px var(--qr-accent);
        animation: scanMove 2.2s ease-in-out infinite;
    }

    @keyframes scanMove {
        0% {
            top: 2%;
        }

        50% {
            top: 96%;
        }

        100% {
            top: 2%;
        }
    }

    .qr-status {
        position: absolute;
        bottom: 10px;
        left: 0;
        right: 0;
        text-align: center;
        color: #fff;
        font-size: 0.82rem;
        background: rgba(0, 0, 0, 0.45);
        padding: 6px 10px;
        margin: 0 auto;
        width: max-content;
        max-width: 90%;
        border-radius: 999px;
        backdrop-filter: blur(2px);
    }

    .qr-controls {
        display: flex;
        justify-content: center;
        gap: 1rem;
        padding: 0.9rem 1rem 1.1rem;
        background: #0c1615;
    }

    .qr-ctrl-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 14px;
        padding: 0.5rem 1.1rem;
        font-size: 0.72rem;
        transition: background 0.2s ease, transform 0.15s ease;
    }

    .qr-ctrl-btn:active {
        transform: scale(0.95);
    }

    .qr-ctrl-btn.active {
        background: var(--qr-accent);
        border-color: var(--qr-accent);
    }

    .qr-ctrl-btn:hover {
        background: rgba(255, 255, 255, 0.16);
    }

    /* ---------- QR details modal ---------- */
    .qr-details-body {
        padding: 2rem 1.5rem;
    }

    .qr-success-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: var(--qr-bg-soft);
        color: var(--qr-accent);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
    }

    .qr-details-label {
        color: var(--qr-muted);
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 0.15rem;
    }

    .qr-details-name {
        color: var(--qr-ink);
        font-weight: 700;
        font-size: 1.25rem;
    }

    /* ---------- Billing modal ---------- */
    .billing-header {
        background: var(--qr-bg-soft);
        border: none;
        align-items: flex-start;
        padding: 1.25rem 1.25rem 1rem;
    }

    .billing-header .modal-title {
        color: var(--qr-ink);
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0.35rem;
    }

    .billing-pos-chip {
        display: inline-block;
        background: #fff;
        color: var(--qr-accent-dark);
        font-weight: 600;
        font-size: 0.78rem;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        border: 1px solid var(--qr-border);
        margin: 0;
    }

    .billing-body {
        padding: 1.25rem 1.25rem 1.5rem;
        max-height: 78vh;
        overflow-y: auto;
    }

    .modal-label {
        color: var(--qr-ink);
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 0.4rem;
        display: block;
    }

    .discount-switch-group {
        background: var(--qr-bg-soft);
        border-radius: 14px;
        padding: 0.75rem 1rem;
        margin-bottom: 1.1rem;
    }

    .switch-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.4rem 0;
        margin: 0;
        cursor: pointer;
    }

    .switch-row+.switch-row {
        border-top: 1px solid var(--qr-border);
    }

    .switch-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        font-size: 0.92rem;
        color: var(--qr-ink);
    }

    .switch-label svg {
        color: var(--qr-accent);
        flex-shrink: 0;
    }

    .toggle {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 26px;
        flex-shrink: 0;
    }

    .toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background-color: #cfd8d6;
        border-radius: 999px;
        transition: 0.2s;
    }

    .slider::before {
        content: "";
        position: absolute;
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: #fff;
        border-radius: 50%;
        transition: 0.2s;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25);
    }

    .toggle input:checked+.slider {
        background-color: var(--qr-accent);
    }

    .toggle input:checked+.slider::before {
        transform: translateX(18px);
    }

    .input-prefix-group {
        display: flex;
        align-items: center;
        border: 1px solid var(--qr-border);
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .input-prefix-group:focus-within {
        border-color: var(--qr-accent);
        box-shadow: 0 0 0 3px rgba(15, 157, 138, 0.12);
    }

    .input-prefix {
        padding: 0 0.75rem;
        color: var(--qr-muted);
        font-weight: 600;
        border-right: 1px solid var(--qr-border);
        background: var(--qr-bg-soft);
        align-self: stretch;
        display: flex;
        align-items: center;
    }

    .input-prefix-group input.form-control {
        border: none;
        box-shadow: none;
        padding: 0.6rem 0.75rem;
    }

    .input-prefix-group input.form-control:focus {
        box-shadow: none;
    }

    .readonly-group {
        background: var(--qr-bg-soft);
    }

    .readonly-group input.form-control {
        background: transparent;
        font-weight: 600;
        color: var(--qr-ink);
    }

    .upi-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.6rem;
    }

    .upi-card-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.35rem;
        border: 1.5px solid var(--qr-border);
        border-radius: 12px;
        padding: 0.6rem 0.4rem;
        cursor: pointer;
        transition: border-color 0.15s ease, background 0.15s ease, transform 0.1s ease;
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--qr-ink);
    }

    .upi-card-label img {
        width: 32px;
        height: 32px;
    }

    .upi-card-label:hover {
        transform: translateY(-1px);
    }

    .upi-radio:checked+.upi-card-label {
        border-color: var(--qr-accent);
        background: var(--qr-bg-soft);
        box-shadow: 0 0 0 3px rgba(15, 157, 138, 0.12);
    }

    .balance-cards {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.6rem;
        margin-bottom: 1.1rem;
    }

    .balance-card {
        background: var(--qr-bg-soft);
        border-radius: 12px;
        padding: 0.65rem 0.8rem;
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
    }

    .balance-label {
        font-size: 0.72rem;
        color: var(--qr-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .balance-value {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--qr-ink);
    }

    .qr-btn-primary {
        background: linear-gradient(135deg, var(--qr-accent), var(--qr-accent-dark));
        border: none;
        color: #fff;
        font-weight: 600;
        padding: 0.7rem 1rem;
        border-radius: 12px;
        transition: filter 0.15s ease, transform 0.1s ease;
    }

    .qr-btn-primary:hover {
        filter: brightness(1.05);
    }

    .qr-btn-primary:active {
        transform: scale(0.98);
    }

    .qr-btn-primary:disabled {
        opacity: 0.6;
    }

    .full-page-loader {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(15, 27, 26, 0.55);
        z-index: 1055;
        justify-content: center;
        align-items: center;
    }

    .loader-spinner {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.35);
        border-top-color: #fff;
        margin: 0 auto;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .loader-text {
        color: #fff;
        font-weight: 500;
    }

    /* ---------- Responsive ---------- */
    @media (max-width: 480px) {
        .qr-modal .modal-dialog {
            max-width: 100%;
            margin: 0;
            height: 100%;
            align-items: stretch;
        }

        .qr-modal .modal-content {
            border-radius: 0;
            height: 100%;
        }

        .qr-scan-body {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .qr-viewport {
            flex: 1;
            aspect-ratio: unset;
        }

        .billing-body {
            max-height: calc(100vh - 90px);
        }

        .upi-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .balance-cards {
            grid-template-columns: 1fr;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .scan-line {
            animation: none;
            top: 50%;
        }
    }
</style>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let html5QrCode;
        let currentCameraId = null;
        let availableCameras = [];
        let cameraIndex = 0;
        let torchOn = false;

        const statusText = document.getElementById("qrStatusText");
        const torchBtn = document.getElementById("qrTorchBtn");
        const switchCamBtn = document.getElementById("qrSwitchCamBtn");

        // Open the QR Code Scanner Modal when the scan button is clicked
        document.getElementById("my-qr-reader").addEventListener("click", function() {
            let qrModal = new bootstrap.Modal(document.getElementById("qrScannerModal"), {
                backdrop: "static",
                keyboard: false,
            });
            qrModal.show();
            startMainScanner();
        });

        function setStatus(msg) {
            if (statusText) statusText.textContent = msg;
        }

        function onScanSuccess(decodedText) {
            // Pause immediately so we don't fire multiple times on the same frame
            html5QrCode.pause(true);

            let qrModal = bootstrap.Modal.getInstance(document.getElementById("qrScannerModal"));
            if (qrModal) qrModal.hide();

            let parts = decodedText.split("|");
            let name = parts[0];

            $.ajax({
                type: "POST",
                url: "{{ route('verify.all_pos') }}",
                data: {
                    name: name,
                    _token: "{{ csrf_token() }}",
                },
                success: function(data) {
                    if (data.success == true) {
                        document.getElementById("qr-details-text").innerHTML = data.name;
                        document.getElementById("qrDataId").value = data.id;
                        document.getElementById("billing-pos-name").innerHTML = data.name;

                        stopScanner();

                        // Directly open Billing Modal
                        let billingModal = new bootstrap.Modal(document.getElementById(
                            "billingModal"), {
                            backdrop: "static",
                            keyboard: false,
                        });
                        billingModal.show();
                    } else {
                        stopScanner();
                        Swal.fire({
                            icon: "error",
                            title: "Invalid!",
                            text: "Kindly scan only the Freebazar QR code.",
                        });
                    }
                },
                error: function() {
                    resumeScanner();
                },
            });
        }

        function onScanFailure() {
            // Fired continuously while no QR is detected — ignore, this is normal.
        }

        function resumeScanner() {
            try {
                if (html5QrCode) html5QrCode.resume();
            } catch (e) {}
        }

        // Start the scanner, requesting the BACK camera by default (no camera-picker UI shown)
        function startMainScanner() {
            html5QrCode = new Html5Qrcode("qr-reader");
            setStatus("Requesting camera access…");

            const config = {
                fps: 12,
                qrbox: {
                    width: 250,
                    height: 250
                },
                aspectRatio: 1.0,
            };

            html5QrCode
                .start({
                    facingMode: {
                        exact: "environment"
                    }
                }, config, onScanSuccess, onScanFailure)
                .then(() => {
                    setStatus("Point your camera at a Freebazar QR code");
                    prepareControls();
                })
                .catch(() => {
                    // Fall back to a generic environment request, then to any available camera
                    html5QrCode
                        .start({
                            facingMode: "environment"
                        }, config, onScanSuccess, onScanFailure)
                        .then(() => {
                            setStatus("Point your camera at a Freebazar QR code");
                            prepareControls();
                        })
                        .catch(() => {
                            Html5Qrcode.getCameras()
                                .then((cameras) => {
                                    availableCameras = cameras || [];
                                    if (availableCameras.length) {
                                        currentCameraId = availableCameras[0].id;
                                        html5QrCode
                                            .start(currentCameraId, config, onScanSuccess,
                                                onScanFailure)
                                            .then(() => {
                                                setStatus(
                                                    "Point your camera at a Freebazar QR code"
                                                );
                                                prepareControls();
                                            });
                                    } else {
                                        setStatus("No camera found on this device.");
                                    }
                                })
                                .catch(() => {
                                    setStatus("Camera permission was denied.");
                                    Swal.fire({
                                        icon: "warning",
                                        title: "Camera access needed",
                                        text: "Please allow camera access in your browser to scan the QR code.",
                                    });
                                });
                        });
                });
        }

        function prepareControls() {
            // Populate camera list quietly for the flip button (does not show any UI)
            Html5Qrcode.getCameras()
                .then((cameras) => {
                    availableCameras = cameras || [];
                    if (currentCameraId) {
                        cameraIndex = availableCameras.findIndex((c) => c.id === currentCameraId);
                        if (cameraIndex < 0) cameraIndex = 0;
                    }
                    switchCamBtn.style.display = availableCameras.length > 1 ? "flex" : "none";
                })
                .catch(() => {
                    switchCamBtn.style.display = "none";
                });

            checkTorchSupport();
        }

        function checkTorchSupport() {
            torchOn = false;
            torchBtn.classList.remove("active");
            try {
                const capabilities = html5QrCode.getRunningTrackCameraCapabilities ?
                    html5QrCode.getRunningTrackCameraCapabilities() :
                    null;
                const supportsTorch = capabilities && capabilities.torchFeature && capabilities.torchFeature()
                    .isSupported();
                torchBtn.style.display = supportsTorch ? "flex" : "none";
            } catch (e) {
                torchBtn.style.display = "none";
            }
        }

        torchBtn.addEventListener("click", function() {
            if (!html5QrCode) return;
            torchOn = !torchOn;
            try {
                const capabilities = html5QrCode.getRunningTrackCameraCapabilities();
                capabilities.torchFeature().apply(torchOn);
                torchBtn.classList.toggle("active", torchOn);
            } catch (e) {
                torchOn = false;
            }
        });

        switchCamBtn.addEventListener("click", function() {
            if (!availableCameras.length || availableCameras.length < 2 || !html5QrCode) return;
            cameraIndex = (cameraIndex + 1) % availableCameras.length;
            const nextCameraId = availableCameras[cameraIndex].id;

            html5QrCode
                .stop()
                .then(() => html5QrCode.clear())
                .then(() => {
                    currentCameraId = nextCameraId;
                    return html5QrCode.start(
                        nextCameraId, {
                            fps: 12,
                            qrbox: {
                                width: 250,
                                height: 250
                            },
                            aspectRatio: 1.0
                        },
                        onScanSuccess,
                        onScanFailure
                    );
                })
                .then(() => checkTorchSupport())
                .catch(() => setStatus("Could not switch camera."));
        });

        function stopScanner() {
            if (html5QrCode) {
                html5QrCode
                    .stop()
                    .then(() => html5QrCode.clear())
                    .catch(() => {});
            }
        }

        // Stop the scanner if the QR scanner modal is manually closed
        document.getElementById("qrScannerModal").addEventListener("hidden.bs.modal", function() {
            stopScanner();
        });

        // Handle OK button click in the QR Details modal to open Billing Modal
        document.getElementById("openBillingModal").addEventListener("click", function() {
            let qrDetailsModal = bootstrap.Modal.getInstance(document.getElementById("qrDetailsModal"));
            if (qrDetailsModal) qrDetailsModal.hide();

            let billingModal = new bootstrap.Modal(document.getElementById("billingModal"), {
                backdrop: "static",
                keyboard: false,
            });
            billingModal.show();
        });
    });
</script>
<script>
    function selectUPI(selectedId) {
        document.getElementById(selectedId).checked = true;
    }

    function toggleExclusive(clickedCheckbox, otherId) {
        const otherCheckbox = document.getElementById(otherId);
        if (clickedCheckbox.checked) {
            otherCheckbox.checked = false;
        }
        if (typeof checkWalletBalance === "function") {
            checkWalletBalance();
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        let originalWalletBalance = parseFloat(document.getElementById('wallet_balance').textContent) || 0;
        let originalRewardBalance = parseFloat(document.getElementById('reward_balance').textContent) || 0;

        window.checkWalletBalance = function checkWalletBalance() {
            const billingAmount = parseFloat(document.getElementById('billing_amount').value) || 0;
            const walletBalanceElement = document.getElementById('wallet_balance');
            const rewardBalanceElement = document.getElementById('reward_balance');
            const checkedWallet = document.getElementById('checked-wallet').checked;
            const checkedReward = document.getElementById('checked-reward').checked;
            const payingAmountField = document.getElementById('paying_amount');
            const insufficientBalanceDiv = document.querySelector('.insufficient-balance');
            const alternativePayBySelect = document.getElementById('alternative_pay_by');
            const remainingAmountField = document.getElementById('remaining_amount');

            let walletDeduction = 0;
            let rewardDeduction = 0;
            let walletBalance = originalWalletBalance;
            let rewardBalance = originalRewardBalance;

            remainingAmountField.style.display = 'none';
            insufficientBalanceDiv.style.display = 'none';
            alternativePayBySelect.style.display = 'none';
            alternativePayBySelect.required = false;
            alternativePayBySelect.value = '';
            payingAmountField.value = Math.round(billingAmount);
            let pos_id = document.getElementById('qrDataId').value;

            if (checkedWallet) {
                walletDeduction = billingAmount;
                if (walletBalance >= walletDeduction) {
                    walletBalance -= walletDeduction;
                } else {
                    walletDeduction = walletBalance;
                    walletBalance = 0;
                }
                const remainingAmount1 = billingAmount - walletDeduction;
                payingAmountField.value = Math.round(remainingAmount1);
                walletBalanceElement.textContent = walletBalance.toFixed(2);
            } else if (checkedReward) {
                if (pos_id == 75) {
                    rewardDeduction = billingAmount * 0.02;
                } else {
                    rewardDeduction = billingAmount * 0.10;
                }
                if (rewardBalance >= rewardDeduction) {
                    rewardBalance -= rewardDeduction;
                } else {
                    rewardDeduction = rewardBalance;
                    rewardBalance = 0;
                }
                const remainingAmount2 = billingAmount - rewardDeduction;
                payingAmountField.value = Math.round(remainingAmount2);
                rewardBalanceElement.textContent = rewardBalance.toFixed(2);
                walletBalanceElement.textContent = walletBalance.toFixed(2);
            } else {
                if (walletBalance >= walletDeduction) {
                    walletBalance -= walletDeduction;
                } else {
                    walletDeduction = walletBalance;
                    walletBalance = 0;
                }
            }
        };

        document.getElementById('checked-wallet').addEventListener('change', checkWalletBalance);
        document.getElementById('checked-reward').addEventListener('change', checkWalletBalance);
        document.getElementById('billing_amount').addEventListener('input', checkWalletBalance);

        document.getElementById("qrForm").addEventListener("submit", function(event) {
            event.preventDefault();
            let formData = new FormData(this);

            document.getElementById("fullPageLoader").style.display = "flex";
            document.getElementById("submitPaymentButton").disabled = true;

            userPayment(formData);
        });

        function userPayment(formData) {
            $.ajax({
                url: "{{ route('user.payment') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                success: function(data) {
                    document.getElementById("fullPageLoader").style.display = "none";
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Transaction Success",
                            text: "Your Transaction was successful.",
                            showConfirmButton: true,
                            timer: 3000,
                            timerProgressBar: true,
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        document.getElementById("submitPaymentButton").disabled = false;
                        Swal.fire({
                            icon: "error",
                            title: "Payment Failed",
                            text: data.message || "Please try again.",
                        });
                    }
                },
                error: function() {
                    document.getElementById("fullPageLoader").style.display = "none";
                    document.getElementById("submitPaymentButton").disabled = false;
                    Swal.fire({
                        icon: "error",
                        title: "Something went wrong!",
                        text: "Please try again later.",
                    });
                },
            });
        }
    });
</script>
