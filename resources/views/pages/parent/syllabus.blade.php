@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Curriculum & Syllabus Progress</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @foreach($students as $student)
                    <div class="p-4 mb-4 bg-gray-100 border-radius-lg">
                        <h6 class="mb-3">Student: {{ $student->first_name }} {{ $student->last_name }} ({{ $student->classData->class_name ?? 'N/A' }})</h6>
                        
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Subject</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Topic</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Completion Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($student->classData->subjects as $subject)
                                        @forelse($subject->syllabuses as $syllabus)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $subject->subject_name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $syllabus->topic_name }}</p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-{{ $syllabus->status === 'Completed' ? 'success' : 'secondary' }}">
                                                    {{ $syllabus->status }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ $syllabus->completion_date ? $syllabus->completion_date->format('d/m/Y') : '-' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td>{{ $subject->subject_name }}</td>
                                            <td colspan="3" class="text-center text-xs">No syllabus data uploaded yet.</td>
                                        </tr>
                                        @endforelse
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No subjects assigned to this class.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
