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
<div class="container-fluid py-4">
    
    <!-- 1. RATIBA YA VIPINDI (TIMETABLE) -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Ratiba Yangu ya Vipindi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Siku</th>
                            <th>Somo</th>
                            <th>Darasa & Mkondo</th>
                            <th>Muda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($timetables as $slot)
                            <tr>
                                <td class="fw-bold">{{ $slot->day_of_week }}</td>
                                <td>{{ $slot->subject->subject_name ?? 'Somo Haulijulikani' }}</td>
                                <td>
                                    {{ $slot->stream->schoolClass->class_name ?? '' }} 
                                    <span class="badge bg-info text-dark">{{ $slot->stream->name ?? 'Mkondo N/A' }}</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">Hujapangiwa ratiba bado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 2. SYLLABUS (TOPICS) -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-book me-2"></i>Syllabus: Mada na Maendeleo</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Somo</th>
                            <th>Mada Kuu (Topic)</th>
                            <th>Mada Ndogo (Sub-Topics)</th>
                            <th>Muda (Hours)</th>
                            <th>Hali (Status)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($syllabuses as $syl)
                            <tr>
                                <td>{{ $syl->subject->subject_name ?? 'N/A' }}</td>
                                <td class="fw-bold">{{ $syl->topic_name }}</td>
                                <td>
                                    @php $subs = json_decode($syl->sub_topics); @endphp
                                    @if(is_array($subs))
                                        @foreach($subs as $sub)
                                            <span class="badge bg-light text-dark border">{{ $sub }}</span>
                                        @endforeach
                                    @else
                                        {{ $syl->sub_topic ?? '-' }}
                                    @endif
                                </td>
                                <td>{{ $syl->estimated_hours }} hrs</td>
                                <td>
                                    <span class="badge {{ $syl->status == 'Completed' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $syl->status }}
                                    </span>
                                </td>
                                <td>
    <div class="d-flex flex-column gap-2">
        <!-- Display Current Status Badge -->
        <span class="badge {{ $syl->status == 'Completed' ? 'bg-success' : 'bg-warning text-dark' }} mb-1">
            {{ $syl->status }}
        </span>

        <!-- Action Buttons -->
        <form action="{{ route('syllabus.updateStatus', $syl->id) }}" method="POST">
            @csrf
            @if($syl->status !== 'Completed')
                <input type="hidden" name="status" value="Completed">
                <button type="submit" class="btn btn-sm btn-outline-success w-100">
                    <i class="fas fa-check-circle"></i> Mark Complete
                </button>
            @else
                <input type="hidden" name="status" value="Pending">
                <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                    <i class="fas fa-times-circle"></i> Mark Not Complete
                </button>
            @endif
        </form>
    </div>
</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Hakuna syllabus iliyorekodiwa.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>