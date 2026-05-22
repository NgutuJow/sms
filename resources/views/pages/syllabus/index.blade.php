@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Syllabus Tracking</h6>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addSyllabus">
                <i class="fas fa-plus"></i> Ongeza Mada
            </button>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Somo</th>
                        <th>Jina la Mada (Topic)</th>
                        <th>Hali (Status)</th>
                        <th>Tarehe Iliyokamilika</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($syllabuses as $syll)
                    <tr>
                        <td>{{ $syll->subject->subject_name }}</td>
                        <td>{{ $syll->topic_name }}</td>
                        <td>
                            <form action="{{ route('academic.syllabus.update', $syll->id) }}" method="POST">
                                @csrf @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="form-select form-select-sm {{ $syll->status == 'Completed' ? 'bg-success text-white' : '' }}">
                                    <option value="Pending" {{ $syll->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="In Progress" {{ $syll->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Completed" {{ $syll->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </form>
                        </td>
                        <td>{{ $syll->completion_date ? date('d M, Y', strtotime($syll->completion_date)) : '---' }}</td>
                        <td>
                            <form action="{{ route('academic.syllabus.destroy', $syll->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-link text-danger" onclick="return confirm('Futa mada hii?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addSyllabus" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('academic.syllabus.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header"><h5>Ongeza Mada ya Somo</h5></div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Chagua Somo</label>
                    <select name="subject_id" class="form-control">
                        @foreach($subjects as $sub) <option value="{{ $sub->id }}">{{ $sub->subject_name }}</option> @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label>Jina la Mada</label>
                    <input type="text" name="topic_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Hali ya Mada</label>
                    <select name="status" class="form-control">
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary w-100">Hifadhi Mada</button></div>
        </form>
    </div>
</div>
@endsection