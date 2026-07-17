<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Page
    |--------------------------------------------------------------------------
    */

    'title' => 'Sales',
    'sale_list' => 'Sale List',
    'search_sale' => 'Search Sale',

    /*
    |--------------------------------------------------------------------------
    | Breadcrumb
    |--------------------------------------------------------------------------
    */

    'home' => 'Home',
    'sales' => 'Sales',

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    'search_placeholder' => 'Search by Sale Code, Invoice No, Customer Name, Sale Status or Payment Status',
    'sale_date' => 'Sale Date',

    /*
    |--------------------------------------------------------------------------
    | Buttons
    |--------------------------------------------------------------------------
    */

    'add_sale' => 'Add Sale',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'view' => 'View',
    'refund' => 'Refund',
    'save_sale' => 'Save Sale',
    'update_sale' => 'Update Sale',
    'add_payment' => 'Add Payment',
    'process_refund' => 'Process Refund',

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    'sale_code' => 'Sale Code',
    'customer' => 'Customer',
    'invoice_number' => 'Invoice No.',
    'subtotal' => 'Subtotal',
    'discount' => 'Discount',
    'tax' => 'Tax',
    'total' => 'Total',
    'paid' => 'Paid',
    'due' => 'Due',
    'sale_status' => 'Sale Status',
    'payment_status' => 'Payment Status',
    'sale_date_column' => 'Sale Date',
    'actions' => 'Actions',

    /*
    |--------------------------------------------------------------------------
    | Customer
    |--------------------------------------------------------------------------
    */

    'walk_in_customer' => 'Walk In Customer',
    'select_customer' => 'Select Customer',

    /*
    |--------------------------------------------------------------------------
    | Sale Form
    |--------------------------------------------------------------------------
    */

    'customer_label' => 'Customer',
    'invoice_label' => 'Invoice Number',
    'sale_date_label' => 'Sale Date',
    'sale_status_label' => 'Sale Status',

    /*
    |--------------------------------------------------------------------------
    | Items
    |--------------------------------------------------------------------------
    */

    'sale_items' => 'Sale Items',
    'product' => 'Product',
    'quantity' => 'Qty',
    'price' => 'Price',
    'unit_price' => 'Unit Price',
    'item_discount' => 'Discount',
    'item_tax' => 'Tax',
    'item_subtotal' => 'Subtotal',
    'add_product' => 'Add Product',

    /*
    |--------------------------------------------------------------------------
    | Totals
    |--------------------------------------------------------------------------
    */

    'grand_total' => 'Grand Total',

    /*
    |--------------------------------------------------------------------------
    | Payment
    |--------------------------------------------------------------------------
    */

    'payment' => 'Payment',
    'payment_summary' => 'Payment Summary',
    'payment_history' => 'Payment History',
    'payment_code' => 'Payment Code',
    'amount' => 'Amount',
    'method' => 'Method',
    'reference' => 'Reference',
    'status' => 'Status',

    /*
    |--------------------------------------------------------------------------
    | Refund
    |--------------------------------------------------------------------------
    */

    'refund_sale' => 'Refund Sale',
    'sale_total' => 'Sale Total',
    'already_refunded' => 'Already Refunded',
    'items_to_refund' => 'Items to Refund',
    'purchased' => 'Purchased',
    'refunded' => 'Already Refunded',
    'refund_qty' => 'Refund Qty',
    'restock' => 'Restock',
    'refund_amount' => 'Refund Amount',
    'refund_method' => 'Refund Method',
    'refund_total' => 'Total Refund Amount',
    'refund_reason' => 'Reason',
    'refund_history' => 'Refund History',
    'refund_code' => 'Code',

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    'notes' => 'Notes',
    'date' => 'Date',
    'reason' => 'Reason',
    'no_sales' => 'No sales found.',
    'no_payments' => 'No payments yet.',
    'no_refunds' => 'No refunds yet.',

    /*
    |--------------------------------------------------------------------------
    | Confirmation
    |--------------------------------------------------------------------------
    */

    'delete_confirmation' => 'Are you sure you want to delete this sale?',
    'refund_validation' => 'Please enter a quantity to refund for at least one item.',

    /*
    |--------------------------------------------------------------------------
    | Errors
    |--------------------------------------------------------------------------
    */

    'unable_load_sale' => 'Unable to load sale.',
    'unable_load_refund' => 'Unable to load sale for refund.',
    'refund_error' => 'Something went wrong processing the refund.',
    'something_wrong' => 'Something went wrong.',

    //status------------------------------------------
   /*
|--------------------------------------------------------------------------
| Sale Status
|--------------------------------------------------------------------------
*/

'draft' => 'Draft',
'pending' => 'Pending',
'completed' => 'Completed',
'cancelled' => 'Cancelled',
'partially_refunded' => 'Partially Refunded',
'refunded' => 'Refunded',

/*
|--------------------------------------------------------------------------
| Payment Status
|--------------------------------------------------------------------------
*/

'paid' => 'Paid',
'partial' => 'Partial',
'unpaid' => 'Unpaid',

/*
|--------------------------------------------------------------------------
| Payment Methods
|--------------------------------------------------------------------------
*/

'cash' => 'Cash',
'card' => 'Card',
'bank_transfer' => 'Bank Transfer',
'mobile_wallet' => 'Mobile Wallet',
'credit' => 'Credit',
];
