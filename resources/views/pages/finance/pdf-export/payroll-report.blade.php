<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payroll Report</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .school-name { font-size: 24px; font-weight: bold; text-transform: uppercase; margin-bottom: 5px; }
        .school-details { font-size: 12px; color: #666; }
        .report-title { text-align: center; font-size: 18px; font-weight: bold; margin-top: 20px; text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; font-size: 12px; }
        th { background: #f8f9fa; font-weight: bold; }
        .text-end { text-align: right; }
        .footer { margin-top: 50px; font-size: 10px; text-align: center; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">{{ $school->name }}</div>
        <div class="school-details">
            {{ $school->address }}, {{ $school->ward }}, {{ $school->district }}, {{ $school->region }}<br>
            Phone: {{ $school->phone }} | Email: {{ $school->email }}
        </div>
    </div>

    <div class="report-title">STAFF PAYROLL DISBURSEMENT REPORT</div>

    <table>
        <thead>
            <tr>
                <th>Staff Name</th>
                <th>Pay Period</th>
                <th>Payment Date</th>
                <th class="text-end">Net Salary</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($payrollRecords as $record)
                <tr>
                    <td>{{ optional($record->teacher)->first_name ?? 'N/A' }} {{ optional($record->teacher)->last_name ?? '' }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->pay_period)->format('F Y') }}</td>
                    <td>{{ optional($record->payment_date)->format('d M, Y') ?? 'Processing' }}</td>
                    <td class="text-end">TZS {{ number_format($record->net_salary, 0) }}</td>
                </tr>
                @php $total += $record->net_salary; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #eee; font-weight: bold;">
                <td colspan="3" class="text-end">TOTAL PAYROLL DISBURSED:</td>
                <td class="text-end">TZS {{ number_format($total, 0) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Generated on {{ date('F d, Y H:i:s') }} | School Management System - Payroll Office
    </div>
</body>
</html>
