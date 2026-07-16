@extends('layouts.app')

@section('content')

<main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">{{ __('customer.page_title') }}</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">{{ __('common.home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ __('customer.customer_list') }}
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

                                    <div class="col-md-6">
                                        <h4 class="mt-3 card-title btn">{{ __('customer.search_customer') }}</h4>
                                        <input
                                            type="text"
                                            id="search"
                                            class="form-control"
                                            placeholder="{{ __('customer.search_placeholder') }}">

                                    </div>

                                </div>
                            <div class="mb-4 card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('customer.customer_list') }}</h3>

                                    <div class="card-tools">
                                        <ul class="pagination pagination-sm float-end">

                                            <a href="{{ route('admin.customer.create') }}" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                                {{ __('customer.add_customer') }}
                                            </a>
                                            &nbsp;
                                            &nbsp;
                                            &nbsp;
                                            <a href="{{ route('admin.report.customer') }}" class="btn btn-secondary btn-sm" >
                                                {{ __('customer.customer_report') }}
                                            </a>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <table id="customer-table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('customer.code') }}</th>
                                                <th>{{ __('customer.customer') }}</th>
                                                <th>{{ __('customer.phone') }}</th>
                                                <th>{{ __('customer.email') }}</th>
                                                <th>{{ __('customer.reward_points') }}</th>
                                                <th>{{ __('customer.credit_limit') }}</th>
                                                <th>{{ __('customer.status') }}</th>
                                                <th>{{ __('common.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($customers as $customer)
                                                <tr>
                                                    <td>{{ $customer->customer_code }}</td>
                                                    <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                                                    <td>{{ $customer->phone }}</td>
                                                    <td>{{ $customer->email }}</td>
                                                    <td>{{ $customer->reward_points }}</td>
                                                    <td>{{ $customer->credit_limit }}</td>
                                                    <td class="{{ $customer->status->value === 'active' ? 'text-success' : ($customer->status->value === 'inactive' ? 'text-warning' : 'text-danger') }}">
                                                        {{ ucfirst($customer->status->value) }}
                                                    </td>
                                                    <td style="text-align:center">
                                                        <button class="btn btn-sm edit-btn btn-warning" data-id="{{ $customer->id }}">{{ __('common.edit') }}</button>
                                                        <button class="btn btn-sm delete-btn btn-danger" data-id="{{ $customer->id }}">{{ __('common.delete') }}</button>
                                                        <button class="btn btn-sm add-credit-btn btn-info" data-id="{{ $customer->id }}">{{ __('customer.add_credit') }}</button>
                                                        <button class="btn btn-sm pdf-btn btn-secondary" data-id="{{ $customer->id }}">{{ __('customer.report') }}</button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">No customers found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div id="pagination-wrapper" style="padding: 10px; float: right;">
                                        {!! $customers->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</main>


{{-- Create Modal --}}
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerModalLabel">{{ __('customer.add_customer') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCustomerForm" method="POST" action="{{ route('admin.customer.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="first_name" class="form-label">{{ __('customer.first_name') }}</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">{{ __('customer.last_name') }}</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="company_name" class="form-label">{{ __('customer.company_name') }}</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" >
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('customer.email') }}</label>
                        <input type="email" class="form-control" id="email" name="email" >
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">{{ __('customer.phone') }}</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">{{ __('customer.date_of_birth') }}</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" >
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">{{ __('customer.address') }}</label>
                        <textarea class="form-control" id="address" name="address"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="credit_limit" class="form-label">{{ __('customer.credit_limit') }}</label>
                        <input type="number" class="form-control" id="credit_limit" name="credit_limit" >
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">{{ __('customer.notes') }}</label>
                        <textarea class="form-control" id="notes" name="notes"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('customer.status') }}</label>
                        <select class="form-control" id="status" name="status">
                            @foreach(App\Enums\CustomerStatus::values() as $status)
                                <option value="{{ $status }}">
                                    {{ $status }}
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
<div class="modal fade" id="editCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>{{ __('customer.edit_customer') }}</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

               <form id="editCustomerForm">
                     @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label>{{ __('customer.code') }}</label>

                        <input
                            type="text"
                            id="edit_customer_code"
                            class="form-control"
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label for="first_name" class="form-label">{{ __('customer.first_name') }}</label>
                        <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">{{ __('customer.last_name') }}</label>
                        <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="company_name" class="form-label">{{ __('customer.company_name') }}</label>
                        <input type="text" class="form-control" id="edit_company_name" name="company_name" >
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('customer.email') }}</label>
                        <input type="email" class="form-control" id="edit_email" name="email" >
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">{{ __('customer.phone') }}</label>
                        <input type="tel" class="form-control" id="edit_phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">{{ __('customer.date_of_birth') }}</label>
                        <input type="date" class="form-control" id="edit_date_of_birth" name="date_of_birth" >
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">{{ __('customer.address') }}</label>
                        <textarea class="form-control" id="edit_address" name="address"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="credit_limit" class="form-label">{{ __('customer.credit_limit') }}</label>
                        <input type="number" class="form-control" id="edit_credit_limit" name="credit_limit" >
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">{{ __('customer.notes') }}</label>
                        <textarea class="form-control" id="edit_notes" name="notes"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('customer.status') }}</label>
                        <select class="form-control" id="edit_status" name="status">
                            <option value="{{ App\Enums\CustomerStatus::Active->value }}">Active</option>
                            <option value="{{ App\Enums\CustomerStatus::Inactive->value }}">Inactive</option>
                            <option value="{{ App\Enums\CustomerStatus::Blocked->value }}">Blocked</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('common.update') }}</button>
                </form>


            </div>

        </div>
    </div>
