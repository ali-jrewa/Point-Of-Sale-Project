<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $data['title'] }}</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #495057;
            color: #fff;
            padding: 6px 10px;
            margin-top: 25px;
            margin-bottom: 8px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-success { color: #198754; }
        .text-danger { color: #dc3545; }
        .text-warning { color: #b8860b; }
        .sale-block {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .sale-meta {
            font-size: 12px;
            margin-bottom: 6px;
        }
        .sale-meta strong {
            display: inline-block;
            min-width: 100px;
        }
        .sub-table {
            margin-left: 10px;
            width: 97%;
        }
        .sub-title {
            font-size: 12px;
            font-weight: bold;
            margin-top: 8px;
            margin-bottom: 4px;
        }
        .totals-row td {
            font-weight: bold;
            background-color: #fafafa;
        }
    </style>
</head>
<body>
     @include('pdf._toolbar')

    <div class="header">
        <h2>{{ $data['title'] }}</h2>
        <p>Generated on: {{ $data['date'] }}</p>
    </div>

    {{-- Customer Info --}}
    <div class="section-title">Customer Information</div>
    <table>
        <tr>
            <th width="20%">Name</th>
            <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
            <th width="20%">Phone</th>
            <td>{{ $customer->phone ?? '-' }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $customer->email ?? '-' }}</td>
            <th>Status</th>
            <td>{{ $customer->status ?? '-' }}</td>
        </tr>
        <tr>
            <th>Credit Limit</th>
            <td>${{ number_format($customer->credit_limit ?? 0, 2) }}</td>
            <th>Credit Used</th>
            <td>${{ number_format($customer->credit_used ?? 0, 2) }}</td>
        </tr>
    </table>

    {{-- Sales Summary --}}
    @php
        $grandTotal = $customer->sales->sum('total');
        $grandPaid = $customer->sales->sum('paid_amount');
        $grandDue = $customer->sales->sum('due_amount');
        $grandRefunded = $customer->sales->flatMap->refunds->sum('amount');
    @endphp

    <div class="section-title">Overall Summary</div>
    <table>
        <tr>
            <th width="25%">Total Sales</th>
            <td>{{ $customer->sales->count() }}</td>
            <th width="25%">Grand Total</th>
            <td>${{ number_format($grandTotal, 2) }}</td>
        </tr>
        <tr>
            <th>Total Paid</th>
            <td class="text-success">${{ number_format($grandPaid, 2) }}</td>
            <th>Total Due</th>
            <td class="{{ $grandDue > 0 ? 'text-danger' : 'text-success' }}">
                ${{ number_format($grandDue, 2) }}
            </td>
        </tr>
        <tr>
            <th>Total Refunded</th>
            <td class="text-danger">${{ number_format($grandRefunded, 2) }}</td>
            <th></th>
            <td></td>
        </tr>
    </table>

    {{-- Per Sale Detail --}}
    <div class="section-title">Sales, Payments &amp; Refunds</div>

    @forelse($customer->sales as $sale)
        <div class="sale-block">

            <div class="sale-meta">
                <strong>Sale Code:</strong> {{ $sale->sale_code }}
                &nbsp;&nbsp;
                <strong>Invoice:</strong> {{ $sale->invoice_number ?? '-' }}
                &nbsp;&nbsp;
                <strong>Date:</strong> {{ \Carbon\Carbon::parse($sale->sold_at)->format('Y-m-d') }}
                <br>
                <strong>Sale Status:</strong> {{ ucfirst($sale->sale_status->value) }}
                &nbsp;&nbsp;
                <strong>Payment Status:</strong> {{ ucfirst($sale->payment_status->value) }}
            </div>

            {{-- Sale Items --}}
            <table class="sub-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Discount</th>
                        <th>Tax</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sale->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->unit_price, 2) }}</td>
                            <td>${{ number_format($item->discount, 2) }}</td>
                            <td>${{ number_format($item->tax, 2) }}</td>
                            <td>${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No items.</td></tr>
                    @endforelse
                    <tr class="totals-row">
                        <td colspan="5" style="text-align:right;">Total</td>
                        <td>${{ number_format($sale->total, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- Payments for this sale --}}
            @if($sale->payments->isNotEmpty())
                <div class="sub-title">Payments</div>
                <table class="sub-table">
                    <thead>
                        <tr>
                            <th>Payment Code</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Reference</th>
                            <th>Paid At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_code }}</td>
                                <td>{{ ucfirst(str_replace('_',' ',$payment->method->value)) }}</td>
                                <td class="text-success">${{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->reference ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- Refunds for this sale --}}
            @if($sale->refunds->isNotEmpty())
                <div class="sub-title">Refunds</div>
                <table class="sub-table">
                    <thead>
                        <tr>
                            <th>Refund Code</th>
                            <th>Items Refunded</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Reason</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->refunds as $refund)
                            <tr>
                                <td>{{ $refund->refund_code }}</td>
                                <td>
                                    @foreach($refund->items as $refundItem)
                                        {{ $refundItem->product->name ?? 'N/A' }}
                                        (x{{ $refundItem->quantity }})
                                        @if(!$loop->last)<br>@endif
                                    @endforeach
                                </td>
                                <td>{{ ucfirst(str_replace('_',' ',$refund->method)) }}</td>
                                <td class="text-danger">${{ number_format($refund->amount, 2) }}</td>
                                <td>{{ $refund->reason ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($refund->refunded_at)->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>
    @empty
        <p>This customer has no sales on record.</p>
    @endforelse

</body>
</html>
