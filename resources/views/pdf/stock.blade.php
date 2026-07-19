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
                    <p>{{ __('report/stock.generated_on') }}: {{ $data['date'] }}</p>
                </div>

                <div class="section-title">{{ __('report/stock.summary') }}</div>
                <table>
                    <tr>
                        <th width="25%">{{ __('report/stock.total_products') }}</th>
                        <td>{{ $products->count() }}</td>

                        <th width="25%">{{ __('report/stock.stock_value_cost') }}</th>
                        <td>${{ number_format($totalStockValueCost, 2) }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('report/stock.stock_value_retail') }}</th>
                        <td>${{ number_format($totalStockValueRetail, 2) }}</td>
                        <th></th>
                        <td></td>
                    </tr>
                </table>

                <div class="section-title">{{ __('report/stock.stock_detail') }}</div>

                <table>
                    <thead>
                        <tr>
                            <th>{{ __('report/stock.sku') }}</th>
                            <th>{{ __('report/stock.product') }}</th>
                            <th>{{ __('report/stock.category') }}</th>
                            <th>{{ __('report/stock.stock_quantity') }}</th>
                            <th>{{ __('report/stock.cost_price') }}</th>
                            <th>{{ __('report/stock.retail_price') }}</th>
                            <th>{{ __('report/stock.stock_value_cost') }}</th>
                            <th>{{ __('report/stock.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->sku ?? __('report/stock.na') }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name ?? __('report/stock.na') }}</td>
                                <td>{{ $product->stock_quantity }}</td>
                                <td>${{ number_format($product->cost_price, 2) }}</td>
                                <td>${{ number_format($product->retail_price, 2) }}</td>
                                <td>${{ number_format($product->stock_quantity * $product->cost_price, 2) }}</td>
                                <td>{{ ucfirst($product->status->value) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    {{ __('report/stock.no_products_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</main>

@endsection
