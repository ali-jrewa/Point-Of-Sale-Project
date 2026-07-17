@extends('layouts.app')

@section('content')

<main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">{{ __('supplier.title') }}</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">{{ __('supplier.home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ __('supplier.supplier_list') }}
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
                                        <h4 class="mt-3 card-title btn">{{ __('supplier.search_supplier') }}</h4>
                                        <input
                                            type="text"
                                            id="search"
                                            class="form-control"
                                            placeholder="{{ __('supplier.search_placeholder') }}">

                                    </div>

                                </div>
                            <div class="mb-4 card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('supplier.supplier_list') }}</h3>

                                    <div class="card-tools">
                                        <ul class="pagination pagination-sm float-end">

                                            <a href="{{ route('admin.supplier.create') }}" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                                                {{ __('supplier.add_supplier') }}
                                            </a>
                                            &nbsp;
                                            &nbsp;
                                            &nbsp;
                                            <a href="{{ route('admin.report.supplier') }}" class="btn btn-secondary btn-sm" >
                                                {{ __('supplier.suppliers_report') }}
                                            </a>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <table id="supplier-table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('supplier.supplier') }}</th>
                                                <th>{{ __('supplier.company_name') }}</th>
                                                <th>{{ __('supplier.phone') }}</th>
                                                <th>{{ __('supplier.email') }}</th>
                                                <th>{{ __('supplier.address') }}</th>
                                                <th>{{ __('supplier.tax_number') }}</th>
                                                <th>{{ __('supplier.status') }}</th>
                                                <th>{{ __('supplier.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($suppliers as $supplier)
                                                <tr>
                                                    <td>{{ $supplier->first_name }} {{ $supplier->last_name }}</td>
                                                    <td>{{ $supplier->company_name ?? __('supplier.na') }}</td>
                                                    <td>{{ $supplier->phone }}</td>
                                                    <td>{{ $supplier->email ?? __('supplier.na') }}</td>
                                                    <td>{{ $supplier->address ?? __('supplier.na') }}</td>
                                                    <td>{{ $supplier->tax_number ?? __('supplier.na')}}</td>

                                                    <td class="{{ $supplier->status === \App\Enums\SupplierStatus::Active ? 'text-success' : 'text-warning' }}">
                                                         {{ $supplier->status->name }}
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm edit-btn btn-warning" data-id="{{ $supplier->id }}">{{ __('supplier.edit') }}</button>
                                                        <button class="btn btn-sm delete-btn btn-danger" data-id="{{ $supplier->id }}">{{ __('supplier.delete') }}</button>
                                                        <button class="btn btn-sm pdf-btn btn-secondary" data-id="{{ $supplier->id }}">{{ __('supplier.report') }}</button>

                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">{{ __('supplier.no_suppliers_found') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div id="pagination-wrapper" style="padding: 10px; float: right;">
                                        {!! $suppliers->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</main>


{{-- Create Modal --}}
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSupplierModalLabel">{{ __('supplier.add_supplier_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addSupplierForm" method="POST" action="{{ route('admin.supplier.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="first_name" class="form-label">{{ __('supplier.first_name') }}</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">{{ __('supplier.last_name') }}</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="company_name" class="form-label">{{ __('supplier.company_name') }}</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" >
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('supplier.email') }}</label>
                        <input type="email" class="form-control" id="email" name="email" >
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">{{ __('supplier.phone') }}</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">{{ __('supplier.address') }}</label>
                        <textarea class="form-control" id="address" name="address"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('supplier.status') }}</label>
                        <select class="form-control" id="status" name="status">
                            @foreach(App\Enums\SupplierStatus::values() as $status)
                                <option value="{{ $status }}">
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('supplier.save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editSupplierModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>{{ __('supplier.edit_supplier') }}</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

               <form id="editSupplierForm">
                     @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="first_name" class="form-label">{{ __('supplier.first_name') }}</label>
                        <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">{{ __('supplier.last_name') }}</label>
                        <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="company_name" class="form-label">{{ __('supplier.company_name') }}</label>
                        <input type="text" class="form-control" id="edit_company_name" name="company_name" >
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('supplier.email') }}</label>
                        <input type="email" class="form-control" id="edit_email" name="email" >
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">{{ __('supplier.phone') }}</label>
                        <input type="tel" class="form-control" id="edit_phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">{{ __('supplier.address') }}</label>
                        <textarea class="form-control" id="edit_address" name="address"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tax_number" class="form-label">{{ __('supplier.tax_number') }}</label>
                        <input type="number" class="form-control" id="edit_tax_number" name="tax_number" >
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label"> {{ __('supplier.notes') }}</label>
                        <textarea class="form-control" id="edit_notes" name="notes"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('supplier.status') }}</label>
                        <select class="form-control" id="edit_status" name="status">
                             @foreach(App\Enums\SupplierStatus::values() as $status)
                                <option value="{{ $status }}">
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('supplier.update') }}</button>
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
         $('.pdf-btn').on('click', handlePdf);


        function fetchSuppliers(search = '') {
            $.ajax({
                url: "{{ route('admin.supplier.data') }}",
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
                                    {{ __('supplier.no_suppliers_found') }}
                                </td>
                            </tr>
                        `;
                    } else {
                        $.each(response, function(index, supplier) {


                            tableBody += `
                                <tr>
                                    <td>${supplier.first_name} ${supplier.last_name}</td>
                                        <td>${supplier.company_name ?? {{ __('supplier.na') }} }</td>
                                    <td>${supplier.phone}</td>
                                    <td>${supplier.email ?? {{ __('supplier.na') }} }</td>
                                    <td>${supplier.address ?? {{ __('supplier.na') }} }</td>
                                    <td>${supplier.tax_number ?? {{ __('supplier.na') }} }</td>
                                    <td class="${
                                        supplier.status === 'active' ? 'text-success' :  'text-warning'
                                    }">
                                        ${
                                            supplier.status === 'active' ? {{ __('supplier.active') }} : {{ __('supplier.inactive') }}
                                        }
                                    </td>
                                    <td>
                                        <button class="btn btn-sm edit-btn btn-warning" data-id="${supplier.id}">{{ __('supplier.edit') }}</button>
                                        <button class="btn btn-sm delete-btn btn-danger" data-id="${supplier.id}">{{ __('supplier.delete') }}</button>
                                        <button class="btn btn-sm pdf-btn btn-secondary" data-id="${supplier.id}">{{ __('supplier.report') }}</button>

                                    </td>
                                </tr>
                            `;
                        });
                    }

                    // Update the DOM after the if/else logic determines tableBody content
                    $('#supplier-table tbody').html(tableBody);

                    // Re-bind event handlers
                    $('.edit-btn').on('click', handleEdit);
                    $('.delete-btn').on('click', handleDelete);
                    $('.pdf-btn').on('click', handlePdf);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching suppliers:', error);
                    console.error('Error fetching suppliers:', xhr.responseText);
                }
            });
        }

        // search
        let timer;
        $("#search").on("keyup",function(){

            clearTimeout(timer);

            let value=$(this).val();

            if (value.trim() === '') {
             fetchSuppliers();
            }
            else {
                $('#pagination-wrapper').hide();
                timer=setTimeout(function(){

                    fetchSuppliers(value);

                },300);
            }



        });

        // PDF for single supplier
        function handlePdf() {
            const supplierId = $(this).data('id');
            const url = "{{ route('admin.report.supplier.row', ['supplier' => ':id']) }}"
                .replace(':id', supplierId);
            window.location.href = url;
        }

        // edit
        function handleEdit(){

            const supplierId = $(this).data('id');

            const url = "{{ route('admin.supplier.edit', ['supplier' => ':id']) }}"
            .replace(':id', supplierId);

            $.ajax({
                method: 'GET',
                url: url,
                success:function(response){

                $("#edit_first_name").val(response.first_name);
                $("#edit_last_name").val(response.last_name);
                $("#edit_company_name").val(response.company_name);

                $("#edit_email").val(response.email);
                $("#edit_phone").val(response.phone);

                $("#edit_status").val(response.status);
                $("#edit_address").val(response.address);

                $("#edit_tax_number").val(response.tax_number);

                $("#editSupplierForm")
                    .attr("data-id", supplierId);

                $("#editSupplierModal").modal("show");
            },
                error: function(xhr, status, error) {
                    console.error('Error fetching supplier data:', error);
                    console.error('Error fetching supplier data:', xhr.responseText);
                    }
                });
        }

        //delete
        function handleDelete(){

            let id = $(this).data("id");

            const url = "{{ route('admin.supplier.destroy', ['supplier' => ':id']) }}"
                .replace(':id', id);

            if(!confirm("{{ __('supplier.delete_confirmation') }}")){
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

                    fetchSuppliers();

                },

                error:function(xhr){

                    console.log(xhr.responseText);

                }

            });
        }

        $('#addSupplierForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('admin.supplier.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // Close the modal
                    $('#addSupplierModal').modal('hide');
                    // Reset the form
                    $('#addSupplierForm')[0].reset();

                    $('.flash-message').text(response.success).fadeIn().delay(3000).fadeOut();

                    fetchSuppliers();
                },
                error: function(xhr, status, error) {
                    let errors = xhr.responseJSON.errors;
                    let erroeMessage = '';
                    $.each(errors, function(key, value) {
                        erroeMessage += value[0] + '\n';
                    });
                    alert("{{ __('supplier.error_add_supplier') }}\n" + erroeMessage);
                    console.error('Error adding supplier:', error);
                    console.error('Error adding supplier:', xhr.responseText);
                }
            });
        });

        $("#editSupplierForm").submit(function(e){

            e.preventDefault();

            let supplierId = $(this).attr("data-id");

            const url = "{{ route('admin.supplier.update', ['supplier' => ':id']) }}"
                .replace(':id', supplierId);

            $.ajax({

                url: url,

                method: "POST",

                data: $(this).serialize(),

                success:function(response){

                    $("#editSupplierModal").modal("hide");

                    fetchSuppliers();

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
                    alert('Error adding supplier:\n' + erroeMessage);
                    console.error('Error adding supplier:', error);
                    console.error('Error adding supplier:', xhr.responseText);
                }

            });
        });

});


</script>

@endsection
