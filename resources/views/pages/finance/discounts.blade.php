@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fee Discounts & Scholarships</h3>
                    <div class="card-tools">
                        <a href="{{ route('finance.discounts.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Discount
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Discount Type</th>
                                <th>Amount/Percentage</th>
                                <th>Reason</th>
                                <th>Valid From</th>
                                <th>Valid To</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($discounts as $discount)
                            <tr>
                                <td>{{ $discount->student->name ?? 'N/A' }}</td>
                                <td>{{ $discount->discount_type }}</td>
                                <td>
                                    @if($discount->amount)
                                        ${{ number_format($discount->amount, 2) }}
                                    @else
                                        {{ $discount->percentage }}%
                                    @endif
                                </td>
                                <td>{{ $discount->reason }}</td>
                                <td>{{ $discount->valid_from->format('M d, Y') }}</td>
                                <td>{{ $discount->valid_to ? $discount->valid_to->format('M d, Y') : 'Ongoing' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info">Edit</button>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No discounts found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection