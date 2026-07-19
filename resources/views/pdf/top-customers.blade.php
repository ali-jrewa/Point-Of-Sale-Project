@extends('layouts.app')

@section('style')
    <title>{{ $data['title'] }}</title>
    @include('pdf._style')
@endsection

    @section('content')


     @include('pdf._toolbar')

    <div class="header">
        <h2>{{ $data['title'] }}</h2>
        <p>{{ __('report/top_customer.generated_on') }}: {{ $data['date'] }}</p>
    </div>

    <p class="range">{{ __('report/top_customer.period') }}: {{ $data['from'] }} {{ __('report/top_customer.to') }} {{ $data['to'] }}</p>

    <div class="section-title"> {{ __('report/top_customer.top_20_customers') }}</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('report/top_customer.customer') }}</th>
                <th>{{ __('report/top_customer.phone') }}</th>
                <th>{{ __('report/top_customer.total_orders') }}</th>
                <th>{{ __('report/top_customer.total_spend') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $index => $customer)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->sales_count }}</td>
                    <td class="text-success">${{ number_format($customer->sales_sum_total, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="5">{{ __('report/top_customer.no_customer_sales') }}</td></tr>
            @endforelse
        </tbody>
    </table>

    @endsection
