@extends('layouts.app')

@section('title')
    {{ $data['title'] }}
@endsection

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
                    <h2>{{ __('report/low-stock.title') }}</h2>

                    <p>
                        {{ __('report/low-stock.generated_on') }}:
                        {{ $data['date'] }}
                    </p>
                </div>


                <div class="section-title">
                    {{ __('report/low-stock.products_requiring_reorder') }}
                </div>


                <table>
                    <thead>
                        <tr>
                            <th>{{ __('report/low-stock.sku') }}</th>
                            <th>{{ __('report/low-stock.product') }}</th>
                            <th>{{ __('report/low-stock.category') }}</th>
                            <th>{{ __('report/low-stock.current_stock') }}</th>
                            <th>{{ __('report/low-stock.low_stock_threshold') }}</th>
                            <th>{{ __('report/low-stock.shortfall') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->sku ?? __('report/low-stock.na') }}</td>

                                <td>
                                    {{ $product->name }}
                                </td>

                                <td>
                                    {{ $product->category->name ?? __('report/low-stock.na') }}
                                </td>

                                <td class="text-danger">
                                    {{ $product->stock_quantity }}
                                </td>

                                <td>
                                    {{ $product->low_stock_threshold }}
                                </td>

                                <td class="text-danger">
                                    {{ max(0, $product->low_stock_threshold - $product->stock_quantity) }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6">
                                    {{ __('report/low-stock.no_low_stock_products') }}
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
