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
            margin-bottom: 10px;
        }

        .logo-text {
            font-size: 26px;
            font-weight: bold;
        }

        .logo-text .free {
            color: #f7941d;
        }

        .logo-text .bazar {
            color: #2e3092;
        }

        .tagline {
            font-size: 9px;
            color: #444;
            letter-spacing: 1px;
            margin-top: -4px;
        }

        .company-info {
            font-size: 10px;
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
        <img src="{{ public_path('assets/images/logofreebazar3.png') }}" alt="logo" style="height: 50px;">
        <div class="company-info">
            (A Unit Of Satshree Marketing Pvt. Ltd.)<br>
            Plot-1625(B), Nilakantha Nagar, Nayapalli, Bhubaneswar<br>
            Mob - 9853560459, website - <a href="https://freebazar.co.in/">Freebazar</a><br>
            GSTIN - 21AAHCS0485R1ZX
        </div>
    </div>

    <div class="ledger-title-row">
        <span class="ledger-title">Account Ledger</span>
        <span class="ledger-account">A/c: {{ strtoupper($pos_name) }}, From {{ $from }} To
            {{ $to }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:3%">Sl.</th>
                <th style="width:11%">Date</th>
                <th style="width:17%">Voucher No</th>
                <th style="width:38%">Account</th>
                <th style="width:10%" class="text-right">Debit</th>
                <th style="width:10%" class="text-right">Credit</th>
                <th style="width:11%" class="text-right">Balance</th>
            </tr>
        </thead>
        <tbody>
            <tr class="opening-row">
                <td colspan="6" class="text-right">Opening Balance</td>
                <td class="text-right">
                    {{ number_format($opening_balance, 2) }}<br>{{ $opening_balance_type }}
                </td>
            </tr>

            @foreach ($rows as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row['date'] }}</td>
                    <td>{{ $row['voucher_number'] }}</td>
                    <td>
                        <span class="voucher-type">{{ $row['voucher_type'] ?? '' }}</span>
                        <div class="remarks">res - {{ $row['reference_number'] ?? '' }},{{ $row['remark'] ?? '' }}
                        </div>
                    </td>
                    <td class="text-right">{{ $row['debit'] > 0 ? number_format($row['debit'], 2) : '' }}</td>
                    <td class="text-right">{{ $row['credit'] > 0 ? number_format($row['credit'], 2) : '' }}</td>
                    <td class="text-right">
                        {{ number_format($row['balance'], 2) }} {{ $row['balance_type'] }}
                    </td>
                </tr>
            @endforeach

            <tr class="total-row">
                <td colspan="4" class="text-right">Total</td>
                <td class="text-right">{{ number_format($total_debit, 2) }}</td>
                <td class="text-right">{{ number_format($total_credit, 2) }}</td>
                <td class="text-right">
                    {{ number_format($total_balance, 2) }}<br>{{ $total_balance_type }}
                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>
