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
    <img src="{{ $logo }}" alt="nile" style="width: 150px; height: auto;"/>
    <h2>Project Report</h2>
    <p>Notes: {!! $notes !!}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Project Name</th>
                <th>Site</th>
                <th>start</th>
                <th>end</th>
                <th>Priority</th>
                <th>status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($estimate_invoices as $invoice)
                <tr>
                    <td>{{ $invoice->id }}</td>
                    <td>{{ $invoice->title }}</td>
                    <td>{{ $invoice->site_id }}</td>
                    <td>{{ $invoice->start_date }}</td>
                    <td>{{ $invoice->end_date }}</td>
                    <td>{{ $invoice->priority_id }}</td>
                    <td>{{ $invoice->status_id }}</td>
                    <!-- Add more data as needed -->
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>