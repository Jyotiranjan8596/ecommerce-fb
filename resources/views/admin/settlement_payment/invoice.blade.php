<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $settlement->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            color: #333;
            font-size: 14px;
        }

        .invoice-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Header Section - DomPDF Compatible */
        .invoice-header {
            background-color: #4a5568;
            color: white;
            padding: 25px;
            margin-bottom: 20px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
            padding: 0;
        }

        .company-info {
            width: 60%;
        }

        .logo-space {
            width: 150px;
            /* background-color: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.5); */
            text-align: center;
            margin-bottom: 10px;
            color: white;
            padding-left: 25px;
        }

        /* Desktop padding for logo */
        @media (min-width: 768px) {
            .logo-space {
                width: 100px;
            }
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .company-details {
            font-size: 13px;
            line-height: 1.3;
        }

        .invoice-info {
            width: 40%;
            text-align: right;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .invoice-details {
            font-size: 13px;
        }

        /* Body Section */
        .invoice-body {
            padding: 0 25px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #4a5568;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e2e8f0;
        }

        .billing-section {
            margin-bottom: 25px;
        }

        .billing-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #4a5568;
        }

        .billing-info div {
            margin-bottom: 3px;
        }

        /* Status Section */
        .status-section {
            text-align: right;
            margin-bottom: 20px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid;
        }

        .status-pending {
            color: #92400e;
            background-color: #fef3c7;
            border-color: #f59e0b;
        }

        .status-rejected {
            color: #991b1b;
            background-color: #fee2e2;
            border-color: #dc2626;
        }

        .status-settled {
            color: #065f46;
            background-color: #d1fae5;
            border-color: #10b981;
        }

        /* Settlement Table - DomPDF Compatible */
        .settlement-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            border: 1px solid #d1d5db;
        }

        .settlement-table thead {
            background-color: #4a5568;
            color: white;
        }

        .settlement-table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 13px;
            border: 1px solid #d1d5db;
        }

        .settlement-table th:last-child {
            text-align: right;
        }

        .settlement-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }

        .settlement-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .settlement-table td {
            padding: 10px 12px;
            font-size: 13px;
            border: 1px solid #d1d5db;
        }

        .settlement-table td:first-child {
            font-weight: normal;
        }

        .settlement-table td:last-child {
            text-align: right;
            font-weight: bold;
        }

        .amount-positive {
            color: #059669;
        }

        .amount-negative {
            color: #dc2626;
        }

        /* Footer */
        .invoice-footer {
            background-color: #f8f9fa;
            padding: 20px 25px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            margin-top: 30px;
        }

        .footer-content {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.4;
        }

        .footer-content p {
            margin-bottom: 4px;
        }

        .highlight {
            color: #4a5568;
            font-weight: bold;
        }

        /* Utility Classes */
        .text-bold {
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .mb-15 {
            margin-bottom: 15px;
        }

        .mb-20 {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <table class="header-table">
                <tr>
                    <td class="company-info">
                        <div class="logo-space">
                            <img src="{{ public_path('assets/images/logofreebazar3.png') }}" alt="Logo"
                                style="max-width: 150px;">
                        </div>
                        <div class="company-name">Freebazar Pvt Ltd</div>
                        <div class="company-details">
                            <div>Bhubaneswar, Odisha</div>
                            <div>support@freebazar.com</div>
                            <div>+91-9876543210</div>
                        </div>
                    </td>
                    <td class="invoice-info">
                        <div class="invoice-title">INVOICE</div>
                        <div class="invoice-details">
                            <div><strong>Invoice #:</strong> {{ $settlement->id }}</div>
                            <div><strong>Date:</strong>
                                {{ \Carbon\Carbon::parse($settlement->intiate_date)->format('d-m-Y') }}</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Body -->
        <div class="invoice-body">
            <!-- Customer Info -->
            <div class="billing-section">
                <h3 class="section-title">Bill To</h3>
                <div class="billing-info">
                    <div class="text-bold">{{ $settlement->creator->name }}</div>
                    <div>POS ID: {{ $settlement->creator->id }}</div>
                    <div>Email: {{ $settlement->creator->email ?? 'N/A' }}</div>
                </div>
            </div>

            <!-- Status -->
            <div class="status-section">
                @if ($settlement->status == 'pending')
                    <span class="status-badge status-pending">Pending</span>
                @elseif ($settlement->status == 'rejected')
                    <span class="status-badge status-rejected">Rejected</span>
                @else
                    <span class="status-badge status-settled">Settled</span>
                @endif
            </div>

            <!-- Settlement Details Table -->
            <table class="settlement-table">
                <thead>
                    <tr>
                        <th>Settlement Details</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total Transactions</td>
                        <td>{{ $settlement->total_transaction }}</td>
                    </tr>
                    <tr>
                        <td>Billing Amount</td>
                        <td>{{ number_format($settlement->total_billing_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Cash / UPI</td>
                        <td>{{ number_format($settlement->by_cash, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Wallet</td>
                        <td>{{ number_format($settlement->by_wallet, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Reward</td>
                        <td>{{ number_format($settlement->by_reward, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Credit</td>
                        <td class="amount-positive">+{{ number_format($settlement->admin_credit, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Debit</td>
                        <td class="amount-negative">-{{ number_format($settlement->admin_debit, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="footer-content">
                <p>Thank you for partnering with <span class="highlight">Freebazar</span>.</p>
                <p>This is a system-generated invoice and does not require a signature.</p>
                <p style="margin-top: 10px; font-size: 11px;">
                    For any queries, please contact us at support@freebazar.com
                </p>
            </div>
        </div>
    </div>
</body>

</html>
