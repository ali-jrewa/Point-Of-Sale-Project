@extends('layouts.app')

@section('content')

<main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">{{ __('category.page_title') }}</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">{{ __('common.home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ __('category.category_list') }}
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
                                    <h3 class="card-title">{{ __('category.category_list') }}</h3>

                                    <div class="card-tools">
                                        <ul class="pagination pagination-sm float-end">

                                            <a href="{{ route('admin.category.create') }}" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                                {{ __('category.add_category') }}
                                            </a>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <table id="category-table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('category.id') }}</th>
                                                <th>{{ __('category.name') }}</th>
                                                <th>{{ __('category.status') }}</th>
                                                <th>{{ __('category.created_at') }}</th>
                                                <th>{{ __('category.updated_at') }}</th>
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
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">{{ __('category.add_category') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm" method="POST" action="{{ route('admin.category.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('category.name') }}</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('category.description') }}</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('category.status') }}</label>
                        <select class="form-control" id="status" name="status">
                            <option value="{{ App\Enums\CategoryStatus::Active->value }}">Active</option>
                            <option value="{{ App\Enums\CategoryStatus::Inactive->value }}">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('common.save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>{{ __('category.edit_category') }}</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="editCategoryForm">

                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>{{ __('category.name') }}</label>
                        <input
                            type="text"
                            id="edit_name"
                            name="name"
                            class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>{{ __('category.description') }}</label>
                        <textarea
                            id="edit_description"
                            name="description"
                            class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>{{ __('category.slug') }}</label>
                        <input
                            type="text"
                            id="edit_slug"
                            name="slug"
                            class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>{{ __('category.status') }}</label>
                        <select class="form-control" id="edit_status" name="status">
                            <option value="{{ App\Enums\CategoryStatus::Active->value }}">Active</option>
                            <option value="{{ App\Enums\CategoryStatus::Inactive->value }}">Inactive</option>
                        </select>
                    </div>

                    <button class="btn btn-primary">
                        {{ __('category.update_category') }}
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
        fetchCategories();


        function fetchCategories() {

            let url = '';
            @if(auth()->user()->hasRole('admin')){
                url = "{{ route('admin.category.data') }}";
            }@else {
                url = "{{ route('manager.category.data') }}";
            }
            @endif

            
            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {

                    let tableBody = '';
                    $.each(response, function(index,category) {

                        let created_at = category.created_at ? dayjs(category.created_at).format('YYYY-MM-DD HH:mm:ss') : '{{ __("category.not_available") }}';
                        let updated_at = category.updated_at ? dayjs(category.updated_at).format('YYYY-MM-DD HH:mm:ss') : '{{ __("category.not_available") }}';

                        tableBody += `
                            <tr>
                                <td>${index + 1}</td>    
                                <td>${category.name}</td> 
                                <td class="${category.status === 'active' ? 'text-success' : 'text-danger'}">${category.status === 'active' ? '{{ __("category.active") }}' : '{{ __("category.inactive") }}'}</td>
                                <td>${created_at}</td>
                                <td>${updated_at}</td>
                                <td>
                                    <button class="btn btn-sm edit-btn btn-warning " data-id="${category.id}">{{ __('common.edit') }}</button>
                                    <button class="btn btn-sm delete-btn btn-danger " data-id="${category.id}">{{ __('common.delete') }}</button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#category-table tbody').html(tableBody);

                    $('.edit-btn').on('click', handleEdit);

                    $('.delete-btn').on('click', handleDelete);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching categories:', error);
                    console.error('Error fetching categories:', xhr.responseText);
                }
            });
        }

        // edit
        function handleEdit(){

            const categoryId = $(this).data('id');

            let url = '';
            @if(auth()->user()->hasRole('admin')){
                url = "{{ route('admin.category.edit', ['category' => ':id']) }}"
            .replace(':id', categoryId);
            }@else {
                url = "{{ route('manager.category.edit', ['category' => ':id']) }}"
            .replace(':id', categoryId);
            }
            @endif 

            $.ajax({
                method: 'GET',
                url: url,
                success:function(response){

                $("#edit_name").val(response.name);

                $("#edit_description").val(response.description);

                $("#edit_slug").val(response.slug);

                $("#edit_status").val(response.status);

                $("#editCategoryForm")
                    .attr("data-id", categoryId);

                $("#editCategoryModal").modal("show");
            },
                error: function(xhr, status, error) {
                    console.error('Error fetching category data:', error);
                    console.error('Error fetching category data:', xhr.responseText);
                    }
                });
        }

        //delete
        function handleDelete(){

            let id = $(this).data("id");

            let url = '';
            @if(auth()->user()->hasRole('admin')){
                url = "{{ route('admin.category.destroy', ['category' => ':id']) }}"
                .replace(':id', id);
            }@else {
                url = "{{ route('manager.category.destroy', ['category' => ':id']) }}"
                .replace(':id', id);
            }
            @endif 

            if(!confirm("{{ __('category.delete_confirmation') }}")){
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

                    fetchCategories();

                },

                error:function(xhr){

                    console.log(xhr.responseText);

                }

            });
        }

        $('#addCategoryForm').on('submit', function(e) {
            
            e.preventDefault();

            let url = '';
            @if(auth()->user()->hasRole('admin')){
                url = "{{ route('admin.category.store') }}";
            }@else {
                url = "{{ route('manager.category.store') }}";
            }
            @endif
            
            $.ajax({
                url: url ,
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // Close the modal
                    $('#addCategoryModal').modal('hide');
                    // Reset the form
                    $('#addCategoryForm')[0].reset();
                    // Refresh the category list

                    $('.flash-message').text(response.success).fadeIn().delay(3000).fadeOut();

                    fetchCategories();
                },
                error: function(xhr, status, error) {
                    let errors = xhr.responseJSON.errors;
                    let erroeMessage = '';
                    $.each(errors, function(key, value) {
                        erroeMessage += value[0] + '\n';
                    });
                    alert('Error adding category:\n' + erroeMessage);
                    console.error('Error adding category:', error);
                    console.error('Error adding category:', xhr.responseText);
                }
            });
        });

        $("#editCategoryForm").submit(function(e){

            e.preventDefault();

            let categoryId = $(this).attr("data-id");


            let url = '';
            @if(auth()->user()->hasRole('admin')){
                url = "{{ route('admin.category.update', ['category' => ':id']) }}"
                .replace(':id', categoryId);
            }@else {
                url = "{{ route('manager.category.update', ['category' => ':id']) }}"
                .replace(':id', categoryId);
            }
            @endif

            $.ajax({

                url: url,

                method: "POST",

                data: $(this).serialize(),

                success:function(response){

                    $("#editCategoryModal").modal("hide");

                    fetchCategories();

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

                error:function(xhr){

                    console.log(xhr.responseText);

                }

            });
        });

});


</script>

@endsection
