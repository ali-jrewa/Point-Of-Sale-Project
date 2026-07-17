@extends('layouts.app')

@section('content')

<main class="app-main">

<div class="app-content-header">
    <div class="container-fluid">

        <div class="row">

            <div class="col-sm-6">
                <h3 class="mb-0">
                     {{ __('purchase_show.page_title') }}
                </h3>
            </div>

            <div class="col-sm-6">

                <ol class="breadcrumb float-sm-end">

                    <li class="breadcrumb-item">
                        <a href="#">{{ __('common.home') }}</a>
                    </li>

                    <li class="breadcrumb-item">
                         {{ __('purchase_show.purchase') }}
                    </li>

                    <li class="breadcrumb-item active">
                        {{ __('purchase_show.view') }}
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
            {{ __('purchase_show.purchase_information') }}
        </h3>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="mb-3 col-md-4">

               <strong>{{ __('purchase_show.purchase_code') }}</strong>

                <p>{{ $purchase->purchase_code }}</p>

            </div>

            <div class="mb-3 col-md-4">

                <strong>{{ __('purchase_show.supplier') }}</strong>

                <p>

                    {{ $purchase->supplier ? $purchase->supplier->first_name . ' ' . $purchase->supplier->last_name
                            : __('purchase_show.not_available') }}

                </p>

            </div>

            <div class="mb-3 col-md-4">

                <strong>{{ __('purchase_show.invoice_number') }}</strong>

                <p>{{ $purchase->invoice_number ?? '-' }}</p>

            </div>

            <div class="mb-3 col-md-4">

               <strong>{{ __('purchase_show.purchase_date') }}</strong>

                <p>{{ $purchase->purchased_at->format('Y-m-d') }}</p>

            </div>

            <div class="mb-3 col-md-4">

                <strong>{{ __('purchase_show.created_by') }}</strong>

                <p>{{ $purchase->user->name }}</p>

            </div>

            <div class="mb-3 col-md-4">

                <strong>{{ __('purchase_show.created_at') }}</strong>

                <p>{{ $purchase->created_at->format('Y-m-d H:i') }}</p>

            </div>

            <div class="mb-3 col-md-4">

                <strong>{{ __('purchase_show.purchase_status') }}</strong>

                <p>

                    <span class="{{ $purchase->purchase_status->value == 'completed'
                        ? 'text-success'
                        : ($purchase->purchase_status->value == 'pending'
                            ? 'text-warning'
                            : 'text-danger') }}">

                        {{ __('purchase_status.' . $purchase->purchase_status->value) }}

                    </span>

                </p>

            </div>

            <div class="mb-3 col-md-4">

                <strong>{{ __('purchase_show.payment_status') }}</strong>

                <p>

                    <span class="{{ $purchase->payment_status->value == 'paid'
                        ? 'text-success'
                        : ($purchase->payment_status->value == 'partial'
                            ? 'text-warning'
                            : 'text-danger') }}">

                        {{ __('common.' . $purchase->payment_status->value) }}

                    </span>

                </p>

            </div>
            <div class="mb-3 col-md-4">

                <strong>{{ __('purchase_show.updated_at') }}</strong>

                <p{{ $purchase->updated_at?->format('Y-m-d H:i') ?? __('purchase_show.not_available') }}</p>

            </div>

        </div>

    </div>

</div>
<div class="mt-3 card">

    <div class="card-header">

        <h3 class="card-title">

             {{ __('purchase_show.purchased_products') }}

        </h3>

    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead>

            <tr>

                <th>{{ __('purchase_show.product') }}</th>

                <th>{{ __('purchase_show.quantity') }}</th>

                <th>{{ __('purchase_show.unit_cost') }}</th>

                <th>{{ __('purchase_show.discount') }}</th>

                <th>{{ __('purchase_show.tax') }}</th>

                <th>{{ __('purchase_show.subtotal') }}</th>

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

             {{ __('purchase_show.purchase_summary') }}

        </h3>

    </div>

    <div class="card-body">

        <table class="table">

            <tr>

                <th width="30%">
                    {{ __('purchase_show.subtotal') }}
                </th>

                <td>

                    ${{ number_format($purchase->subtotal,2) }}

                </td>

            </tr>

            <tr>

                <th>
                    {{ __('purchase_show.discount') }}
                </th>

                <td>

                    ${{ number_format($purchase->discount,2) }}

                </td>

            </tr>

            <tr>

                <th>
                    {{ __('purchase_show.tax') }}
                </th>

                <td>

                    ${{ number_format($purchase->tax,2) }}

                </td>

            </tr>

            <tr>

                <th>{{ __('purchase_show.grand_total') }}</th>

                <td>

                    <strong>

                        ${{ number_format($purchase->total,2) }}

                    </strong>

                </td>

            </tr>

            <tr>

                <th>{{ __('purchase_show.notes') }}</th>

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

        {{ __('purchase_show.back') }}

    </a>

    <a href="{{ route('admin.purchase.index', ['edit' => $purchase->id]) }}"
        class="btn btn-warning">
            {{ __('purchase_show.edit_purchase') }}
    </a>

</div>

</div>

</div>

</main>

@endsection
