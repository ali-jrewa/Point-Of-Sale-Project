@extends('layouts.app')

@section('content')

<main class="app-main">

    {{-- Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Sales</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Sale List</li>
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
                            <h4 class="mt-3 card-title btn">Search Sale</h4>
                            <input type="text" id="search" class="form-control"
                                placeholder="Search by Sale Code, Invoice No, Customer Name, Sale Status or Payment Status">
                        </div>
                        <div class="col-md-3">
                            <label class="mt-4 form-label">Sale Date</label>
                            <input type="date" id="sale_date_search" class="form-control">
                        </div>
                    </div>

                    {{-- Card --}}
                    <div class="mb-4 card">
                        <div class="card-header">
                            <h3 class="card-title">Sale List</h3>
                            <div class="card-tools">
                                <ul class="pagination pagination-sm float-end">
                                    <a href="{{ route('admin.sale.create') }}" class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#addSaleModal">
                                        Add Sale
                                    </a>
                                </ul>
                            </div>
                        </div>

                        <div class="card-body">
                            <table id="sale-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="font-size:15px">Sale Code</th>
                                        <th style="font-size:15px">Customer</th>
                                        <th style="font-size:15px">Invoice No.</th>
                                        <th style="font-size:15px">Subtotal</th>
                                        <th style="font-size:15px">Discount</th>
                                        <th style="font-size:15px">Tax</th>
                                        <th style="font-size:15px">Total</th>
                                        <th style="font-size:15px">Sale Status</th>
                                        <th style="font-size:15px">Payment Status</th>
                                        <th style="font-size:15px">Sale Date</th>
                                        <th style="font-size:15px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $grandTotal = 0; @endphp

                                    @forelse($sales as $sale)
                                        @php $grandTotal += $sale->total; @endphp
                                        <tr>
                                            <td style="font-size:15px">{{ $sale->sale_code }}</td>
                                            <td style="font-size:15px">
                                                @if($sale->customer)
                                                    {{ $sale->customer->first_name.' '.$sale->customer->last_name }}
                                                @else
                                                    Walk In Customer
                                                @endif
                                            </td>
                                            <td style="font-size:15px">{{ $sale->invoice_number ?? '-' }}</td>
                                            <td>${{ number_format($sale->subtotal,2) }}</td>
                                            <td>${{ number_format($sale->discount,2) }}</td>
                                            <td>${{ number_format($sale->tax,2) }}</td>
                                            <td>${{ number_format($sale->total,2) }}</td>
                                            <td>
                                                <span class="{{ $sale->sale_status->value == 'completed' ? 'text-success' : ($sale->sale_status->value == 'pending' ? 'text-warning' : 'text-danger') }}">
                                                    {{ ucfirst($sale->sale_status->value) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="{{ $sale->payment_status->value == 'paid' ? 'text-success' : ($sale->payment_status->value == 'partial' ? 'text-warning' : 'text-danger') }}">
                                                    {{ ucfirst($sale->payment_status->value) }}
                                                </span>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($sale->sold_at)->format('Y-m-d') }}</td>
                                            <td>
                                                <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $sale->id }}">Edit</button>
                                                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $sale->id }}">Delete</button>
                                                <a href="{{ route('admin.sale.show',$sale->id) }}" role="button" class="mt-2 btn btn-info btn-sm">View</a>
                                                @if($sale->paid_amount > 0 && $sale->sale_status->value !== 'refunded')
                                                    <button style="max-width:52px;max-height:30px !important;font-size:12px;padding:5px;margin-top:5px" class=" btn btn-secondary refund-btn" data-id="{{ $sale->id }}">Refund</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="13" class="text-center">No sales found.</td>
                                        </tr>
                                    @endforelse

                                    @if($grandTotal > 0)
                                        <tr>
                                            <th colspan="6" class="text-center">Grand Total</th>
                                            <th>${{ number_format($grandTotal,2) }}</th>
                                            <th colspan="6"></th>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <div id="pagination-wrapper" style="padding:10px;float:right;">
                                {!! $sales->links() !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</main>

{{-- Create Sale Modal --}}
<div class="modal fade" id="addSaleModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Sale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addSaleForm">
                    @csrf

                    <div class="mb-3">
                        <label>Customer</label>
                        <select name="customer_id" class="form-control">
                            <option value="">Walk In Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->first_name.' '.$customer->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Invoice Number</label>
                            <input type="text" name="invoice_number" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Sale Date</label>
                            <input type="date" name="sold_at" value="{{ now()->format('Y-m-d') }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="mt-3 row">
                        <div class="col-md-6">
                            <label>Sale Status</label>
                            <select name="sale_status" class="form-control">
                                @foreach(\App\Enums\SaleStatus::values() as $status)
                                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr>
                    <h5>Sale Items</h5>

                    <table class="table table-bordered" id="saleItemsTable">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Tax</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <button type="button" class="btn btn-success btn-sm" id="addSaleItem">+ Add Product</button>

                    <hr>

                    {{-- Totals --}}
                    <div class="row">
                        <div class="offset-md-8 col-md-4">
                            <label>Discount</label>
                            <input type="number" step="0.01" value="0" id="sale_discount" name="discount" class="form-control">

                            <label class="mt-2">Tax</label>
                            <input type="number" step="0.01" value="0" id="sale_tax" name="tax" class="form-control">

                            <label class="mt-2">Total</label>
                            <input type="text" id="sale_total" class="form-control" readonly>
                        </div>
                    </div>

                    <hr>

                    {{-- Payment --}}
                    <h5>Payment</h5>

                    <div class="row">
                        <div class="col-md-4">
                            <label>Amount</label>
                            <input type="number" step="0.01" name="payment[amount]" id="payment_amount" value="0" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Method</label>
                            <select name="payment[method]" class="form-control">
                                @foreach(\App\Enums\PaymentMethod::values() as $method)
                                    <option value="{{ $method }}">{{ ucfirst($method) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Reference</label>
                            <input type="text" name="payment[reference]" class="form-control">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control"></textarea>
                    </div>

                    <button type="submit" class="mt-3 btn btn-primary">Save Sale</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Edit Sale Modal --}}
<div class="modal fade" id="editSaleModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Sale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editSaleForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_sale_id">

                    <hr>

                    <div class="mb-3 card border-info">
                        <div class="text-white card-header bg-info">
                            <strong>Payment Summary</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Total</label>
                                    <input type="text" id="edit_summary_total" class="form-control" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label>Paid</label>
                                    <input type="text" id="edit_summary_paid" class="form-control" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label>Due</label>
                                    <input type="text" id="edit_summary_due" class="form-control" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label>Status</label>
                                    <input type="text" id="edit_summary_status" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <strong>Payment History</strong>
                        </div>
                        <div class="p-0 card-body">
                            <table class="table mb-0 table-bordered">
                                <thead>
                                    <tr>
                                        <th>Payment Code</th>
                                        <th>Method</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Reference</th>
                                    </tr>
                                </thead>
                                <tbody id="paymentHistoryTable"></tbody>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label>Customer</label>
                        <select id="edit_customer_id" name="customer_id" class="form-control">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->first_name.' '.$customer->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Invoice Number</label>
                            <input type="text" id="edit_invoice_number" name="invoice_number" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Sale Date</label>
                            <input type="date" id="edit_sold_at" name="sold_at" class="form-control">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label>Sale Status</label>
                        <select id="edit_sale_status" name="sale_status" class="form-control">
                            @foreach(\App\Enums\SaleStatus::values() as $status)
                                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr>
                    <h5>Sale Items</h5>

                    <table class="table table-bordered" id="editSaleItemsTable">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Tax</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <button type="button" id="editAddSaleItem" class="btn btn-success btn-sm">+ Add Product</button>

                    <hr>

                    <div class="row">
                        <div class="offset-md-8 col-md-4">
                            <label>Discount</label>
                            <input type="number" step="0.01" id="edit_sale_discount" name="discount" class="form-control">

                            <label class="mt-2">Tax</label>
                            <input type="number" step="0.01" id="edit_sale_tax" name="tax" class="form-control">

                            <label class="mt-2">Total</label>
                            <input readonly id="edit_sale_total" class="form-control">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label>Notes</label>
                        <textarea name="notes" id="edit_notes" class="form-control"></textarea>
                    </div>

                    <hr>
                    <h5>Add Payment</h5>

                    <div class="row">
                        <div class="col-md-3">
                            <label>Amount</label>
                            <input type="number" step="0.01" id="new_payment_amount" class="form-control" value="0">
                        </div>
                        <div class="col-md-3">
                            <label>Method</label>
                            <select id="new_payment_method" class="form-control">
                                @foreach(\App\Enums\PaymentMethod::values() as $method)
                                    <option value="{{ $method }}">{{ ucfirst(str_replace('_',' ',$method)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Reference</label>
                            <input type="text" id="new_payment_reference" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <button type="button" id="btnAddPayment" class="btn btn-success w-100">Add Payment</button>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label>Notes</label>
                        <textarea id="new_payment_notes" class="form-control"></textarea>
                    </div>

                    <button class="mt-3 btn btn-primary">Update Sale</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Refund Sale Modal --}}
<div class="modal fade" id="refundSaleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Refund Sale <span id="refund_sale_code"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="refundSaleForm">
                    @csrf
                    <input type="hidden" id="refund_sale_id">

                    <div class="mb-3 row">
                        <div class="col-md-4">
                            <label>Sale Total</label>
                            <input type="text" id="refund_sale_total" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label>Paid</label>
                            <input type="text" id="refund_sale_paid" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label>Already Refunded</label>
                            <input type="text" id="refund_already_refunded" class="form-control" readonly>
                        </div>
                    </div>

                    <h6>Items to Refund</h6>

                    <table class="table table-bordered" id="refundItemsTable">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Purchased</th>
                                <th>Already Refunded</th>
                                <th>Refund Qty</th>
                                <th>Restock</th>
                                <th>Refund Amount</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <div class="mt-3 row">
                        <div class="col-md-4">
                            <label>Refund Method</label>
                            <select id="refund_method" class="form-control" required>
                                @foreach(\App\Enums\PaymentMethod::values() as $method)
                                    <option value="{{ $method }}">{{ ucfirst(str_replace('_',' ',$method)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Total Refund Amount</label>
                            <input type="text" id="refund_total_amount" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label>Reason</label>
                        <textarea id="refund_reason" class="form-control" rows="2"></textarea>
                    </div>

                    <hr>
                    <h6>Refund History</h6>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Date</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody id="refundHistoryTable"></tbody>
                    </table>

                    <button type="submit" class="mt-2 btn btn-primary">Process Refund</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Flash Message --}}
<div class="flash-message alert alert-success" id="flash"
    style="display:none;position:fixed;top:20px;right:20px;z-index:9999;padding:10px 20px;border-radius:5px;">
</div>

<style>
.btn-x-sm {
    padding: 0.15rem 0.4rem;
    font-size: 0.7rem;
    line-height: 1.2;
    border-radius: 0.2rem;
    margin: 1px;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs/dayjs.min.js"></script>

<script>
$(document).ready(function () {

    let timer;
    let saleRowIndex = 0;
    let editSaleRowIndex = 0;

    // Fix Bootstrap aria-hidden/focus warning globally
    document.addEventListener('hide.bs.modal', function (event) {
        if (document.activeElement && event.target.contains(document.activeElement)) {
            document.activeElement.blur();
        }
    });

    $(document).on('click', '.edit-btn', handleEdit);
    $(document).on('click', '.delete-btn', handleDelete);
    $(document).on('click', '.refund-btn', function () {
        loadSaleForRefund($(this).data('id'));
    });

    // ==========================
    // Fetch Sales
    // ==========================
    function fetchSales(search = '', saleDate = '') {

        $.ajax({
            url: "{{ route('admin.sale.data') }}",
            method: "GET",
            data: { search: search, sale_date: saleDate },
            success: function (response) {

                let tableBody = '';
                let grandTotal = 0;

                if (!response.data || response.data.length === 0) {

                    tableBody = `
                        <tr>
                            <td colspan="13" class="text-center">No sales found.</td>
                        </tr>
                    `;

                } else {

                    $.each(response.data, function (index, sale) {

                        grandTotal += parseFloat(sale.total);

                        let customer = sale.customer
                            ? sale.customer.first_name + ' ' + sale.customer.last_name
                            : 'Walk In Customer';

                        tableBody += `
                        <tr>
                            <td>${sale.sale_code}</td>
                            <td>${customer}</td>
                            <td>${sale.invoice_number ?? '-'}</td>
                            <td>$${parseFloat(sale.subtotal).toFixed(2)}</td>
                            <td>$${parseFloat(sale.discount).toFixed(2)}</td>
                            <td>$${parseFloat(sale.tax).toFixed(2)}</td>
                            <td>$${parseFloat(sale.total).toFixed(2)}</td>
                            <td>$${parseFloat(sale.paid_amount).toFixed(2)}</td>
                            <td>$${parseFloat(sale.due_amount).toFixed(2)}</td>
                            <td class="${sale.sale_status === 'completed' ? 'text-success' : sale.sale_status === 'pending' ? 'text-warning' : 'text-danger'}">
                                ${sale.sale_status.charAt(0).toUpperCase() + sale.sale_status.slice(1)}
                            </td>
                            <td class="${sale.payment_status === 'paid' ? 'text-success' : sale.payment_status === 'partial' ? 'text-warning' : 'text-danger'}">
                                ${sale.payment_status.charAt(0).toUpperCase() + sale.payment_status.slice(1)}
                            </td>
                            <td>${dayjs(sale.sold_at).format('YYYY-MM-DD')}</td>
                            <td>
                                <button class="btn btn-warning btn-x-sm edit-btn" data-id="${sale.id}">Edit</button>
                                <button class="btn btn-danger btn-x-sm delete-btn" data-id="${sale.id}">Delete</button>
                                <a href="/admin/sale/${sale.id}" class="btn btn-info btn-x-sm">View</a>
                                ${sale.paid_amount > 0 && sale.sale_status !== 'refunded'
                                    ? `<button class="btn btn-secondary btn-x-sm refund-btn" data-id="${sale.id}">Refund</button>`
                                    : ''}
                            </td>
                        </tr>
                        `;
                    });

                    tableBody += `
                    <tr>
                        <th colspan="6" class="text-center">Grand Total</th>
                        <th>$${grandTotal.toFixed(2)}</th>
                        <th colspan="6"></th>
                    </tr>
                    `;
                }

                $("#sale-table tbody").html(tableBody);
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    // ==========================
    // Refund Sale
    // ==========================
    function loadSaleForRefund(id) {

        $.ajax({
            url: "{{ route('admin.sale.refund.create', ':id') }}".replace(':id', id),
            method: "GET",
            success: function (response) {
                populateRefundModal(response);
            },
            error: function () {
                alert("Unable to load sale for refund.");
            }
        });
    }

    function populateRefundModal(response) {

        const sale = response.sale;
        const items = response.items;
        const refunds = response.refunds;

        $("#refund_sale_id").val(sale.id);
        $("#refund_sale_code").text('(' + sale.sale_code + ')');
        $("#refund_sale_total").val(parseFloat(sale.total).toFixed(2));
        $("#refund_sale_paid").val(parseFloat(sale.paid_amount).toFixed(2));

        let totalRefunded = 0;
        refunds.forEach(function (r) { totalRefunded += parseFloat(r.amount); });

        $("#refund_already_refunded").val(totalRefunded.toFixed(2));
        $("#refund_reason").val("");

        let rows = "";

        items.forEach(function (item) {

            const unitRefund = item.subtotal / item.quantity;

            rows += `
            <tr data-sale-item-id="${item.sale_item_id}" data-unit-refund="${unitRefund}" data-max="${item.refundable_qty}">
                <td>${item.product_name}</td>
                <td>${item.quantity}</td>
                <td>${item.refunded_qty}</td>
                <td>
                    <input type="number" class="form-control refund-qty" value="0" min="0"
                        max="${item.refundable_qty}" ${item.refundable_qty === 0 ? 'disabled' : ''}>
                </td>
                <td class="text-center">
                    <input type="checkbox" class="refund-restock" checked
                        ${item.refundable_qty === 0 ? 'disabled' : ''}>
                </td>
                <td><span class="refund-line-amount">0.00</span></td>
            </tr>
            `;
        });

        $("#refundItemsTable tbody").html(rows);
        calculateRefundTotal();

        let historyRows = "";

        refunds.forEach(function (r) {
            historyRows += `
            <tr>
                <td>${r.refund_code}</td>
                <td>$${parseFloat(r.amount).toFixed(2)}</td>
                <td>${r.method.replaceAll('_',' ')}</td>
                <td>${dayjs(r.refunded_at).format('YYYY-MM-DD')}</td>
                <td>${r.reason ?? '-'}</td>
            </tr>
            `;
        });

        if (historyRows === "") {
            historyRows = `<tr><td colspan="5" class="text-center">No refunds yet.</td></tr>`;
        }

        $("#refundHistoryTable").html(historyRows);

        new bootstrap.Modal(document.getElementById("refundSaleModal")).show();
    }

    $(document).on("keyup change", "#refundItemsTable .refund-qty, #refundItemsTable .refund-restock", function () {
        calculateRefundTotal();
    });

    function calculateRefundTotal() {

        let total = 0;

        $("#refundItemsTable tbody tr").each(function () {

            const row = $(this);
            const max = parseInt(row.data("max"));
            let qty = parseInt(row.find(".refund-qty").val()) || 0;

            if (qty > max) {
                qty = max;
                row.find(".refund-qty").val(max);
            }

            const unitRefund = parseFloat(row.data("unit-refund"));
            const lineAmount = qty * unitRefund;

            row.find(".refund-line-amount").text(lineAmount.toFixed(2));
            total += lineAmount;
        });

        $("#refund_total_amount").val(total.toFixed(2));
    }

    $("#refundSaleForm").submit(function (e) {

        e.preventDefault();

        let saleId = $("#refund_sale_id").val();
        let items = [];

        $("#refundItemsTable tbody tr").each(function () {

            const row = $(this);
            const qty = parseInt(row.find(".refund-qty").val()) || 0;

            if (qty > 0) {
                items.push({
                    sale_item_id: row.data("sale-item-id"),
                    quantity: qty,
                    restock: row.find(".refund-restock").is(":checked") ? 1 : 0
                });
            }
        });

        if (items.length === 0) {
            alert("Please enter a quantity to refund for at least one item.");
            return;
        }

        $.ajax({
            url: "{{ route('admin.sale.refund.store', ':id') }}".replace(':id', saleId),
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                method: $("#refund_method").val(),
                reason: $("#refund_reason").val(),
                items: items
            },
            success: function (response) {

                $("#refundSaleModal").modal("hide");

                $(".flash-message")
                    .removeClass("alert-danger")
                    .addClass("alert-success")
                    .text(response.message)
                    .fadeIn().delay(3000).fadeOut();

                fetchSales();
            },
            error: function (xhr) {

                if (xhr.responseJSON && xhr.responseJSON.errors) {

                    let message = "";
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        message += value[0] + "\n";
                    });
                    alert(message);

                } else {
                    alert("Something went wrong processing the refund.");
                }
            }
        });
    });

    // ==========================
    // Sale Item Rows
    // ==========================
    $("#addSaleItem").click(function () {
        createSaleItemRow(saleRowIndex);
        saleRowIndex++;
    });

    function createSaleItemRow(index) {

        let row = `
        <tr>
            <td>
                <select name="items[${index}][product_id]" class="form-control sale-product" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->sale_price ?? $product->retail_price }}">
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="items[${index}][quantity]" min="1" class="form-control sale-qty" value="1" required></td>
            <td><input class="form-control sale-price" type="number" name="items[${index}][unit_price]" step="0.01" min="0" required></td>
            <td><input type="number" name="items[${index}][discount]" class="form-control sale-discount" value="0"></td>
            <td><input type="number" name="items[${index}][tax]" class="form-control sale-tax" value="0"></td>
            <td><input class="form-control sale-subtotal" type="number" step="0.01" readonly value="0"></td>
            <td><button type="button" class="btn btn-danger remove-sale-item">X</button></td>
        </tr>
        `;

        $("#saleItemsTable tbody").append(row);
    }

    function createEditSaleRow(index) {

        let row = `
        <tr>
            <td>
                <select name="items[${index}][product_id]" class="form-control sale-product" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->sale_price ?? $product->retail_price }}">
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="items[${index}][quantity]" value="1" min="1" required class="form-control sale-qty"></td>
            <td><input type="number" step="0.01" name="items[${index}][unit_price]" required value="0" class="form-control sale-price"></td>
            <td><input type="number" step="0.01" name="items[${index}][discount]" value="0" class="form-control sale-discount"></td>
            <td><input type="number" step="0.01" name="items[${index}][tax]" value="0" class="form-control sale-tax"></td>
            <td><input type="number" step="0.01" class="form-control sale-subtotal" readonly value="0"></td>
            <td><button type="button" class="btn btn-danger remove-sale-item">X</button></td>
        </tr>
        `;

        $("#editSaleItemsTable tbody").append(row);
    }

    $(document).on("keyup change",
        "#saleItemsTable .sale-qty, #saleItemsTable .sale-price, #saleItemsTable .sale-discount, #saleItemsTable .sale-tax",
        function () {

            const row = $(this).closest("tr");
            let qty = parseFloat(row.find(".sale-qty").val()) || 0;
            let price = parseFloat(row.find(".sale-price").val()) || 0;
            let discount = parseFloat(row.find(".sale-discount").val()) || 0;
            let tax = parseFloat(row.find(".sale-tax").val()) || 0;

            row.find(".sale-subtotal").val(((qty * price) - discount + tax).toFixed(2));

            calculateSaleTotal();
        });

    $(document).on("change", ".sale-product", function () {

        let price = $(this).find(":selected").data("price");

        $(this).closest("tr").find(".sale-price").val(price).trigger("keyup");

        calculateSaleTotal();
    });

    $(document).on("click", ".remove-sale-item", function () {

        let table = $(this).closest("table");
        $(this).closest("tr").remove();

        if (table.attr("id") === "saleItemsTable") {
            calculateSaleTotal();
        } else {
            calculateEditSaleTotal();
        }
    });

    // ==========================
    // Edit Sale
    // ==========================
    function handleEdit() {
        loadSaleForEdit($(this).data("id"));
    }

    function loadSaleForEdit(id) {

        $.ajax({
            url: "{{ route('admin.sale.edit', ':id') }}".replace(':id', id),
            method: "GET",
            success: function (sale) {
                populateEditModal(sale);
            },
            error: function () {
                alert("Unable to load sale.");
            }
        });
    }

    function populateEditModal(sale) {

        $("#edit_sale_id").val(sale.id);
        $("#edit_customer_id").val(sale.customer_id);
        $("#edit_invoice_number").val(sale.invoice_number);
        $("#edit_sold_at").val(dayjs(sale.sold_at).format("YYYY-MM-DD"));
        $("#edit_sale_status").val(sale.sale_status);
        $("#edit_sale_discount").val(sale.discount);
        $("#edit_sale_tax").val(sale.tax);
        $("#edit_notes").val(sale.notes);
        $("#edit_summary_total").val(parseFloat(sale.total).toFixed(2));
        $("#edit_summary_paid").val(parseFloat(sale.paid_amount).toFixed(2));
        $("#edit_summary_due").val(parseFloat(sale.due_amount).toFixed(2));
        $("#edit_summary_status").val(
            sale.payment_status.charAt(0).toUpperCase() + sale.payment_status.slice(1)
        );
        $("#new_payment_amount").val(0);
        $("#new_payment_reference").val("");
        $("#new_payment_notes").val("");
        $("#new_payment_method").prop("selectedIndex", 0);

        let paymentRows = "";

        $.each(sale.payments, function (index, payment) {
            paymentRows += `
            <tr>
                <td>${payment.payment_code}</td>
                <td>${payment.method.replaceAll("_"," ").replace(/\b\w/g, c => c.toUpperCase())}</td>
                <td>$${parseFloat(payment.amount).toFixed(2)}</td>
                <td>${dayjs(payment.paid_at).format("YYYY-MM-DD")}</td>
                <td>${payment.reference ?? "-"}</td>
            </tr>
            `;
        });

        if (paymentRows === "") {
            paymentRows = `<tr><td colspan="5" class="text-center">No payments yet.</td></tr>`;
        }

        $("#paymentHistoryTable").html(paymentRows);

        $("#editSaleItemsTable tbody").html("");
        editSaleRowIndex = 0;

        $.each(sale.items, function (index, item) {

            createEditSaleRow(editSaleRowIndex);

            let row = $("#editSaleItemsTable tbody tr").last();

            row.find(".sale-product").val(item.product_id);
            row.find(".sale-qty").val(item.quantity);
            row.find(".sale-price").val(item.unit_price);
            row.find(".sale-discount").val(item.discount);
            row.find(".sale-tax").val(item.tax);
            row.find(".sale-price").trigger("keyup");

            editSaleRowIndex++;
        });

        calculateEditSaleTotal();

        new bootstrap.Modal(document.getElementById("editSaleModal")).show();
    }

    $('#addSaleModal').on('shown.bs.modal', function () {

        if ($("#saleItemsTable tbody tr").length === 0) {
            createSaleItemRow(0);
            calculateSaleTotal();
            saleRowIndex = 1;
        }
    });

    $("#addSaleModal").on("hidden.bs.modal", function () {

        $("#addSaleForm")[0].reset();
        $("#saleItemsTable tbody").empty();
        $("#sale_total").val("");
        saleRowIndex = 0;
    });

    $("#sale_discount, #sale_tax").on("keyup change", function () {
        calculateSaleTotal();
    });

    $("#editAddSaleItem").click(function () {
        createEditSaleRow(editSaleRowIndex);
        editSaleRowIndex++;
    });

    function calculateEditSaleTotal() {

        let total = 0;

        $("#editSaleItemsTable .sale-subtotal").each(function () {
            total += parseFloat($(this).val()) || 0;
        });

        let discount = parseFloat($("#edit_sale_discount").val()) || 0;
        let tax = parseFloat($("#edit_sale_tax").val()) || 0;

        total = total - discount + tax;

        $("#edit_sale_total").val(total.toFixed(2));
    }

    function calculateSaleTotal() {

        let subtotal = 0;

        $("#saleItemsTable .sale-subtotal").each(function () {
            subtotal += parseFloat($(this).val()) || 0;
        });

        let discount = parseFloat($("#sale_discount").val()) || 0;
        let tax = parseFloat($("#sale_tax").val()) || 0;

        let total = subtotal - discount + tax;

        $("#sale_total").val(total.toFixed(2));
    }

    // ==========================
    // Form Submissions
    // ==========================
    $("#addSaleForm").submit(function (e) {

        e.preventDefault();

        $.ajax({
            url: "{{ route('admin.sale.store') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function (response) {

                $(".flash-message")
                    .removeClass("alert-danger")
                    .addClass("alert-success")
                    .text(response.message)
                    .fadeIn().delay(2000).fadeOut();

                fetchSales();
                $("#addSaleModal").modal("hide");
                $("#addSaleForm")[0].reset();
                $("#saleItemsTable tbody").empty();
                saleRowIndex = 0;
            },
            error: function (xhr) {

                if (xhr.responseJSON && xhr.responseJSON.errors) {

                let message = "";
                $.each(xhr.responseJSON.errors, function (key, value) {
                    message += value[0] + "\n";
                });
                alert(message);

            } else if (xhr.responseJSON && xhr.responseJSON.message) {

                alert(xhr.responseJSON.message);

            } else {

                console.log(xhr.responseText);
                alert("Something went wrong (status " + xhr.status + "). Check console for details.");
            }
            }
        });
    });

    $("#editSaleForm").submit(function (e) {

        e.preventDefault();

        let id = $("#edit_sale_id").val();

        $.ajax({
            url: "{{ route('admin.sale.update', ':id') }}".replace(':id', id),
            method: "POST",
            data: $(this).serialize(),
            success: function (response) {

                $("#editSaleModal").modal("hide");

                $(".flash-message")
                    .removeClass("alert-danger")
                    .addClass("alert-success")
                    .text(response.message)
                    .fadeIn().delay(2000).fadeOut();

                fetchSales();
            },
            error: function (xhr) {

                if (xhr.responseJSON && xhr.responseJSON.errors) {

                let message = "";
                $.each(xhr.responseJSON.errors, function (key, value) {
                    message += value[0] + "\n";
                });
                alert(message);

            } else if (xhr.responseJSON && xhr.responseJSON.message) {

                alert(xhr.responseJSON.message);

            } else {

                console.log(xhr.responseText);
                alert("Something went wrong (status " + xhr.status + "). Check console for details.");
            }
            }
        });
    });

    $("#btnAddPayment").click(function () {

        let saleId = $("#edit_sale_id").val();

        $.ajax({
            url: "{{ route('admin.payment.store', ':id') }}".replace(':id', saleId),
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                amount: $("#new_payment_amount").val(),
                method: $("#new_payment_method").val(),
                reference: $("#new_payment_reference").val(),
                notes: $("#new_payment_notes").val()
            },
            success: function (response) {

                $(".flash-message")
                    .removeClass("alert-danger")
                    .addClass("alert-success")
                    .text(response.message)
                    .fadeIn().delay(2000).fadeOut();

                loadSaleForEdit(saleId);
            },
            error: function (xhr) {

                if (xhr.responseJSON && xhr.responseJSON.errors) {

                    let message = "";

                    $.each(xhr.responseJSON.errors, function (key, value) {
                        message += value[0] + "\n";
                    });

                    alert(message);

                } else {
                    alert("Something went wrong.");
                }
            }
        });
    });

    // ==========================
    // Search
    // ==========================
    $("#search").on("keyup", function () {

        clearTimeout(timer);

        let value = $(this).val();

        if (value.trim() === '') {

            $("#pagination-wrapper").show();
            fetchSales('', '');

        } else {

            $("#pagination-wrapper").hide();

            timer = setTimeout(function () {
                fetchSales(value, $("#sale_date_search").val());
            }, 300);
        }
    });

    $("#sale_date_search").on("change", function () {
        fetchSales($("#search").val(), $(this).val());
    });

    // ==========================
    // Delete Sale
    // ==========================
    function handleDelete() {

        let id = $(this).data("id");

        if (!confirm("Are you sure you want to delete this sale?")) {
            return;
        }

        let url = "{{ route('admin.sale.destroy', ['sale' => ':id']) }}".replace(':id', id);

        $.ajax({
            url: url,
            method: "DELETE",
            data: { _token: "{{ csrf_token() }}" },
            success: function (response) {

                $(".flash-message")
                    .removeClass("alert-success")
                    .addClass("alert-danger")
                    .text(response.message)
                    .fadeIn().delay(3000).fadeOut();

                fetchSales();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    $(document).on("keyup change",
        "#editSaleItemsTable .sale-qty, #editSaleItemsTable .sale-price, #editSaleItemsTable .sale-discount, #editSaleItemsTable .sale-tax",
        function () {

            const row = $(this).closest("tr");

            let qty = parseFloat(row.find(".sale-qty").val()) || 0;
            let price = parseFloat(row.find(".sale-price").val()) || 0;
            let discount = parseFloat(row.find(".sale-discount").val()) || 0;
            let tax = parseFloat(row.find(".sale-tax").val()) || 0;

            row.find(".sale-subtotal").val(((qty * price) - discount + tax).toFixed(2));

            calculateEditSaleTotal();
        });

    $("#edit_sale_discount, #edit_sale_tax").on("keyup change", function () {
        calculateEditSaleTotal();
    });

    $(document).on("change", "#editSaleItemsTable .sale-product", function () {

        let price = $(this).find(":selected").data("price");

        $(this).closest("tr").find(".sale-price").val(price).trigger("keyup");
    });

});
</script>

@endsection
