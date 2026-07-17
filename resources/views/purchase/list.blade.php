@extends('layouts.app')

@section('content')

<main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">{{ __('purchase.page_title') }}</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">{{ __('common.home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ __('purchase.purchase_list') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3 row">

                                    <div class="col-md-9">
                                        <h4 class="mt-3 card-title btn">{{ __('purchase.search_purchase') }}</h4>
                                        <input
                                            type="text"
                                            id="search"
                                            class="form-control"
                                           placeholder="{{ __('purchase.search_placeholder') }}">

                                    </div>
                                    <div class="col-md-3">

                                        <label class="mt-4 form-label">
                                            {{ __('purchase.purchase_date') }}
                                        </label>

                                        <input
                                            type="date"
                                            id="purchase_date_search"
                                            class="form-control">

                                    </div>


                            </div>
                            <div class="mb-4 card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        {{ __('purchase.page_title') }}
                                    </h3>

                                    <div class="card-tools">
                                        <ul class="pagination pagination-sm float-end">

                                            <a href="{{ route('admin.purchase.create') }}" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPurchaseModal">
                                                {{ __('purchase.add_purchase') }}
                                            </a>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <table id="purchase-table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('purchase.purchase_code') }}</th>
                                                <th>{{ __('purchase.supplier') }}</th>
                                                <th>{{ __('purchase.invoice_number') }}</th>
                                                <th>{{ __('purchase.subtotal') }}</th>
                                                <th>{{ __('purchase.discount') }}</th>
                                                <th>{{ __('purchase.tax') }}</th>
                                                <th>{{ __('purchase.total') }}</th>
                                                <th>{{ __('purchase.purchase_status') }}</th>
                                                <th>{{ __('purchase.payment_status') }}</th>
                                                <th>{{ __('purchase.purchase_date') }}</th>
                                                <th>{{ __('common.actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $grandTotal = 0;
                                            @endphp

                                            @forelse($purchases as $purchase)

                                            @php
                                                $grandTotal += $purchase->total;
                                            @endphp
                                            <tr>
                                                <td style="font-size: 15px;align-content:center" >{{ $purchase->purchase_code }}</td>
                                                <td style="font-size: 15px;align-content:center">{{ $purchase->supplier?->first_name . ' ' .$purchase->supplier?->last_name ?? "N/A" }}</td>
                                                <td style="font-size: 15px;align-content:center;">{{ $purchase->invoice_number ?? '-' }}</td>
                                                <td style="font-size: 15px;align-content:center">${{ number_format($purchase->subtotal,2) }}</td>
                                                <td style="font-size: 15px;align-content:center">${{ number_format($purchase->discount,2) }}</td>
                                                <td style="font-size: 15px;align-content:center">${{ number_format($purchase->tax,2) }}</td>
                                                <td style="font-size: 15px;align-content:center">${{ number_format($purchase->total,2) }}</td>
                                                <td style="font-size: 15px;align-content:center">
                                                    <span class="{{ $purchase->purchase_status->value == 'received'
                                                            ? 'text-success'
                                                            : ($purchase->purchase_status->value == 'pending'
                                                                ? 'text-warning'
                                                                : 'text-danger') }}">
                                                        {{ __('purchase_status.' . $purchase->purchase_status->value) }}
                                                    </span>
                                                </td>
                                                <td style="font-size: 15px;align-content:center">
                                                    <span class="{{ $purchase->payment_status->value == 'paid'
                                                            ? 'text-success'
                                                            : ($purchase->payment_status->value == 'partial'
                                                                ? 'text-warning'
                                                                : 'text-danger') }}">
                                                        {{ __('common.' . $purchase->payment_status->value) }}
                                                    </span>
                                                </td>
                                                <td style="font-size: 15px;align-content:center">{{ \Carbon\Carbon::parse($purchase->purchased_at)->format('Y-m-d') }}</td>
                                                <td style="font-size: 15px;align-content:center">


                                                    <button
                                                        class="btn btn-warning w-100 edit-btn"
                                                        data-id="{{ $purchase->id }}">{{ __('common.edit') }}</button>
                                                    <button
                                                        class="btn btn-danger delete-btn w-100" style="text-"
                                                        data-id="{{ $purchase->id }}">{{ __('common.delete') }}</button>

                                                    <a role="button"
                                                        href="{{ route('admin.purchase.show', $purchase->id) }}"
                                                        class="btn btn-info w-100">
                                                        {{ __('purchase.view_purchase') }}
                                                    </a>
                                                </td>
                                            </tr>

                                            @empty

                                            <tr>

                                                <td colspan="11" class="text-center">

                                                    {{ __('purchase.no_purchases_found') }}

                                                </td>

                                            </tr>

                                            @endforelse
                                            @if($grandTotal > 0)

                                                <tr>

                                                    <th colspan="6" class="text-center">

                                                        {{ __('purchase.grand_total') }}

                                                    </th>

                                                    <th>

                                                        ${{ number_format($grandTotal,2) }}

                                                    </th>

                                                    <th colspan="4"></th>

                                                </tr>

                                                @endif
                                        </tbody>
                                    </table>
                                    <div id="pagination-wrapper" style="padding:10px;float:right;">
                                        {!! $purchases->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</main>


{{-- Create Modal --}}
<div class="modal fade" id="addPurchaseModal" tabindex="-1" aria-labelledby="addPurchaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPurchaseModalLabel">{{ __('purchase.add_purchase_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addPurchaseForm" method="POST" action="{{ route('admin.purchase.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label>{{ __('purchase.supplier') }}</label>

                        <select
                            class="form-control"
                            name="supplier_id"
                            required>

                            <option value="">{{ __('purchase.select_supplier') }}</option>

                            @foreach($suppliers as $id => $name)

                                <option value="{{ $id }}">

                                    {{ $name }}

                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">

                        <label>{{ __('purchase.invoice_number') }}</label>

                        <input
                            type="text"
                            class="form-control"
                            name="invoice_number">

                    </div>

                    <div class="row">

                        <div class="col-md-6">

                            <label>{{ __('purchase.purchase_date') }}</label>

                            <input
                                type="date"
                                class="form-control"
                                name="purchased_at"
                                value="{{ now()->format('Y-m-d') }}"
                                required>

                        </div>

                        <div class="col-md-6">

                            <label>{{ __('purchase.purchase_status') }}</label>

                            <select
                                class="form-control"
                                name="purchase_status">

                                @foreach(\App\Enums\PurchaseStatus::values() as $status)

                                    <option value="{{ $status }}">

                                        {{ __('purchase_status.' . $status) }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                    <div class="mt-3 row">

                        <div class="col-md-6">

                            <label>{{ __('purchase.payment_status') }}</label>

                            <select
                                class="form-control"
                                name="payment_status">

                                @foreach(\App\Enums\PaymentStatus::values() as $status)

                                    <option value="{{ $status }}">

                                        {{ __('common.' . $status) }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>


                    <div class="mt-3">

                        <label>{{ __('purchase.notes') }}</label>

                        <textarea
                            class="form-control"
                            rows="3"
                            name="notes"></textarea>

                    </div>

                    <hr>

                    <h5>{{ __('purchase.purchase_items') }}</h5>

                    <table
                        class="table table-bordered"
                        id="purchaseItemsTable">

                        <thead>

                            <tr>

                                <th width="15%">{{ __('purchase.product') }}</th>

                                <th width="15%">{{ __('purchase.quantity') }}</th>

                                <th width="15%">{{ __('purchase.cost') }}</th>

                                <th width="15%">{{ __('purchase.item_discount') }}</th>

                                <th width="15%">{{ __('purchase.item_tax') }}</th>

                                <th width="15%">{{ __('purchase.item_subtotal') }}</th>

                                <th>{{ __('common.actions') }}</th>

                            </tr>

                        </thead>

                        <tbody>

                        </tbody>

                    </table>

                    <button
                        type="button"
                        class="btn btn-success btn-sm"
                        id="addItem">

                        {{ __('purchase.add_product') }}

                    </button>

                    <hr>

                    <div class="row">

                        <div class="offset-md-8 col-md-4">

                            <div class="mb-2">

                                <label>{{ __('purchase.purchase_discount') }}</label>

                                <input
                                    type="number"
                                    step="0.01"
                                    value="0"
                                    id="purchase_discount"
                                    name="discount"
                                    class="form-control">

                            </div>

                            <div class="mb-2">

                                <label>{{ __('purchase.purchase_tax') }}</label>

                                <input
                                    type="number"
                                    step="0.01"
                                    value="0"
                                    id="purchase_tax"
                                    name="tax"
                                    class="form-control">

                            </div>

                            <div class="mb-2">

                                <label>{{ __('purchase.grand_total') }}</label>

                                <input
                                    type="text"
                                    id="grand_total"
                                    class="form-control"
                                    readonly>

                            </div>

                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('purchase.save_purchase') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editPurchaseModal" tabindex="-1" aria-labelledby="editPurchaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPurchaseModalLabel"> {{ __('purchase.edit_purchase_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPurchaseForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>{{ __('purchase.supplier') }}</label>

                        <select
                            class="form-control"
                            name="supplier_id"
                            id="edit_supplier_id"
                            required>

                            <option value="">{{ __('purchase.select_supplier') }}</option>

                            @foreach($suppliers as $id => $name)

                                <option value="{{ $id }}">

                                    {{ $name }}

                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">

                        <label>{{ __('purchase.invoice_number') }}</label>

                        <input
                            type="text"
                            class="form-control"
                            id="edit_invoice_number"
                            name="invoice_number">

                    </div>

                    <div class="row">

                        <div class="col-md-6">

                            <label>{{ __('purchase.purchase_date') }}</label>

                            <input
                                type="date"
                                class="form-control"
                                name="purchased_at"
                                id="edit_purchased_at"
                                value="{{ now()->format('Y-m-d') }}"
                                required>

                        </div>

                        <div class="col-md-6">

                            <label>{{ __('purchase.purchase_status') }}</label>

                            <select
                                class="form-control"
                                name="purchase_status"
                                id="edit_purchase_status">

                                @foreach(\App\Enums\PurchaseStatus::values() as $status)

                                    <option value="{{ $status }}">

                                       {{ __('purchase_status.' . $status) }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                    <div class="mt-3 row">

                        <div class="col-md-6">

                            <label>{{ __('purchase.payment_status') }}</label>

                            <select
                                class="form-control"
                                name="payment_status"
                                id="edit_payment_status">

                                @foreach(\App\Enums\PaymentStatus::values() as $status)

                                    <option value="{{ $status }}">

                                        {{ __('common.' . $status) }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>


                    <div class="mt-3">

                       <label>{{ __('purchase.notes') }}</label>

                        <textarea
                            class="form-control"
                            rows="3"
                            id="edit_notes"
                            name="notes"></textarea>

                    </div>

                    <hr>

                    <h5>{{ __('purchase.purchase_items') }}</h5>

                    <table
                        class="table table-bordered"
                       id="editPurchaseItemsTable">

                        <thead>

                            <tr>

                                <th width="16%">{{ __('purchase.product') }}</th>

                                <th width="16%">{{ __('purchase.quantity') }}</th>

                                <th width="16%">{{ __('purchase.cost') }}</th>

                                <th width="16%">{{ __('purchase.item_discount') }}</th>

                                <th width="16%">{{ __('purchase.item_tax') }}</th>

                                <th width="16%">{{ __('purchase.item_subtotal') }}</th>

                                <th></th>

                            </tr>

                        </thead>

                        <tbody>

                        </tbody>

                    </table>

                    <button
                        type="button"
                        class="btn btn-success btn-sm"
                        id="editAddItem">

                        {{ __('purchase.add_product') }}

                    </button>

                    <hr>

                    <div class="row">

                        <div class="offset-md-8 col-md-4">

                            <div class="mb-2">

                                <label>{{ __('purchase.purchase_discount') }}</label>

                                <input
                                    type="number"
                                    step="0.01"
                                    value="0"
                                    id="edit_purchase_discount"
                                    name="discount"
                                    class="form-control">

                            </div>

                            <div class="mb-2">

                                <label>{{ __('purchase.purchase_tax') }}</label>

                                <input
                                    type="number"
                                    step="0.01"
                                    value="0"
                                    id="edit_purchase_tax"
                                    name="tax"
                                    class="form-control">

                            </div>

                            <div class="mb-2">

                                <label>{{ __('purchase.grand_total') }}</label>

                                <input
                                    type="text"
                                   id="edit_grand_total"
                                    class="form-control"
                                    readonly>

                            </div>

                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('purchase.update_purchase') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

 <!-- Flash message content will be inserted here -->
<div class="flash-message alert alert-success" id="flash" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 9999; background-color: #d4edda; color: #155724; padding: 10px 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs/dayjs.min.js"></script>
<script type="text/javascript">

$(document).ready(function () {

    const purchaseStatusTranslations = {
    pending: "{{ __('purchase_status.pending') }}",
    received: "{{ __('purchase_status.received') }}",
    cancelled: "{{ __('purchase_status.cancelled') }}"
};

const paymentStatusTranslations = {
    paid: "{{ __('common.paid') }}",
    partial: "{{ __('common.partial') }}",
    unpaid: "{{ __('common.unpaid') }}"
};


    // ==========================
    // Variables
    // ==========================

    let timer;
    let rowIndex = 0;
    let editRowIndex = 0;



    // ==========================
    // Global Events
    // ==========================

    $(document).on('click', '.edit-btn', handleEdit);

    $(document).on('click', '.delete-btn', handleDelete);



    // ==========================
    // Fetch Purchases
    // ==========================

    function fetchPurchases(search = '', purchaseDate = '') {

        $.ajax({

            url: "{{ route('admin.purchase.data') }}",

            method: "GET",

            data: {

                search: search,

                purchase_date: purchaseDate

            },


            success: function(response) {


                let tableBody = '';

                let grandTotal = 0;



                if (!response.data || response.data.length === 0) {


                    tableBody = `

                        <tr>

                            <td colspan="11" class="text-center">

                               {{ __('purchase.no_purchases_found') }}

                            </td>

                        </tr>

                    `;


                } else {



                    $.each(response.data, function(index, purchase) {


                        grandTotal += parseFloat(purchase.total);



                        tableBody += `

                        <tr>


                            <td>
                                ${purchase.purchase_code}
                            </td>


                            <td>
                                ${purchase.supplier.name}
                            </td>


                            <td>
                                ${purchase.invoice_number ?? '-'}
                            </td>


                            <td>
                                $${parseFloat(purchase.subtotal).toFixed(2)}
                            </td>


                            <td>
                                $${parseFloat(purchase.discount).toFixed(2)}
                            </td>


                            <td>
                                $${parseFloat(purchase.tax).toFixed(2)}
                            </td>


                            <td>
                                $${parseFloat(purchase.total).toFixed(2)}
                            </td>



                            <td class="${
                                purchase.purchase_status === 'received'
                                ? 'text-success'
                                : purchase.purchase_status === 'pending'
                                ? 'text-warning'
                                : 'text-danger'
                            }">

                                ${purchaseStatusTranslations[purchase.purchase_status]}

                            </td>




                            <td class="${
                                purchase.payment_status === 'paid'
                                ? 'text-success'
                                : purchase.payment_status === 'partial'
                                ? 'text-warning'
                                : 'text-danger'
                            }">


                                ${paymentStatusTranslations[purchase.payment_status]}


                            </td>



                            <td>

                                ${dayjs(purchase.purchased_at)
                                    .format('YYYY-MM-DD')}

                            </td>



                            <td>


                                <button

                                    class="btn w-100 btn-warning edit-btn"

                                    data-id="${purchase.id}"

                                >

                                    {{ __('common.edit') }}

                                </button>



                                <button

                                    class="btn w-100 btn-danger delete-btn"

                                    data-id="${purchase.id}"

                                >

                                    {{ __('common.delete') }}
                                    

                                </button>

                                <a role="button" 
                                    href="${'{{ route('admin.purchase.show', ':id') }}'.replace(':id', purchase.id)}"
                                    class="btn btn-info w-100">
                                    {{ __('purchase.view_purchase') }}
                                </a>


                            </td>



                        </tr>

                        `;


                    });



                    tableBody += `

                    <tr>


                        <th colspan="6" class="text-center">

                            {{ __('purchase.grand_total') }}

                        </th>


                        <th>

                            $${grandTotal.toFixed(2)}

                        </th>


                        <th colspan="4"></th>


                    </tr>

                    `;


                }



                $("#purchase-table tbody")
                    .html(tableBody);


            },


            error:function(xhr){

                console.log(xhr.responseText);

            }


        });


    }





    // ==========================
    // Search
    // ==========================


    $("#purchase_date_search").on("change",function(){


        fetchPurchases(

            $("#search").val(),

            $(this).val()

        );


    });



    $("#search").on("keyup",function(){


        clearTimeout(timer);



        let value = $(this).val();



        if(value.trim() === ''){


            $("#pagination-wrapper")
                .show();


            fetchPurchases('','');


        }else{


            $("#pagination-wrapper")
                .hide();



            timer = setTimeout(function(){


                fetchPurchases(

                    value,

                    $("#purchase_date_search").val()

                );


            },300);



        }


    });






    // ==========================
    // Edit Purchase
    // ==========================


    function handleEdit(){


        let purchaseId = $(this).data("id");



        let url = "{{ route('admin.purchase.edit', ':id') }}"
                    .replace(':id',purchaseId);



        $.ajax({


            url:url,

            method:"GET",



            success:function(purchase){



                $("#editPurchaseForm")
                    .attr("data-id",purchase.id);



                $("#edit_supplier_id")
                    .val(purchase.supplier_id);



                $("#edit_invoice_number")
                    .val(purchase.invoice_number);



                $("#edit_purchased_at")
                    .val(
                        dayjs(purchase.purchased_at)
                        .format("YYYY-MM-DD")
                    );



                $("#edit_purchase_status")
                    .val(purchase.purchase_status);



                $("#edit_payment_status")
                    .val(purchase.payment_status);



                $("#edit_notes")
                    .val(purchase.notes);



                $("#edit_purchase_discount")
                    .val(purchase.discount);



                $("#edit_purchase_tax")
                    .val(purchase.tax);




                loadEditItems(purchase.items);



                editRowIndex = purchase.items.length;



                $("#editPurchaseModal")
                    .modal("show");



            },

            error:function(xhr){

                console.log(xhr.responseText);

            }


        });


    }







    // ==========================
    // Load Edit Items
    // ==========================


    function loadEditItems(items){


        $("#editPurchaseItemsTable tbody")
            .html("");



        editRowIndex = 0;



        $.each(items,function(index,item){



            createPurchaseItemRow(

                editRowIndex,

                "#editPurchaseItemsTable"

            );



            let row = $("#editPurchaseItemsTable tbody tr")
                        .last();




            row.find(".product")
                .val(item.product_id);



            row.find(".quantity")
                .val(item.quantity);



            row.find(".unit_cost")
                .val(item.unit_cost);



            row.find(".discount")
                .val(item.discount);



            row.find(".tax")
                .val(item.tax);



            row.find(".subtotal")
                .val(item.subtotal);



            editRowIndex++;


        });



        calculateEditGrandTotal();


    }






    // ==========================
    // Delete Purchase
    // ==========================


    function handleDelete(){


        let id = $(this).data("id");



        let url = "{{ route('admin.purchase.destroy',['purchase'=>':id']) }}"
                    .replace(':id',id);



        if(!confirm("{{ __('purchase.delete_confirmation') }}")){

            return;

        }




        $.ajax({


            url:url,

            method:"DELETE",



            data:{


                _token:"{{ csrf_token() }}"


            },



            success:function(response){



                $(".flash-message")

                    .removeClass("alert-success")

                    .addClass("alert-danger")

                    .text(response.success)

                    .fadeIn()

                    .delay(3000)

                    .fadeOut();



                location.reload();



            },


            error:function(xhr){

                console.log(xhr.responseText);

            }


        });


    }

        // ==========================
    // Create Purchase Item Row
    // ==========================


    function createPurchaseItemRow(index, tableId)
    {


        let row = `

        <tr>


            <td style="min-width:220px">


                <select

                    name="items[${index}][product_id]"

                    class="form-control product"

                    required

                >


                    <option value="">

                        {{ __('purchase.select_product') }}

                    </option>



                    @foreach($products as $product)


                    <option

                        value="{{ $product->id }}"

                        data-cost="{{ $product->cost_price }}"

                    >

                        {{ $product->name }}

                    </option>



                    @endforeach



                </select>


            </td>





            <td>


                <input

                    type="number"

                    class="form-control quantity"

                    name="items[${index}][quantity]"

                    value="1"

                    min="1"

                    required

                >


            </td>





            <td>


                <input

                    type="number"

                    class="form-control unit_cost"

                    name="items[${index}][unit_cost]"

                    value="0"

                    step="0.01"

                    required

                >


            </td>





            <td>


                <input

                    type="number"

                    class="form-control discount"

                    name="items[${index}][discount]"

                    value="0"

                    step="0.01"

                >


            </td>





            <td>


                <input

                    type="number"

                    class="form-control tax"

                    name="items[${index}][tax]"

                    value="0"

                    step="0.01"

                >


            </td>





            <td>


                <input

                    type="text"

                    class="form-control subtotal"

                    value="0.00"

                    readonly

                >


            </td>





            <td>


                <button

                    type="button"

                    class="btn btn-danger removeItem"

                >

                    X

                </button>


            </td>



        </tr>


        `;



        $(tableId+" tbody")
            .append(row);


    }






    // ==========================
    // Add Item Buttons
    // ==========================


    $("#addItem").click(function(){


        createPurchaseItemRow(

            rowIndex,

            "#purchaseItemsTable"

        );


        rowIndex++;


    });





    $("#editAddItem").click(function(){


        createPurchaseItemRow(

            editRowIndex,

            "#editPurchaseItemsTable"

        );


        editRowIndex++;


    });






    // ==========================
    // Remove Item
    // ==========================


    $(document).on(
        "click",
        ".removeItem",
        function(){


            let table = $(this)
                .closest("table")
                .attr("id");



            $(this)
                .closest("tr")
                .remove();




            if(table === "purchaseItemsTable"){


                calculateGrandTotal();


            }



            if(table === "editPurchaseItemsTable"){


                calculateEditGrandTotal();


            }



        }
    );








    // ==========================
    // Product Change
    // ==========================


    $(document).on(
        "change",
        ".product",
        function(){


            let cost = $(this)
                .find(":selected")
                .data("cost");



            let row = $(this)
                .closest("tr");



            row.find(".unit_cost")
                .val(cost);



            row.find(".quantity")
                .trigger("change");



        }
    );








    // ==========================
    // Calculate Row
    // ==========================


    $(document).on(

        "keyup change",

        ".quantity,.unit_cost,.discount,.tax,#purchase_discount,#purchase_tax,#edit_purchase_discount,#edit_purchase_tax",

        function(){



            let row = $(this)
                .closest("tr");



            if(row.length){



                let qty =
                    parseFloat(row.find(".quantity").val()) || 0;



                let cost =
                    parseFloat(row.find(".unit_cost").val()) || 0;



                let discount =
                    parseFloat(row.find(".discount").val()) || 0;



                let tax =
                    parseFloat(row.find(".tax").val()) || 0;




                let subtotal =
                    (qty * cost) - discount + tax;



                row.find(".subtotal")
                    .val(subtotal.toFixed(2));



            }



            calculateGrandTotal();

            calculateEditGrandTotal();



        }

    );








    // ==========================
    // Calculate Add Total
    // ==========================


    function calculateGrandTotal(){



        let subtotal = 0;



        $("#purchaseItemsTable .subtotal")
            .each(function(){



                subtotal += parseFloat($(this).val()) || 0;



            });



        let discount =

            parseFloat($("#purchase_discount").val()) || 0;



        let tax =

            parseFloat($("#purchase_tax").val()) || 0;




        let total =

            subtotal - discount + tax;




        $("#grand_total")
            .val(total.toFixed(2));



    }








    // ==========================
    // Calculate Edit Total
    // ==========================


    function calculateEditGrandTotal(){



        let subtotal = 0;



        $("#editPurchaseItemsTable .subtotal")
            .each(function(){



                subtotal += parseFloat($(this).val()) || 0;



            });



        let discount =

            parseFloat($("#edit_purchase_discount").val()) || 0;




        let tax =

            parseFloat($("#edit_purchase_tax").val()) || 0;




        let total =

            subtotal - discount + tax;




        $("#edit_grand_total")
            .val(total.toFixed(2));



    }








    // ==========================
    // Add Purchase Submit
    // ==========================


    $("#addPurchaseForm").submit(function(e){


        e.preventDefault();



        if($("#purchaseItemsTable tbody tr").length === 0){


            alert("{{ __('purchase.minimum_product') }}");

            return;


        }





        $.ajax({



            url:"{{ route('admin.purchase.store') }}",



            method:"POST",



            data:$(this).serialize(),



            success:function(response){



                $("#addPurchaseModal")
                    .modal("hide");



                $(".flash-message")

                    .removeClass("alert-danger")

                    .addClass("alert-success")

                    .text(response.success)

                    .fadeIn()

                    .delay(3000)

                    .fadeOut();



                window.location.href = "{{ route('admin.purchase.index') }}";



            },



            error:function(xhr){



                let errors = xhr.responseJSON.errors;



                let message = "";



                $.each(errors,function(key,value){


                    message += value[0]+"\n";


                });



                alert(message);



            }



        });



    });









    // ==========================
    // Edit Purchase Submit
    // ==========================


    $("#editPurchaseForm").submit(function(e){


        e.preventDefault();



        let id = $(this)
            .attr("data-id");



        let url = "{{ route('admin.purchase.update',':id') }}"
                    .replace(':id',id);




        $.ajax({



            url:url,

            method:"POST",



            data:$(this).serialize(),



            success:function(response){



                $("#editPurchaseModal")
                    .modal("hide");



                $(".flash-message")

                    .removeClass("alert-danger")

                    .addClass("alert-success")

                    .text(response.success)

                    .fadeIn()

                    .delay(3000)

                    .fadeOut();



                window.location.href = "{{ route('admin.purchase.index') }}";



            },



            error:function(xhr){



                let errors = xhr.responseJSON.errors;



                let message = "";



                $.each(errors,function(key,value){


                    message += value[0]+"\n";


                });



                alert(message);



            }



        });



    });

    const purchaseId = "{{ request('edit') }}";

    if (purchaseId) {

        setTimeout(function () {

            $(`.edit-btn[data-id="${purchaseId}"]`).trigger('click');

        }, 300);

    }


});
</script>



@endsection
