@extends('layouts.app')

@section('content')

<main class="app-main">

<div class="app-content-header">
    <div class="container-fluid">

        <div class="row">

            <div class="col-sm-6">
                <h3 class="mb-0">
                    Purchase Details
                </h3>
            </div>

            <div class="col-sm-6">

                <ol class="breadcrumb float-sm-end">

                    <li class="breadcrumb-item">
                        <a href="#">Home</a>
                    </li>

                    <li class="breadcrumb-item">
                        Purchase
                    </li>

                    <li class="breadcrumb-item active">
                        View
                    </li>

                </ol>

            </div>

        </div>

    </div>
</div>

<div class="app-content">

<div class="container-fluid">
    <div class="card">

    <div class="card-header">

        <h3 class="card-title">
            Purchase Information
        </h3>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="mb-3 col-md-4">

                <strong>Purchase Code</strong>

                <p>{{ $purchase->purchase_code }}</p>

            </div>

            <div class="mb-3 col-md-4">

                <strong>Supplier</strong>

                <p>

                    {{ $purchase->supplier?->first_name }}

                    {{ $purchase->supplier?->last_name }}

                </p>

            </div>

            <div class="mb-3 col-md-4">

                <strong>Invoice Number</strong>

                <p>{{ $purchase->invoice_number ?? '-' }}</p>

            </div>

            <div class="mb-3 col-md-4">

                <strong>Purchase Date</strong>

                <p>{{ $purchase->purchased_at->format('Y-m-d') }}</p>

            </div>

            <div class="mb-3 col-md-4">

                <strong>Created By</strong>

                <p>{{ $purchase->user->name }}</p>

            </div>

            <div class="mb-3 col-md-4">

                <strong>Created At</strong>

                <p>{{ $purchase->created_at->format('Y-m-d H:i') }}</p>

            </div>

            <div class="mb-3 col-md-4">

                <strong>Purchase Status</strong>

                <p>

                    <span class="{{ $purchase->purchase_status->value == 'completed'
                        ? 'text-success'
                        : ($purchase->purchase_status->value == 'pending'
                            ? 'text-warning'
                            : 'text-danger') }}">

                        {{ ucfirst($purchase->purchase_status->value) }}

                    </span>

                </p>

            </div>

            <div class="mb-3 col-md-4">

                <strong>Payment Status</strong>

                <p>

                    <span class="{{ $purchase->payment_status->value == 'paid'
                        ? 'text-success'
                        : ($purchase->payment_status->value == 'partial'
                            ? 'text-warning'
                            : 'text-danger') }}">

                        {{ ucfirst($purchase->payment_status->value) }}

                    </span>

                </p>

            </div>
            <div class="mb-3 col-md-4">

                <strong>Updated At</strong>

                <p>{{ $purchase->updated_at->format('Y-m-d H:i') ?? "N/A" }}</p>

            </div>

        </div>

    </div>

</div>
<div class="mt-3 card">

    <div class="card-header">

        <h3 class="card-title">

            Purchased Products

        </h3>

    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead>

            <tr>

                <th>Product</th>

                <th>Quantity</th>

                <th>Unit Cost</th>

                <th>Discount</th>

                <th>Tax</th>

                <th>Subtotal</th>

            </tr>

            </thead>

            <tbody>

            @foreach($purchase->items as $item)

            <tr>

                <td>

                    {{ $item->product->name }}

                </td>

                <td>

                    {{ $item->quantity }}

                </td>

                <td>

                    ${{ number_format($item->unit_cost,2) }}

                </td>

                <td>

                    ${{ number_format($item->discount,2) }}

                </td>

                <td>

                    ${{ number_format($item->tax,2) }}

                </td>

                <td>

                    ${{ number_format($item->subtotal,2) }}

                </td>

            </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</div>

<div class="mt-3 card">

    <div class="card-header">

        <h3 class="card-title">

            Purchase Summary

        </h3>

    </div>

    <div class="card-body">

        <table class="table">

            <tr>

                <th width="30%">
                    Subtotal
                </th>

                <td>

                    ${{ number_format($purchase->subtotal,2) }}

                </td>

            </tr>

            <tr>

                <th>
                    Discount
                </th>

                <td>

                    ${{ number_format($purchase->discount,2) }}

                </td>

            </tr>

            <tr>

                <th>
                    Tax
                </th>

                <td>

                    ${{ number_format($purchase->tax,2) }}

                </td>

            </tr>

            <tr>

                <th>
                    Grand Total
                </th>

                <td>

                    <strong>

                        ${{ number_format($purchase->total,2) }}

                    </strong>

                </td>

            </tr>

            <tr>

                <th>
                    Notes
                </th>

                <td>

                    {{ $purchase->notes ?? '-' }}

                </td>

            </tr>

        </table>

    </div>

</div>
<div class="mt-3">

    <a href="{{ route('admin.purchase.index') }}"
       class="btn btn-secondary">

        Back

    </a>

    <a href="{{ route('admin.purchase.index', ['edit' => $purchase->id]) }}"
        class="btn btn-warning">
            Edit Purchase
    </a>

</div>

</div>

</div>

</main>

@endsection
