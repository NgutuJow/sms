<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .present { color: green; font-weight: bold; }
        .absent { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>STUDENT ATTENDANCE REPORT</h2>
        <p>Name: <strong>{{ $student->name }}</strong></p>
        <p>Class: <strong>{{ $student->classes }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $row)
            <tr>
                <td>{{ $row->date }}</td>
                <td class="{{ $row->status }}">{{ ucfirst($row->status) }}</td>
                <td>{{ $row->remarks }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>