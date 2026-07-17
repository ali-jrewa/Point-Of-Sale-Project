@extends('layouts.app')

@section('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

<main class="app-main">

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><h3 class="mb-0">{{ __('sale_show.title') }}</h3></h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">{{ __('common.home') }}</a></li>
                    <li class="breadcrumb-item">{{ __('sale_show.sales') }}</li>
                    <li class="breadcrumb-item active">{{ __('common.view') }}</li>
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
           <h3 class="card-title">{{ __('sale_show.sale_information') }}</h3>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="mb-3 col-md-4">
                    <strong>{{ __('sale_show.sale_code') }}</strong>
                    <p>{{ $sale->sale_code }}</p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>{{ __('sale_show.customer') }}</strong>
                    <p>
                        @if($sale->customer)
                            {{ $sale->customer->first_name }} {{ $sale->customer->last_name }}
                        @else
                            {{ __('sale_show.walk_in_customer') }}
                        @endif
                    </p>
                </div>

                <div class="mb-3 col-md-4">
                   <strong>{{ __('sale_show.invoice_number') }}</strong>
                    <p>{{ $sale->invoice_number ?? '-' }}</p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>{{ __('sale_show.sale_date') }}</strong>
                    <p>{{ \Carbon\Carbon::parse($sale->sold_at)->format('Y-m-d') }}</p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>{{ __('sale_show.created_by') }}</strong>
                    <p>{{ $sale->user->name ?? '-' }}</p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>{{ __('sale_show.created_at') }}</strong>
                    <p>{{ $sale->created_at->format('Y-m-d H:i') }}</p>
                </div>

                <div class="mb-3 col-md-4">
                   <strong>{{ __('sale_show.sale_status') }}</strong>
                    <p>
                        <span class="{{ $sale->sale_status->value == 'completed'
                            ? 'text-success'
                            : ($sale->sale_status->value == 'pending'
                                ? 'text-warning'
                                : 'text-danger') }}">
                           {{ __('sale.' . $sale->sale_status->value) }}
                        </span>
                    </p>
                </div>

                <div class="mb-3 col-md-4">
                   <strong>{{ __('sale_show.payment_status') }}</strong>
                    <p>
                        <span class="{{ $sale->payment_status->value == 'paid'
                            ? 'text-success'
                            : ($sale->payment_status->value == 'partial'
                                ? 'text-warning'
                                : 'text-danger') }}">
                            {{ __('sale.' . $sale->payment_status->value) }}
                        </span>
                    </p>
                </div>

                <div class="mb-3 col-md-4">
                    <strong>{{ __('sale_show.updated_at') }}</strong>
                    <p>{{ $sale->updated_at?->format('Y-m-d H:i') ?? __('sale_show.na') }}</p>
                </div>

            </div>
        </div>
    </div>

    {{-- Sold Products --}}
    <div class="mt-3 card">
        <div class="card-header">
            <h3 class="card-title">{{ __('sale_show.sold_products') }}</h3>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('sale_show.product') }}</th>
                        <th>{{ __('sale_show.quantity') }}</th>
                        <th>{{ __('sale_show.unit_price') }}</th>
                        <th>{{ __('sale_show.discount') }}</th>
                        <th>{{ __('sale_show.tax') }}</th>
                        <th>{{ __('sale_show.subtotal') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? __('sale_show.na') }}</td>
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
           <h3 class="card-title">{{ __('sale_show.sale_summary') }}</h3>
        </div>

        <div class="card-body">
            <table class="table">
                <tr>
                    <th width="30%">{{ __('sale_show.subtotal') }}</th>
                    <td>${{ number_format($sale->subtotal,2) }}</td>
                </tr>
                <tr>
                    <th>{{ __('sale_show.discount') }}</th>
                    <td>${{ number_format($sale->discount,2) }}</td>
                </tr>
                <tr>
                    <th>{{ __('sale_show.tax') }}</th>
                    <td>${{ number_format($sale->tax,2) }}</td>
                </tr>
                <tr>
                    <th>{{ __('sale_show.grand_total') }}</th>
                    <td><strong>${{ number_format($sale->total,2) }}</strong></td>
                </tr>
                <tr>
                    <th>{{ __('sale_show.paid_amount') }}</th>
                    <td class="text-success">${{ number_format($sale->paid_amount,2) }}</td>
                </tr>
                <tr>
                    <th>{{ __('sale_show.due_amount') }}</th>
                    <td class="{{ $sale->due_amount > 0 ? 'text-danger' : 'text-success' }}">
                        ${{ number_format($sale->due_amount,2) }}
                    </td>
                </tr>
                <tr>
                    <th>{{ __('sale_show.notes') }}</th>
                    <td>{{ $sale->notes ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Payment History --}}
    <div class="mt-3 card">
        <div class="card-header">
            <h3 class="card-title">{{ __('sale_show.payment_history') }}</h3>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('sale_show.payment_code') }}</th>
                        <th>{{ __('sale_show.method') }}</th>
                        <th>{{ __('sale_show.amount') }}</th>
                        <th>{{ __('sale_show.reference') }}</th>
                        <th>{{ __('sale_show.received_by') }}</th>
                        <th>{{ __('sale_show.date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sale->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_code }}</td>
                            <td> {{ __('sale.' . $payment->method->value) }}</td>
                            <td>${{ number_format($payment->amount,2) }}</td>
                            <td>{{ $payment->reference ?? '-' }}</td>
                            <td>{{ $payment->user->name ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('sale_show.no_payments') }}</td>
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
            <h3 class="card-title">{{ __('sale_show.refund_history') }}</h3>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('sale_show.refund_code') }}</th>
                        <th>{{ __('sale_show.items_refunded') }}</th>
                        <th>{{ __('sale_show.method') }}</th>
                        <th>{{ __('sale_show.amount') }}</th>
                        <th>{{ __('sale_show.reason') }}</th>
                        <th>{{ __('sale_show.date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->refunds as $refund)
                        <tr>
                            <td>{{ $refund->refund_code }}</td>
                            <td>
                                @foreach($refund->items as $refundItem)
                                    {{ $refundItem->product->name ?? __('sale_show.na') }}
                                    (x{{ $refundItem->quantity }}{{ $refundItem->restocked ? '' : ', ' . __('sale_show.not_restocked') }})
                                    @if(!$loop->last)<br>@endif
                                @endforeach
                            </td>
                            <td>{{ __('sale.' . $refund->method->value) }}</td>
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
            {{ __('sale_show.back') }}
        </a>

        <a href="{{ route('admin.sale.index', ['edit' => $sale->id]) }}" class="btn btn-warning">
            {{ __('sale_show.edit_sale') }}
        </a>

        @if($sale->paid_amount > 0 && $sale->sale_status->value !== 'refunded')
            <button type="button"
                    id="refundSaleBtn"
                    class="btn btn-secondary"
                    data-sale-id="{{ $sale->id }}"
                    data-create-url="{{ route('admin.sale.refund.create', $sale->id) }}"
                    data-store-url="{{ route('admin.sale.refund.store', $sale->id) }}"
                    data-method="{{ $sale->payments->last()->method->value ?? 'cash' }}">
                {{ __('sale_show.refund_sale') }}
            </button>
        @endif
    </div>

</div>
</div>

</main>

@endsection

@push('scripts')
<script>
document.getElementById('refundSaleBtn')?.addEventListener('click', function () {

    const btn = this;
    const createUrl = btn.dataset.createUrl;
    const storeUrl = btn.dataset.storeUrl;
    const refundMethod = btn.dataset.method;
    const csrfTokenEl = document.querySelector('meta[name="csrf-token"]');

    if (!csrfTokenEl) {
        alert('CSRF token meta tag missing. Cannot proceed.');
        return;
    }
    const csrfToken = csrfTokenEl.content;

    if (!confirm('{{ __('sale_show.confirm_refund_all') ?? 'Refund this entire sale?' }}')) {
        return;
    }

    btn.disabled = true;
    const originalText = btn.textContent;
    btn.textContent = '{{ __('common.processing') ?? 'Processing...' }}';

    // Step 1: get refundable items for this sale
    fetch(createUrl, {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {

        const items = data.items
            .filter(item => item.refundable_qty > 0)
            .map(item => ({
                sale_item_id: item.sale_item_id,
                quantity: item.refundable_qty,
                restock: true
            }));

        if (items.length === 0) {
            alert('{{ __('sale_show.nothing_to_refund') ?? 'Nothing left to refund.' }}');
            btn.disabled = false;
            btn.textContent = originalText;
            return;
        }

        // Step 2: submit the full refund
        return fetch(storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                items: items,
                method: refundMethod,
                reason: 'Full sale refund'
            })
        });
    })
    .then(res => {
        if (!res) return; // stopped early above (nothing to refund)
        if (!res.ok) {
            return res.json().then(err => { throw err; });
        }
        return res.json();
    })
    .then(data => {
        if (!data) return;

        // Success
        alert(data.message ?? '{{ __('sale_show.refund_success') ?? 'Refund processed successfully.' }}');
        window.location.reload();
    })
    .catch(err => {
        console.error(err);
        const msg = Object.values(err?.errors ?? {}).flat().join('\n')
            || err?.message
            || '{{ __('sale_show.refund_failed') ?? 'Refund failed.' }}';
        alert(msg);

        btn.disabled = false;
        btn.textContent = originalText;
    });
});
</script>
@endpush
