<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Year-End Financial Summary - {{ $year }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .school-name { font-size: 24px; font-weight: bold; text-transform: uppercase; margin-bottom: 5px; }
        .school-details { font-size: 12px; color: #666; }
        .report-title { text-align: center; font-size: 20px; font-weight: bold; margin-top: 20px; color: #1a202c; }
        .summary-box { margin-top: 30px; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 12px; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .footer { margin-top: 50px; font-size: 10px; text-align: center; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">{{ $school->name }}</div>
        <div class="school-details">
            {{ $school->address }}, {{ $school->ward }}, {{ $school->district }}, {{ $school->region }}<br>
            Phone: {{ $school->phone }} | Email: {{ $school->email }}<br>
            Institutional Registration: {{ $school->code }}
        </div>
    </div>

    <div class="report-title">ANNUAL FISCAL AUDIT REPORT ({{ $year }})</div>

    <div class="summary-box">
        <h4 style="margin-bottom: 10px; border-left: 4px solid #444; padding-left: 10px;">Executive Aggregate</h4>
        <table>
            <tbody>
                <tr>
                    <th>Annual Gross Revenue</th>
                    <td class="text-end text-success fw-bold">TZS {{ number_format($summary['income'], 0) }}</td>
                </tr>
                <tr>
                    <th>Annual Aggregate Expenditure</th>
                    <td class="text-end text-danger fw-bold">TZS {{ number_format($summary['expenses'] + $summary['payroll'], 0) }}</td>
                </tr>
                <tr style="background-color: #f0f4f8;">
                    <th>Net Fiscal Surplus / Deficit</th>
                    <td class="text-end fw-bold" style="color: {{ ($summary['income'] - ($summary['expenses'] + $summary['payroll'])) >= 0 ? '#007bff' : '#dc3545' }};">
                        TZS {{ number_format($summary['income'] - ($summary['expenses'] + $summary['payroll']), 0) }}
                    </td>
                </tr>
                <tr>
                    <th>Unrealized Revenue (Outstanding Invoices)</th>
                    <td class="text-end text-danger">TZS {{ number_format($summary['outstanding'], 0) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <h4 style="margin-bottom: 10px; border-left: 4px solid #444; padding-left: 10px;">Monthly Performance Matrix</h4>
    <table>
        <thead>
            <tr>
                <th>Fiscal Period</th>
                <th class="text-end">Revenue Inflow</th>
                <th class="text-end">Expense Outflow</th>
                <th class="text-end">Net Period Result</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyData as $data)
            <tr>
                <td class="fw-bold">{{ $data['month'] }}</td>
                <td class="text-end text-success">TZS {{ number_format($data['income'], 0) }}</td>
                <td class="text-end text-danger">TZS {{ number_format($data['expense'], 0) }}</td>
                <td class="text-end fw-bold">
                    @php $diff = $data['income'] - $data['expense']; @endphp
                    <span style="color: {{ $diff >= 0 ? '#007bff' : '#dc3545' }};">
                        TZS {{ number_format($diff, 0) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ date('F d, Y H:i:s') }} | School Management System - Financial Audit Division
    </div>
</body>
</html>
