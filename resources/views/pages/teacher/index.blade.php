<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance Management</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        .marked-row {
            border-left: 5px solid #1cc88a !important; /* Rangi ya kijani upande wa pembeni */
            background-color: rgba(28, 200, 138, 0.05);
        }
        .badge-marked {
            background-color: #1cc88a;
            color: white;
            font-size: 0.65rem;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .student-img {
            width: 40px; 
            height: 40px; 
            object-fit: cover;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body class="bg-light">
    
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                    <h4 class="mb-0">
    <i class="fas fa-clipboard-user me-2"></i>
    Mahudhurio:
    <span class="fw-bold">{{ $className }}</span>

    @if(!empty($streamName))
        <span class="badge bg-warning text-dark ms-2">
            <i class="fas fa-layer-group"></i> {{ $streamName }}
        </span>
    @endif
</h4>

<span class="badge bg-white text-primary p-2">
    <i class="far fa-calendar-alt me-1"></i>
    {{ \Carbon\Carbon::parse($date)->format('d M, Y') }}
</span>

                    <span class="badge bg-white text-primary p-2">
                        <i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($date)->format('d M, Y') }}
                    </span>
                </div>
                <div class="card-body">
                    
                    {{-- Summary & Filter Section --}}
                    <form action="{{ route('teacher-attendance.index') }}" method="GET" class="mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted">BADILISHA TAREHE:</label>
                                <input type="date" name="date" value="{{ $date }}" class="form-control" onchange="this.form.submit()">
                                <a href="/teacher-attendance/review">Review Attendance</a>
                            </div>
                            <div class="col-md-9 text-end mt-3 mt-md-0">
                                <div class="btn-group shadow-sm">
                                    <button type="button" class="btn btn-success btn-sm">Waliopo: {{ $stats['present'] }}</button>
                                    <button type="button" class="btn btn-danger btn-sm">Hajaja: {{ $stats['total'] - $stats['present'] }}</button>
                                    <button type="button" class="btn btn-info btn-sm text-white">Asilimia: {{ $stats['percent'] }}%</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <hr>

                    {{-- Form ya Kusave Mahudhurio --}}
                    <form action="{{ route('teacher-attendance.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ $selectedClass }}">
                        <input type="hidden" name="date" value="{{ $date }}">

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th width="70">Picha</th>
                                        <th>Jina la Mwanafunzi</th>
                                        <th>Jinsia</th>
                                        <th class="text-center" width="220">Mahudhurio</th>
                                        <th>Maoni (Remarks)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($students as $index => $student)
                                        @php 
                                            // LOGIC: Angalia kama kuna record ya huyu mwanafunzi
                                            $record = $attendances->get($student->id);
                                            $isMarked = $record ? true : false; 
                                            $currentStatus = $record ? $record->status : 'absent';
                                        @endphp
                                        <tr class="{{ $isMarked ? 'marked-row' : '' }}">
                                            <td class="text-muted fw-bold">
                                                {{ $index + 1 }}
                                                @if($isMarked)
                                                    <i class="fas fa-check-circle text-success ms-1"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <img src="{{ $student->image ? asset('uploads/students/'.$student->image) : asset('assets/images/user.png') }}" 
                                                     class="rounded-circle student-img">
                                            </td>
                                            <td>
                                                <div class="fw-bold {{ $isMarked ? 'text-success' : 'text-dark' }}">
                                                    {{ $student->full_name ?? $student->name }}
                                                </div>
                                                @if($isMarked)
                                                    <span class="badge-marked">Marked</span>
                                                @else
                                                    <small class="text-muted" style="font-size: 0.7rem;">Not marked yet</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary border">
                                                    {{ ucfirst($student->gender) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group w-100" role="group">
                                                    <input type="radio" class="btn-check" name="attendance[{{ $student->id }}]" id="p_{{ $student->id }}" value="present" {{ $currentStatus == 'present' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-success btn-sm" for="p_{{ $student->id }}">P</label>

                                                    <input type="radio" class="btn-check" name="attendance[{{ $student->id }}]" id="a_{{ $student->id }}" value="absent" {{ $currentStatus == 'absent' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-danger btn-sm" for="a_{{ $student->id }}">A</label>

                                                    <input type="radio" class="btn-check" name="attendance[{{ $student->id }}]" id="l_{{ $student->id }}" value="late" {{ $currentStatus == 'late' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-warning btn-sm" for="l_{{ $student->id }}">L</label>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" name="remarks[{{ $student->id }}]" 
                                                       value="{{ $record->remarks ?? '' }}" 
                                                       class="form-control form-control-sm border-0 bg-light" 
                                                       placeholder="Sababu...">
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="fas fa-users-slash fa-2x d-block mb-2"></i>
                                                Hakuna wanafunzi waliopatikana.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(count($students) > 0)
                            <div class="mt-4 mb-4 text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                                    <i class="fas fa-save me-2"></i> Save Daily Attendance
                                </button>
                            </div>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>