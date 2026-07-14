@extends('layouts.app')

@section('content')

<main class="app-main">

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Payment Details</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.payment.index') }}">Payments</a></li>
                    <li class="breadcrumb-item active">View</li>
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
            <h3 class="card-title">Payment Information</h3>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="mb-3 col-md-4">
                    <strong>Payment Code</strong>
                    <p>{{ $payment->payment_code }}</p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>Sale Code</strong>
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
                    <strong>Customer</strong>
                    <p>
                        @if($payment->sale && $payment->sale->customer)
                            {{ $payment->sale->customer->first_name }} {{ $payment->sale->customer->last_name }}
                        @else
                            Walk In Customer
                        @endif
                    </p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>Method</strong>
                    <p>
                        <span class="badge bg-secondary">
                            {{ ucfirst(str_replace('_',' ',$payment->method->value)) }}
                        </span>
                    </p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>Amount</strong>
                    <p class="text-success"><strong>${{ number_format($payment->amount,2) }}</strong></p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>Reference</strong>
                    <p>{{ $payment->reference ?? '-' }}</p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>Received By</strong>
                    <p>{{ $payment->user->name ?? '-' }}</p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>Paid At</strong>
                    <p>{{ \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d H:i') }}</p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>Recorded At</strong>
                    <p>{{ $payment->created_at->format('Y-m-d H:i') }}</p>
                </div>

                <div class="mb-3 col-md-8">
                    <strong>Notes</strong>
                    <p>{{ $payment->notes ?? '-' }}</p>
                </div>

            </div>
        </div>
    </div>

    {{-- Related Sale Summary (optional context) --}}
    @if($payment->sale)
    <div class="mt-3 card">
        <div class="card-header">
            <h3 class="card-title">Sale Summary</h3>
        </div>

        <div class="card-body">
            <table class="table">
                <tr>
                    <th width="30%">Sale Total</th>
                    <td>${{ number_format($payment->sale->total,2) }}</td>
                </tr>
                <tr>
                    <th>Total Paid</th>
                    <td class="text-success">${{ number_format($payment->sale->paid_amount,2) }}</td>
                </tr>
                <tr>
                    <th>Due Amount</th>
                    <td class="{{ $payment->sale->due_amount > 0 ? 'text-danger' : 'text-success' }}">
                        ${{ number_format($payment->sale->due_amount,2) }}
                    </td>
                </tr>
                <tr>
                    <th>Payment Status</th>
                    <td>{{ ucfirst($payment->sale->payment_status->value) }}</td>
                </tr>
            </table>
        </div>
    </div>
    @endif

    {{-- Actions --}}
    <div class="mt-3 mb-4">
        <a href="{{ route('admin.payment.index') }}" class="btn btn-secondary">
            Back
        </a>

        @if($payment->sale)
            <a href="{{ route('admin.sale.show', $payment->sale->id) }}" class="btn btn-info">
                View Sale
            </a>
        @endif
    </div>

</div>
</div>

</main>

@endsection
