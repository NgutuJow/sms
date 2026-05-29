<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - {{ $payment->provider_ref }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 30px;
            font-size: 12px;
            color: #333;
        }
        .receipt-container {
            border: 1px solid #e2e8f0;
            padding: 20px;
            position: relative;
        }
        .header-table {
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 2px solid #16a34a;
            padding-bottom: 15px;
        }
        .school-name {
            font-size: 22px;
            font-weight: bold;
            color: #16a34a;
        }
        .receipt-title {
            font-size: 18px;
            font-weight: bold;
            color: #16a34a;
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
        .amount-box {
            background: #f0fdf4;
            border: 2px solid #16a34a;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        .amount-label {
            font-size: 12px;
            color: #166534;
            margin-bottom: 5px;
        }
        .amount-value {
            font-size: 28px;
            font-weight: bold;
            color: #166534;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
        .signature-table {
            width: 100%;
            margin-top: 40px;
        }
        .signature-box {
            width: 50%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin: 40px 40px 0 40px;
            padding-top: 5px;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(22, 163, 74, 0.1);
            font-weight: bold;
            z-index: -1;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="watermark">PAID</div>
        
        <table class="header-table">
            <tr>
                <td>
                    <div class="school-name">{{ config('app.name', 'School Management System') }}</div>
                    <div>Official Payment Receipt</div>
                </td>
                <td class="receipt-title">RECEIPT</td>
            </tr>
        </table>

        <table class="info-table">
            <tr>
                <td>
                    <div class="label">Received From:</div>
                    <div class="value">
                        @if($payment->student)
                            <strong>{{ $payment->student->first_name }} {{ $payment->student->last_name }}</strong><br>
                            Admission No: {{ $payment->student->admission_no }}<br>
                            Class: {{ $payment->student->classData->class_name ?? 'N/A' }}
                        @else
                            <strong>[STUDENT DATA MISSING]</strong><br>
                            Please contact administration to verify student record.
                        @endif
                    </div>
                </td>
                <td class="text-right">
                    <div class="label">Receipt Details:</div>
                    <div class="value">
                        Receipt No: <strong>{{ $payment->provider_ref }}</strong><br>
                        Date: {{ $payment->created_at->format('M d, Y H:i') }}<br>
                        Method: {{ strtoupper($payment->payment_method) }}<br>
                        Ref No: {{ $payment->provider_ref }}
                    </div>
                </td>
            </tr>
        </table>

        <div class="amount-box">
            <div class="amount-label">Amount Received</div>
            <div class="amount-value">TZS {{ number_format($payment->amount, 0) }}</div>
        </div>

        <table class="info-table">
            <tr>
                <td>
                    <div class="label">Payment For:</div>
                    <div class="value">
                        Invoice Ref: {{ $payment->invoice->reference_no ?? 'N/A' }}<br>
                        Description: School Fees Payment
                    </div>
                </td>
                <td class="text-right">
                    <div class="label">Balance Information:</div>
                    <div class="value">
                        Invoice Total: TZS {{ number_format($payment->invoice->total_amount ?? 0, 0) }}<br>
                        Remaining Balance: <strong>TZS {{ number_format($payment->invoice->balance ?? 0, 0) }}</strong>
                    </div>
                </td>
            </tr>
        </table>

        <table class="signature-table">
            <tr>
                <td class="signature-box">
                    <div class="signature-line">Student/Parent Signature</div>
                </td>
                <td class="signature-box">
                    <div class="signature-line">Bursar/Cashier Signature</div>
                </td>
            </tr>
        </table>

        <div class="footer">
            <p>Thank you for your payment. Keep this receipt for your records.</p>
            <p>This is a computer-generated document. No signature is required unless requested.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
