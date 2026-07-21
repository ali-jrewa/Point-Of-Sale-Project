@extends('layouts.app')

@section('title', $data['title'])

@section('style')
    @include('pdf._style')
@endsection

@section('content')
<main class="app-main">
    <div class="app-content">
        <div class="container-fluid">
            <div class="pdf-report">
                @include('pdf._toolbar')

                <div class="header">
                    <h2>{{ $data['title'] }}</h2>
                    <p>{{ __('report_products.generated_on') }}: {{ $data['date'] }}</p>
                </div>

                @if(!empty($data['from']) && !empty($data['to']))
                    <p class="range">{{ __('product.period') }}: {{ $data['from'] }} {{ __('sales.to') }} {{ $data['to'] }}</p>
                @endif

                <div class="section-title">{{ __('report_products.summary') }}</div>

                <table>
                    <thead>
                        <tr>
                            <th>{{ __('product.id') }}</th>
                            <th>{{ __('product.category') }}</th>
                            <th>{{ __('product.name') }}</th>
                            <th>{{ __('product.sku') }}</th>
                            <th>{{ __('product.cost_price') ?? __('product.cost_price') }}</th>
                            <th>{{ __('product.retail_price') }}</th>
                            <th>{{ __('product.stock') }}</th>
                            <th>{{ __('report_products.sold_value') }}</th>
                            <th>{{ __('report_products.purchased_qty') }}</th>
                            <th>{{ __('report_products.purchased_value') }}</th>
                            <th>{{ __('report_products.sold_qty') }}</th>
                            <th>{{ __('report_products.low_stock_threshold') ?? __('product.low_stock_threshold') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $index => $product)
                            @php
                                $purchasedQty = $product->purchaseItems->sum('quantity');
                                $purchasedValue = $product->purchaseItems->sum('subtotal');
                                $soldQty = $product->saleItems->sum('quantity');
                                $soldValue = $product->saleItems->sum('subtotal');
                            @endphp
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->category->name ?? '-' }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>${{ number_format($product->cost_price,2) }}</td>
                                <td>${{ number_format($product->retail_price,2) }}</td>
                                <td>{{ $product->stock_quantity }}</td>
                                <td>{{ $purchasedQty }}</td>
                                <td>${{ number_format($purchasedValue,2) }}</td>
                                <td>{{ $soldQty }}</td>
                                <td>${{ number_format($soldValue,2) }}</td>
                                <td>{{ $product->low_stock_threshold }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="section-title">{{ __('report_products.details') }}</div>

                @foreach($products as $product)
                    <div class="product-block">
                        <h4>{{ $product->name }} ({{ $product->sku }})</h4>
                        <p>{{ __('product.category') }}: {{ $product->category->name ?? '-' }}</p>

                        @if($product->purchaseItems->isNotEmpty())
                            <div class="sub-title">{{ __('report_products.purchases') }}</div>
                            <table class="sub-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('report_products.purchase_code') }}</th>
                                        <th>{{ __('report_products.date') }}</th>
                                        <th>{{ __('report_products.quantity') }}</th>
                                        <th>{{ __('report_products.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->purchaseItems as $item)
                                        <tr>
                                            <td>{{ $item->purchase->purchase_code ?? '-' }}</td>
                                            <td>{{ optional($item->purchase)->purchased_at ? \Carbon\Carbon::parse($item->purchase->purchased_at)->format('Y-m-d') : '-' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>${{ number_format($item->subtotal,2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        @if($product->saleItems->isNotEmpty())
                            <div class="sub-title">{{ __('report_products.sales') }}</div>
                            <table class="sub-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('report_products.sale_code') }}</th>
                                        <th>{{ __('report_products.date') }}</th>
                                        <th>{{ __('report_products.quantity') }}</th>
                                        <th>{{ __('report_products.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->saleItems as $item)
                                        <tr>
                                            <td>{{ $item->sale->sale_code ?? '-' }}</td>
                                            <td>{{ optional($item->sale)->sold_at ? \Carbon\Carbon::parse($item->sale->sold_at)->format('Y-m-d') : '-' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>${{ number_format($item->subtotal,2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                    </div>
                @endforeach

            </div>
        </div>
    </div>
</main>
@endsection
