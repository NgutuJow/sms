<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Expense Report</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }
        body {
            font-family: 'Inter', 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        .header-bg {
            background-color: #0f172a;
            height: 180px;
            width: 100%;
            position: absolute;
            top: 0;
            z-index: -1;
        }
        .container {
            padding: 40px 50px;
        }
        .header {
            color: white;
            margin-bottom: 40px;
            overflow: hidden;
        }
        .school-info {
            float: left;
            width: 60%;
        }
        .school-name {
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        .school-details {
            font-size: 11px;
            opacity: 0.8;
            line-height: 1.4;
        }
        .report-meta {
            float: right;
            width: 35%;
            text-align: right;
        }
        .report-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.1);
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .date-info {
            font-size: 11px;
            opacity: 0.8;
        }

        .summary-boxes {
            margin-top: 10px;
            margin-bottom: 30px;
            clear: both;
        }
        .summary-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 20px;
            border-radius: 12px;
            width: 30%;
            float: left;
            margin-right: 3%;
        }
        .summary-box:last-child {
            margin-right: 0;
        }
        .summary-label {
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }
        .summary-value.total {
            color: #e11d48;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            clear: both;
        }
        th {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }
        td {
            padding: 12px 15px;
            font-size: 11px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .tr-even { background-color: #fafafa; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: 700; }
        .text-dark { color: #0f172a; }
        .text-muted { color: #64748b; }
        
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-light { background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

        .footer {
            position: fixed;
            bottom: 30px;
            left: 50px;
            right: 50px;
            border-top: 1px solid #f1f5f9;
            padding-top: 15px;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
        }
        .page-number:before {
            content: "Page " counter(page);
        }
    </style>
</head>
<body>
    <div class="header-bg"></div>
    
    <div class="container">
        <div class="header">
            <div class="school-info">
                <div class="school-name">{{ $school->name }}</div>
                <div class="school-details">
                    {{ $school->address }}<br>
                    @if(isset($school->ward)) {{ $school->ward }}, @endif 
                    @if(isset($school->district)) {{ $school->district }}, @endif 
                    @if(isset($school->region)) {{ $school->region }} @endif<br>
                    <strong>Email:</strong> {{ $school->email }} | <strong>Phone:</strong> {{ $school->phone }}
                </div>
            </div>
            <div class="report-meta">
                <div class="report-badge">Expenditure Report</div>
                <div class="date-info">
                    <strong>Generated:</strong> {{ date('d M, Y | H:i') }}<br>
                    <strong>Currency:</strong> TZS (Shillings)
                </div>
            </div>
        </div>

        @php 
            $totalAmount = $expenses->sum('amount');
            $avgAmount = $expenses->count() > 0 ? $totalAmount / $expenses->count() : 0;
            $topCategory = $expenses->groupBy('category')->map->sum('amount')->sortDesc()->take(1);
        @endphp

        <div class="summary-boxes">
            <div class="summary-box">
                <div class="summary-label">Total Expenditure</div>
                <div class="summary-value total">{{ number_format($totalAmount, 0) }}</div>
            </div>
            <div class="summary-box">
                <div class="summary-label">Average per Entry</div>
                <div class="summary-value">{{ number_format($avgAmount, 0) }}</div>
            </div>
            <div class="summary-box">
                <div class="summary-label">Primary Category</div>
                <div class="summary-value">{{ $expenses->count() > 0 ? ucfirst($expenses->groupBy('category')->keys()->first()) : 'N/A' }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="15%">Ref No.</th>
                    <th width="40%">Description</th>
                    <th width="15%" class="text-center">Category</th>
                    <th width="15%" class="text-center">Date</th>
                    <th width="15%" class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $index => $expense)
                <tr class="{{ $index % 2 == 0 ? '' : 'tr-even' }}">
                    <td class="fw-bold text-dark">{{ $expense->reference_no }}</td>
                    <td>
                        <div class="text-dark">{{ Str::limit($expense->description, 50) }}</div>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-light">{{ ucfirst($expense->category) }}</span>
                    </td>
                    <td class="text-center text-muted">
                        {{ optional($expense->expense_date)->format('d M, Y') ?? 'N/A' }}
                    </td>
                    <td class="text-end fw-bold text-dark">
                        {{ number_format($expense->amount, 0) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <div style="float: left;">Institution Expenditure Ledger - Confidential</div>
        <div style="float: right;" class="page-number"></div>
        <div style="clear: both;"></div>
        <div style="margin-top: 5px;">&copy; {{ date('Y') }} {{ $school->name }}. All rights reserved.</div>
    </div>
</body>
</html>
