@extends('layouts.app')

@section('content')

<main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">{{ __('expense.page_title') }}</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">{{ __('common.home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ __('expense.expense_list') }}
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
                                        <h4 class="mt-3 card-title btn">{{ __('expense.search_expense') }}</h4>
                                        <input
                                            type="text"
                                            id="search"
                                            class="form-control"
                                           placeholder="{{ __('expense.search_placeholder') }}">

                                    </div>
                                    <div class="col-md-3">
                                        <label class="mt-4 form-label">{{ __('expense.expense_date') }}</label>
                                        <input
                                            type="date"
                                            id="expense_date_search"
                                            class="form-control">
                                    </div>

                            </div>
                            <div class="mb-4 card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('expense.expense_list') }}</h3>

                                    <div class="card-tools">
                                        <ul class="pagination pagination-sm float-end">

                                            <a href="{{ route('admin.expense.create') }}" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                                                {{ __('expense.add_expense') }}
                                            </a>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <table id="expense-table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th style="font-size: 15px">{{ __('expense.expense_no') }}</th>
                                                <th style="font-size: 15px">{{ __('expense.category') }}</th>
                                                <th style="font-size: 15px">{{ __('expense.title') }}</th>
                                                <th style="font-size: 15px">{{ __('expense.vendor') }}</th>
                                                <th style="font-size: 15px">{{ __('expense.amount') }}</th>
                                                <th style="font-size: 15px">{{ __('expense.expense_date') }}</th>
                                                <th style="font-size: 15px">{{ __('expense.payment_method') }}</th>
                                                <th style="font-size: 15px">{{ __('expense.status') }}</th>
                                                <th style="font-size: 15px">{{ __('expense.created_at') }}</th>
                                                <th style="font-size: 15px">{{ __('expense.updated_at') }}</th>
                                                <th style="font-size: 15px">{{ __('common.actions') }}</th>
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
                                                        {{ __('expense_status.' . $expense->status->value) }}
                                                    </td>

                                                    <td>{{ $expense->created_at->format('Y-m-d H:i:s') }}</td>
                                                    <td>{{ $expense->updated_at->format('Y-m-d H:i:s') }}</td>
                                                    <td>
                                                        <button class="btn btn-sm edit-btn btn-warning" data-id="{{ $expense->id }}">{{ __('common.edit') }}</button>
                                                        <button class="btn btn-sm delete-btn btn-danger" data-id="{{ $expense->id }}">{{ __('common.delete') }}</button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center">{{ __('expense.no_expenses_found') }}</td>
                                                </tr>
                                            @endforelse
                                            @if(!empty($totalAmount))                                            <tr >
                                                <th colspan="2" class="text-center">{{ __('expense.total_amount') }}</th>
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
                <h5 class="modal-title" id="addExpenseModalLabel"> {{ __('expense.add_expense_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addExpenseForm" method="POST" action="{{ route('admin.expense.store') }}">
                    @csrf

                    <div class="mb-3">
                       <label>{{ __('expense.expense_category') }}</label>

                        <select
                            class="form-control"
                            name="expense_category_id"
                            required>

                            <option value="">
                                {{ __('expense.select_expense_category') }}
                            </option>

                            @foreach($expenseCategories as $category)

                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">
                        <label>{{ __('expense.title') }}</label>

                        <input
                            type="text"
                            class="form-control"
                            name="title"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>{{ __('expense.description') }}</label>

                        <textarea
                            class="form-control"
                            name="description"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>{{ __('expense.amount') }}</label>

                        <input
                            type="number"
                            step="0.01"
                            class="form-control"
                            name="amount"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>{{ __('expense.expense_date') }}</label>

                        <input
                            type="date"
                            class="form-control"
                            name="expense_date"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>{{ __('expense.payment_method') }}</label>

                        <select
                            class="form-control"
                            name="payment_method">

                            @foreach(\App\Enums\PaymentMethod::values() as $method)

                                <option value="{{ $method }}">
                                     {{ $method }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">
                        <label>{{ __('expense.vendor_name') }}</label>

                        <input
                            type="text"
                            class="form-control"
                            name="vendor_name">
                    </div>

                    <div class="mb-3">
                        <label>{{ __('expense.receipt_number') }}</label>

                        <input
                            type="text"
                            class="form-control"
                            name="receipt_number">
                    </div>

                    <div class="mb-3">
                        <label>{{ __('expense.reference_number') }}</label>

                        <input
                            type="text"
                            class="form-control"
                            name="reference_no">
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('expense.status') }}</label>
                        <select class="form-control" id="status" name="status">
                           @foreach(\App\Enums\ExpenseStatus::values() as $status)
                                <option value="{{ $status }}">
                                {{ __('expense_status.' . $status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('common.save') }}</button>
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
                <h5>{{ __('expense.edit_expense_title') }}</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

               <form id="editExpenseForm">
                     @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>{{ __('expense.expense_number') }}</label>

                        <input
                            type="text"
                            id="edit_expense_number"
                            class="form-control"
                            readonly>
                    </div>


                     <div class="mb-3">
                        <label>{{ __('expense.expense_category') }}</label>

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
                        <label>{{ __('expense.title') }}</label>

                        <input
                            type="text"
                            class="form-control"
                            name="title"
                            id="edit_title"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>{{ __('expense.description') }}</label>

                        <textarea
                            class="form-control"
                            id="edit_description"
                            name="description"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>{{ __('expense.amount') }}</label>

                        <input
                            type="number"
                            step="0.01"
                            class="form-control"
                            name="amount"
                            id="edit_amount"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>{{ __('expense.expense_date') }}</label>

                        <input
                            type="date"
                            class="form-control"
                            name="expense_date"
                            id="edit_expense_date"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>{{ __('expense.payment_method') }}</label>

                        <select
                            class="form-control"
                            name="payment_method"
                            id="edit_payment_method">

                            @foreach(\App\Enums\PaymentMethod::values() as $method)

                                <option value="{{ $method }}">
                                    {{ __('payment_method.' . $method) }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">
                        <label>{{ __('expense.vendor_name') }}</label>

                        <input
                            type="text"
                            class="form-control"
                            id="edit_vendor_name"
                            name="vendor_name">
                    </div>

                    <div class="mb-3">
                        <label>{{ __('expense.receipt_number') }}</label>

                        <input
                            type="text"
                            class="form-control"
                            name="receipt_number"
                            id="edit_receipt_number">
                    </div>

                    <div class="mb-3">
                        <label>{{ __('expense.reference_number') }}</label>

                        <input
                            type="text"
                            class="form-control"
                            name="reference_no"
                            id="edit_reference_no">
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('expense.status') }}</label>
                        <select class="form-control" name="status" id="edit_status">
                           @foreach(\App\Enums\ExpenseStatus::values() as $status)
                                <option value="{{ $status }}">
                                   {{ __('expense_status.' . $status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('common.update') }}</button>
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
<script>
    const expenseStatusTranslations = {
        paid: "{{ __('expense_status.paid') }}",
        pending: "{{ __('expense_status.pending') }}",
        cancelled: "{{ __('expense_status.cancelled') }}"
    };

    const paymentMethodTranslations = {
        cash: "{{ __('payment_method.cash') }}",
        credit_card: "{{ __('payment_method.credit_card') }}",
        debit_card: "{{ __('payment_method.debit_card') }}",
        bank_transfer: "{{ __('payment_method.bank_transfer') }}",
        cheque: "{{ __('payment_method.cheque') }}",
        mobile_payment: "{{ __('payment_method.mobile_payment') }}",
        other: "{{ __('payment_method.other') }}"
    };
</script>
<script type="text/javascript">

    $(document).ready(function() {
        $('.edit-btn').on('click', handleEdit);
        $('.delete-btn').on('click', handleDelete);




        function fetchExpenses(search = '',expenseDate = '') {
            let url = '';
            @if(auth()->user()->hasRole('admin')){
                url = "{{ route('admin.expense.data') }}";
            }@else {
                url = "{{ route('manager.expense.data') }}";
            }
            @endif

            $.ajax({
                url: url,
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
                                   {{ __('expense.no_expenses_found') }}
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
                                    <td>${paymentMethodTranslations[expense.payment_method] ?? expense.payment_method}</td>
                                    <td class="${
                                        expense.status === 'paid'
                                            ? 'text-success'
                                            : expense.status === 'pending'
                                                ? 'text-warning'
                                                : 'text-danger'
                                    }">
                                        ${expenseStatusTranslations[expense.status] ?? expense.status}
                                    </td>
                                    <td>${created_at}</td>
                                    <td>${updated_at}</td>
                                    <td>
                                        <button class="btn btn-sm edit-btn btn-warning" data-id="${expense.id}">{{ __('common.edit') }}
</button>
                                        <button class="btn btn-sm delete-btn btn-danger" data-id="${expense.id}">{{ __('common.delete') }}</button>
                                    </td>
                                </tr>
                            `;
                        });
                         if (totalAmount > 0) {
                            tableBody += `
                                <tr>
                                    <td colspan="2" class="text-center text-right"><strong>{{ __('expense.total_amount') }}</strong></td>
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
                    console.error("{{ __('expense.error_fetching') }}", error);
                    console.error("{{ __('expense.error_fetching') }}", error);
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

            let url = '';
            @if(auth()->user()->hasRole('admin')){
                url = "{{ route('admin.expense.edit', ['expense' => ':id']) }}"
            .replace(':id', expenseId);
            }@else {
                url = "{{ route('manager.expense.edit', ['expense' => ':id']) }}"
            .replace(':id', expenseId);
            }
            @endif

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

            let url = '';
            @if(auth()->user()->hasRole('admin')){
                url = "{{ route('admin.expense.destroy', ['expense' => ':id']) }}"
                .replace(':id', id);
            }@else {
                url = "{{ route('manager.expense.destroy', ['expense' => ':id']) }}"
                .replace(':id', id);
            }
            @endif
 

            if(!confirm("{{ __('expense.delete_confirmation') }}")){
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

                    fetchExpenses('','');


                },

                error:function(xhr){

                    console.log(xhr.responseText);

                }

            });
        }

        $('#addExpenseForm').on('submit', function(e) {
            e.preventDefault();

            let url = '';
            @if(auth()->user()->hasRole('admin')){
                url = "{{ route('admin.expense.store') }}";
            }@else {
                url = "{{ route('manager.expense.store') }}";
            }
            @endif

            $.ajax({
                url: url,
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
                    alert("{{ __('expense.error_adding') }}\n" + erroeMessage);
                    console.error('Error adding expense:', error);
                    console.error('Error adding expense:', xhr.responseText);
                }
            });
        });

        $("#editExpenseForm").submit(function(e){

            e.preventDefault();

            let expenseId = $(this).attr("data-id");

            let url = '';
            @if(auth()->user()->hasRole('admin')){
                url = "{{ route('admin.expense.update', ['expense' => ':id']) }}"
                .replace(':id', expenseId);
            }@else {
                url = "{{ route('manager.expense.update', ['expense' => ':id']) }}"
                .replace(':id', expenseId);
            }
            @endif

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
                    alert("{{ __('expense.error_updating') }}\n" + erroeMessage);
                    console.error('Error adding expense:', error);
                    console.error('Error adding expense:', xhr.responseText);
                }

            });
        });

});


</script>

@endsection
