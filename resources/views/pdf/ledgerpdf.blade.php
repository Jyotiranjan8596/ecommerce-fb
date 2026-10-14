<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #000;
        }

        .print-meta {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            margin-bottom: 5px;
        }

        .header {
            text-align: center;
            margin-bottom: 8px;
        }

        .brand {
            color: #174b54;
            font-size: 20px;
            font-weight: bold;
        }

        .company-info {
            font-size: 9px;
            margin-top: 4px;
            line-height: 1.4;
        }

        .ledger-title-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            border-bottom: 2px solid #000;
            padding-bottom: 4px;
            margin-bottom: 0;
        }

        .ledger-title {
            font-size: 15px;
            font-weight: bold;
            text-decoration: underline;
        }

        .ledger-account {
            font-size: 11px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }

        th {
            background: #fff;
            text-align: left;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .voucher-type {
            font-weight: normal;
        }

        .remarks {
            font-style: italic;
            font-size: 9.5px;
            color: #000;
            margin-top: 2px;
        }

        .bold {
            font-weight: bold;
        }

        .opening-row td,
        .total-row td {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="print-meta">
        <span>{{ now()->format('d/m/Y, H:i') }}</span>
        <span>Print Account Ledger - Freebazar</span>
    </div>

    <div class="header">
        <div class="brand">FREEBAZAR</div>
        <div class="company-info">
            (A Unit Of Satshree Marketing Pvt. Ltd.)<br>
            Plot-1625(B), Nilakantha Nagar, Nayapalli, Bhubaneswar<br>
            Mob - 9853560459, website - <a href="https://freebazar.co.in/">Freebazar</a><br>
            GSTIN - 21AAHCS0485R1ZX
        </div>
    </div>

    <div class="ledger-title-row">
        <span class="ledger-title">Account Ledger Details</span>
        <span class="ledger-account">Account: {{ strtoupper($name) }} | Period:
            @if ($from || $to)
                {{ $from ?: 'Beginning' }} to {{ $to ?: 'Today' }}
            @else
                All dates
            @endif
        </span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:7%">Date</th>
                <th style="width:10%">Voucher</th>
                <th style="width:12%">Account</th>
                <th style="width:18%">Details</th>
                <th style="width:9%">Reference</th>
                <th style="width:8%">Method</th>
                <th style="width:8%" class="text-right">Debit</th>
                <th style="width:8%" class="text-right">Credit</th>
                <th style="width:7%" class="text-right">Due</th>
                <th style="width:5%">GST No</th>
                <th style="width:8%" class="text-right">Balance</th>
            </tr>
        </thead>
        <tbody>
            <tr class="opening-row">
                <td colspan="10" class="text-right">Opening Balance</td>
                <td class="text-right">
                    {{ number_format($opening_balance, 2) }}<br>{{ $opening_balance_type }}
                </td>
            </tr>

            @forelse ($rows as $index => $row)
                <tr>
                    <td>{{ $row['date'] }}</td>
                    <td>{{ $row['voucher_number'] }}</td>
                    <td>{{ $row['account_name'] ?? '' }}</td>
                    <td>
                        {{ $row['account_details'] ?? '' }}
                        @if (!empty($row['remark']))
                            <div class="remarks">{{ $row['remark'] }}</div>
                        @endif
                    </td>
                    <td>{{ $row['reference_number'] ?? '' }}</td>
                    <td>{{ $row['payment_method'] ?? '' }}</td>
                    <td class="text-right">{{ $row['debit'] > 0 ? number_format($row['debit'], 2) : '' }}</td>
                    <td class="text-right">{{ $row['credit'] > 0 ? number_format($row['credit'], 2) : '' }}</td>
                    <td class="text-right">{{ number_format((float) $row['due'], 2) }}</td>
                    <td>{{ $row['gst_no'] ?? '' }}</td>
                    <td class="text-right">
                        {{ number_format($row['balance'], 2) }} {{ $row['balance_type'] }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">No transactions in the selected period.</td>
                </tr>
            @endforelse

            <tr class="total-row">
                <td colspan="6" class="text-right">Period Total / Closing Balance</td>
                <td class="text-right">{{ number_format($total_debit, 2) }}</td>
                <td class="text-right">{{ number_format($total_credit, 2) }}</td>
                <td></td>
                <td></td>
                <td class="text-right">
                    {{ number_format($total_balance, 2) }}<br>{{ $total_balance_type }}
                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>
