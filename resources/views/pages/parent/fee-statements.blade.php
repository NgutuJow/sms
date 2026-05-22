@extends('pages.parent.layout.layout')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4>Fee Statements</h4>
                    <p class="text-muted">Review annual statements and see whether tuition fees are paid, partially paid, or still due.</p>
                </div>
                <a href="{{ route('parent.finance.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
            </div>

            @if($statementGroups->count())
                @foreach($statementGroups as $group)
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="mb-1">{{ $group['year'] }} Statement</h5>
                                    <p class="text-muted mb-0">Annual fee summary for the selected academic year.</p>
                                </div>
                                <span class="badge bg-{{ $group['status'] === 'Paid' ? 'success' : ($group['status'] === 'Partially Paid' ? 'warning text-dark' : 'danger') }} py-2 px-3">
                                    {{ $group['status'] }}
                                </span>
                            </div>

                            <div class="row text-center mb-3">
                                <div class="col-md-3">
                                    <div class="text-muted">Total Billed</div>
                                    <div class="h5">TZS {{ number_format($group['total_invoiced'], 2) }}</div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-muted">Total Paid</div>
                                    <div class="h5 text-success">TZS {{ number_format($group['total_paid'], 2) }}</div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-muted">Total Balance</div>
                                    <div class="h5 text-warning">TZS {{ number_format($group['total_balance'], 2) }}</div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-muted">Invoices</div>
                                    <div class="h5">{{ $group['invoices']->count() }}</div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Invoice</th>
                                            <th>Student</th>
                                            <th>Period</th>
                                            <th>Total</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($group['invoices'] as $invoice)
                                            <tr>
                                                <td>{{ $invoice->reference_no }}</td>
                                                <td>{{ optional($invoice->student)->first_name ?? 'Unknown' }} {{ optional($invoice->student)->last_name ?? '' }}</td>
                                                <td>{{ $invoice->academic_year ?: 'Annual' }}</td>
                                                <td>TZS {{ number_format($invoice->total_amount, 2) }}</td>
                                                <td>TZS {{ number_format($invoice->paid_amount, 2) }}</td>
                                                <td>TZS {{ number_format($invoice->balance, 2) }}</td>
                                                <td>{{ ucfirst($invoice->status) }}</td>
                                                <td>
                                                    <a href="{{ route('parent.finance.statements.details', $invoice->id) }}" class="btn btn-sm btn-primary">Details</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-secondary">
                    No fee statements available yet. Your invoices will appear here once they are generated.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection