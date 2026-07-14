@extends('layouts.app')

@section('content')

<main class="app-main">

    {{-- Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Payments</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Payment List</li>
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
                            <h4 class="mt-3 card-title btn">Search Payment</h4>
                            <input type="text" id="search" class="form-control"
                                placeholder="Search by Payment Code, Sale Code, Customer, Method or Reference">
                        </div>
                        <div class="col-md-3">
                            <label class="mt-4 form-label">Payment Date</label>
                            <input type="date" id="payment_date_search" class="form-control">
                        </div>
                    </div>

                    {{-- Card --}}
                    <div class="mb-4 card">
                        <div class="card-header">
                            <h3 class="card-title">Payment List</h3>
                        </div>

                        <div class="card-body">
                            <table id="payment-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Payment Code</th>
                                        <th>Sale Code</th>
                                        <th>Customer</th>
                                        <th>Method</th>
                                        <th>Amount</th>
                                        <th>Reference</th>
                                        <th>Received By</th>
                                        <th>Paid At</th>
                                        <th>Actions</th>
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
                                                    Walk In Customer
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ ucfirst(str_replace('_',' ',$payment->method->value)) }}
                                                </span>
                                            </td>
                                            <td class="text-success">${{ number_format($payment->amount,2) }}</td>
                                            <td>{{ $payment->reference ?? '-' }}</td>
                                            <td>{{ $payment->user->name ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <a href="{{ route('admin.payment.show', $payment->id) }}"
                                                    class="btn btn-info btn-sm">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No payments found.</td>
                                        </tr>
                                    @endforelse

                                    @if($grandTotal > 0)
                                        <tr>
                                            <th colspan="4" class="text-center">Grand Total</th>
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

                    tableBody = `<tr><td colspan="9" class="text-center">No payments found.</td></tr>`;

                } else {

                    $.each(response.data, function (index, payment) {

                        grandTotal += parseFloat(payment.amount);

                        let customer = payment.sale && payment.sale.customer
                            ? payment.sale.customer.first_name + ' ' + payment.sale.customer.last_name
                            : 'Walk In Customer';

                        tableBody += `
                        <tr>
                            <td>${payment.payment_code}</td>
                            <td>${payment.sale ? payment.sale.sale_code : '-'}</td>
                            <td>${customer}</td>
                            <td><span class="badge bg-secondary">${payment.method.replaceAll('_',' ')}</span></td>
                            <td class="text-success">$${parseFloat(payment.amount).toFixed(2)}</td>
                            <td>${payment.reference ?? '-'}</td>
                            <td>${payment.user ? payment.user.name : '-'}</td>
                            <td>${dayjs(payment.paid_at).format('YYYY-MM-DD HH:mm')}</td>
                            <td>
                                <a href="/admin/payment/${payment.id}" class="btn btn-info btn-sm">View</a>
                            </td>
                        </tr>
                        `;
                    });

                    tableBody += `
                    <tr>
                        <th colspan="4" class="text-center">Grand Total</th>
                        <th>$${grandTotal.toFixed(2)}</th>
                        <th colspan="4"></th>
                    </tr>
                    `;
                }

                $("#payment-table tbody").html(tableBody);
            },
            error: function (xhr) {
                console.log(xhr.responseText);
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
