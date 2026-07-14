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

    <p class="range">Period: {{ $data['from'] }} to {{ $data['to'] }}</p>

    @php $grandTotal = $refunds->sum('amount'); @endphp

    <div class="section-title">Summary</div>
    <table>
        <tr>
            <th width="25%">Total Refunds</th>
            <td>{{ $refunds->count() }}</td>
            <th width="25%">Total Amount</th>
            <td class="text-danger">${{ number_format($grandTotal, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Refund Detail</div>
    <table>
        <thead>
            <tr>
                <th>Refund Code</th>
                <th>Sale Code</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Method</th>
                <th>Amount</th>
                <th>Reason</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($refunds as $refund)
                <tr>
                    <td>{{ $refund->refund_code }}</td>
                    <td>{{ $refund->sale->sale_code ?? '-' }}</td>
                    <td>
                        @if($refund->sale && $refund->sale->customer)
                            {{ $refund->sale->customer->first_name }} {{ $refund->sale->customer->last_name }}
                        @else
                            Walk In
                        @endif
                    </td>
                    <td>
                        @foreach($refund->items as $item)
                            {{ $item->product->name ?? 'N/A' }} (x{{ $item->quantity }})
                            @if(!$loop->last)<br>@endif
                        @endforeach
                    </td>
                    <td>{{ ucfirst(str_replace('_',' ',$refund->method)) }}</td>
                    <td class="text-danger">${{ number_format($refund->amount, 2) }}</td>
                    <td>{{ $refund->reason ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($refund->refunded_at)->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="8">No refunds found for this period.</td></tr>
            @endforelse
            <tr class="totals-row">
                <td colspan="5">Total</td>
                <td>${{ number_format($grandTotal, 2) }}</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
