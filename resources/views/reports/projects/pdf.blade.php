<!DOCTYPE html>
<html>
<head>
    <title>Project Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>{{ $company_title }}</h1>
    <img src="{{ public_path($logo) }}" alt="Company Logo" style="width: 150px; height: auto;"/>
    <h2>Project Report</h2>
    <p>Notes: {!! $notes !!}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Project Name</th>
                <th>Status</th>
                <!-- Add more columns as needed -->
            </tr>
        </thead>
        <tbody>
            @foreach ($estimate_invoices as $invoice)
                <tr>
                    <td>{{ $invoice->id }}</td>
                    <td>{{ $invoice->name }}</td>
                    <td>{{ $invoice->status }}</td>
                    <!-- Add more data as needed -->
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>