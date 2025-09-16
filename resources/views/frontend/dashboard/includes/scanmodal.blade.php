{{-- Modals  --}}
<!-- QR Code Scanner Modal -->
<div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrScannerModalLabel">
                    Scan QR Code
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="qr-reader" style="width: 100%;height: 300px;background-color: rgb(238, 179, 92);">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Details Modal -->
<div class="modal fade" id="qrDetailsModal" tabindex="-1" aria-labelledby="qrDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrDetailsModalLabel">
                    QR Code Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 id="qr-details-text"></h5>
                {{-- <input type="text" id="qrData" name="pos_id" /> --}}
                <button id="openBillingModal" class="btn btn-primary">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Billing Modal -->
<div class="modal fade" id="billingModal" tabindex="-1" aria-labelledby="billingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="billingModalLabel" style="color: black">
                    Billing Information
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="qrForm" method="post" action="{{ route('user.payment') }}">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div style="display: flex; justify-content: space-between">
                        <p id="billing-pos-name"><b>Not Scanned</b></p> <!-- Display POS Name Here -->
                        <div style="display: block">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <input type="checkbox" name="reward_select" id="checked-reward" checked
                                    style="width: 18px; height: 18px; cursor: pointer;"
                                    onchange="toggleExclusive(this, 'checked-wallet')">
                                <label for="checked-reward"
                                    style="font-size: 16px; cursor: pointer; font-weight: 500; line-height: 18px;">
                                    Reward Points
                                </label>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <input type="checkbox" name="wallet_select" id="checked-wallet"
                                    style="width: 18px; height: 18px; cursor: pointer;"
                                    onchange="toggleExclusive(this, 'checked-reward')">
                                <label for="checked-wallet"
                                    style="font-size: 16px; cursor: pointer; font-weight: 500; line-height: 18px;">
                                    Use Wallet
                                </label>
                            </div>
                        </div>
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
                        <label for="billing_amount" style="color: black" class="modal-label">Billing
                            Amount</label>
                        <input type="number" class="form-control" id="billing_amount" name="billing_amount"
                            required min="0" step="any" placeholder="Enter Billing Amount"
                            oninput="checkWalletBalance()" />
                    </div>
                    <input id="sponsors_count" type="hidden" name="wallet_balance" value="{{ $sponsors_count }}" />
                    <!-- Paying Amount -->
                    <div class="form-group mb-3">
                        <label for="paying_amount" style="color: black;">Paying
                            Amount</label>
                        <input type="number" class="form-control" name="paying_amount" id="paying_amount" required
                            min="0" readonly />
                    </div>
                    <!-- Pay By -->
                    <div id="upi-options" class="form-group mb-3" style="display: none;">
                        <label style="color: black" class="modal-label">Select UPI Provider</label>
                        <div class="d-flex gap-3">
                            <div>
                                <input type="radio" id="gpay" name="upi_provider" value="googlepay" hidden
                                    class="upi-radio">
                                <label for="gpay" style="cursor: pointer;">
                                    <img width="48" height="48" class="upi-image"
                                        src="https://img.icons8.com/fluency/48/google-pay-new.png" alt="googlePay"
                                        onclick="selectUPI('gpay')" />
                                </label>
                            </div>
                            <div>
                                <input type="radio" id="phonepe" name="upi_provider" value="phonepe" hidden
                                    class="upi-radio">
                                <label for="phonepe" style="cursor: pointer;">
                                    <img width="48" height="48" class="upi-image"
                                        src="https://img.icons8.com/color/48/phone-pe.png" alt="phone-pe"
                                        onclick="selectUPI('phonepe')" />
                                </label>
                            </div>
                            <div>
                                <input type="radio" id="paytm" name="upi_provider" value="paytm" hidden
                                    class="upi-radio">
                                <label for="paytm" style="cursor: pointer;">
                                    <img width="48" height="48" class="upi-image"
                                        src="https://img.icons8.com/fluency/48/paytm.png" alt="paytm"
                                        onclick="selectUPI('paytm')" />
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Wallet Balance -->
                    <div>
                        <div class="wallet-balance mb-3">
                            <strong style="color: black">Wallet Balance:
                            </strong>
                            <span id="wallet_balance">{{ $walletBalance }}</span>
                            <input type="hidden" name="wallet_balance" value="{{ $walletBalance }}" />
                        </div>
                        <div class="reward-balance mb-3">
                            <strong style="color: black">Reward Points:
                            </strong>
                            <span id="reward_balance">{{ $rewardBalance }}</span>
                            <input type="hidden" name="reward_balance" value="{{ $rewardBalance }}" />
                        </div>
                    </div>

                    <!-- Insufficient Balance -->
                    <div class="insufficient-balance mb-3" style="display: none">
                        <label class="modal-label" style="color: black">Alternative Payment Method for
                            Remaining
                            Paying
                            Amount:</label>
                        <select name="alternative_pay_by" id="alternative_pay_by" class="form-control" required
                            style="display: none">
                            <option value="">Select Payment Method</option>
                            <option value="cash">Cash</option>
                            <option value="upi">UPI</option>
                        </select>
                    </div>

                    <!-- Remaining Balance -->
                    <div class="remaining-balance mb-3" style="display: none">
                        <label for="remaining_amount" style="color: black">Remaining Amount to be
                            Paid:</label>
                        <input type="text" id="remaining_amount" class="form-control" readonly />
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" id="submitPaymentButton" class="btn btn-info">
                            Submit Payment
                        </button>
                    </div>
                    <!-- Loader -->
                    <!-- Full Page Loader -->
                    <div id="fullPageLoader"
                        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1055; justify-content: center; align-items: center;">
                        <div class="text-center">
                            <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-white mt-3">Processing Payment...</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Reference to scanner instance for global access
        let htmlscanner;

        // Open the QR Code Scanner Modal when the scan button is clicked
        document.getElementById("my-qr-reader").addEventListener("click", function() {
            let qrModal = new bootstrap.Modal(document.getElementById("qrScannerModal"), {
                backdrop: 'static',
                keyboard: false
            });
            qrModal.show();
            startMainScanner();
        });

        // QR Code Scanner function
        function startMainScanner() {
            const config = {
                fps: 10,
                qrbox: 250,
                // Specify camera facing mode to prefer back camera
                // This is passed to getUserMedia constraints
                experimentalFeatures: {
                    useBarCodeDetectorIfSupported: true // optional
                },
                rememberLastUsedCamera: true,
                supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
            };

            htmlscanner = new Html5QrcodeScanner("qr-reader", config);

            // Pass constraints for back camera
            Html5Qrcode.getCameras().then(cameras => {
                if (cameras && cameras.length) {
                    let cameraId = cameras.find(cam => cam.label.toLowerCase().includes("back"))?.id ||
                        cameras[0].id;
                    htmlscanner.render((decodedText, decodedResult) => {
                        let qrModal = bootstrap.Modal.getInstance(document.getElementById(
                            "qrScannerModal"));
                        qrModal.hide();

                        console.log("Scanned text:", decodedText);
                        // handle the scanned result...
                    }, {
                        facingMode: {
                            exact: "environment"
                        }
                    }, cameraId);
                }
            }).catch(err => {
                console.error("Camera not found or error:", err);
            });
        }


        // Stop the scanner if the QR scanner modal is manually closed
        document.getElementById("qrScannerModal").addEventListener('hidden.bs.modal', function() {
            if (htmlscanner) {
                htmlscanner.clear(); // Ensure scanner is stopped
            }
        });
        // Handle OK button click in the QR Details modal to open Billing Modal
        document.getElementById("openBillingModal").addEventListener("click", function() {
            let qrDetailsModal = bootstrap.Modal.getInstance(document.getElementById("qrDetailsModal"));
            qrDetailsModal.hide();

            let billingModal = new bootstrap.Modal(document.getElementById("billingModal"), {
                backdrop: 'static',
                keyboard: false
            });
            billingModal.show();
        });
    });
