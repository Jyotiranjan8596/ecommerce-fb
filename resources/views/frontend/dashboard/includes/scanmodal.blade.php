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
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="wallet_select" id="checked-wallet" checked
                                style="width: 18px; height: 18px; cursor: pointer;">
                            <label for="checked-wallet"
                                style="font-size: 16px; cursor: pointer; font-weight: 500; line-height: 18px;">
                                Use Wallet
                            </label>
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
                        <input type="number" class="form-control" id="billing_amount" name="billing_amount" required
                            min="0" step="any" placeholder="Enter Billing Amount"
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
                    <div class="form-group mb-3" id="pay_by_select">
                        <label for="pay_by" style="color: black" class="modal-label">Pay By</label>
                        <select class="form-control" id="pay_by" name="pay_by">
                            <option selected value="">Select Payment Method</option>
                            <option value="cash">Cash</option>
                            {{-- <option hidden value="wallet">Wallet</option> --}}
                            <option value="upi">UPI</option>
                        </select>
                    </div>
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
                    <div class="wallet-balance mb-3">
                        <strong style="color: black">Your Wallet Balance:
                        </strong>
                        <span id="wallet_balance">{{ $walletBalance }}</span>
                        <input type="hidden" name="wallet_balance" value="{{ $walletBalance }}" />
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
                        <button type="submit" class="btn btn-info">
                            Submit Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Password Confirmation Modal -->
{{-- <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordModalLabel">
                    Enter Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="passwordForm" method="post" action="{{ route('user.payment') }}">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <input type="hidden" name="user_id" value="{{ $user_profile->id }}" />
                    <input type="hidden" name="billing_amount" />
                    <!-- Example -->
                    <input type="hidden" name="paying_amount" />
                    <input type="hidden" name="amount_wallet" />
                    <input type="hidden" name="mobilenumber" />
                    <input type="hidden" name="pos_id" />
                    <input type="hidden" name="alternative_pay_by" />
                    <!-- Example -->
                    <input type="hidden" name="pay_by" />
                    <!-- Example -->
                    <input type="hidden" name="transaction_date" value="{{ now()->format('Y-m-d') }}" />
                    <div class="form-group mt-2">
                        <label for="password" class="modal-label">Login Password</label>
                        <input type="password" class="form-control" id="password" name="password" required />
                        <input type="hidden" name="form_data" id="form_data" />
                    </div>
                    <button type="submit" class="btn btn-info mt-2">
                        Verify & Proceed
                    </button>
                </form>
            </div>
        </div>
    </div>
</div> --}}
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
            // Initialize a new Html5QrcodeScanner instance
            htmlscanner = new Html5QrcodeScanner("qr-reader", {
                fps: 10,
                qrbox: 250
            });

            // Render the scanner and handle successful scan
            htmlscanner.render((decodedText, decodedResult) => {
                // Hide QR Scanner modal on successful scan
                let qrModal = bootstrap.Modal.getInstance(document.getElementById("qrScannerModal"));
                qrModal.hide();

                console.log("jyoti" + decodedText);

                let parts = decodedText.split('|');
                let name = parts[0]; // The name part before '|'
                let id = parts[1]; // The id part after '|'
                console.log(name);
                console.log(id);

                document.getElementById("qr-details-text").innerHTML = "Freebazar";
                document.getElementById("upi_ID").value = name;
                console.log(document.getElementById("upi_ID").value);
                // Store the ID in a hidden input field
                // document.getElementById("qrDataId").value = id;
                // console.log("POS ID: " + id);

                // Open the QR Details modal and stop the scanner
                let qrDetailsModal = new bootstrap.Modal(document.getElementById("qrDetailsModal"), {
                    backdrop: 'static',
                    keyboard: false
                });
                htmlscanner.clear(); // Properly stop the scanner
                // qrDetailsModal.show();

                // document.getElementById("billing-pos-name").innerHTML = "POS NAME: <b>" + name + "</b>";
                document.getElementById("billing-pos-name").innerHTML = "POS NAME: Freebazar";


                // Directly open Billing Modal without showing QR Details Modal
                let billingModal = new bootstrap.Modal(document.getElementById("billingModal"), {
                    backdrop: 'static',
                    keyboard: false
                });
                billingModal.show();
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

        function checkWalletBalance() {
            const billingAmount = parseFloat(document.getElementById('billing_amount').value) || 0;
            const walletBalanceElement = document.getElementById('wallet_balance');
            const payBySelect = document.getElementById('pay_by');
            const checkedWallet = document.getElementById('checked-wallet').checked;
            const select_wallet = document.getElementById('select-wallet');
            const payingAmountField = document.getElementById('paying_amount');
            const insufficientBalanceDiv = document.querySelector('.insufficient-balance');
            const alternativePayBySelect = document.getElementById('alternative_pay_by');
            const remainingAmountField = document.getElementById('remaining_amount');
            const sponsors_count = document.getElementById('sponsors_count').value;
            let walletDeduction = 0;
            // Reset wallet balance and fields
            let walletBalance = originalWalletBalance;
            remainingAmountField.style.display = 'none';
            insufficientBalanceDiv.style.display = 'none';
            alternativePayBySelect.style.display = 'none';
            alternativePayBySelect.required = false;
            alternativePayBySelect.value = '';
            payingAmountField.value = Math.round(billingAmount);
            console.log(payBySelect);

            console.log(checkedWallet);

            // Cash or UPI payment logic
            // Calculate 5% deduction
            if (sponsors_count >= 10) {
                console.log(walletBalance);

                payingAmountField.value = Math.round(billingAmount);
                if (checkedWallet) {
                    console.log("comming to check wallet");
                    document.getElementById("pay_by_select").style.display = 'none';
                    payBySelect.value = "wallet";
                    if (walletBalance >= billingAmount) {
                        walletBalance -= billingAmount;
                        payingAmountField.value = 0; // Fully paid by wallet
                        walletBalanceElement.textContent = walletBalance.toFixed(2);
                    } else {
                        const remainingAmount = billingAmount - walletBalance;
                        walletBalance = 0;
                        remainingAmountField.style.display = 'block';
                        remainingAmountField.value = remainingAmount.toFixed(2);
                        insufficientBalanceDiv.style.display = 'block';
                        alternativePayBySelect.style.display = 'block';
                        alternativePayBySelect.required = true;
                        payingAmountField.value = Math.round(remainingAmount); // Remaining amount to be paid
                        walletBalanceElement.textContent = walletBalance.toFixed(2);
                    }
                } else {
                    document.getElementById("pay_by_select").style.display = 'block';
                    // payBySelect.value = "";
                }
                if (payBySelect.value === "cash" || payBySelect.value === "upi") {
                    console.log("comming to pay check");
                    // Cash or UPI payment logic
                    //  walletDeduction = billingAmount * 0.05; // Calculate 5% deduction
                    if (walletBalance >= walletDeduction) {
                        walletBalance -= walletDeduction;
                    } else {
                        walletDeduction = walletBalance;
                        walletBalance = 0;
                    }

                    const remainingAmount = billingAmount - walletDeduction;
                    walletBalanceElement.textContent = walletBalance.toFixed(2);
                    payingAmountField.value = Math.round(remainingAmount) // Amount to be paid
                }
            } else {
                if (checkedWallet) {
                    // document.getElementById("pay_by_select").style.display = 'none';
                    console.log("without sponsor");
                    walletDeduction = billingAmount * 0.05;
                    if (walletBalance >= walletDeduction) {
                        walletBalance -= walletDeduction;
                    } else {
                        walletDeduction = walletBalance;
                        walletBalance = 0;
                    }
                    const remainingAmount1 = billingAmount - walletDeduction;
                    payingAmountField.value = Math.round(remainingAmount1);
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

                const remainingAmount = billingAmount - walletDeduction;
                walletBalanceElement.textContent = walletBalance.toFixed(2);
                payingAmountField.value = Math.round(remainingAmount) // Amount to be paid
            }


            // Amount to be paid


            // Update the wallet balance display

        }

        // Event listeners for real-time updates
        document.getElementById('checked-wallet').addEventListener('change', checkWalletBalance);
        document.getElementById('pay_by').addEventListener('change', checkWalletBalance);
        document.getElementById('billing_amount').addEventListener('input', checkWalletBalance);

        document.getElementById("qrForm").addEventListener("submit", function(event) {

            event.preventDefault(); // Prevent form from submitting normally
            // Create FormData object
            console.log("comming");
            let formData = new FormData(this);
            console.log(formData);

            // let selectedUPI = document.querySelector('input[name="upi_provider"]:checked').value;
            let payingAmount = document.getElementById("paying_amount").value;
            let payBy = document.getElementById("pay_by").value;
            const sponsors_counts = document.getElementById('sponsors_count').value;
            // let testUpiName = document.getElementById("qr-details-text").value;
            // console.log(testUpiName);
            if (sponsors_counts >= 10) {
                if (payBy == "upi") {
                    let upiUrl = document.getElementById("upi_ID").value;
                    console.log(upiUrl);

                    // Open UPI scanners
                    userPayment(formData, payBy);
                    window.location.href = upiUrl; // Use replace instead of href to avoid going back

                } else {
                    userPayment(formData, payBy);
                }
            } else {
                if (!payingAmount || payingAmount <= 0) {
                    alert("Please enter a valid amount.");
                    return;
                }
                if (payBy == "upi") {
                    let upiUrl = document.getElementById("upi_ID").value;
                    console.log(upiUrl);

                    // Open UPI scanners
                    userPayment(formData, payBy);
                    window.location.href = upiUrl; // Use replace instead of href to avoid going back

                } else {
                    userPayment(formData, payBy);
                }
            }
        });

        function userPayment(formData, payBy) {
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
                        console.log("Pay by", payBy);
                        
                        if (payBy == "upi") {
                            Swal.fire({
                                icon: "info",
                                title: "Please pay only on <b>CRED</b>",
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                                customClass: {
                                    title: 'swal2-title-custom',
                                },
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: "success",
                                title: "Payment Success",
                                toast: true,
                                position: 'top-end', // or 'bottom-end' / 'top-start' etc.
                                showConfirmButton: false,
                                timer: 2000, // Duration in ms
                                timerProgressBar: true,
                            }).then(() => {
                                window.location.reload();
                            });
                        }

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

<script>
    // document.querySelector('#qrForm button[data-bs-target="#passwordModal"]').addEventListener('click', function() {
    //     const qrForm = document.getElementById('qrForm');
    //     const passwordForm = document.getElementById('passwordForm');

    //     const billingAmount = parseFloat(qrForm.querySelector('input[name="billing_amount"]').value) || 0;
    //     const payingAmount = parseFloat(qrForm.querySelector('input[name="paying_amount"]').value) || 0;
    //     const walletAmount = billingAmount - payingAmount;
    //     const selectedUPI = document.querySelector('input[name="upi_provider"]:checked');
    //     const walletCheckbox = document.getElementById("checked-wallet");

    //     // Copy data to the passwordForm
    //     passwordForm.querySelector('input[name="user_id"]').value = qrForm.querySelector(
    //             'input[name="user_id"]')
    //         .value;
    //     passwordForm.querySelector('input[name="billing_amount"]').value = billingAmount;
    //     passwordForm.querySelector('input[name="paying_amount"]').value = payingAmount;
    //     passwordForm.querySelector('input[name="amount_wallet"]').value =walletAmount; // Set wallet amount here
    //     passwordForm.querySelector('input[name="mobilenumber"]').value = qrForm.querySelector('input[name="mobilenumber"]').value;
    //     passwordForm.querySelector('input[name="pos_id"]').value = qrForm.querySelector('input[name="pos_id"]').value;
    //     passwordForm.querySelector('input[name="pay_by"]').value = qrForm.querySelector('select[name="pay_by"]').value;

    //     // chat gpt code 
    //     document.getElementById("passwordForm").addEventListener("submit", function(event) {
    //         event.preventDefault(); // Prevent form from submitting normally

    //         let password = document.getElementById("password").value; // Get password input

    //         if (password.trim() === "") {
    //             Swal.fire({
    //                 icon: "error",
    //                 title: "Oops...",
    //                 text: "Password cannot be empty!",
    //             });
    //             return;
    //         }

    //         // Create FormData object
    //         let formData = new FormData(this);
    //         if (selectedUPI) {
    //             formData.append("upi_provider", selectedUPI.value);
    //         } else {
    //             formData.append("upi_provider", ""); // No selection
    //         }
    //         // Append wallet checkbox value (1 if checked, 0 if not)
    //         formData.append("wallet_select", walletCheckbox.checked ? "1" : "0");

    //         fetch("{{ route('user.payment') }}", {
    //                 method: "POST",
    //                 body: formData,
    //                 headers: {
    //                     "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
    //                 }
    //             })
    //             .then(response => response.json())
    //             .then(data => {
    //                 if (data.success) {
    //                     Swal.fire({
    //                         icon: "success",
    //                         title: "Payment Success",
    //                         html: `<b>Paying Amount: <del style="color: silver;">₹${data.billing_amount}</del> ₹${data.paying_amount}</b>`,
    //                         showConfirmButton: true
    //                     }).then(() => {
    //                         // Close password modal
    //                         let passwordModal = bootstrap.Modal.getInstance(document
    //                             .getElementById(
    //                                 "passwordModal"));
    //                         if (passwordModal) passwordModal.hide();

    //                         // Close Billing Modal
    //                         let billingModal = new bootstrap.Modal(document.getElementById(
    //                             "billingModal"));
    //                         if (billingModal) billingModal.hide();

    //                         window.location.reload();
    //                     });
    //                 } else {
    //                     Swal.fire({
    //                         icon: "error",
    //                         title: "Invalid Password",
    //                         text: data.message || "Please try again.",
    //                     });
    //                 }
    //             })
    //             .catch(error => {
    //                 console.error("Error:", error);
    //                 Swal.fire({
    //                     icon: "error",
    //                     title: "Something went wrong!",
    //                     text: "Please try again later.",
    //                 });
    //             });
    //     });
    // });
</script>
