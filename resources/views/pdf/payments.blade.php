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

    @php $grandTotal = $payments->sum('amount'); @endphp

    <div class="section-title">By Payment Method</div>
    <table>
        <thead>
            <tr>
                <th>Method</th>
                <th>Total Collected</th>
            </tr>
        </thead>
        <tbody>
            @foreach($byMethod as $method => $amount)
                <tr>
                    <td>{{ ucfirst(str_replace('_',' ',$method)) }}</td>
                    <td class="text-success">${{ number_format($amount, 2) }}</td>
                </tr>
            @endforeach
            <tr class="totals-row">
                <td>Total</td>
                <td>${{ number_format($grandTotal, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Payment Detail</div>
    <table>
        <thead>
            <tr>
                <th>Payment Code</th>
                <th>Sale Code</th>
                <th>Customer</th>
                <th>Method</th>
                <th>Amount</th>
                <th>Reference</th>
                <th>Received By</th>
                <th>Paid At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->payment_code }}</td>
                    <td>{{ $payment->sale->sale_code ?? '-' }}</td>
                    <td>
                        @if($payment->sale && $payment->sale->customer)
                            {{ $payment->sale->customer->first_name }} {{ $payment->sale->customer->last_name }}
                        @else
                            Walk In
                        @endif
                    </td>
                    <td>{{ ucfirst(str_replace('_',' ',$payment->method->value)) }}</td>
                    <td class="text-success">${{ number_format($payment->amount, 2) }}</td>
                    <td>{{ $payment->reference ?? '-' }}</td>
                    <td>{{ $payment->user->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="8">No payments found for this period.</td></tr>
            @endforelse
            <tr class="totals-row">
                <td colspan="4">Total</td>
                <td>${{ number_format($grandTotal, 2) }}</td>
                <td colspan="3"></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