</script>
<script>
    function selectUPI(selectedId) {
        // Remove border from all images first
        document.querySelectorAll('.upi-image').forEach(img => {
            img.style.border = "none";
        });

        // Get the radio button
        let radioButton = document.getElementById(selectedId);

        // Toggle selection: If it's not checked, check it and apply a border
        radioButton.checked = true;
        if (radioButton.checked) {
            document.querySelector(`label[for="${selectedId}"] img`).style.border = "2px solid black";
        }
    }

    function toggleExclusive(clickedCheckbox, otherId) {
        const otherCheckbox = document.getElementById(otherId);
        if (clickedCheckbox.checked) {
            otherCheckbox.checked = false;
        }
    }
    // document.getElementById("pay_by").addEventListener("change", function() {
    //     let upiOptions = document.getElementById("upi-options");

    //     if (this.value === "upi") {
    //         upiOptions.style.display = "block";
    //     } else {
    //         upiOptions.style.display = "none";
    //     }
    // });
    document.addEventListener("DOMContentLoaded", () => {
        console.log("comming here");

        let originalWalletBalance = parseFloat(document.getElementById('wallet_balance').textContent) || 0;
        let originalRewardBalance = parseFloat(document.getElementById('reward_balance').textContent) || 0;


        function checkWalletBalance() {
            const billingAmount = parseFloat(document.getElementById('billing_amount').value) || 0;
            const walletBalanceElement = document.getElementById('wallet_balance');
            const rewardBalanceElement = document.getElementById('reward_balance');
            // const payBySelect = document.getElementById('pay_by');
            const checkedWallet = document.getElementById('checked-wallet').checked;
            const checkedReward = document.getElementById('checked-reward').checked;
            const select_wallet = document.getElementById('select-wallet');
            const payingAmountField = document.getElementById('paying_amount');
            const insufficientBalanceDiv = document.querySelector('.insufficient-balance');
            const alternativePayBySelect = document.getElementById('alternative_pay_by');
            const remainingAmountField = document.getElementById('remaining_amount');
            const sponsors_count = document.getElementById('sponsors_count').value;
            let walletDeduction = 0;
            let rewardDeduction = 0;
            // Reset wallet balance and fields
            let walletBalance = originalWalletBalance;
            let rewardBalance = originalRewardBalance;
            remainingAmountField.style.display = 'none';
            insufficientBalanceDiv.style.display = 'none';
            alternativePayBySelect.style.display = 'none';
            alternativePayBySelect.required = false;
            alternativePayBySelect.value = '';
            payingAmountField.value = Math.round(billingAmount);
            let pos_id = document.getElementById('qrDataId').value;
            console.log(pos_id);

            if (checkedWallet) {
                // document.getElementById("pay_by_select").style.display = 'none';
                console.log("without sponsor");
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
                console.log(rewardBalance);
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
                console.log(remainingAmount2);

                payingAmountField.value = Math.round(remainingAmount2);
                rewardBalanceElement.textContent = rewardBalance.toFixed(2);
                walletBalanceElement.textContent = walletBalance.toFixed(2);
            } else {
                // document.getElementById("pay_by_select").style.display = 'none';
                if (walletBalance >= walletDeduction) {
                    walletBalance -= walletDeduction;
                } else {
                    walletDeduction = walletBalance;
                    walletBalance = 0;
                }
            }

            // const remainingAmount = billingAmount - walletDeduction;
            // walletBalanceElement.textContent = walletBalance.toFixed(2);
            // payingAmountField.value = Math.round(remainingAmount) // Amount to be paid
            // }


            // Amount to be paid


            // Update the wallet balance display

        }

        // Event listeners for real-time updates
        document.getElementById('checked-wallet').addEventListener('change', checkWalletBalance);
        document.getElementById('checked-reward').addEventListener('change', checkWalletBalance);
        // document.getElementById('pay_by').addEventListener('change', checkWalletBalance);
        document.getElementById('billing_amount').addEventListener('input', checkWalletBalance);

        document.getElementById("qrForm").addEventListener("submit", function(event) {

            event.preventDefault(); // Prevent form from submitting normally
            // Create FormData object
            console.log("comming");
            let formData = new FormData(this);
            console.log(formData);

            // let selectedUPI = document.querySelector('input[name="upi_provider"]:checked').value;
            let payingAmount = document.getElementById("paying_amount").value;
            // let payBy = document.getElementById("pay_by").value;
            const sponsors_counts = document.getElementById('sponsors_count').value;

            document.getElementById("fullPageLoader").style.display = "flex";
            document.getElementById("submitPaymentButton").disabled = true;
            // Open UPI scanners
            userPayment(formData);

        });

        function userPayment(formData) {
            $.ajax({
                url: "{{ route('user.payment') }}",
                type: "POST",
                data: formData,
                processData: false, // Prevent jQuery from converting data
                contentType: false, // Prevent jQuery from setting content type
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                success: function(data) {
                    console.log("Response JSON:", data); // ✅ Log response JSON
                    if (data.success) {

                        Swal.fire({
                            icon: "success",
                            title: "Transaction Success",
                            text: "Your Transaction was successful.",
                            showConfirmButton: true,
                            timer: 3000, // optional
                            timerProgressBar: true,
                        }).then(() => {
                            window.location.reload();
                        });
                        // }
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Payment Failed",
                            text: data.message || "Please try again.",
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    document.getElementById("fullPageLoader").style.display = "flex";
                    document.getElementById("submitPaymentButton").disabled = true;
                    // Swal.fire({
                    //     icon: "error",
                    //     title: "Something went wrong!",
                    //     text: "Please try again later.",
                    // });
                },
            });
        }
    });
</script>
