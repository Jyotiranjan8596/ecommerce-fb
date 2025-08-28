<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreeBazar Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: white;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 0;
        }

        .header {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid #000;
        }

        .logo {
            margin-bottom: 10px;
        }

        .logo-image {
            max-height: 80px;
            max-width: 300px;
            height: auto;
            width: auto;
        }

        .company-details {
            font-size: 14px;
            color: #333;
            line-height: 1.4;
        }

        .invoice-info {
            display: flex;
            justify-content: space-between;
            padding: 10px 20px;
            border-bottom: 1px solid #000;
        }

        .bill-to {
            flex: 1;
        }

        .bill-details {
            text-align: right;
        }

        .bill-to h3,
        .bill-details h3 {
            margin: 0 0 4px 0;
            /* tightened margin */
            font-size: 16px;
            font-weight: bold;
        }

        .bill-to p,
        .bill-details p {
            margin: 0;
            /* removed extra vertical gaps */
            font-size: 14px;
            color: #333;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th {
            background-color: #f8f9fa;
            border: none;
            border-bottom: 1px solid #000;
            padding: 10px 8px;
            text-align: left;
            font-size: 14px;
            font-weight: bold;
        }

        .items-table td {
            border: none;
            padding: 8px;
            font-size: 13px;
        }

        .items-table .sr-no {
            width: 40px;
            text-align: center;
        }

        .items-table .particulars {
            width: 60%;
        }

        .items-table .amount {
            width: 100px;
            text-align: right;
        }

        .total-row {
            font-weight: bold;
        }

        .total-section {
            border-top: 1px solid #000;
        }

        .total-section td {
            border: none;
        }

        .receivable-payable {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 8px;
        }

        .footer {
            border-top: 1px solid #000;
            padding: 5px 20px;
            /* reduced padding */
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            border: none;
            padding: 5px;
            vertical-align: top;
        }

        .footer-left {
            width: 40%;
            text-align: left;
        }

        .footer-center {
            width: 21%;
            text-align: center;
        }

        .footer-right {
            width: 40%;
            text-align: right;
        }

        .notes {
            font-size: 16px;
        }

        .qr-code {
            width: 80px;
            height: 80px;
            background-color: #000;
            color: white;
            font-size: 12px;
            text-align: center;
            line-height: 80px;
            margin: 0 auto;
        }

        .bank-logo {
            background-color: #004B9F;
            color: white;
            padding: 4px 8px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
            text-align: center;
        }

        .bank-logo img {
            height: 20px;
            width: 20px
        }

        .team-name {
            font-weight: bold;
            font-size: 18px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                {{-- <img src="freebazar-logo.png" alt="FreeBazar Logo" class="logo-image"> --}}
                <img src="{{ public_path('assets/images/logofreebazar3.png') }}" alt="Freebazar Logo" class="logo-image">
            </div>
            <div class="company-details">
                Plot-424 , Nayapalli, Bhubaneswar<br>
                Mob - 9853560459, website - freebazar.in<br>
                GSTIN - 21AAHCS0488R1ZX
            </div>
        </div>

        <!-- Invoice Information -->
        <div class="invoice-info">
            <div class="bill-to">
                <h3>BILL TO :</h3>
                <p><strong>{{ $settlement['pos_name'] }} (ID {{ $settlement['pos_user_id'] }})</strong></p>
                <p>PLOT NO. 1625, NILAKANTHA NAGAR,</p>
                <p>BHUBANESWAR, 751012</p>
                <p>MOB: 9853560459</p>
            </div>
            <div class="bill-details">
                <p>Bill Date: {{ $settlement['bill_date'] }}</p>
                <p>INVOICE. NO.05 / 20-08-25</p>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="sr-no"></th>
                    <th class="particulars">PARTICULARS</th>
                    <th class="amount">AMOUNT<br>IN (RS.)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="sr-no">1</td>
                    <td>No of Transaction</td>
                    <td class="amount">{{ $settlement['total_transaction'] }}</td>
                </tr>
                <tr>
                    <td class="sr-no">2</td>
                    <td>Total Billing Amount</td>
                    <td class="amount">{{ $settlement['total_billing_amount'] }}</td>
                </tr>
                <tr>
                    <td class="sr-no">3</td>
                    <td>Received Cash/ UPI</td>
                    <td class="amount">{{ $settlement['by_cash'] }}</td>
                </tr>
                <tr>
                    <td class="sr-no">4</td>
                    <td>Trade Discount(Payable)</td>
                    <td class="amount">{{ $settlement['by_wallet'] }}</td>
                </tr>
                <tr>
                    <td class="sr-no">5</td>
                    <td>Reward points redeemed</td>
                    <td class="amount">{{ $settlement['by_reward'] }}</td>
                </tr>
                <tr>
                    <td class="sr-no">6</td>
                    <td>Wallet balance redeemed</td>
                    <td class="amount">{{ $settlement['by_wallet'] }}</td>
                </tr>
                {{-- <tr>
                    <td class="sr-no">7</td>
                    <td>Rounded off</td>
                    <td class="amount">300.00</td>
                </tr> --}}
            </tbody>
        </table>

        <!-- Total Section -->
        <table class="items-table total-section">
            <tr class="total-row">
                <td style="border: none; padding-left: 20px;">
                    <strong>TOTAL RS.</strong><br>
                    <span style="font-size: 10px;">(Rs. {{ $settlement['in_letter'] }})</span>
                </td>
                <td style="border: none; padding-right: 20px;">
                    <div class="receivable-payable">
                        <div>
                            <div>Receivable</div>
                            <div>Payable</div>
                        </div>
                        <div style="text-align: right; margin: 0%">
                            <div>{{ $settlement['credit'] }}</div>
                            <div><strong>{{ $settlement['debit'] }}</strong></div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Notes and Footer Combined -->
        <div class="footer">
            <table class="footer-table">
                <tr>
                    <td class="footer-left">
                        <div class="notes">
                            <strong>Note:</strong><br>
                            1 &nbsp; Please pay before 2 PM today.<br>
                            2 &nbsp; E&OE
                        </div>
                    </td>
                    <td class="footer-right">
                        <div class="team-name">Team Freebazar</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
