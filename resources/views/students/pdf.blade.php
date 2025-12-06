<!DOCTYPE html>
<html>
<head>
    <title>Students PDF</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>Students List</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>CIN</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Formation</th>
                <th>Status</th>
                <th>Paid</th>
                <th>Remaining</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student->name }}</td>
                <td>{{ $student->cin }}</td>
                <td>{{ $student->phone }}</td>
                <td>{{ $student->email ?? '-' }}</td>
                <td>{{ $student->formation->name ?? '-' }}</td>
                <td>{{ ucfirst($student->status) }}</td>
                <td>{{ $student->payment_done }}</td>
                <td>{{ $student->payment_remaining }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
