@extends('pages.parent.layout.layout')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4>Student Fee Accounts</h4>
                    <p class="text-muted">See the balance for each child and monitor fee account status.</p>
                </div>
                <a href="{{ route('parent.finance.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Status</th>
                            <th>Expected Fee</th>
                            <th>Amount Owed</th>
                            <th>Installments</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $account)
                            <tr>
                                <td>{{ $account['student']->first_name }} {{ $account['student']->last_name }}</td>
                                <td>{{ $account['student']->classData->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $account['status'] === 'Paid' ? 'success' : ($account['status'] === 'Partially Paid' ? 'warning text-dark' : 'danger') }}">
                                        {{ $account['status'] }}
                                    </span>
                                </td>
                                <td>
                                    @if($account['expected_fee'] > 0)
                                        TZS {{ number_format($account['expected_fee'], 2) }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($account['due_amount'] > 0)
                                        TZS {{ number_format($account['due_amount'], 2) }}
                                    @else
                                        <span class="text-success">None</span>
                                    @endif
                                </td>
                                <td>{{ $account['installment_plan'] }}</td>
                                <td>
                                    @if($account['pay_route'])
                                        <a href="{{ $account['pay_route'] }}" class="btn btn-sm btn-outline-primary">Pay Now</a>
                                    @elseif($account['expected_fee'] > 0)
                                        <span class="text-muted">Invoice pending</span>
                                    @else
                                        <span class="text-muted">Awaiting setup</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No students found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection