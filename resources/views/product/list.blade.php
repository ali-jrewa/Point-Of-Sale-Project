@extends('layouts.app')

@section('content')

<main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Product</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Product List
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
                                        <h4 class="mt-3 card-title btn">Search Product</h4>
                                        <input
                                            type="text"
                                            id="search"
                                            class="form-control"
                                            placeholder="Search by category, name, barcode or status">

                                    </div>

                                </div>
                            <div class="mb-4 card">
                                <div class="card-header">
                                    <h3 class="card-title">Product List</h3>

                                    <div class="card-tools">
                                        <ul class="pagination pagination-sm float-end">

                                            <a href="{{ route('admin.product.create') }}" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                                Add Product
                                            </a>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <table id="product-table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Category</th>
                                                <th>Name</th>
                                                <th>SKU</th>
                                                <th>Retail Price</th>
                                                <th>Stock</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Actions</th>
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
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addProductForm" method="POST" action="{{ route('admin.product.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category Name</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cost_price" class="form-label">Cost Price</label>
                        <input type="number" class="form-control" id="cost_price" name="cost_price" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="retail_price" class="form-label">Retail Price</label>
                        <input type="number" class="form-control" id="retail_price" name="retail_price" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="sku" class="form-label">SKU</label>
                        <input type="text" class="form-control" id="sku" name="sku" required>
                    </div>
                    <div class="mb-3">
                        <label for="barcode" class="form-label">Barcode</label>
                        <input type="text" class="form-control" id="barcode" name="barcode" required>
                    </div>
                    <div class="mb-3">
                        <label for="low_stock_threshold" class="form-label">Low Stock Threshold</label>
                        <input type="number" class="form-control" id="low_stock_threshold" name="low_stock_threshold" step="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            @foreach(\App\Enums\ProductStatus::values() as $status)
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
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Edit Product</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="editProductForm">

                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category Name</label>
                        <select class="form-control" id="edit_category_id" name="category_id" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cost_price" class="form-label">Cost Price</label>
                        <input type="number" class="form-control" id="edit_cost_price" name="cost_price" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="retail_price" class="form-label">Retail Price</label>
                        <input type="number" class="form-control" id="edit_retail_price" name="retail_price" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="sku" class="form-label">SKU</label>
                        <input type="text" class="form-control" id="edit_sku" name="sku" required>
                    </div>
                    <div class="mb-3">
                        <label for="barcode" class="form-label">Barcode</label>
                        <input type="text" class="form-control" id="edit_barcode" name="barcode" required>
                    </div>
                    <div class="mb-3">
                        <label for="low_stock_threshold" class="form-label">Low Stock Threshold</label>
                        <input type="number" class="form-control" id="edit_low_stock_threshold" name="low_stock_threshold" step="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="edit_status" name="status" required>
                            @foreach(\App\Enums\ProductStatus::values() as $status)
                                <option value="{{ $status }}">
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn btn-primary">
                        Update Product
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
       fetchProducts($("#search").val());


        function fetchProducts(search = '') {
            $.ajax({
                url: "{{ route('admin.product.data') }}",
                method: 'GET',
                 data: {
                        search: search
                    },
                success: function(response) {

                    let tableBody = '';

                    if (!response.length) {

                        $('#product-table tbody').html(`
                            <tr>
                                <td colspan="10" class="py-4 text-center text-muted">
                                    No products found.
                                </td>
                            </tr>
                        `);
                        return;
                    }

                    $.each(response, function(index,product) {

                        let created_at = product.created_at ? dayjs(product.created_at).format('YYYY-MM-DD HH:mm:ss') : 'N/A';
                        let updated_at = product.updated_at ? dayjs(product.updated_at).format('YYYY-MM-DD HH:mm:ss') : 'N/A';

                        tableBody += `
                            <tr>
                                <td>${index + 1}</td>    
                                <td>${product.category.name ?? 'N/A'}</td> 
                                <td>${product.name}</td> 
                                <td>${product.sku}</td>
                                <td>${product.retail_price}</td>
                                <td>${product.stock_quantity}</td>
                                <td class="
                                    ${product.status === 'active' ? 'text-success': product.status === 'inactive'
                                                ? 'text-warning'
                                                : 'text-danger'
                                    }">
                                    ${
                                        product.status === 'active'
                                            ? 'Active'
                                            : product.status === 'inactive'
                                                ? 'Inactive'
                                                : 'Archived'
                                    }
                                </td>
                                <td>${created_at}</td>
                                <td>${updated_at}</td>
                                <td>
                                    <button class="btn btn-sm edit-btn btn-warning " data-id="${product.id}">Edit</button>
                                    <button class="btn btn-sm delete-btn btn-danger " data-id="${product.id}">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#product-table tbody').html(tableBody);

                    $('.edit-btn').on('click', handleEdit);

                    $('.delete-btn').on('click', handleDelete);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching products:', error);
                    console.error('Error fetching products:', xhr.responseText);
                }
            });
        }

        // serach
        let timer;

        $("#search").on("keyup", function () {

            clearTimeout(timer);

            let value = $(this).val();

            timer = setTimeout(function () {

                fetchProducts(value);

            }, 300);

        });
        // edit
        function handleEdit(){

            const productId = $(this).data('id');

            const url = "{{ route('admin.product.edit', ['product' => ':id']) }}"
            .replace(':id', productId);

            $.ajax({
                method: 'GET',
                url: url,
                success:function(response){

                $("#edit_category_id").val(response.category.id);

                $("#edit_name").val(response.name);

                $("#edit_description").val(response.description);

                $("#edit_sku").val(response.sku);

                $("#edit_barcode").val(response.barcode);

                $("#edit_cost_price").val(response.cost_price);

                $("#edit_retail_price").val(response.retail_price);

                $("#edit_low_stock_threshold").val(response.low_stock_threshold);

                $("#edit_status").val(response.status);

                $("#editProductForm")
                    .attr("data-id", productId);

                $("#editProductModal").modal("show");
            },
                error: function(xhr, status, error) {
                    console.error('Error fetching product data:', error);
                    console.error('Error fetching product data:', xhr.responseText);
                    }
                });
        }

        //delete
        function handleDelete(){

            let id = $(this).data("id");

            const url = "{{ route('admin.product.destroy', ['product' => ':id']) }}"
                .replace(':id', id);

            if(!confirm("Are you sure you want to delete this product?")){
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

                    fetchProducts($("#search").val());

                },

                error:function(xhr){

                    console.log(xhr.responseText);

                }

            });
        }

        $('#addProductForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('admin.product.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // Close the modal
                    $('#addProductModal').modal('hide');
                    // Reset the form
                    $('#addProductForm')[0].reset();
                    // Refresh the product list

                    $('.flash-message').text(response.success).fadeIn().delay(3000).fadeOut();

                   fetchProducts($("#search").val());
                },
                error: function(xhr, status, error) {
                    let errors = xhr.responseJSON.errors;
                    let erroeMessage = '';
                    $.each(errors, function(key, value) {
                        erroeMessage += value[0] + '\n';
                    });
                    alert('Error adding product:\n' + erroeMessage);
                    console.error('Error adding product:', error);
                    console.error('Error adding product:', xhr.responseText);
                }
            });
        });

        $("#editProductForm").submit(function(e){

            e.preventDefault();

            let productId = $(this).attr("data-id");

            const url = "{{ route('admin.product.update', ['product' => ':id']) }}"
                .replace(':id', productId);

            $.ajax({

                url: url,

                method: "POST",

                data: $(this).serialize(),

                success:function(response){

                    $("#editProductModal").modal("hide");

                   fetchProducts($("#search").val());

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
                    alert('Error adding product category:\n' + erroeMessage);

                    console.log(xhr.responseText);

                }

            });
        });

});


</script>

@endsection
