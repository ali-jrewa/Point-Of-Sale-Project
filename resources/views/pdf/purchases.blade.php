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
        $grandSubtotal = $purchases->sum('subtotal');
        $grandDiscount = $purchases->sum('discount');
        $grandTax = $purchases->sum('tax');
        $grandTotal = $purchases->sum('total');
    @endphp

    <div class="section-title">Summary</div>
    <table>
        <tr>
            <th width="25%">Total Purchases</th>
            <td>{{ $purchases->count() }}</td>
            <th width="25%">Grand Total</th>
            <td>${{ number_format($grandTotal, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Purchase Detail</div>
    <table>
        <thead>
            <tr>
                <th>Purchase Code</th>
                <th>Supplier</th>
                <th>Invoice</th>
                <th>Date</th>
                <th>Subtotal</th>
                <th>Discount</th>
                <th>Tax</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchases as $purchase)
                <tr>
                    <td>{{ $purchase->purchase_code }}</td>
                    <td>{{ $purchase->supplier ? $purchase->supplier->first_name.' '.$purchase->supplier->last_name : '-' }}</td>
                    <td>{{ $purchase->invoice_number ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($purchase->purchased_at)->format('Y-m-d') }}</td>
                    <td>${{ number_format($purchase->subtotal, 2) }}</td>
                    <td>${{ number_format($purchase->discount, 2) }}</td>
                    <td>${{ number_format($purchase->tax, 2) }}</td>
                    <td>${{ number_format($purchase->total, 2) }}</td>
                    <td>{{ ucfirst($purchase->purchase_status->value) }}</td>
                </tr>
            @empty
                <tr><td colspan="9">No purchases found for this period.</td></tr>
            @endforelse
            <tr class="totals-row">
                <td colspan="4">Totals</td>
                <td>${{ number_format($grandSubtotal, 2) }}</td>
                <td>${{ number_format($grandDiscount, 2) }}</td>
                <td>${{ number_format($grandTax, 2) }}</td>
                <td>${{ number_format($grandTotal, 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
