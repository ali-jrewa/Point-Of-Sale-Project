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
                    <p class="range">{{ __('report_products.period') }}: {{ $data['from'] }} {{ __('sales.to') }} {{ $data['to'] }}</p>
                @endif

                <div class="section-title">{{ __('report_products.product_information') }}</div>
                <table>
                    <tr>
                        <th>{{ __('product.id') }}</th>
                        <td>{{ $product->id }}</td>
                        <th>{{ __('product.name') }}</th>
                        <td>{{ $product->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('product.category') }}</th>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <th>{{ __('product.sku') }}</th>
                        <td>{{ $product->sku }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('product.stock') }}</th>
                        <td>{{ $product->stock_quantity }}</td>
                        <th>{{ __('product.retail_price') }}</th>
                        <td>${{ number_format($product->retail_price,2) }}</td>
                    </tr>
                </table>

                <div class="section-title">{{ __('report_products.purchases') }}</div>

                @if($product->purchaseItems->isEmpty())
                    <p>{{ __('report_products.no_purchases') }}</p>
                @else
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

                <div class="section-title">{{ __('report_products.sales') }}</div>

                @if($product->saleItems->isEmpty())
                    <p>{{ __('report_products.no_sales') }}</p>
                @else
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
        </div>
    </div>
</main>
@endsection
