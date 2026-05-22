<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Finance Summary</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .school-name { font-size: 24px; font-weight: bold; text-transform: uppercase; margin-bottom: 5px; }
        .school-details { font-size: 12px; color: #666; }
        .report-title { text-align: center; font-size: 18px; font-weight: bold; margin-top: 20px; text-decoration: underline; }
        .period { text-align: center; font-size: 14px; margin-bottom: 30px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 12px; text-align: left; }
        th { background: #f8f9fa; font-weight: bold; width: 40%; }
        .footer { margin-top: 50px; font-size: 10px; text-align: center; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">{{ $school->name }}</div>
        <div class="school-details">
            {{ $school->address }}, {{ $school->ward }}, {{ $school->district }}, {{ $school->region }}<br>
            Phone: {{ $school->phone }} | Email: {{ $school->email }}<br>
            School Code: {{ $school->code }}
        </div>
    </div>

    <div class="report-title">FINANCIAL PERFORMANCE SUMMARY</div>
    <div class="period">Reporting Period: {{ $startDate->format('d M, Y') }} – {{ $endDate->format('d M, Y') }}</div>

    <table>
        <tbody>
            <tr>
                <th>Total Income (Collected)</th>
                <td>TZS {{ number_format($totalIncome, 0) }}</td>
            </tr>
            <tr>
                <th>Operational Expenses</th>
                <td>TZS {{ number_format($totalExpenses, 0) }}</td>
            </tr>
            <tr>
                <th>Staff Payroll Expenditure</th>
                <td>TZS {{ number_format($totalPayroll, 0) }}</td>
            </tr>
            <tr style="background-color: #eee;">
                <th>Net Performance (Surplus/Deficit)</th>
                <td style="font-weight: bold; color: {{ $netIncome >= 0 ? '#28a745' : '#dc3545' }};">
                    TZS {{ number_format($netIncome, 0) }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ date('F d, Y H:i:s') }} | School Management System - Financial Audit Division
    </div>
</body>
</html>
