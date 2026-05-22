@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ratiba ya Vipindi</h1>
        <button class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addTimetable">
            <i class="fas fa-plus fa-sm text-white-50"></i> Ongeza Kipindi
        </button>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Siku</th>
                            <th>Darasa</th>
                            <th>Somo</th>
                            <th>Mwalimu</th>
                            <th>Muda</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timetables as $row)
                        <tr>
                            <td>{{ $row->day_of_week }}</td>
                            <td>{{ $row->stream->stream_name }}</td>
                            <td>{{ $row->subject->subject_name }}</td>
                            <td>{{ $row->teacher->name }}</td>
                            <td>{{ $row->start_time }} - {{ $row->end_time }}</td>
                            <td>
                                <form action="{{ route('academic.timetable.destroy', $row->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Futa kipindi hiki?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addTimetable" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('academic.timetable.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header text-white bg-primary">
                <h5 class="modal-title">Ongeza Kipindi Kipya</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Darasa</label>
                    <select name="stream_id" class="form-control" required>
                        @foreach($streams as $s) <option value="{{ $s->id }}">{{ $s->stream_name }}</option> @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Siku</label>
                        <select name="day_of_week" class="form-control">
                            <option>Monday</option><option>Tuesday</option><option>Wednesday</option>
                            <option>Thursday</option><option>Friday</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Somo</label>
                        <select name="subject_id" class="form-control">
                            @foreach($subjects as $sub) <option value="{{ $sub->id }}">{{ $sub->subject_name }}</option> @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6"><label>Muda wa kuanza</label><input type="time" name="start_time" class="form-control"></div>
                    <div class="col-md-6"><label>Muda wa kuisha</label><input type="time" name="end_time" class="form-control"></div>
                </div>
                <div class="mt-3">
                    <label>Mwalimu</label>
                    <select name="teacher_id" class="form-control">
                        @foreach($teachers as $t) <option value="{{ $t->id }}">{{ $t->name }}</option> @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save Ratiba</button>
            </div>
        </form>
    </div>
</div>
@endsection