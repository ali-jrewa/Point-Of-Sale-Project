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

    @php
        $grandSubtotal = $sales->sum('subtotal');
        $grandDiscount = $sales->sum('discount');
        $grandTax = $sales->sum('tax');
        $grandTotal = $sales->sum('total');
        $grandPaid = $sales->sum('paid_amount');
        $grandDue = $sales->sum('due_amount');
    @endphp

    <div class="section-title">Summary</div>
    <table>
        <tr>
            <th width="25%">Total Sales</th>
            <td>{{ $sales->count() }}</td>
            <th width="25%">Grand Total</th>
            <td>${{ number_format($grandTotal, 2) }}</td>
        </tr>
        <tr>
            <th>Total Paid</th>
            <td class="text-success">${{ number_format($grandPaid, 2) }}</td>
            <th>Total Due</th>
            <td class="{{ $grandDue > 0 ? 'text-danger' : 'text-success' }}">${{ number_format($grandDue, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Sales Detail</div>
    <table>
        <thead>
            <tr>
                <th>Sale Code</th>
                <th>Customer</th>
                <th>Invoice</th>
                <th>Date</th>
                <th>Subtotal</th>
                <th>Discount</th>
                <th>Tax</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Due</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td>{{ $sale->sale_code }}</td>
                    <td>{{ $sale->customer ? $sale->customer->first_name.' '.$sale->customer->last_name : 'Walk In' }}</td>
                    <td>{{ $sale->invoice_number ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->sold_at)->format('Y-m-d') }}</td>
                    <td>${{ number_format($sale->subtotal, 2) }}</td>
                    <td>${{ number_format($sale->discount, 2) }}</td>
                    <td>${{ number_format($sale->tax, 2) }}</td>
                    <td>${{ number_format($sale->total, 2) }}</td>
                    <td class="text-success">${{ number_format($sale->paid_amount, 2) }}</td>
                    <td class="{{ $sale->due_amount > 0 ? 'text-danger' : '' }}">${{ number_format($sale->due_amount, 2) }}</td>
                    <td>{{ ucfirst($sale->sale_status->value) }}</td>
                </tr>
            @empty
                <tr><td colspan="11">No sales found for this period.</td></tr>
            @endforelse
            <tr class="totals-row">
                <td colspan="4">Totals</td>
                <td>${{ number_format($grandSubtotal, 2) }}</td>
                <td>${{ number_format($grandDiscount, 2) }}</td>
                <td>${{ number_format($grandTax, 2) }}</td>
                <td>${{ number_format($grandTotal, 2) }}</td>
                <td>${{ number_format($grandPaid, 2) }}</td>
                <td>${{ number_format($grandDue, 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
