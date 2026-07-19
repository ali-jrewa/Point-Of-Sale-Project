@extends('layouts.app')

@section('title', $data['title'])

@section('style')

    @include('pdf._style')
     <style>

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
@endsection

@section('content')


<main class="app-main">
    <div class="app-content">
        <div class="container-fluid">
            <div class="pdf-report">
                @include('pdf._toolbar')


                <div class="header">
                    <h2>{{ $data['title'] }}</h2>
                    <p>{{ __('report/supplier.generated_on') }}: {{ $data['date'] }}</p>
                </div>

                {{-- Supplier Info --}}
                <div class="section-title">{{ __('report/supplier.supplier_information') }}</div>
                <table>
                    <tr>
                        <th width="20%">{{ __('report/supplier.name') }}</th>
                        <td style="text-align:center;">{{ $supplier->first_name }} {{ $supplier->last_name }}</td>
                        <th width="20%">{{ __('report/supplier.company') }}</th>
                        <td style="text-align:center;">{{ $supplier->company_name ??  __('report/supplier.na') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('report/supplier.phone') }}</th>
                        <td style="text-align:center;">{{ $supplier->phone }}</td>
                        <th>{{ __('report/supplier.email') }}</th>
                        <td style="text-align:center;">{{ $supplier->email ??  __('report/supplier.na') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('report/supplier.tax_number') }}</th>
                        <td style="text-align:center;">{{ $supplier->tax_number ??  __('report/supplier.na') }}</td>
                        <th>{{ __('report/supplier.status') }}</th>
                        <td style="text-align:center;">{{ ucfirst($supplier->status->value) }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('report/supplier.address') }}</th>
                        <td style="text-align:center;" colspan="3">{{ $supplier->address ??  __('report/supplier.na') }}</td>
                    </tr>
                </table>

                {{-- Purchases Summary --}}
                @php
                    $grandTotal = $supplier->purchases->sum('total');
                    $grandSubtotal = $supplier->purchases->sum('subtotal');
                    $grandDiscount = $supplier->purchases->sum('discount');
                    $grandTax = $supplier->purchases->sum('tax');
                @endphp

                <div class="section-title">{{ __('report/supplier.overall_summary') }}</div>
                <table>
                    <tr>
                        <th width="25%">{{ __('report/supplier.total_purchases') }}</th>
                        <td style="text-align:center;">{{ $supplier->purchases->count() }}</td>
                        <th width="25%">{{ __('report/supplier.grand_total') }}</th>
                        <td style="text-align:center;">${{ number_format($grandTotal, 2) }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('report/supplier.total_subtotal') }}</th>
                        <td style="text-align:center;">${{ number_format($grandSubtotal, 2) }}</td>
                        <th>{{ __('report/supplier.total_discount') }}</th>
                        <td style="text-align:center;" class="text-danger">${{ number_format($grandDiscount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('report/supplier.total_tax') }}</th>
                        <td style="text-align:center;">${{ number_format($grandTax, 2) }}</td>
                        <th></th>
                        <td style="text-align:center;"></td>
                    </tr>
                </table>

                {{-- Per Purchase Detail --}}
                <div class="section-title">{{ __('report/supplier.purchases') }}</div>

                @forelse($supplier->purchases as $purchase)
                    <div class="purchase-block">

                        <div class="purchase-meta">
                            <strong>{{ __('report/supplier.purchase_code') }}:</strong> {{ $purchase->purchase_code }}
                            &nbsp;&nbsp;
                            <strong>{{ __('report/supplier.invoice') }}:</strong> {{ $purchase->invoice_number ?? '-' }}
                            &nbsp;&nbsp;
                            <strong>{{ __('report/supplier.date') }}:</strong> {{ \Carbon\Carbon::parse($purchase->purchased_at)->format('Y-m-d') }}
                            <br>
                            <strong>{{ __('report/supplier.purchase_status') }}:</strong> {{ ucfirst($purchase->purchase_status->value) }}
                            &nbsp;&nbsp;
                            <strong>{{ __('report/supplier.payment_status') }}:</strong> {{ ucfirst($purchase->payment_status->value) }}
                            @if($purchase->notes)
                                <br>
                                <strong>{{ __('report/supplier.notes') }}:</strong> {{ $purchase->notes }}
                            @endif
                        </div>

                        {{-- Purchase Items --}}
                        <table class="sub-table">
                            <thead>
                                <tr>
                                    <th>{{ __('report/supplier.product') }}</th>
                                    <th>{{ __('report/supplier.quantity') }}</th>
                                    <th>{{ __('report/supplier.unit_cost') }}</th>
                                    <th>{{ __('report/supplier.discount') }}</th>
                                    <th>{{ __('report/supplier.tax') }}</th>
                                    <th>{{ __('report/supplier.subtotal') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchase->items as $item)
                                    <tr>
                                        <td style="text-align:center;">{{ $item->product->name ?? __('report/supplier.na') }}</td>
                                        <td style="text-align:center;">{{ $item->quantity }}</td>
                                        <td style="text-align:center;">${{ number_format($item->unit_cost, 2) }}</td>
                                        <td style="text-align:center;">${{ number_format($item->discount, 2) }}</td>
                                        <td style="text-align:center;">${{ number_format($item->tax, 2) }}</td>
                                        <td style="text-align:center;">${{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td style="text-align:center;" colspan="6"> {{ __('report/supplier.no_items') }}</td></tr>
                                @endforelse
                                <tr class="totals-row">
                                    <td style="text-align:right;" colspan="5"> {{ __('report/supplier.total') }}</td>
                                    <td style="text-align:center;">${{ number_format($purchase->total, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                @empty
                    <p>{{ __('report/supplier.no_purchases') }}</p>
                @endforelse

    </div>
    </div>
    </div>
</main>
@endsection
