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
                        {{ __('report/payments.title') }}
                    </h2>

                    <p>
                        {{ __('report/payments.generated_on') }}:
                        {{ $data['date'] }}
                    </p>

                </div>


                <p class="range">

                    {{ __('report/payments.period') }}:
                    {{ $data['from'] }}

                    {{ __('report/payments.to') }}

                    {{ $data['to'] }}

                </p>


                @php 
                    $grandTotal = $payments->sum('amount'); 
                @endphp



                <div class="section-title">
                    {{ __('report/payments.by_payment_method') }}
                </div>


                <table>

                    <thead>
                        <tr>

                            <th>
                                {{ __('report/payments.method') }}
                            </th>

                            <th>
                                {{ __('report/payments.total_collected') }}
                            </th>

                        </tr>
                    </thead>


                    <tbody>

                    @foreach($byMethod as $method => $amount)

                        <tr>

                            <td>
                                {{ ucfirst(str_replace('_',' ',$method)) }}
                            </td>

                            <td class="text-success">
                                ${{ number_format($amount, 2) }}
                            </td>

                        </tr>

                    @endforeach


                    <tr class="totals-row">

                        <td>
                            {{ __('report/payments.total') }}
                        </td>

                        <td>
                            ${{ number_format($grandTotal, 2) }}
                        </td>

                    </tr>


                    </tbody>

                </table>



                <div class="section-title">

                    {{ __('report/payments.payment_detail') }}

                </div>



                <table>

                    <thead>

                        <tr>

                            <th>{{ __('report/payments.payment_code') }}</th>
                            <th>{{ __('report/payments.sale_code') }}</th>
                            <th>{{ __('report/payments.customer') }}</th>
                            <th>{{ __('report/payments.method') }}</th>
                            <th>{{ __('report/payments.amount') }}</th>
                            <th>{{ __('report/payments.reference') }}</th>
                            <th>{{ __('report/payments.received_by') }}</th>
                            <th>{{ __('report/payments.paid_at') }}</th>

                        </tr>

                    </thead>



                    <tbody>


                    @forelse($payments as $payment)

                        <tr>

                            <td>
                                {{ $payment->payment_code }}
                            </td>


                            <td>
                                {{ $payment->sale->sale_code ?? __('report/payments.na') }}
                            </td>


                            <td>

                                @if($payment->sale && $payment->sale->customer)

                                    {{ $payment->sale->customer->first_name }}
                                    {{ $payment->sale->customer->last_name }}

                                @else

                                    {{ __('report/payments.walk_in') }}

                                @endif

                            </td>


                            <td>
                                {{ ucfirst(str_replace('_',' ',$payment->method->value)) }}
                            </td>


                            <td class="text-success">
                                ${{ number_format($payment->amount, 2) }}
                            </td>


                            <td>
                                {{ $payment->reference ?? __('report/payments.na') }}
                            </td>


                            <td>
                                {{ $payment->user->name ?? __('report/payments.na') }}
                            </td>


                            <td>
                                {{ \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d H:i') }}
                            </td>


                        </tr>


                    @empty

                        <tr>

                            <td colspan="8">

                                {{ __('report/payments.no_payments_found') }}

                            </td>

                        </tr>


                    @endforelse



                    <tr class="totals-row">

                        <td colspan="4">

                            {{ __('report/payments.total') }}

                        </td>


                        <td>

                            ${{ number_format($grandTotal, 2) }}

                        </td>


                        <td colspan="3"></td>

                    </tr>


                    </tbody>

                </table>


            </div>
        </div>
    </div>
</main>
@endsection
