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
        <h2>{{ $data['title'] }}</h2>
        <p>{{ __('report/due.generated_on') }}: {{ $data['date'] }}</p>
    </div>

    <div class="section-title">{{ __('report/due.summary') }}</div>
    <table>
        <tr>
            <th width="25%">{{ __('report/due.outstanding_sales') }}</th>
            <td>{{ $sales->count() }}</td>
            <th width="25%">{{ __('report/due.total_due') }}</th>
            <td class="text-danger">${{ number_format($totalDue, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">{{ __('report/due.outstanding_detail') }}</div>
    <table>
        <thead>
            <tr>
                <th>{{ __('report/due.sale_code') }}</th>
                <th>{{ __('report/due.customer') }}</th>
                <th>{{ __('report/due.phone') }}</th>
                <th>{{ __('report/due.sale_date') }}</th>
                <th>{{ __('report/due.total') }}</th>
                <th>{{ __('report/due.paid') }}</th>
                <th>{{ __('report/due.due') }}</th>
                <th>{{ __('report/due.payment_status') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td>{{ $sale->sale_code }}</td>
                    <td>
                        @if($sale->customer)
                            {{ $sale->customer->first_name }} {{ $sale->customer->last_name }}
                        @else
                            {{ __('report/due.walk_in') }}
                        @endif
                    </td>
                    <td>{{ $sale->customer->phone ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->sold_at)->format('Y-m-d') }}</td>
                    <td>${{ number_format($sale->total, 2) }}</td>
                    <td class="text-success">${{ number_format($sale->paid_amount, 2) }}</td>
                    <td class="text-danger">${{ number_format($sale->due_amount, 2) }}</td>
                    <td>{{ ucfirst($sale->payment_status->value) }}</td>
                </tr>
            @empty
                <tr><td colspan="8">{{ __('report/due.no_outstanding_balances') }}</td></tr>
            @endforelse
            <tr class="totals-row">
                <td colspan="6">{{ __('report/due.total_due') }}</td>
                <td>${{ number_format($totalDue, 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

            </div>
        </div>
    </div>
</main>
@endsection

