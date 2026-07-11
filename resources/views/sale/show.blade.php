@extends('layouts.app')

@section('content')

<main class="app-main">

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Sale Details</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item">Sale</li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
<div class="container-fluid">

    {{-- Sale Information --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Sale Information</h3>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="mb-3 col-md-4">
                    <strong>Sale Code</strong>
                    <p>{{ $sale->sale_code }}</p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>Customer</strong>
                    <p>
                        @if($sale->customer)
                            {{ $sale->customer->first_name }} {{ $sale->customer->last_name }}
                        @else
                            Walk In Customer
                        @endif
                    </p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>Invoice Number</strong>
                    <p>{{ $sale->invoice_number ?? '-' }}</p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>Sale Date</strong>
                    <p>{{ \Carbon\Carbon::parse($sale->sold_at)->format('Y-m-d') }}</p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>Created By</strong>
                    <p>{{ $sale->user->name ?? '-' }}</p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>Created At</strong>
                    <p>{{ $sale->created_at->format('Y-m-d H:i') }}</p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>Sale Status</strong>
                    <p>
                        <span class="{{ $sale->sale_status->value == 'completed'
                            ? 'text-success'
                            : ($sale->sale_status->value == 'pending'
                                ? 'text-warning'
                                : 'text-danger') }}">
                            {{ ucfirst($sale->sale_status->value) }}
                        </span>
                    </p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>Payment Status</strong>
                    <p>
                        <span class="{{ $sale->payment_status->value == 'paid'
                            ? 'text-success'
                            : ($sale->payment_status->value == 'partial'
                                ? 'text-warning'
                                : 'text-danger') }}">
                            {{ ucfirst($sale->payment_status->value) }}
                        </span>
                    </p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>Updated At</strong>
                    <p>{{ $sale->updated_at?->format('Y-m-d H:i') ?? 'N/A' }}</p>
                </div>

            </div>
        </div>
    </div>

    {{-- Sold Products --}}
    <div class="mt-3 card">
        <div class="card-header">
            <h3 class="card-title">Sold Products</h3>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Discount</th>
                        <th>Tax</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->unit_price,2) }}</td>
                            <td>${{ number_format($item->discount,2) }}</td>
                            <td>${{ number_format($item->tax,2) }}</td>
                            <td>${{ number_format($item->subtotal,2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Sale Summary --}}
    <div class="mt-3 card">
        <div class="card-header">
            <h3 class="card-title">Sale Summary</h3>
        </div>

        <div class="card-body">
            <table class="table">
                <tr>
                    <th width="30%">Subtotal</th>
                    <td>${{ number_format($sale->subtotal,2) }}</td>
                </tr>
                <tr>
                    <th>Discount</th>
                    <td>${{ number_format($sale->discount,2) }}</td>
                </tr>
                <tr>
                    <th>Tax</th>
                    <td>${{ number_format($sale->tax,2) }}</td>
                </tr>
                <tr>
                    <th>Grand Total</th>
                    <td><strong>${{ number_format($sale->total,2) }}</strong></td>
                </tr>
                <tr>
                    <th>Paid Amount</th>
                    <td class="text-success">${{ number_format($sale->paid_amount,2) }}</td>
                </tr>
                <tr>
                    <th>Due Amount</th>
                    <td class="{{ $sale->due_amount > 0 ? 'text-danger' : 'text-success' }}">
                        ${{ number_format($sale->due_amount,2) }}
                    </td>
                </tr>
                <tr>
                    <th>Notes</th>
                    <td>{{ $sale->notes ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Payment History --}}
    <div class="mt-3 card">
        <div class="card-header">
            <h3 class="card-title">Payment History</h3>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Payment Code</th>
                        <th>Method</th>
                        <th>Amount</th>
                        <th>Reference</th>
                        <th>Received By</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sale->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_code }}</td>
                            <td>{{ ucfirst(str_replace('_',' ',$payment->method->value)) }}</td>
                            <td>${{ number_format($payment->amount,2) }}</td>
                            <td>{{ $payment->reference ?? '-' }}</td>
                            <td>{{ $payment->user->name ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No payments recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Refund History --}}
    @if($sale->refunds->isNotEmpty())
    <div class="mt-3 card">
        <div class="card-header">
            <h3 class="card-title">Refund History</h3>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Refund Code</th>
                        <th>Items Refunded</th>
                        <th>Method</th>
                        <th>Amount</th>
                        <th>Reason</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->refunds as $refund)
                        <tr>
                            <td>{{ $refund->refund_code }}</td>
                            <td>
                                @foreach($refund->items as $refundItem)
                                    {{ $refundItem->product->name ?? 'N/A' }}
                                    (x{{ $refundItem->quantity }}{{ $refundItem->restocked ? '' : ', not restocked' }})
                                    @if(!$loop->last)<br>@endif
                                @endforeach
                            </td>
                            <td>{{ ucfirst(str_replace('_',' ',$refund->method)) }}</td>
                            <td class="text-danger">${{ number_format($refund->amount,2) }}</td>
                            <td>{{ $refund->reason ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($refund->refunded_at)->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Actions --}}
    <div class="mt-3 mb-4">
        <a href="{{ route('admin.sale.index') }}" class="btn btn-secondary">
            Back
        </a>

        <a href="{{ route('admin.sale.index', ['edit' => $sale->id]) }}" class="btn btn-warning">
            Edit Sale
        </a>

        @if($sale->paid_amount > 0 && $sale->sale_status->value !== 'refunded')
            <a href="{{ route('admin.sale.index', ['refund' => $sale->id]) }}" class="btn btn-secondary">
                Refund Sale
            </a>
        @endif
    </div>

</div>
</div>

</main>

@endsection
