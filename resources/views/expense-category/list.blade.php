@extends('layouts.app')

@section('content')

<main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">{{ __('expense_category.page_title') }}</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                 <li class="breadcrumb-item"><a href="#">{{ __('common.home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ __('expense_category.expense_category_list') }}
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
                            <div class="mb-4 card">
                                <div class="card-header">
                                    <h3 class="card-title">Expenses Category List</h3>

                                    <div class="card-tools">
                                        <ul class="pagination pagination-sm float-end">

                                            <a href="{{ route('admin.expense-category.create') }}" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addExpenseCategoryModal">
                                               {{ __('expense_category.add_expense_category') }}
                                            </a>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <table id="expense-category-table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('expense_category.id') }}</th>
                                                <th>{{ __('expense_category.code') }}</th>
                                                <th>{{ __('expense_category.code') }}</th>
                                                <th>{{ __('expense_category.name') }}</th>
                                                <th>{{ __('common.status') }}</th>
                                                <th>{{ __('expense_category.created_at') }}</th>
                                                <th>{{ __('expense_category.updated_at') }}</th>
                                                <th>{{ __('common.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data will be populated by DataTables -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</main>

{{-- Create Modal --}}
<div class="modal fade" id="addExpenseCategoryModal" tabindex="-1" aria-labelledby="addExpenseCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addExpenseCategoryModalLabel">{{ __('expense_category.add_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addExpenseCategoryForm" method="POST" action="{{ route('admin.expense-category.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('expense_category.expense_category_name') }}</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('expense_category.code') }}</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('expense_category.status') }}</label>
                        <select class="form-control" id="status" name="status" required>
                            @foreach(\App\Enums\ExpenseCategoryStatus::values() as $status)
                                <option value="{{ $status }}">
                                    {{ __('common.' . $status) }}
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
<div class="modal fade" id="editExpenseCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>{{ __('expense_category.edit_title') }}</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="editExpenseCategoryForm">

                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="edit_name" class="form-label">{{ __('expense_category.expense_category_name') }}</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_code" class="form-label">{{ __('expense_category.expense_category_code') }}</label>
                        <input type="text" class="form-control" id="edit_code" name="code">
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">{{ __('expense_category.description') }}</label>
                        <textarea class="form-control" id="edit_description" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('expense_category.status') }}</label>
                        <select class="form-control" id="edit_status" name="status" required>
                            @foreach(\App\Enums\ExpenseCategoryStatus::values() as $status)
                                 <option value="{{ $status }}">
                                    {{ __('common.' . $status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn btn-primary">
                        {{ __('expense_category.update_expense_category') }}
                    </button>

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

        let url = '';
        @if(auth()->user()->hasRole('admin')) {
            url = "{{ route('admin.expense-category.data') }}";
        }@else {
             url = "{{ route('manager.expense-category.data') }}";
        }
        @endif

        fetchExpenseCategories();

        function fetchExpenseCategories() {
            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {

                    let tableBody = '';
                    $.each(response, function(index,expenseCategory) {

                        let created_at = expenseCategory.created_at ? dayjs(expenseCategory.created_at).format('YYYY-MM-DD HH:mm:ss') : 'N/A';
                        let updated_at = expenseCategory.updated_at ? dayjs(expenseCategory.updated_at).format('YYYY-MM-DD HH:mm:ss') : 'N/A';

                        tableBody += `
                            <tr>
                                 <td>${index + 1}</td>

                                <td>${expenseCategory.code}</td>

                                <td>${expenseCategory.name}</td>

                                <td>${expenseCategory.description ?? '-'}</td>

                                <td class="${expenseCategory.status === 'active'
                                    ? 'text-success'
                                    : 'text-warning'}">

                                    ${expenseCategory.status === 'active'
                                        ? 'Active'
                                        : 'Inactive'}

                                </td>

                                <td>${created_at}</td>

                                <td>${updated_at}</td>
                                <td>
                                    <button class="btn btn-sm edit-btn btn-warning " data-id="${expenseCategory.id}">{{ __('common.edit') }}</button>
                                    <button class="btn btn-sm delete-btn btn-danger " data-id="${expenseCategory.id}">{{ __('common.delete') }}</button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#expense-category-table tbody').html(tableBody);

                    $('.edit-btn').on('click', handleEdit);

                    $('.delete-btn').on('click', handleDelete);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching expense category:', error);
                    console.error('Error fetching expense category:', xhr.responseText);
                }
            });
        }

        // edit
        function handleEdit(){

            const expenseCategoryId = $(this).data('id');



            let editUrl = "";

            @if(auth()->user()->hasRole('admin')){
            editUrl = "{{ route('admin.expense-category.edit', ['expense_category' => ':id']) }}"
            .replace(':id', expenseCategoryId);
            }@else {
                editUrl = "{{ route('manager.expense-category.edit', ['expense_category' => ':id']) }}"
            .replace(':id', expenseCategoryId);
            }
            @endif

            $.ajax({
                method: 'GET',
                url: editUrl,
                success:function(response){

                $("#edit_name").val(response.name);

                $("#edit_code").val(response.code);

                $("#edit_description").val(response.description);

                $("#edit_status").val(response.status);

                $("#editExpenseCategoryForm")
                    .attr("data-id", expenseCategoryId);

                $("#editExpenseCategoryModal").modal("show");
            },
                error: function(xhr, status, error) {
                    console.error("{{ __('expense_category.error_fetching') }}", error);
                    console.error("{{ __('expense_category.error_fetching') }}", error);
                    }
                });
        }

        //delete
        function handleDelete(){

            let id = $(this).data("id");

            let deleteUrl = "";

            @if(auth()->user()->hasRole('admin')){
            deleteUrl = "{{ route('admin.expense-category.destroy', ['expense_category' => ':id']) }}"
                .replace(':id', id);
            }@else {
                deleteUrl = "{{ route('manager.expense-category.destroy', ['expense_category' => ':id']) }}"
                .replace(':id', id);
            }
            @endif

            if(!confirm("{{ __('expense_category.delete_confirmation') }}")){
                return;
            }

            $.ajax({

                url: deleteUrl,

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

                    fetchExpenseCategories();

                },

                error:function(xhr){

                    console.log(xhr.responseText);

                }

            });
        }

        $('#addExpenseCategoryForm').on('submit', function(e) {
            e.preventDefault();
            let storeUrl = "";

            @if(auth()->user()->hasRole('admin')){
            storeUrl = "{{ route('admin.expense-category.store') }}";
            }@else {

                storeUrl = "{{ route('manager.expense-category.store') }}";
            }@endif

            $.ajax({
                url: storeUrl,
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // Close the modal
                    $('#addExpenseCategoryModal').modal('hide');
                    // Reset the form
                    $('#addExpenseCategoryForm')[0].reset();
                    // Refresh the expense category list

                    $('#flash')
                        .removeClass('alert-danger alert-warning')
                        .addClass('alert-success')
                        .css({
                            'background-color': '#d4edda',
                            'color': '#155724'
                        });

                    $('.flash-message')
                        .text(response.success)
                        .fadeIn()
                        .delay(3000)
                        .fadeOut();

                    fetchExpenseCategories();
                },
                error: function(xhr, status, error) {
                    let errors = xhr.responseJSON.errors;
                    let erroeMessage = '';
                    $.each(errors, function(key, value) {
                        erroeMessage += value[0] + '\n';
                    });
                    alert("{{ __('expense_category.error_adding') }}\n" + erroeMessage);
                    console.error("{{ __('expense_category.error_fetching') }}", error);
                    console.error('Error adding expense category:', xhr.responseText);
                }
            });
        });

        $("#editExpenseCategoryForm").submit(function(e){

            e.preventDefault();

            let expenseCategoryId = $(this).attr("data-id");

            let updateUrl = "";

            @if(auth()->user()->hasRole('admin')){
            updateUrl = "{{ route('admin.expense-category.update', ['expense_category' => ':id']) }}"
                .replace(':id', expenseCategoryId);

            }@else {
                updateUrl = "{{ route('manager.expense-category.update', ['expense_category' => ':id']) }}"
                .replace(':id', expenseCategoryId);
            }
            @endif

            

            $.ajax({

                url: updateUrl,

                method: "POST",

                data: $(this).serialize(),

                success:function(response){

                    $("#editExpenseCategoryModal").modal("hide");

                    fetchExpenseCategories();

                    $("#flash")
                    .removeClass('alert-success')
                    .addClass('alert-warning')
                    .css({
                        'background-color': '#fff3cd', // Bootstrap light warning yellow background
                        'color': '#856404'             // Bootstrap dark warning text color
                    });

                    $(".flash-message")
                        .text(response.success)
                        .fadeIn()
                        .delay(3000)
                        .fadeOut();

                },

                error:function(xhr,error){
                    let errors = xhr.responseJSON.errors;
                    let erroeMessage = '';
                    $.each(errors, function(key, value) {
                        erroeMessage += value[0] + '\n';
                    });
                    alert('Error adding expense category:\n' + erroeMessage);
                    console.log(xhr.responseText);

                }

            });
        });

});


</script>

@endsection
