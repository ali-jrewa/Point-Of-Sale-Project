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

                    <h2>{{ __('report/refunds.title') }}</h2>

                    <p>
                        {{ __('report/refunds.generated_on') }}:
                        {{ $data['date'] }}
                    </p>

                </div>


                <p class="range">
                    {{ __('report/refunds.period') }}:
                    {{ $data['from'] }}
                    {{ __('report/refunds.to') }}
                    {{ $data['to'] }}
                </p>


                @php
                    $grandTotal = $refunds->sum('amount');
                @endphp


                <div class="section-title">
                    {{ __('report/refunds.summary') }}
                </div>


                <table>

                    <tr>

                        <th width="25%">
                            {{ __('report/refunds.total_refunds') }}
                        </th>

                        <td>
                            {{ $refunds->count() }}
                        </td>


                        <th width="25%">
                            {{ __('report/refunds.total_amount') }}
                        </th>

                        <td class="text-danger">
                            ${{ number_format($grandTotal, 2) }}
                        </td>

                    </tr>

                </table>



                <div class="section-title">
                    {{ __('report/refunds.refund_detail') }}
                </div>


                <table>

                    <thead>

                        <tr>

                            <th>{{ __('report/refunds.refund_code') }}</th>
                            <th>{{ __('report/refunds.sale_code') }}</th>
                            <th>{{ __('report/refunds.customer') }}</th>
                            <th>{{ __('report/refunds.items') }}</th>
                            <th>{{ __('report/refunds.method') }}</th>
                            <th>{{ __('report/refunds.amount') }}</th>
                            <th>{{ __('report/refunds.reason') }}</th>
                            <th>{{ __('report/refunds.date') }}</th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($refunds as $refund)

                        <tr>

                            <td>{{ $refund->refund_code }}</td>


                            <td>
                                {{ $refund->sale->sale_code ?? __('report/refunds.na') }}
                            </td>


                            <td>

                                @if($refund->sale && $refund->sale->customer)

                                    {{ $refund->sale->customer->first_name }}
                                    {{ $refund->sale->customer->last_name }}

                                @else

                                    {{ __('report/refunds.walk_in') }}

                                @endif

                            </td>


                            <td>

                                @foreach($refund->items as $item)

                                    {{ $item->product->name ?? __('report/refunds.na') }}
                                    (x{{ $item->quantity }})

                                    @if(!$loop->last)
                                        <br>
                                    @endif

                                @endforeach

                            </td>


                            <td>
                                {{ ucfirst(str_replace('_',' ',$refund->method)) }}
                            </td>


                            <td class="text-danger">
                                ${{ number_format($refund->amount,2) }}
                            </td>


                            <td>
                                {{ $refund->reason ?? __('report/refunds.na') }}
                            </td>


                            <td>
                                {{ \Carbon\Carbon::parse($refund->refunded_at)->format('Y-m-d H:i') }}
                            </td>


                        </tr>


                    @empty

                        <tr>

                            <td colspan="8">
                                {{ __('report/refunds.no_refunds_found') }}
                            </td>

                        </tr>


                    @endforelse


                    <tr class="totals-row">

                        <td colspan="5">
                            {{ __('report/refunds.total') }}
                        </td>

                        <td>
                            ${{ number_format($grandTotal,2) }}
                        </td>

                        <td colspan="2"></td>

                    </tr>


                    </tbody>

                </table>


            </div>
        </div>
    </div>
</main>
@endsection
