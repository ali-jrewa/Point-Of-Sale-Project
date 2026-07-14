<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Customers Report</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            font-size: 14px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        /* Forces a clean break to the next page */
        .page-break {
            page-break-after: always;
        }
        /* Prevent the last page from printing a blank extra page */
        .page-break:last-child {
            page-break-after: avoid;
        }
    </style>
</head>
<body>

    <!-- FIXED: Accessing array syntax using bracket notation -->
    <div class="header">
        <h2>{{ $data['title'] }}</h2>
        <p>Generated on: {{ $data['date'] }}</p>
    </div>

    @foreach($customerChunks as $chunkIndex => $chunk)
        <div class="page-break">
            <h2>Customer Directory - Page {{ $chunkIndex + 1 }}</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Credit Limit</th>
                        <th>Credit Used</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chunk as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <!-- FIXED: Changed second first_name to last_name -->
                            <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->credit_limit }}</td>
                            <td>{{ $customer->credit_used }}</td>
                            <td>{{ $customer->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

</body>
</html>
