<!DOCTYPE html>
<html dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('report/all_customer.customers_report') }}</title>
    <style>
        body {
            font-family: {{ app()->getLocale() === 'ar' ? "'DejaVu Sans', sans-serif" : "sans-serif" }};
        }
        @if(app()->getLocale() === 'ar')
        table, th, td { text-align: right; }
        @endif
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


     @include('pdf._toolbar')


    <div class="header">
        <h2>{{ $data['title'] }}</h2>
        <p>{{ __('report/all_customer.generated_on') }}: {{ $data['date'] }}</p>
    </div>

    @foreach($customerChunks as $chunkIndex => $chunk)
        <div class="page-break">
            <h2>
    {{ __('report/all_customer.customer_directory') }} - {{ __('report/all_customer.page') }} {{ $chunkIndex + 1 }}</h2>
            <table>
                <thead>
                    <tr>
                        <th>{{ __('report/all_customer.id') }}</th>
                        <th>{{ __('report/all_customer.name') }}</th>
                        <th>{{ __('report/all_customer.email') }}</th>
                        <th>{{ __('report/all_customer.phone') }}</th>
                        <th>{{ __('report/all_customer.address') }}</th>
                        <th>{{ __('report/all_customer.credit_limit') }}</th>
                        <th>{{ __('report/all_customer.credit_used') }}</th>
                        <th>{{ __('report/all_customer.status') }}</th>
                        <th>{{ __('report/all_customer.created_at') }}</th>
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
                            <td>{{ $customer->address }}</td>
                            <td>{{ $customer->credit_limit }}</td>
                            <td>{{ $customer->credit_used }}</td>
                            <td>{{ $customer->status }}</td>
                            <td>{{ date('Y-m-d H:i A' , strtotime($customer->created_at)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

</body>
</html>