</div>

{{-- Add Credit Modal --}}
<div class="modal fade" id="addCreditModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>{{ __('customer.add_credit') }}</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addCreditForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __('customer.customer') }}</label>
                        <input type="text" class="form-control" id="credit_customer_name" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('customer.current_credit_limit') }}</label>
                        <input type="text" class="form-control" id="credit_current_limit" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="credit_limit" class="form-label">{{ __('customer.amount_to_add') }}</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="credit_limit" name="credit_limit" required>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('customer.add_credit') }}</button>
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
        $('.add-credit-btn').on('click', handleAddCredit);
        $('.pdf-btn').on('click', handlePdf);


        function fetchCustomers(search = '') {
            $.ajax({
                url: "{{ route('admin.customer.data') }}",
                method: 'GET',
                data: {
                    search: search
                },
                success: function(response) {
                    let tableBody = '';

                    if (!response || response.length === 0) {
                        tableBody = `
                            <tr>
                                <td colspan="8" class="text-center">
                                   '{{ __("customer.no_customers_found") }}'
                                </td>
                            </tr>
                        `;
                    } else {
                        $.each(response, function(index, customer) {
                            let created_at = customer.created_at ? dayjs(customer.created_at).format('YYYY-MM-DD HH:mm:ss') : 'N/A';
                            let updated_at = customer.updated_at ? dayjs(customer.updated_at).format('YYYY-MM-DD HH:mm:ss') : 'N/A';

                            tableBody += `
                                <tr>
                                    <td>${customer.customer_code}</td>       
                                    <td>${customer.first_name} ${customer.last_name}</td>
                                    <td>${customer.phone}</td>
                                    <td>${customer.email}</td>
                                    <td>${customer.reward_points}</td>
                                    <td>${customer.credit_limit}</td>
                                    <td class="${
                                        customer.status === 'active' ? 'text-success' : customer.status === 'inactive' ? 'text-warning' : 'text-danger'
                                    }">
                                        ${
                                            customer.status === 'active' ? '{{ __("common.active") }}' : customer.status === 'inactive' ? '{{ __("common.inactive") }}' : '{{ __("common.blocked") }}'
                                        }
                                    </td>
                                    <td style="text-align:center">
                                        <button class="btn btn-sm edit-btn btn-warning" data-id="${customer.id}">{{ __("common.edit") }}</button>
                                        <button class="btn btn-sm delete-btn btn-danger" data-id="${customer.id}">{{ __("common.delete") }}</button>
                                        <button class="btn btn-sm add-credit-btn btn-info" data-id="${customer.id}">{{ __("customer.add_credit") }}</button>
                                        <button class="btn btn-sm pdf-btn btn-secondary" data-id="${customer.id}">{{ __("customer.report") }}</button>

                                    </td>
                                </tr>
                            `;
                        });
                    }

                    // Update the DOM after the if/else logic determines tableBody content
                    $('#customer-table tbody').html(tableBody);

                    // Re-bind event handlers
                    $('.edit-btn').on('click', handleEdit);
                    $('.delete-btn').on('click', handleDelete);
                    $('.add-credit-btn').on('click', handleAddCredit);
                    $('.pdf-btn').on('click', handlePdf);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching customers:', error);
                    console.error('Error fetching customers:', xhr.responseText);
                }
            });
        }

        // search
        let timer;
        $("#search").on("keyup",function(){

            clearTimeout(timer);

            let value=$(this).val();

            if (value.trim() === '') {
            fetchCustomers('');
            }
            else {
                $('#pagination-wrapper').hide();
                timer=setTimeout(function(){

                    fetchCustomers(value);

                },300);
            }



        });

        // add credit
        $('.add-credit-btn').on('click', handleAddCredit);

        function handleAddCredit(){
            const customerId = $(this).data('id');
            const row = $(this).closest('tr');

            $('#credit_customer_name').val(row.find('td').eq(1).text());
            $('#credit_current_limit').val(row.find('td').eq(5).text());
            $('#credit_limit').val('');

            $('#addCreditForm').attr('data-id', customerId);
            $('#addCreditModal').modal('show');
        }

        $('#addCreditForm').submit(function(e){
            e.preventDefault();

            let customerId = $(this).attr('data-id');

            const url = "{{ route('admin.customer.addCredit', ['customer' => ':id']) }}"
                .replace(':id', customerId);

            $.ajax({
                url: url,
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#addCreditModal').modal('hide');

                    $('#addCreditForm')[0].reset();

                    fetchCustomers();

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
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = '';
                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + '\n';
                    });
                    alert('{{ __("customer.error_add_credit") }}\n' + errorMessage);
                    console.error('Error adding credit:', xhr.responseText);
                }
            });
        });

        // PDF for single customer
        function handlePdf() {
            const customerId = $(this).data('id');
            const url = "{{ route('admin.report.customer.row', ['customer' => ':id']) }}"
                .replace(':id', customerId);
            window.location.href = url;
        }

        // edit
        function handleEdit(){

            const customerId = $(this).data('id');

            const url = "{{ route('admin.customer.edit', ['customer' => ':id']) }}"
            .replace(':id', customerId);

            $.ajax({
                method: 'GET',
                url: url,
                success:function(response){

                $("#edit_first_name").val(response.first_name);
                $("#edit_last_name").val(response.last_name);
                $("#edit_company_name").val(response.company_name);

                $("#edit_email").val(response.email);
                $("#edit_phone").val(response.phone);

                $("#edit_date_of_birth").val(response.date_of_birth);

                $("#edit_notes").val(response.notes);

                $("#edit_status").val(response.status);
                $("#edit_address").val(response.address);

                $("#edit_credit_limit").val(response.credit_limit);

                $("#edit_customer_code").val(response.customer_code);

                $("#editCustomerForm")
                    .attr("data-id", customerId);

                $("#editCustomerModal").modal("show");
            },
                error: function(xhr, status, error) {
                    console.error('Error fetching customer data:', error);
                    console.error('Error fetching customer data:', xhr.responseText);
                    }
                });
        }

        //delete
        function handleDelete(){

            let id = $(this).data("id");

            const url = "{{ route('admin.customer.destroy', ['customer' => ':id']) }}"
                .replace(':id', id);

            if(!confirm('{{ __("customer.delete_confirmation") }}')){
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

                    fetchCustomers();

                },

                error:function(xhr){

                    console.log(xhr.responseText);

                }

            });
        }

        $('#addCustomerForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('admin.customer.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // Close the modal
                    $('#addCustomerModal').modal('hide');
                    // Reset the form
                    $('#addCustomerForm')[0].reset();
                    // Refresh the customer list

                    $('.flash-message').text(response.success).fadeIn().delay(3000).fadeOut();

                    fetchCustomers();
                },
                error: function(xhr, status, error) {
                    let errors = xhr.responseJSON.errors;
                    let erroeMessage = '';
                    $.each(errors, function(key, value) {
                        erroeMessage += value[0] + '\n';
                    });
                    alert('Error adding customer:\n' + erroeMessage);
                    console.error('Error adding customer:', error);
                    console.error('Error adding customer:', xhr.responseText);
                }
            });
        });

        $("#editCustomerForm").submit(function(e){

            e.preventDefault();

            let customerId = $(this).attr("data-id");

            const url = "{{ route('admin.customer.update', ['customer' => ':id']) }}"
                .replace(':id', customerId);

            $.ajax({

                url: url,

                method: "POST",

                data: $(this).serialize(),

                success:function(response){

                    $("#editCustomerModal").modal("hide");

                    fetchCustomers();

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
                    alert('{{ __("customer.error_add_customer") }}\n' + erroeMessage);
                    console.error('Error adding customer:', error);
                    console.error('Error adding customer:', xhr.responseText);
                }

            });
        });

});


</script>

@endsection
