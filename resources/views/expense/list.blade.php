@extends('layouts.app')

@section('content')

<main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Expense</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Expense List
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
                                        <h4 class="mt-3 card-title btn">Search Expense</h4>
                                        <input
                                            type="text"
                                            id="search"
                                            class="form-control"
                                            placeholder="Search by expense number, title, vendor, receipt_number, reference_no, payment_method or status">

                                    </div>
                                    <div class="col-md-3">
                                        <label class="mt-4 form-label">Expense Date</label>
                                        <input
                                            type="date"
                                            id="expense_date_search"
                                            class="form-control">
                                    </div>

                            </div>
                            <div class="mb-4 card">
                                <div class="card-header">
                                    <h3 class="card-title">Expense List</h3>

                                    <div class="card-tools">
                                        <ul class="pagination pagination-sm float-end">

                                            <a href="{{ route('admin.expense.create') }}" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                                                Add Expense
                                            </a>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <table id="expense-table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th style="font-size: 15px">Expense No.</th>
                                                <th style="font-size: 15px">Category</th>
                                                <th style="font-size: 15px">Title</th>
                                                <th style="font-size: 15px">Vendor</th>
                                                <th style="font-size: 15px">Amount</th>
                                                <th style="font-size: 15px">Expense Date</th>
                                                <th style="font-size: 15px">Payment Method</th>
                                                <th style="font-size: 15px">Status</th>
                                                <th style="font-size: 15px">Created At</th>
                                                <th style="font-size: 15px">Updated At</th>
                                                <th style="font-size: 15px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalAmount = 0;
                                            @endphp
                                            @forelse($expenses as $expense)

                                            @php
                                                $totalAmount += $expense->amount;
                                            @endphp
                                                <tr>
                                                    <td>{{ $expense->expense_number }}</td>

                                                    <td>{{ $expense->category->name }}</td>

                                                    <td>{{ $expense->title }}</td>

                                                    <td>{{ $expense->vendor_name ?? '-' }}</td>

                                                   <td>${{ number_format((float)$expense->amount, 2) }}</td>

                                                    <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d') }}</td>

                                                   <td>{{ ucwords(str_replace('_', ' ', $expense->payment_method->value)) }}</td>
                                                    <td class="{{ $expense->status->value === 'paid'
                                                        ? 'text-success'
                                                        : ($expense->status->value === 'pending'
                                                            ? 'text-warning'
                                                            : 'text-danger') }}">
                                                        {{ ucfirst($expense->status->value) }}
                                                    </td>

                                                    <td>{{ $expense->created_at->format('Y-m-d H:i:s') }}</td>
                                                    <td>{{ $expense->updated_at->format('Y-m-d H:i:s') }}</td>
                                                    <td>
                                                        <button class="btn btn-sm edit-btn btn-warning" data-id="{{ $expense->id }}">Edit</button>
                                                        <button class="btn btn-sm delete-btn btn-danger" data-id="{{ $expense->id }}">Delete</button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center">No expenses found.</td>
                                                </tr>
                                            @endforelse
                                            @if(!empty($totalAmount))                                            <tr >
                                                <th colspan="2" class="text-center">Total Amount</th>
                                                <td>${{number_format($totalAmount,2)}}</td>
                                                <th colspan="3"></th>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <div id="pagination-wrapper" style="padding: 10px; float: right;">
                                        {!! $expenses->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</main>


{{-- Create Modal --}}
<div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addExpenseModalLabel">Add Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addExpenseForm" method="POST" action="{{ route('admin.expense.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label>Expense Category</label>

                        <select
                            class="form-control"
                            name="expense_category_id"
                            required>

                            <option value=''>select expense category</option>

                            @foreach($expenseCategories as $category)

                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Title</label>

                        <input
                            type="text"
                            class="form-control"
                            name="title"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>Description</label>

                        <textarea
                            class="form-control"
                            name="description"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Amount</label>

                        <input
                            type="number"
                            step="0.01"
                            class="form-control"
                            name="amount"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>Expense Date</label>

                        <input
                            type="date"
                            class="form-control"
                            name="expense_date"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>Payment Method</label>

                        <select
                            class="form-control"
                            name="payment_method">

                            @foreach(\App\Enums\PaymentMethod::values() as $method)

                                <option value="{{ $method }}">
                                    {{ ucwords(str_replace('_',' ',$method)) }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Vendor Name</label>

                        <input
                            type="text"
                            class="form-control"
                            name="vendor_name">
                    </div>

                    <div class="mb-3">
                        <label>Receipt Number</label>

                        <input
                            type="text"
                            class="form-control"
                            name="receipt_number">
                    </div>

                    <div class="mb-3">
                        <label>Reference Number</label>

                        <input
                            type="text"
                            class="form-control"
                            name="reference_no">
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                           @foreach(\App\Enums\ExpenseStatus::values() as $status)
                                <option value="{{ $status }}">
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editExpenseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Edit Expense</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

               <form id="editExpenseForm">
                     @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Expense Number</label>

                        <input
                            type="text"
                            id="edit_expense_number"
                            class="form-control"
                            readonly>
                    </div>


                     <div class="mb-3">
                        <label>Expense Category</label>

                        <select
                            class="form-control"
                            name="expense_category_id"
                            id="edit_expense_category_id"
                            required>

                            @foreach($expenseCategories as $category)

                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Title</label>

                        <input
                            type="text"
                            class="form-control"
                            name="title"
                            id="edit_title"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>Description</label>

                        <textarea
                            class="form-control"
                            id="edit_description"
                            name="description"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Amount</label>

                        <input
                            type="number"
                            step="0.01"
                            class="form-control"
                            name="amount"
                            id="edit_amount"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>Expense Date</label>

                        <input
                            type="date"
                            class="form-control"
                            name="expense_date"
                            id="edit_expense_date"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>Payment Method</label>

                        <select
                            class="form-control"
                            name="payment_method"
                            id="edit_payment_method">

                            @foreach(\App\Enums\PaymentMethod::values() as $method)

                                <option value="{{ $method }}">
                                    {{ ucwords(str_replace('_',' ',$method)) }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Vendor Name</label>

                        <input
                            type="text"
                            class="form-control"
                            id="edit_vendor_name"
                            name="vendor_name">
                    </div>

                    <div class="mb-3">
                        <label>Receipt Number</label>

                        <input
                            type="text"
                            class="form-control"
                            name="receipt_number"
                            id="edit_receipt_number">
                    </div>

                    <div class="mb-3">
                        <label>Reference Number</label>

                        <input
                            type="text"
                            class="form-control"
                            name="reference_no"
                            id="edit_reference_no">
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" name="status" id="edit_status">
                           @foreach(\App\Enums\ExpenseStatus::values() as $status)
                                <option value="{{ $status }}">
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
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

    $(document).ready(function() {
        $('.edit-btn').on('click', handleEdit);
        $('.delete-btn').on('click', handleDelete);




        function fetchExpenses(search = '',expenseDate = '') {
            $.ajax({
                url: "{{ route('admin.expense.data') }}",
                method: 'GET',
                data: {
                    search: search,
                    expense_date: expenseDate
                },
                success: function(response) {
                    let tableBody = '';

                    if (!response || response.length === 0) {
                        tableBody = `
                            <tr>
                                <td colspan="10" class="text-center">
                                    No expenses found.
                                </td>
                            </tr>
                        `;
                    } else {
                        let totalAmount = 0;
                        $.each(response, function(index, expense) {
                            let created_at = expense.created_at ? dayjs(expense.created_at).format('YYYY-MM-DD HH:mm:ss') : 'N/A';
                            let updated_at = expense.updated_at ? dayjs(expense.updated_at).format('YYYY-MM-DD HH:mm:ss') : 'N/A';
                              totalAmount += parseFloat(expense.amount || 0);
                            tableBody += `
                                <tr>
                                    <td>${expense.expense_number}</td>
                                    <td>${expense.category.name}</td>
                                    <td>${expense.title}</td>
                                    <td>${expense.vendor_name ?? '-'}</td>
                                    <td>${expense.amount}</td>
                                    <td>${dayjs(expense.expense_date).format('YYYY-MM-DD')}</td>
                                    <td>${expense.payment_method}</td>
                                    <td class="${
                                        expense.status === 'paid'
                                            ? 'text-success'
                                            : expense.status === 'pending'
                                                ? 'text-warning'
                                                : 'text-danger'
                                    }">
                                        ${
                                            expense.status === 'paid'
                                                ? 'Paid'
                                                : expense.status === 'pending'
                                                    ? 'Pending'
                                                    : 'Cancelled'
                                        }
                                    </td>
                                    <td>${created_at}</td>
                                    <td>${updated_at}</td>
                                    <td>
                                        <button class="btn btn-sm edit-btn btn-warning" data-id="${expense.id}">Edit</button>
                                        <button class="btn btn-sm delete-btn btn-danger" data-id="${expense.id}">Delete</button>
                                    </td>
                                </tr>
                            `;
                        });
                         if (totalAmount > 0) {
                            tableBody += `
                                <tr>
                                    <td colspan="2" class="text-center text-right"><strong>Total Amount:</strong></td>
                                    <td><strong>$${totalAmount.toFixed(2)}</strong></td>
                                    <td colspan="3"></td>
                                </tr>
                            `;
                        }
                    }

                    // Update the DOM after the if/else logic determines tableBody content
                    $('#expense-table tbody').html(tableBody);

                    // Re-bind event handlers
                    $('.edit-btn').on('click', handleEdit);
                    $('.delete-btn').on('click', handleDelete);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching expenses:', error);
                    console.error('Error fetching expenses:', xhr.responseText);
                }
            });
        }

        // search
        let timer;

        $("#expense_date_search").on("change", function () {

                fetchExpenses($("#search").val(), $(this).val());

            });

        $("#search").on("keyup",function(){

            clearTimeout(timer);

            let value=$(this).val();

            if (value.trim() === '') {
            fetchExpenses('', '');
            }
            else {
                $('#pagination-wrapper').hide();
                timer=setTimeout(function(){

                    fetchExpenses(value, $("#expense_date_search").val());

                },300);
            }



        });

        // edit
        function handleEdit(){

            const expenseId = $(this).data('id');

            const url = "{{ route('admin.expense.edit', ['expense' => ':id']) }}"
            .replace(':id', expenseId);

            $.ajax({
                method: 'GET',
                url: url,
                success:function(response){

                $("#edit_expense_number").val(response.expense_number);

                $("#edit_expense_category_id").val(response.expense_category_id);

                $("#edit_title").val(response.title);

                $("#edit_description").val(response.description);

                $("#edit_amount").val(response.amount);

                $("#edit_expense_date").val(response.expense_date ? dayjs(response.expense_date).format('YYYY-MM-DD') : '');

                $("#edit_payment_method").val(response.payment_method);

                $("#edit_vendor_name").val(response.vendor_name);

                $("#edit_receipt_number").val(response.receipt_number);

                $("#edit_reference_no").val(response.reference_no);

                $("#edit_status").val(response.status);



                $("#editExpenseForm")
                    .attr("data-id", expenseId);

                $("#editExpenseModal").modal("show");
            },
                error: function(xhr, status, error) {
                    console.error('Error fetching expense data:', error);
                    console.error('Error fetching expense data:', xhr.responseText);
                    }
                });
        }

        //delete
        function handleDelete(){

            let id = $(this).data("id");

            const url = "{{ route('admin.expense.destroy', ['expense' => ':id']) }}"
                .replace(':id', id);

            if(!confirm("Are you sure you want to delete this expense?")){
                return;
            }

            $.ajax({

                url: url,

                method: "DELETE",

                data:{
                    _token: "{{ csrf_token() }}",
                },

                success:function(response){

                    $("#flash")
                    .removeClass('alert-success alert-warning') // Clear previous alert classes
                    .addClass('alert-danger')
                    .css({
                        'background-color': '#f8d7da', // Bootstrap light danger red background
                        'color': '#721c24'             // Bootstrap dark danger text color
                    });

                    $(".flash-message")
                        .text(response.success)
                        .fadeIn()
                        .delay(3000)
                        .fadeOut();

                    fetchExpenses();

                },

                error:function(xhr){

                    console.log(xhr.responseText);

                }

            });
        }

        $('#addExpenseForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('admin.expense.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // Close the modal
                    $('#addExpenseModal').modal('hide');
                    // Reset the form
                    $('#addExpenseForm')[0].reset();
                    // Refresh the expense list

                    $('.flash-message').text(response.success).fadeIn().delay(3000).fadeOut();

                    fetchExpenses('','');
                },
                error: function(xhr, status, error) {
                    let errors = xhr.responseJSON.errors;
                    let erroeMessage = '';
                    $.each(errors, function(key, value) {
                        erroeMessage += value[0] + '\n';
                    });
                    alert('Error adding expense:\n' + erroeMessage);
                    console.error('Error adding expense:', error);
                    console.error('Error adding expense:', xhr.responseText);
                }
            });
        });

        $("#editExpenseForm").submit(function(e){

            e.preventDefault();

            let expenseId = $(this).attr("data-id");

            const url = "{{ route('admin.expense.update', ['expense' => ':id']) }}"
                .replace(':id', expenseId);

            $.ajax({

                url: url,

                method: "POST",

                data: $(this).serialize(),

                success:function(response){

                    $("#editExpenseModal").modal("hide");

                    fetchExpenses('','');

                    $("#flash")
                    .removeClass('alert-success alert-danger')
                    .addClass('alert-warning')
                    .css({
                        'background-color': '#fff3cd', // Bootstrap light warning yellow background
                        'color': '#856404'             // Bootstrap dark warning text color
                    });

                    $(".flash-message").removeClass('alert-success').addClass('alert-warning')
                        .text(response.success)
                        .fadeIn()
                        .delay(3000)
                        .fadeOut();

                },
                error: function(xhr, status, error) {
                    let errors = xhr.responseJSON.errors;
                    let erroeMessage = '';
                    $.each(errors, function(key, value) {
                        erroeMessage += value[0] + '\n';
                    });
                    alert('Error adding expense:\n' + erroeMessage);
                    console.error('Error adding expense:', error);
                    console.error('Error adding expense:', xhr.responseText);
                }

            });
        });

});


</script>

@endsection
