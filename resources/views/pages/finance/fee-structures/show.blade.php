@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Fee Structure Details</h4>
            <p class="text-muted mb-0">Review the fee structure details for this class.</p>
        </div>
        <a href="{{ route('finance.fee-structures.index') }}" class="btn btn-outline-primary">Back to Fee Structures</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Class</dt>
                <dd class="col-sm-9">{{ optional($feeStructure->schoolClass)->class_name ?? 'Unknown' }}</dd>

                <dt class="col-sm-3">Academic Year</dt>
                <dd class="col-sm-9">{{ $feeStructure->academic_year }}</dd>

                <dt class="col-sm-3">Fee Type</dt>
                <dd class="col-sm-9">{{ $feeStructure->fee_type }}</dd>

                <dt class="col-sm-3">Amount</dt>
                <dd class="col-sm-9">{{ number_format($feeStructure->amount, 2) }}</dd>

                <dt class="col-sm-3">Created At</dt>
                <dd class="col-sm-9">{{ $feeStructure->created_at->format('Y-m-d') }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection