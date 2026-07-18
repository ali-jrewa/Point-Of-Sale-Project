<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
   <title>{{ __('report/all_supplier.title') }}</title>
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
        <p>{{ __('report/all_supplier.generated_on') }}: {{ $data['date'] }}</p>
    </div>

    @foreach($supplierChunks as $chunkIndex => $chunk)
        <div class="page-break">
            <h2> {{ __('report/all_supplier.supplier_directory') }} - {{ __('report/all_supplier.page') }} {{ $chunkIndex + 1 }} </h2>
            <table>
                <thead>
                    <tr>
                        <th>{{ __('report/all_supplier.id') }}</th>
                        <th>{{ __('report/all_supplier.name') }}</th>
                        <th>{{ __('report/all_supplier.company') }}</th>
                        <th>{{ __('report/all_supplier.phone') }}</th>
                        <th>{{ __('report/all_supplier.email') }}</th>
                        <th>{{ __('report/all_supplier.address') }}</th>
                        <th>{{ __('report/all_supplier.tax_number') }}</th>
                        <th>{{ __('report/all_supplier.status') }}</th>
                        <th>{{ __('report/all_supplier.created_at') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chunk as $supplier)
                        <tr>
                            <td>{{ $supplier->id }}</td>
                            <td>{{ $supplier->first_name }} {{ $supplier->last_name }}</td>
                            <td>{{ $supplier->company_name ?? __('report/all_supplier.na') }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td>{{ $supplier->email ?? __('report/all_supplier.na') }} </td>
                            <td>{{ $supplier->address ?? __('report/all_supplier.na') }} </td>
                            <td>{{ $supplier->tax_number ?? __('report/all_supplier.na') }} </td>
                            <td>{{ __('report/all_supplier.' . strtolower($supplier->status->value)) }}</td>
                            <td>{{ $supplier->created_at->format('Y-m-d H:i A') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

</body>
</html>
