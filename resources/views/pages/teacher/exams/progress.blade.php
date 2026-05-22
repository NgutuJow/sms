@extends('pages.teacher.layout.layout')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Marking Progress: {{ $exam->name }}</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('teacher.exams.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        @foreach($progress as $classProgress)
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">{{ $classProgress['class_id'] }} - Progress</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Marked:</strong> {{ $classProgress['marked'] }} / {{ $classProgress['total'] }}</p>
                        <div class="progress mb-3" style="height: 25px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $classProgress['percentage'] }}%">
                                {{ round($classProgress['percentage'], 1) }}%
                            </div>
                        </div>
                        <a href="{{ route('teacher.exams.mark', ['exam' => $exam->id, 'class_id' => $classProgress['class_id']]) }}" class="btn btn-sm btn-primary">
                            Continue Marking
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
