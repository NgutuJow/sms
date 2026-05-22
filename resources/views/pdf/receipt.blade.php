<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - {{ $receipt->receipt_no }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .receipt-title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        .receipt-details {
            margin: 20px 0;
        }
        .detail-row {
            margin-bottom: 8px;
        }
        .detail-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        .amount {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            border: 2px solid #333;
            background-color: #f8f9fa;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #333;
            width: 200px;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">{{ config('app.name', 'School Management System') }}</div>
        <div>Payment Receipt</div>
    </div>

    <div class="receipt-title">RECEIPT #{{ $receipt->receipt_no }}</div>

    <div class="receipt-details">
        <div class="detail-row">
            <span class="detail-label">Receipt Number:</span>
            {{ $receipt->receipt_no }}
        </div>
        <div class="detail-row">
            <span class="detail-label">Date:</span>
            {{ $receipt->issued_at ? $receipt->issued_at->format('F d, Y') : now()->format('F d, Y') }}
        </div>
        @if($receipt->payment && $receipt->payment->student)
        <div class="detail-row">
            <span class="detail-label">Student Name:</span>
            {{ $receipt->payment->student->first_name }} {{ $receipt->payment->student->last_name }}
        </div>
        <div class="detail-row">
            <span class="detail-label">Student ID:</span>
            {{ $receipt->payment->student->student_id }}
        </div>
        @endif
        <div class="detail-row">
            <span class="detail-label">Payment Method:</span>
            {{ ucfirst($receipt->payment_method ?? 'Cash') }}
        </div>
        @if($receipt->payment)
        <div class="detail-row">
            <span class="detail-label">Reference:</span>
            {{ $receipt->payment->reference_no ?? 'N/A' }}
        </div>
        @endif
    </div>

    <div class="amount">
        Amount Paid: ${{ number_format($receipt->amount ?? 0, 2) }}
    </div>

    <div class="receipt-details">
        <div class="detail-row">
            <span class="detail-label">Received By:</span>
            {{ Auth::user()->name ?? 'System' }}
        </div>
        <div class="detail-row">
            <span class="detail-label">Payment For:</span>
            {{ $receipt->description ?? 'School Fees' }}
        </div>
    </div>

    <div class="signature-line">
        Authorized Signature
    </div>

    <div class="footer">
        This is a computer-generated receipt. Thank you for your payment.
    </div>
</body>
</html>