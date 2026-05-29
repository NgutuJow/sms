<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Expense Report - {{ $school->name }}</title>
    <style>
        @page {
            margin: 0cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #334155;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .header-strip {
            height: 12px;
            background: linear-gradient(to right, #1e293b, #334155);
        }
        .container {
            padding: 40px 50px;
        }
        .header {
            margin-bottom: 40px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 30px;
        }
        .school-logo-area {
            float: left;
            width: 50%;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: -0.5px;
        }
        .school-info {
            font-size: 11px;
            color: #64748b;
        }
        .report-info-area {
            float: right;
            width: 45%;
            text-align: right;
        }
        .report-title {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 10px 0;
        }
        .report-meta {
            font-size: 11px;
            color: #64748b;
        }
        .report-meta strong {
            color: #334155;
        }

        .summary-grid {
            margin-top: 20px;
            margin-bottom: 40px;
            clear: both;
        }
        .summary-card {
            float: left;
            width: 31%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-right: 2.5%;
        }
        .summary-card:last-child {
            margin-right: 0;
        }
        .summary-card.highlight {
            border-left: 4px solid #1e293b;
        }
        .card-label {
            font-size: 10px;
            font-weight: bold;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .card-value {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
        }
        .card-value.amount {
            color: #1e293b;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-left: 4px solid #1e293b;
            padding-left: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }
        td {
            padding: 12px 15px;
            font-size: 11px;
            border-bottom: 1px solid #f1f5f9;
        }
        .tr-even { background-color: #fafafa; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        
        .category-badge {
            background: #f1f5f9;
            color: #475569;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 30px;
            left: 50px;
            right: 50px;
            border-top: 1px solid #f1f5f9;
            padding-top: 20px;
            font-size: 10px;
            color: #94a3b8;
        }
        .footer-left { float: left; }
        .footer-right { float: right; }
    </style>
</head>
<body>
    <div class="header-strip"></div>
    
    <div class="container">
        <div class="header">
            <div class="school-logo-area">
                <h1 class="school-name">{{ $school->name }}</h1>
                <div class="school-info">
                    {{ $school->address }}<br>
                    {{ $school->ward }}, {{ $school->district }}, {{ $school->region }}<br>
                    Email: {{ $school->email }} | Phone: {{ $school->phone }}
                </div>
            </div>
            <div class="report-info-area">
                <h2 class="report-title">Expenditure Ledger</h2>
                <div class="report-meta">
                    <strong>Report ID:</strong> EXP-{{ date('Ymd') }}<br>
                    <strong>Generated:</strong> {{ date('F d, Y | H:i') }}<br>
                    <strong>Currency:</strong> Tanzanian Shillings (TZS)
                </div>
            </div>
        </div>

        @php 
            $totalAmount = $expenses->sum('amount');
            $avgAmount = $expenses->count() > 0 ? $totalAmount / $expenses->count() : 0;
            $primaryCategory = $expenses->count() > 0 ? ucfirst($expenses->groupBy('category')->keys()->first()) : 'N/A';
        @endphp

        <div class="summary-grid">
            <div class="summary-card highlight">
                <div class="card-label">Total Expenditure</div>
                <div class="card-value amount">{{ number_format($totalAmount, 0) }}</div>
            </div>
            <div class="summary-card">
                <div class="card-label">Average per Entry</div>
                <div class="card-value">{{ number_format($avgAmount, 0) }}</div>
            </div>
            <div class="summary-card">
                <div class="card-label">Primary Category</div>
                <div class="card-value">{{ $primaryCategory }}</div>
            </div>
        </div>

        <div class="section-title">Detailed Transaction History</div>
        <table>
            <thead>
                <tr>
                    <th width="15%">Ref No.</th>
                    <th width="35%">Description</th>
                    <th width="15%" class="text-center">Category</th>
                    <th width="15%" class="text-center">Date</th>
                    <th width="20%" class="text-right">Amount (TZS)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $index => $expense)
                <tr class="{{ $index % 2 == 0 ? '' : 'tr-even' }}">
                    <td class="font-bold">{{ $expense->reference_no }}</td>
                    <td>{{ $expense->description }}</td>
                    <td class="text-center">
                        <span class="category-badge">{{ ucfirst($expense->category) }}</span>
                    </td>
                    <td class="text-center">
                        {{ optional($expense->expense_date)->format('M d, Y') ?? 'N/A' }}
                    </td>
                    <td class="text-right font-bold">
                        {{ number_format($expense->amount, 0) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">No expenditure records found for this period.</td>
                </tr>
                @endforelse
                @if($expenses->count() > 0)
                <tr style="background: #f8fafc; border-top: 2px solid #e2e8f0;">
                    <td colspan="4" class="text-right font-bold" style="padding: 15px;">TOTAL EXPENDITURE</td>
                    <td class="text-right font-bold" style="padding: 15px; font-size: 14px;">
                        {{ number_format($totalAmount, 0) }}
                    </td>
                </tr>
                @endif
            </tbody>
        </table>

        <div style="margin-top: 50px; clear: both;">
            <div style="float: left; width: 40%;">
                <div style="border-bottom: 1px solid #334155; margin-bottom: 10px;"></div>
                <div style="font-size: 11px; font-weight: bold; color: #334155;">Prepared By</div>
                <div style="font-size: 10px; color: #64748b;">Financial Department Accountant</div>
            </div>
            <div style="float: right; width: 40%;">
                <div style="border-bottom: 1px solid #334155; margin-bottom: 10px;"></div>
                <div style="font-size: 11px; font-weight: bold; color: #334155;">Authorized Signature</div>
                <div style="font-size: 10px; color: #64748b;">Head of Institution / Director</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="footer-left">
            &copy; {{ date('Y') }} {{ $school->name }}. All rights reserved.
        </div>
        <div class="footer-right">
            Financial Audit Division | System Generated Report
        </div>
    </div>
</body>
</html>
