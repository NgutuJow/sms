@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h4 class="fw-bold mb-3 text-uppercase">Marks List</h4>

    <!-- FILTERS -->
    <form method="GET" class="row g-2 mb-3">

        <div class="col-md-3">
            <select name="class_id" class="form-control">
                <option value="">All Classes</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}"
                        {{ request('class_id') == $class->id ? 'selected' : '' }}>
                        {{ $class->class_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <select name="exam_id" class="form-control">
                <option value="">All Exams</option>
                @foreach($exams as $exam)
                    <option value="{{ $exam->id }}"
                        {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                        {{ $exam->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-dark w-100">Filter</button>
        </div>

        <div class="col-md-2">
            <a href="{{ route('marks.index') }}" class="btn btn-secondary w-100">
                Reset
            </a>
        </div>

        <div class="col-md-2">
            <a href="{{ route('marks.create') }}" class="btn btn-primary w-100">
                + Add Marks
            </a>
        </div>

    </form>
<a href="{{ url('/promote/'.$class->id.'/'.$exam->id) }}"
   class="btn btn-success">
    Run Promotion
</a>
    <!-- TABLE -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">

            <table class="table table-bordered align-middle mb-0">

                <thead class="table-dark text-uppercase small">
                    <tr>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Exam</th>
                        <th>Marks</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($marks as $mark)
                    <tr>
                        <td>
                            {{ $mark->student->first_name }}
                            {{ $mark->student->last_name }}
                        </td>

                        <td>{{ $mark->classData->class_name ?? '' }}</td>

                        <td>{{ $mark->subject->subject_name ?? '' }}</td>

                        <td>{{ $mark->exam->name ?? '' }}</td>

                        <td>
                            <span class="badge bg-dark rounded-0">
                                {{ $mark->marks }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">
                            No marks found
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection