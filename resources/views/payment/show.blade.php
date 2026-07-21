@extends('layouts.app')

@section('content')

<main class="app-main">

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">{{ __('paymentShow.page_title') }}</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">{{ __('common.home') }}</a></li>
                     @if(auth()->user()->hasRole('admin'))
                    <li class="breadcrumb-item"><a href="{{ route('admin.payment.index') }}"> {{ __('paymentShow.payments') }}</a></li>
                    @endif
                     @if(auth()->user()->hasRole('manager'))
                        <li class="breadcrumb-item"><a href="{{ route('manager.payment.index') }}"> {{ __('paymentShow.payments') }}</a></li>
                    @endif
                      @if(auth()->user()->hasRole('cashier'))
                        <li class="breadcrumb-item"><a href="{{ route('cashier.payment.index') }}"> {{ __('paymentShow.payments') }}</a></li>

                      @endif
                      
                    <li class="breadcrumb-item active"> {{ __('common.view') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
<div class="container-fluid">

    {{-- Payment Information --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('paymentShow.payment_information') }}</h3>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="mb-3 col-md-4">
                    <strong>{{ __('paymentShow.payment_code') }}</strong>
                    <p>{{ $payment->payment_code }}</p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>{{ __('paymentShow.sale_code') }}</strong>
                    <p>
                        @if($payment->sale)
                            <a href="{{ route('admin.sale.show', $payment->sale->id) }}">
                                {{ $payment->sale->sale_code }}
                            </a>
                        @else
                            -
                        @endif
                    </p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>{{ __('paymentShow.customer') }}</strong>
                    <p>
                        @if($payment->sale && $payment->sale->customer)
                            {{ $payment->sale->customer->first_name }} {{ $payment->sale->customer->last_name }}
                        @else
                            {{ __('paymentShow.walk_in_customer') }}
                        @endif
                    </p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>{{ __('paymentShow.method') }}</strong>
                    <p>
                        <span class="badge bg-secondary">
                           {{ __('payment_method.' . $payment->method->value) }}
                        </span>
                    </p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>{{ __('paymentShow.amount') }}</strong>
                    <p class="text-success"><strong>${{ number_format($payment->amount,2) }}</strong></p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>{{ __('paymentShow.reference') }}</strong>
                    <p>{{ $payment->reference ?? '-' }}</p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>{{ __('paymentShow.received_by') }}</strong>
                    <p>{{ $payment->user->name ?? '-' }}</p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>{{ __('paymentShow.paid_at') }}</strong>
                    <p>{{ \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d H:i') }}</p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>{{ __('paymentShow.recorded_at') }}</strong>
                    <p>{{ $payment->created_at->format('Y-m-d H:i') }}</p>
                </div>

                <div class="mb-3 col-md-8">
                    <strong>{{ __('paymentShow.notes') }}</strong>
                    <p>{{ $payment->notes ?? '-' }}</p>
                </div>

            </div>
        </div>
    </div>

    {{-- Related Sale Summary (optional context) --}}
    @if($payment->sale)
    <div class="mt-3 card">
        <div class="card-header">
            <h3 class="card-title">
                {{ __('paymentShow.sale_summary') }}
            </h3>
        </div>

        <div class="card-body">
            <table class="table">
                <tr>
                    <th width="30%">{{ __('paymentShow.sale_total') }}</th>
                    <td>${{ number_format($payment->sale->total,2) }}</td>
                </tr>
                <tr>
                    <th>{{ __('paymentShow.total_paid') }}</th>
                    <td class="text-success">${{ number_format($payment->sale->paid_amount,2) }}</td>
                </tr>
                <tr>
                    <th>{{ __('paymentShow.due_amount') }}</th>
                    <td class="{{ $payment->sale->due_amount > 0 ? 'text-danger' : 'text-success' }}">
                        ${{ number_format($payment->sale->due_amount,2) }}
                    </td>
                </tr>
                <tr>
                    <th>{{ __('paymentShow.payment_status') }}</th>
                    <td>{{ __('common.' . $payment->sale->payment_status->value) }}</td>
                </tr>
            </table>
        </div>
    </div>
    @endif

    {{-- Actions --}}
    <div class="mt-3 mb-4">
        @if(auth()->user()->hasRole('admin'))
        <a href="{{ route('admin.payment.index') }}" class="btn btn-secondary">
            {{ __('paymentShow.back') }}
        </a>

        @elseif (auth()->user()->hasRole('manager'))

             <a href="{{ route('manager.payment.index') }}" class="btn btn-secondary">
                {{ __('paymentShow.back') }}
            </a>

        @elseif (auth()->user()->hasRole('cashier'))
            
             <a href="{{ route('cashier.payment.index') }}" class="btn btn-secondary">
                {{ __('paymentShow.back') }}
            </a>
        @endif

        @if($payment->sale)
             @if (auth()->user()->hasRole('admin'))
                <a href="{{ route('admin.sale.show', $payment->sale->id) }}" class="btn btn-info">
                    {{ __('paymentShow.view_sale') }}
                </a>
            @elseif (auth()->user()->hasRole('manager'))
                <a href="{{ route('manager.sale.show', $payment->sale->id) }}" class="btn btn-info">
                    {{ __('paymentShow.view_sale') }}
                </a>
            @elseif (auth()->user()->hasRole('cashier'))
                <a href="{{ route('cashier.sale.show', $payment->sale->id) }}" class="btn btn-info">
                    {{ __('paymentShow.view_sale') }}
                </a>
                @endif
        @endif
    </div>

</div>
</div>

</main>

@endsection
