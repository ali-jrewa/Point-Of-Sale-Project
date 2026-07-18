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
        <h2>{{ __('report/customer.generated_on') }}</h2>
        <p>Generated on: : {{ $data['date'] }}</p>
    </div>

    {{-- Customer Info --}}
    <div class="section-title">{{ __('report/customer.customer_information') }}</div>
    <table>
        <tr>
            <th width="20%">{{ __('report/customer.name') }}</th>
            <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
            <th width="20%">{{ __('report/customer.phone') }}</th>
            <td>{{ $customer->phone ?? '-' }}</td>
        </tr>
        <tr>
            <th>{{ __('report/customer.email') }}</th>
            <td>{{ $customer->email ?? '-' }}</td>
            <th>{{ __('report/customer.status') }}</th>
            <td>{{ $customer->status ?? '-' }}</td>
        </tr>
        <tr>
            <th>{{ __('report/customer.credit_limit') }}</th>
            <td>${{ number_format($customer->credit_limit ?? 0, 2) }}</td>
            <th>{{ __('report/customer.credit_used') }}</th>
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

    <div class="section-title">{{ __('report/customer.overall_summary') }}</div>
    <table>
        <tr>
            <th width="25%">{{ __('report/customer.total_sales') }}</th>
            <td>{{ $customer->sales->count() }}</td>
            <th width="25%">{{ __('report/customer.grand_total') }}</th>
            <td>${{ number_format($grandTotal, 2) }}</td>
        </tr>
        <tr>
            <th>{{ __('report/customer.total_paid') }}</th>
            <td class="text-success">${{ number_format($grandPaid, 2) }}</td>
            <th>{{ __('report/customer.total_due') }}</th>
            <td class="{{ $grandDue > 0 ? 'text-danger' : 'text-success' }}">
                ${{ number_format($grandDue, 2) }}
            </td>
        </tr>
        <tr>
            <th>{{ __('report/customer.total_refunded') }}</th>
            <td class="text-danger">${{ number_format($grandRefunded, 2) }}</td>
            <th></th>
            <td></td>
        </tr>
    </table>

    {{-- Per Sale Detail --}}
    <div class="section-title">{{ __('report/customer.sales_payments_refunds') }}</div>

    @forelse($customer->sales as $sale)
        <div class="sale-block">

            <div class="sale-meta">
                <strong>{{ __('report/customer.sale_code') }}:</strong> {{ $sale->sale_code }}
                &nbsp;&nbsp;
                <strong>{{ __('report/customer.invoice') }}:</strong> {{ $sale->invoice_number ?? '-' }}
                &nbsp;&nbsp;
                <strong>{{ __('report/customer.date') }}:</strong> {{ \Carbon\Carbon::parse($sale->sold_at)->format('Y-m-d') }}
                <br>
                <strong>{{ __('report/customer.sale_status') }}:</strong> {{ ucfirst($sale->sale_status->value) }}
                &nbsp;&nbsp;
                <strong>{{ __('report/customer.payment_status') }}:</strong> {{ ucfirst($sale->payment_status->value) }}
            </div>

            {{-- Sale Items --}}
            <table class="sub-table">
                <thead>
                    <tr>
                        <th>{{ __('report/customer.product') }}</th>
                        <th>{{ __('report/customer.quantity') }}</th>
                        <th>{{ __('report/customer.unit_price') }}</th>
                        <th>{{ __('report/customer.discount') }}</th>
                        <th>{{ __('report/customer.tax') }}</th>
                        <th>{{ __('report/customer.subtotal') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sale->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? __('report/customer.na') }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->unit_price, 2) }}</td>
                            <td>${{ number_format($item->discount, 2) }}</td>
                            <td>${{ number_format($item->tax, 2) }}</td>
                            <td>${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">{{ __('report/customer.no_items') }}</td></tr>
                    @endforelse
                    <tr class="totals-row">
                        <td colspan="5" style="text-align:right;">{{ __('report/customer.total') }}</td>
                        <td>${{ number_format($sale->total, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- Payments for this sale --}}
            @if($sale->payments->isNotEmpty())
                <div class="sub-title">{{ __('report/customer.payments') }}</div>
                <table class="sub-table">
                    <thead>
                        <tr>
                            <th>{{ __('report/customer.payment_code') }}</th>
                            <th>{{ __('report/customer.method') }}</th>
                            <th>{{ __('report/customer.amount') }}</th>
                            <th>{{ __('report/customer.reference') }}</th>
                            <th>{{ __('report/customer.paid_at') }}</th>
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
                <div class="sub-title">{{ __('report/customer.refunds') }}</div>
                <table class="sub-table">
                    <thead>
                        <tr>
                            <th>{{ __('report/customer.refund_code') }}</th>
                            <th>{{ __('report/customer.items_refunded') }}</th>
                            <th>{{ __('report/customer.method') }}</th>
                            <th>{{ __('report/customer.amount') }}</th>
                            <th>{{ __('report/customer.reason') }}</th>
                            <th>{{ __('report/customer.refunded_at') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->refunds as $refund)
                            <tr>
                                <td>{{ $refund->refund_code }}</td>
                                <td>
                                    @foreach($refund->items as $refundItem)
                                        {{ $refundItem->product->name ?? __('report/customer.na') }}
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
        <p>{{ __('report/customer.no_sales') }}</p>
    @endforelse

</body>
</html>
