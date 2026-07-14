<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $data['title'] }}</title>
    @include('pdf._style')
</head>
<body>

    <div class="header">
        <h2>{{ $data['title'] }}</h2>
        <p>Generated on: {{ $data['date'] }}</p>
    </div>

    <div class="section-title">Summary</div>
    <table>
        <tr>
            <th width="25%">Outstanding Sales</th>
            <td>{{ $sales->count() }}</td>
            <th width="25%">Total Due</th>
            <td class="text-danger">${{ number_format($totalDue, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Outstanding Detail</div>
    <table>
        <thead>
            <tr>
                <th>Sale Code</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Sale Date</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Due</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td>{{ $sale->sale_code }}</td>
                    <td>
                        @if($sale->customer)
                            {{ $sale->customer->first_name }} {{ $sale->customer->last_name }}
                        @else
                            Walk In
                        @endif
                    </td>
                    <td>{{ $sale->customer->phone ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->sold_at)->format('Y-m-d') }}</td>
                    <td>${{ number_format($sale->total, 2) }}</td>
                    <td class="text-success">${{ number_format($sale->paid_amount, 2) }}</td>
                    <td class="text-danger">${{ number_format($sale->due_amount, 2) }}</td>
                    <td>{{ ucfirst($sale->payment_status->value) }}</td>
                </tr>
            @empty
                <tr><td colspan="8">No outstanding balances. Everything is settled.</td></tr>
            @endforelse
            <tr class="totals-row">
                <td colspan="6">Total Due</td>
                <td>${{ number_format($totalDue, 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
