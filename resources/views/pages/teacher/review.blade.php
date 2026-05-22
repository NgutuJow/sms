<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Review</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        .student-img { width: 40px; height: 40px; object-fit: cover; border: 1px solid #ddd; }
        .filter-section { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .stats-card { border: none; border-radius: 8px; transition: transform 0.2s; }
        .stats-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-history me-2 text-primary"></i> Attendance Review</h4>
        <span class="badge bg-primary px-3 py-2">
            @if(auth()->user()->role === 'teacher')
                Darasa: {{ $classes->first()->class_name ?? 'N/A' }} {{ $classes->first()->stream->name ?? '' }}
            @else
                Admin Mode
            @endif
        </span>
    </div>

    {{-- FILTER FORM --}}
    <div class="filter-section mb-4">
        <form method="GET" class="row g-3">
            
            <div class="col-md-3">
                <label class="small fw-bold">DARASA</label>
                @if(auth()->user()->role === 'teacher')
                    {{-- Mwalimu hawezi kuchagua, tunaweka darasa lake moja tu --}}
                    <input type="text" class="form-control bg-light" value="{{ $classes->first()->class_name ?? '' }} {{ $classes->first()->stream->name ?? '' }}" readonly>
                    <input type="hidden" name="class_id" value="{{ $selectedClass }}">
                @else
                    {{-- Admin anaona dropdown yote --}}
                    <select name="class_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>
                                {{ $class->class_name }} {{ $class->stream->name ?? '' }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>

            <div class="col-md-2">
                <label class="small fw-bold">KUANZIA</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control">
            </div>

            <div class="col-md-2">
                <label class="small fw-bold">MPAKA</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control">
            </div>

            <div class="col-md-2">
                <label class="small fw-bold">HALI (STATUS)</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="present" {{ $status=='present'?'selected':'' }}>Waliopo</option>
                    <option value="absent" {{ $status=='absent'?'selected':'' }}>Wasiofika</option>
                    <option value="late" {{ $status=='late'?'selected':'' }}>Waliochelewa</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="small fw-bold">ID YA MWANAFUNZI</label>
                <input type="text" name="student_id" value="{{ $studentId }}" placeholder="Search ID..." class="form-control">
            </div>

            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    {{-- STATS --}}
    <div class="row g-3 mb-4 text-white">
        <div class="col-md-4">
            <div class="card stats-card bg-success p-3">
                <div class="small">Waliopo (Present)</div>
                <h3 class="mb-0">{{ $stats['present'] }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card bg-danger p-3">
                <div class="small">Wasiofika (Absent)</div>
                <h3 class="mb-0">{{ $stats['absent'] }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card bg-warning text-dark p-3">
                <div class="small">Waliochelewa (Late)</div>
                <h3 class="mb-0">{{ $stats['late'] }}</h3>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Tarehe</th>
                        <th>Mwanafunzi</th>
                        <th>Darasa / Mkondo</th>
                        <th>Hali</th>
                        <th>Maoni (Remarks)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        <tr>
                            <td class="ps-3">{{ \Carbon\Carbon::parse($record->date)->format('d M, Y') }}</td>
                            <td class="fw-bold text-dark">{{ $record->student->full_name ?? $record->student->name }}</td>
                            <td>
                                <small class="text-muted">
                                    {{ $record->classesRelation->class_name ?? 'N/A' }} 
                                    {{ $record->classesRelation->stream->name ?? '' }}
                                </small>
                            </td>
                            <td>
                                <span class="badge {{ $record->status=='present' ? 'bg-success' : ($record->status=='absent' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                    {{ strtoupper($record->status) }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $record->remarks ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 d-block"></i>
                                Hakuna kumbukumbu zilizopatikana.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>