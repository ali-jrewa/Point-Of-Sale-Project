@extends('layouts.app')

@section('content')

<main class="app-main">

    {{-- Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3>{{ __('payment.page_title') }}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">{{ __('common.home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('payment.payment_list') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    {{-- Search Area --}}
                    <div class="mb-3 row">
                        <div class="col-md-9">
                            <h4 class="mt-3 card-title btn">{{ __('payment.search_payment') }}</h4>
                            <input type="text" id="search" class="form-control"
                                placeholder="{{ __('payment.search_placeholder') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="mt-4 form-label">{{ __('payment.payment_date') }}</label>
                            <input type="date" id="payment_date_search" class="form-control">
                        </div>
                    </div>

                    {{-- Card --}}
                    <div class="mb-4 card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('payment.payment_list') }}</h3>
                        </div>

                        <div class="card-body">
                            <table id="payment-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('payment.payment_code') }}</th>
                                        <th>{{ __('payment.sale_code') }}</th>
                                        <th>{{ __('payment.customer') }}</th>
                                        <th>{{ __('payment.method') }}</th>
                                        <th>{{ __('payment.amount') }}</th>
                                        <th>{{ __('payment.reference') }}</th>
                                        <th>{{ __('payment.received_by') }}</th>
                                        <th>{{ __('payment.paid_at') }}</th>
                                        <th>{{ __('common.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $grandTotal = 0; @endphp

                                    @forelse($payments as $payment)
                                        @php $grandTotal += $payment->amount; @endphp
                                        <tr>
                                            <td>{{ $payment->payment_code }}</td>
                                            <td>{{ $payment->sale->sale_code ?? '-' }}</td>
                                            <td>
                                                @if($payment->sale && $payment->sale->customer)
                                                    {{ $payment->sale->customer->first_name.' '.$payment->sale->customer->last_name }}
                                                @else
                                                    {{ __('payment.walk_in_customer') }}
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ __('payment_method.' . $payment->method->value) }}
                                                </span>
                                            </td>
                                            <td class="text-success">${{ number_format($payment->amount,2) }}</td>
                                            <td>{{ $payment->reference ?? '-' }}</td>
                                            <td>{{ $payment->user->name ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d H:i') }}</td>
                                            <td>
                                                @if(auth()->user()->hasRole('admin'))
                                                    <a href="{{ route('admin.payment.show', $payment->id) }}"
                                                        class="btn btn-info btn-sm">
                                                        {{ __('common.view') }}
                                                    </a>
                                                @elseif (auth()->user()->hasRole('manager'))
                                                    <a href="{{ route('manager.payment.show', $payment->id) }}"
                                                        class="btn btn-info btn-sm">
                                                        {{ __('common.view') }}
                                                    </a>
                                                
                                                @elseif (auth()->user()->hasRole('cashier'))
                                                    <a href="{{ route('cashier.payment.show', $payment->id) }}"
                                                            class="btn btn-info btn-sm">
                                                            {{ __('common.view') }}
                                                        </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">{{ __('payment.no_payments_found') }}</td>
                                        </tr>
                                    @endforelse

                                    @if($grandTotal > 0)
                                        <tr>
                                            <th colspan="4" class="text-center">{{ __('payment.grand_total') }}</th>
                                            <th>${{ number_format($grandTotal,2) }}</th>
                                            <th colspan="4"></th>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <div id="pagination-wrapper" style="padding:10px;float:right;">
                                {!! $payments->links() !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs/dayjs.min.js"></script>


<script>
$(document).ready(function () {

    const paymentMethodTranslations = {
    cash: "{{ __('payment_method.cash') }}",
    credit_card: "{{ __('payment_method.credit_card') }}",
    debit_card: "{{ __('payment_method.debit_card') }}",
    bank_transfer: "{{ __('payment_method.bank_transfer') }}",
    cheque: "{{ __('payment_method.cheque') }}",
    mobile_payment: "{{ __('payment_method.mobile_payment') }}",
    other: "{{ __('payment_method.other') }}"
};

    let timer;

    function fetchPayments(search = '', paymentDate = '') {

        $.ajax({
            url: "{{ route('admin.payment.data') }}",
            method: "GET",
            data: { search: search, payment_date: paymentDate },
            success: function (response) {

                let tableBody = '';
                let grandTotal = 0;

                if (!response.data || response.data.length === 0) {

                    tableBody = `<tr><td colspan="9" class="text-center">{{ __('payment.no_payments_found') }}</td></tr>`;

                } else {

                    $.each(response.data, function (index, payment) {

                        grandTotal += parseFloat(payment.amount);

                        let customer = payment.sale && payment.sale.customer
                            ? payment.sale.customer.first_name + ' ' + payment.sale.customer.last_name
                            : {{ __('payment.walk_in_customer') }};

                        tableBody += `
                        <tr>
                            <td>${payment.payment_code}</td>
                            <td>${payment.sale ? payment.sale.sale_code : '-'}</td>
                            <td>${customer}</td>
                            <td><span class="badge bg-secondary">${paymentMethodTranslations[payment.method] ?? payment.method}</span></td>
                            <td class="text-success">$${parseFloat(payment.amount).toFixed(2)}</td>
                            <td>${payment.reference ?? '-'}</td>
                            <td>${payment.user ? payment.user.name : '-'}</td>
                            <td>${dayjs(payment.paid_at).format('YYYY-MM-DD HH:mm')}</td>
                            <td>
                                <a href="/admin/payment/${payment.id}" class="btn btn-info btn-sm">{{ __('payment.view') }}</a>
                            </td>
                        </tr>
                        `;
                    });

                    tableBody += `
                    <tr>
                        <th colspan="4" class="text-center">{{ __('payment.grand_total') }}</th>
                        <th>$${grandTotal.toFixed(2)}</th>
                        <th colspan="4"></th>
                    </tr>
                    `;
                }

                $("#payment-table tbody").html(tableBody);
            },
            error: function (xhr) {
                console.error("{{ __('payment.error_fetching') }}", xhr.responseText);
            }
        });
    }

    $("#search").on("keyup", function () {

        clearTimeout(timer);
        let value = $(this).val();

        if (value.trim() === '') {
            $("#pagination-wrapper").show();
            fetchPayments('', $("#payment_date_search").val());
        } else {
            $("#pagination-wrapper").hide();
            timer = setTimeout(function () {
                fetchPayments(value, $("#payment_date_search").val());
            }, 300);
        }
    });

    $("#payment_date_search").on("change", function () {
        fetchPayments($("#search").val(), $(this).val());
    });

});
</script>

@endsection
