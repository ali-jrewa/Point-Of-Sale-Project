<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Suppliers Report</title>
    <style>
        body { font-family: sans-serif; color: #333; font-size: 14px; margin: 20px; }
        .header { text-align: center; margin-bottom: 25px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .page-break { page-break-after: always; }
        .page-break:last-child { page-break-after: avoid; }
    </style>
</head>
<body>
     @include('pdf._toolbar')

    <div class="header">
        <h2>{{ $data['title'] }}</h2>
        <p>Generated on: {{ $data['date'] }}</p>
    </div>

    @foreach($supplierChunks as $chunkIndex => $chunk)
        <div class="page-break">
            <h2>Supplier Directory - Page {{ $chunkIndex + 1 }}</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Tax Number</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chunk as $supplier)
                        <tr>
                            <td>{{ $supplier->id }}</td>
                            <td>{{ $supplier->first_name }} {{ $supplier->last_name }}</td>
                            <td>{{ $supplier->company_name ?? '-' }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td>{{ $supplier->email ?? '-' }}</td>
                            <td>{{ $supplier->address ?? '-' }}</td>
                            <td>{{ $supplier->tax_number ?? '-' }}</td>
                            <td>{{ ucfirst($supplier->status->value) }}</td>
                            <td>{{ $supplier->created_at->format('Y-m-d H:i A') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

</body>
</html>
