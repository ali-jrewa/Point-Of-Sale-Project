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

                    <h2>
                        {{ __('report/sales.title') }}
                    </h2>

                    <p>
                        {{ __('report/sales.generated_on') }}:
                        {{ $data['date'] }}
                    </p>

                </div>



                <p class="range">

                    {{ __('report/sales.period') }}:
                    {{ $data['from'] }}

                    {{ __('report/sales.to') }}

                    {{ $data['to'] }}

                </p>



                @php

                    $grandSubtotal = $sales->sum('subtotal');
                    $grandDiscount = $sales->sum('discount');
                    $grandTax = $sales->sum('tax');
                    $grandTotal = $sales->sum('total');
                    $grandPaid = $sales->sum('paid_amount');
                    $grandDue = $sales->sum('due_amount');

                @endphp




                <div class="section-title">

                    {{ __('report/sales.summary') }}

                </div>



                <table>

                    <tr>

                        <th width="25%">
                            {{ __('report/sales.total_sales') }}
                        </th>

                        <td>
                            {{ $sales->count() }}
                        </td>


                        <th width="25%">
                            {{ __('report/sales.grand_total') }}
                        </th>


                        <td>
                            ${{ number_format($grandTotal,2) }}
                        </td>

                    </tr>



                    <tr>

                        <th>
                            {{ __('report/sales.total_paid') }}
                        </th>


                        <td class="text-success">
                            ${{ number_format($grandPaid,2) }}
                        </td>



                        <th>
                            {{ __('report/sales.total_due') }}
                        </th>


                        <td class="{{ $grandDue > 0 ? 'text-danger' : 'text-success' }}">

                            ${{ number_format($grandDue,2) }}

                        </td>


                    </tr>


                </table>





                <div class="section-title">

                    {{ __('report/sales.sales_detail') }}

                </div>




                <table>

                    <thead>

                        <tr>

                            <th>{{ __('report/sales.sale_code') }}</th>
                            <th>{{ __('report/sales.customer') }}</th>
                            <th>{{ __('report/sales.invoice') }}</th>
                            <th>{{ __('report/sales.date') }}</th>
                            <th>{{ __('report/sales.subtotal') }}</th>
                            <th>{{ __('report/sales.discount') }}</th>
                            <th>{{ __('report/sales.tax') }}</th>
                            <th>{{ __('report/sales.total') }}</th>
                            <th>{{ __('report/sales.paid') }}</th>
                            <th>{{ __('report/sales.due') }}</th>
                            <th>{{ __('report/sales.status') }}</th>

                        </tr>

                    </thead>



                    <tbody>


                    @forelse($sales as $sale)

                        <tr>

                            <td>
                                {{ $sale->sale_code }}
                            </td>


                            <td>

                                {{ $sale->customer
                                    ? $sale->customer->first_name.' '.$sale->customer->last_name
                                    : __('report/sales.walk_in')
                                }}

                            </td>


                            <td>
                                {{ $sale->invoice_number ?? __('report/sales.na') }}
                            </td>


                            <td>
                                {{ \Carbon\Carbon::parse($sale->sold_at)->format('Y-m-d') }}
                            </td>


                            <td>
                                ${{ number_format($sale->subtotal,2) }}
                            </td>


                            <td>
                                ${{ number_format($sale->discount,2) }}
                            </td>


                            <td>
                                ${{ number_format($sale->tax,2) }}
                            </td>


                            <td>
                                ${{ number_format($sale->total,2) }}
                            </td>


                            <td class="text-success">
                                ${{ number_format($sale->paid_amount,2) }}
                            </td>


                            <td class="{{ $sale->due_amount > 0 ? 'text-danger' : '' }}">

                                ${{ number_format($sale->due_amount,2) }}

                            </td>


                            <td>
                                {{ ucfirst($sale->sale_status->value) }}
                            </td>


                        </tr>


                    @empty

                        <tr>

                            <td colspan="11">

                                {{ __('report/sales.no_sales_found') }}

                            </td>

                        </tr>


                    @endforelse




                    <tr class="totals-row">


                        <td colspan="4">

                            {{ __('report/sales.totals') }}

                        </td>


                        <td>
                            ${{ number_format($grandSubtotal,2) }}
                        </td>


                        <td>
                            ${{ number_format($grandDiscount,2) }}
                        </td>


                        <td>
                            ${{ number_format($grandTax,2) }}
                        </td>


                        <td>
                            ${{ number_format($grandTotal,2) }}
                        </td>


                        <td>
                            ${{ number_format($grandPaid,2) }}
                        </td>


                        <td>
                            ${{ number_format($grandDue,2) }}
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
