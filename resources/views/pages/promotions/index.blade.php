@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Promotions Management</h4>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <h5 class="mb-3">Select Class to Promote</h5>
            <div class="row g-3">
                @foreach ($classes as $class)
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h6 class="fw-bold mb-1">{{ $class->class_name }}</h6>
                                <p class="small text-muted mb-3">{{ $class->branch->branch_name ?? 'N/A' }}</p>
                                <a href="{{ route('promotions.show', $class->id) }}" class="btn btn-primary btn-sm w-100">View Students</a>
                            </div>  
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-8">
            <h5 class="mb-3">Recent Promotions History</h5>
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Student</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Academic Year</th>
                                    <th>Date</th>
                                    <th>By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPromotions as $promotion)
                                    <tr>
                                        <td class="fw-bold">{{ $promotion->student->first_name ?? 'N/A' }} {{ $promotion->student->last_name ?? '' }}</td>
                                        <td><span class="badge bg-secondary">{{ $promotion->fromClass->class_name ?? 'N/A' }}</span></td>
                                        <td><span class="badge bg-success">{{ $promotion->toClass->class_name ?? 'N/A' }}</span></td>
                                        <td>{{ $promotion->academic_year }}</td>
                                        <td>{{ $promotion->created_at->format('M d, Y') }}</td>
                                        <td><small>{{ $promotion->promoter->name ?? 'N/A' }}</small></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">No recent promotions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection