@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">PDF Export</h4>
            <p class="text-muted mb-0">Export financial summaries, expense reports, and payroll reports as PDF documents.</p>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm p-4 text-center">
                <h6>Financial Summary</h6>
                <a href="{{ route('finance.pdf-export.financial') }}" class="btn btn-primary mt-3">Download PDF</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-4 text-center">
                <h6>Expense Report</h6>
                <a href="{{ route('finance.pdf-export.expenses') }}" class="btn btn-primary mt-3">Download PDF</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-4 text-center h-100">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-users-viewfinder text-primary fs-4"></i>
                </div>
                <h6>Payroll Report</h6>
                <a href="{{ route('finance.pdf-export.payroll') }}" class="btn btn-outline-primary w-100 mt-3">Download PDF</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-4 text-center h-100">
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle d-inline-block mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-calendar-alt text-warning fs-4"></i>
                </div>
                <h6>Year-End Summary</h6>
                <form action="{{ route('finance.pdf-export.year-end') }}" method="GET" class="mt-3">
                    <div class="input-group mb-2">
                        <select name="year" class="form-select form-select-sm">
                            @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">Download PDF</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-opacity-10 { background-color: rgba(var(--bs-primary-rgb), 0.1) !important; }
</style>
@endsection