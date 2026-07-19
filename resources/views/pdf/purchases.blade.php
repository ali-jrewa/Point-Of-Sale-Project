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

                    <h2>
                        {{ __('report/purchases.title') }}
                    </h2>


                    <p>
                        {{ __('report/purchases.generated_on') }}:
                        {{ $data['date'] }}
                    </p>

                </div>



                <p class="range">

                    {{ __('report/purchases.period') }}:
                    {{ $data['from'] }}

                    {{ __('report/purchases.to') }}

                    {{ $data['to'] }}

                </p>



                @php

                    $grandSubtotal = $purchases->sum('subtotal');
                    $grandDiscount = $purchases->sum('discount');
                    $grandTax = $purchases->sum('tax');
                    $grandTotal = $purchases->sum('total');

                @endphp



                <div class="section-title">

                    {{ __('report/purchases.summary') }}

                </div>



                <table>

                    <tr>

                        <th width="25%">
                            {{ __('report/purchases.total_purchases') }}
                        </th>


                        <td>
                            {{ $purchases->count() }}
                        </td>


                        <th width="25%">
                            {{ __('report/purchases.grand_total') }}
                        </th>


                        <td>
                            ${{ number_format($grandTotal, 2) }}
                        </td>

                    </tr>

                </table>




                <div class="section-title">

                    {{ __('report/purchases.purchase_detail') }}

                </div>




                <table>

                    <thead>

                        <tr>

                            <th>{{ __('report/purchases.purchase_code') }}</th>
                            <th>{{ __('report/purchases.supplier') }}</th>
                            <th>{{ __('report/purchases.invoice') }}</th>
                            <th>{{ __('report/purchases.date') }}</th>
                            <th>{{ __('report/purchases.subtotal') }}</th>
                            <th>{{ __('report/purchases.discount') }}</th>
                            <th>{{ __('report/purchases.tax') }}</th>
                            <th>{{ __('report/purchases.total') }}</th>
                            <th>{{ __('report/purchases.status') }}</th>

                        </tr>

                    </thead>



                    <tbody>


                    @forelse($purchases as $purchase)

                        <tr>


                            <td>
                                {{ $purchase->purchase_code }}
                            </td>


                            <td>
                                {{ $purchase->supplier 
                                    ? $purchase->supplier->first_name.' '.$purchase->supplier->last_name 
                                    : __('report/purchases.na') 
                                }}
                            </td>


                            <td>
                                {{ $purchase->invoice_number ?? __('report/purchases.na') }}
                            </td>


                            <td>
                                {{ \Carbon\Carbon::parse($purchase->purchased_at)->format('Y-m-d') }}
                            </td>


                            <td>
                                ${{ number_format($purchase->subtotal, 2) }}
                            </td>


                            <td>
                                ${{ number_format($purchase->discount, 2) }}
                            </td>


                            <td>
                                ${{ number_format($purchase->tax, 2) }}
                            </td>


                            <td>
                                ${{ number_format($purchase->total, 2) }}
                            </td>


                            <td>
                                {{ ucfirst($purchase->purchase_status->value) }}
                            </td>


                        </tr>


                    @empty


                        <tr>

                            <td colspan="9">

                                {{ __('report/purchases.no_purchases_found') }}

                            </td>

                        </tr>


                    @endforelse



                    <tr class="totals-row">


                        <td colspan="4">

                            {{ __('report/purchases.totals') }}

                        </td>


                        <td>
                            ${{ number_format($grandSubtotal, 2) }}
                        </td>


                        <td>
                            ${{ number_format($grandDiscount, 2) }}
                        </td>


                        <td>
                            ${{ number_format($grandTax, 2) }}
                        </td>


                        <td>
                            ${{ number_format($grandTotal, 2) }}
                        </td>


                        <td></td>


                    </tr>


                    </tbody>

                </table>


            </div>
        </div>
    </div>
</main>
@endsection
