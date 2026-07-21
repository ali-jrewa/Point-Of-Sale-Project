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
                    <h2>{{ __('report/inputs-outputs.title') }}</h2>
                    <p>{{ __('report/inputs-outputs.generated_on') }}: {{ $data['date'] }}</p>
                </div>

                <p class="range">
                    {{ __('report/inputs-outputs.period') }}: {{ $data['from'] }} {{ __('report/inputs-outputs.to') }} {{ $data['to'] }}
                </p>

                <div class="section-title">{{ __('report/inputs-outputs.summary') }}</div>
                <table>
                    <tr>
                        <th>{{ __('report/inputs-outputs.total_purchases') }}</th>
                        <td>${{ number_format($summary['totalPurchases'], 2) }}</td>
                        <th>{{ __('report/inputs-outputs.total_sales') }}</th>
                        <td>${{ number_format($summary['totalSales'], 2) }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('report/inputs-outputs.total_payments') }}</th>
                        <td>${{ number_format($summary['totalPayments'], 2) }}</td>
                        <th>{{ __('report/inputs-outputs.total_expenses') }}</th>
                        <td>${{ number_format($summary['totalExpenses'], 2) }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('report/inputs-outputs.total_refunds') }}</th>
                        <td>${{ number_format($summary['totalRefunds'], 2) }}</td>
                        <th>{{ __('report/inputs-outputs.gross_profit') }}</th>
                        <td>${{ number_format($summary['grossProfit'], 2) }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('report/inputs-outputs.net_profit') }}</th>
                        <td colspan="3">${{ number_format($summary['netProfit'], 2) }}</td>
                    </tr>
                </table>

                <div class="section-title">{{ __('report/inputs-outputs.purchases') }}</div>
                @if($purchases->isEmpty())
                    <p>{{ __('report/inputs-outputs.no_purchases') }}</p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('report/inputs-outputs.purchase_code') }}</th>
                                <th>{{ __('report/inputs-outputs.supplier') }}</th>
                                <th>{{ __('report/inputs-outputs.date') }}</th>
                                <th>{{ __('report/inputs-outputs.amount') }}</th>
                                <th>{{ __('report/inputs-outputs.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchases as $purchase)
                                <tr>
                                    <td>{{ $purchase->purchase_code }}</td>
                                    <td>{{ $purchase->supplier->first_name ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($purchase->purchased_at)->format('Y-m-d') }}</td>
                                    <td>${{ number_format($purchase->total, 2) }}</td>
                                    <td>{{ ucfirst($purchase->purchase_status->value) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <div class="section-title">{{ __('report/inputs-outputs.sales') }}</div>
                @if($sales->isEmpty())
                    <p>{{ __('report/inputs-outputs.no_sales') }}</p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('report/inputs-outputs.sale_code') }}</th>
                                <th>{{ __('report/inputs-outputs.customer') }}</th>
                                <th>{{ __('report/inputs-outputs.date') }}</th>
                                <th>{{ __('report/inputs-outputs.amount') }}</th>
                                <th>{{ __('report/inputs-outputs.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                                <tr>
                                    <td>{{ $sale->sale_code }}</td>
                                    <td>{{ $sale->customer ? $sale->customer->first_name . ' ' . $sale->customer->last_name : __('report/inputs-outputs.customer') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($sale->sold_at)->format('Y-m-d') }}</td>
                                    <td>${{ number_format($sale->total, 2) }}</td>
                                    <td>{{ ucfirst($sale->sale_status->value) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <div class="section-title">{{ __('report/inputs-outputs.payments') }}</div>
                @if($payments->isEmpty())
                    <p>{{ __('report/inputs-outputs.no_payments') }}</p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('report/inputs-outputs.method') }}</th>
                                <th>{{ __('report/inputs-outputs.reference') }}</th>
                                <th>{{ __('report/inputs-outputs.amount') }}</th>
                                <th>{{ __('report/inputs-outputs.date') }}</th>
                                <th>{{ __('report/inputs-outputs.customer') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>{{ ucfirst(str_replace('_', ' ', $payment->method->value ?? $payment->method)) }}</td>
                                    <td>{{ $payment->reference ?? '-' }}</td>
                                    <td>${{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d') }}</td>
                                    <td>{{ $payment->sale && $payment->sale->customer ? $payment->sale->customer->first_name . ' ' . $payment->sale->customer->last_name : '-' }}</td>
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
