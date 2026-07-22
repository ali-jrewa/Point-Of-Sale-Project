@extends('layouts.app')

@section('title', __('report/user.user_report'))

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
                    <h2>{{ __('report/user.user_report') }}</h2>
                    <p>{{ __('report/user.generated_on') }}: {{ $data['date'] }}</p>
                </div>

                <p class="range">
                    {{ __('report/user.period') }}: {{ $data['from'] }} {{ __('report/user.to') }} {{ $data['to'] }}
                </p>

                <div class="section-title">{{ __('report/user.user_information') }}</div>

                <table>
                    <tr>
                        <th>{{ __('report/user.name') }}</th>
                        <td>{{ $user->name }}</td>
                        <th>{{ __('report/user.role') }}</th>
                        <td>{{ $user->role?->display_name ?? __('report/user.na') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('report/user.email') }}</th>
                        <td>{{ $user->email }}</td>
                        <th>{{ __('report/user.status') }}</th>
                        <td>{{ ucfirst($user->status->value ?? '') }}</td>
                    </tr>
                </table>

                <div class="section-title">{{ __('report/user.summary') }}</div>

                @php
                    $totalSales = $sales->count();
                    $totalSalesAmount = $sales->sum('total');
                    $totalPurchases = $purchases->count();
                    $totalPurchasesAmount = $purchases->sum('total');
                    $totalRefunds = $refunds->count();
                    $totalRefundsAmount = $refunds->sum('amount');
                @endphp

                <table>
                    <tr>
                        <th>{{ __('report/user.total_sales') }}</th>
                        <td>{{ $totalSales }}</td>
                        <th>{{ __('report/user.total_sales_amount') }}</th>
                        <td>${{ number_format($totalSalesAmount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('report/user.total_purchases') }}</th>
                        <td>{{ $totalPurchases }}</td>
                        <th>{{ __('report/user.total_purchases_amount') }}</th>
                        <td>${{ number_format($totalPurchasesAmount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('report/user.total_refunds') }}</th>
                        <td>{{ $totalRefunds }}</td>
                        <th>{{ __('report/user.total_refunds_amount') }}</th>
                        <td>${{ number_format($totalRefundsAmount, 2) }}</td>
                    </tr>
                </table>

                <div class="section-title">{{ __('report/user.sales_detail') }}</div>
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('report/user.sale_code') }}</th>
                            <th>{{ __('report/user.customer') }}</th>
                            <th>{{ __('report/user.invoice') }}</th>
                            <th>{{ __('report/user.date') }}</th>
                            <th>{{ __('report/user.total') }}</th>
                            <th>{{ __('report/user.payment_status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td>{{ $sale->sale_code }}</td>
                                <td>{{ $sale->customer?->first_name . ' ' . $sale->customer?->last_name ?? __('report/user.na') }}</td>
                                <td>{{ $sale->invoice_number ?? __('report/user.na') }}</td>
                                <td>{{ \Carbon\Carbon::parse($sale->sold_at)->format('Y-m-d') }}</td>
                                <td>${{ number_format($sale->total, 2) }}</td>
                                <td>{{ ucfirst($sale->payment_status->value ?? '') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">{{ __('report/user.no_sales_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="section-title">{{ __('report/user.purchase_detail') }}</div>
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('report/user.purchase_code') }}</th>
                            <th>{{ __('report/user.supplier') }}</th>
                            <th>{{ __('report/user.invoice') }}</th>
                            <th>{{ __('report/user.date') }}</th>
                            <th>{{ __('report/user.total') }}</th>
                            <th>{{ __('report/user.payment_status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                            <tr>
                                <td>{{ $purchase->purchase_code }}</td>
                                <td>{{ $purchase->supplier?->company_name ?? __('report/user.na') }}</td>
                                <td>{{ $purchase->invoice_number ?? __('report/user.na') }}</td>
                                <td>{{ \Carbon\Carbon::parse($purchase->purchased_at)->format('Y-m-d') }}</td>
                                <td>${{ number_format($purchase->total, 2) }}</td>
                                <td>{{ ucfirst($purchase->payment_status->value ?? '') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">{{ __('report/user.no_purchases_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="section-title">{{ __('report/user.refund_detail') }}</div>
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('report/user.refund_code') }}</th>
                            <th>{{ __('report/user.sale_code') }}</th>
                            <th>{{ __('report/user.customer') }}</th>
                            <th>{{ __('report/user.date') }}</th>
                            <th>{{ __('report/user.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($refunds as $refund)
                            <tr>
                                <td>{{ $refund->refund_code }}</td>
                                <td>{{ $refund->sale?->sale_code ?? __('report/user.na') }}</td>
                                <td>{{ $refund->sale && $refund->sale->customer ? $refund->sale->customer->first_name . ' ' . $refund->sale->customer->last_name : __('report/user.na') }}</td>
                                <td>{{ \Carbon\Carbon::parse($refund->refunded_at)->format('Y-m-d') }}</td>
                                <td>${{ number_format($refund->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">{{ __('report/user.no_refunds_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
