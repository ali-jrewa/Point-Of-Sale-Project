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
        .purchase-block {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .purchase-meta {
            font-size: 12px;
            margin-bottom: 6px;
        }
        .purchase-meta strong {
            display: inline-block;
            min-width: 110px;
        }
        .sub-table {
            margin-left: 10px;
            width: 97%;
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

    {{-- Supplier Info --}}
    <div class="section-title">Supplier Information</div>
    <table>
        <tr>
            <th width="20%">Name</th>
            <td style="text-align:center;">{{ $supplier->first_name }} {{ $supplier->last_name }}</td>
            <th width="20%">Company</th>
            <td style="text-align:center;">{{ $supplier->company_name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Phone</th>
            <td style="text-align:center;">{{ $supplier->phone }}</td>
            <th>Email</th>
            <td style="text-align:center;">{{ $supplier->email ?? '-' }}</td>
        </tr>
        <tr>
            <th>Tax Number</th>
            <td style="text-align:center;">{{ $supplier->tax_number ?? '-' }}</td>
            <th>Status</th>
            <td style="text-align:center;">{{ ucfirst($supplier->status->value) }}</td>
        </tr>
        <tr>
            <th>Address</th>
            <td style="text-align:center;" colspan="3">{{ $supplier->address ?? '-' }}</td>
        </tr>
    </table>

    {{-- Purchases Summary --}}
    @php
        $grandTotal = $supplier->purchases->sum('total');
        $grandSubtotal = $supplier->purchases->sum('subtotal');
        $grandDiscount = $supplier->purchases->sum('discount');
        $grandTax = $supplier->purchases->sum('tax');
    @endphp

    <div class="section-title">Overall Summary</div>
    <table>
        <tr>
            <th width="25%">Total Purchases</th>
            <td style="text-align:center;">{{ $supplier->purchases->count() }}</td>
            <th width="25%">Grand Total</th>
            <td style="text-align:center;">${{ number_format($grandTotal, 2) }}</td>
        </tr>
        <tr>
            <th>Total Subtotal</th>
            <td style="text-align:center;">${{ number_format($grandSubtotal, 2) }}</td>
            <th>Total Discount</th>
            <td style="text-align:center;" class="text-danger">${{ number_format($grandDiscount, 2) }}</td>
        </tr>
        <tr>
            <th>Total Tax</th>
            <td style="text-align:center;">${{ number_format($grandTax, 2) }}</td>
            <th></th>
            <td style="text-align:center;"></td>
        </tr>
    </table>

    {{-- Per Purchase Detail --}}
    <div class="section-title">Purchases</div>

    @forelse($supplier->purchases as $purchase)
        <div class="purchase-block">

            <div class="purchase-meta">
                <strong>Purchase Code:</strong> {{ $purchase->purchase_code }}
                &nbsp;&nbsp;
                <strong>Invoice:</strong> {{ $purchase->invoice_number ?? '-' }}
                &nbsp;&nbsp;
                <strong>Date:</strong> {{ \Carbon\Carbon::parse($purchase->purchased_at)->format('Y-m-d') }}
                <br>
                <strong>Purchase Status:</strong> {{ ucfirst($purchase->purchase_status->value) }}
                &nbsp;&nbsp;
                <strong>Payment Status:</strong> {{ ucfirst($purchase->payment_status->value) }}
                @if($purchase->notes)
                    <br>
                    <strong>Notes:</strong> {{ $purchase->notes }}
                @endif
            </div>

            {{-- Purchase Items --}}
            <table class="sub-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Unit Cost</th>
                        <th>Discount</th>
                        <th>Tax</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchase->items as $item)
                        <tr>
                            <td style="text-align:center;">{{ $item->product->name ?? 'N/A' }}</td>
                            <td style="text-align:center;">{{ $item->quantity }}</td>
                            <td style="text-align:center;">${{ number_format($item->unit_cost, 2) }}</td>
                            <td style="text-align:center;">${{ number_format($item->discount, 2) }}</td>
                            <td style="text-align:center;">${{ number_format($item->tax, 2) }}</td>
                            <td style="text-align:center;">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td style="text-align:center;" colspan="6">No items.</td></tr>
                    @endforelse
                    <tr class="totals-row">
                        <td style="text-align:right;" colspan="5">Total</td>
                        <td style="text-align:center;">${{ number_format($purchase->total, 2) }}</td>
                    </tr>
                </tbody>
            </table>

        </div>
    @empty
        <p>This supplier has no purchases on record.</p>
    @endforelse

</body>
</html>
