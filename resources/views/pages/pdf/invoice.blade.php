<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice->reference_no }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 30px;
            font-size: 12px;
            color: #333;
        }
        .header-table {
            width: 100%;
            margin-bottom: 40px;
            border-bottom: 2px solid #1e40af;
            padding-bottom: 20px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
        }
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            text-align: right;
        }
        .info-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-table td {
            vertical-align: top;
            width: 50%;
        }
        .label {
            font-weight: bold;
            color: #666;
            font-size: 10px;
            text-transform: uppercase;
        }
        .value {
            margin-bottom: 10px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background: #f0f4f8;
            padding: 10px;
            text-align: left;
            border-bottom: 2px solid #1e40af;
            color: #1e40af;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .text-right {
            text-align: right;
        }
        .totals-table {
            width: 40%;
            margin-left: auto;
        }
        .totals-table td {
            padding: 8px 10px;
        }
        .total-row {
            font-weight: bold;
            font-size: 14px;
            color: #1e40af;
            background: #f0f4f8;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        .status-paid { background: #dcfce7; color: #166534; }
        .status-partial { background: #fef9c3; color: #854d0e; }
        .status-unpaid { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td>
                <div class="school-name">{{ config('app.name', 'School Management System') }}</div>
                <div>Official Student Invoice</div>
            </td>
            <td class="invoice-title">INVOICE</td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td>
                <div class="label">Billed To:</div>
                <div class="value">
                    <strong>{{ $invoice->student->first_name }} {{ $invoice->student->last_name }}</strong><br>
                    Admission No: {{ $invoice->student->admission_no }}<br>
                    Class: {{ $invoice->student->classData->class_name ?? 'N/A' }}<br>
                    Branch: {{ $invoice->student->branchData->name ?? 'N/A' }}
                </div>
            </td>
            <td class="text-right">
                <div class="label">Invoice Details:</div>
                <div class="value">
                    Invoice No: <strong>{{ $invoice->reference_no }}</strong><br>
                    Date: {{ $invoice->created_at->format('M d, Y') }}<br>
                    Status: <span class="status-badge status-{{ $invoice->status }}">{{ $invoice->status }}</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items ?? [] as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td class="text-right">{{ number_format($item->amount, 0) }}</td>
            </tr>
            @endforeach
            @if(!isset($invoice->items) || count($invoice->items) == 0)
            <tr>
                <td>General School Fees / Tuition</td>
                <td class="text-right">{{ number_format($invoice->total_amount, 0) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td>Total Amount:</td>
            <td class="text-right">TZS {{ number_format($invoice->total_amount, 0) }}</td>
        </tr>
        <tr>
            <td>Paid Amount:</td>
            <td class="text-right text-success">TZS {{ number_format($invoice->paid_amount, 0) }}</td>
        </tr>
        <tr class="total-row">
            <td>Balance Due:</td>
            <td class="text-right">TZS {{ number_format($invoice->balance, 0) }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>Please use the Invoice No <strong>{{ $invoice->reference_no }}</strong> as your payment reference.</p>
        <p>This is an official document generated by the School Management System.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
