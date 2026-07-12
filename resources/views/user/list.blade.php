@extends('layouts.app')

@section('content')

<main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">User</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    User List
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
                                        <h4 class="mt-3 card-title btn">Search User</h4>
                                        <input
                                            type="text"
                                            id="search"
                                            class="form-control"
                                            placeholder="Search by name, email, role or status">

                                    </div>

                                </div>
                            <div class="mb-4 card">
                                <div class="card-header">
                                    <h3 class="card-title">User List</h3>

                                    <div class="card-tools">
                                        <ul class="pagination pagination-sm float-end">

                                            <a href="{{ route('admin.user.create') }}" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                                Add User
                                            </a>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <table id="user-table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($users as $user)
                                                <tr>
                                                    <td>{{ $user->id }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->role->display_name }}</td>
                                                    <td class="{{ $user->status->value == 'active' ? 'text-success' : 'text-danger' }}">
                                                        {{ $user->status }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('Y-m-d') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($user->updated_at)->format('Y-m-d') }}</td>
                                                    <td>
                                                        <button class="btn btn-sm edit-btn btn-warning" data-id="{{ $user->id }}">Edit</button>
                                                        <button class="btn btn-sm delete-btn btn-danger" data-id="{{ $user->id }}">Delete</button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">No users found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div id="pagination-wrapper" style="padding: 10px; float: right;">
                                        {!! $users->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</main>


{{-- Create Modal --}}
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" method="POST" action="{{ route('admin.user.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" >
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" >
                    </div>
                    <div class="mb-3">
                        <label for="password_c" class="form-label">Password Confirmation</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>

                    <div class="mb-3">
                        <label for="role_id" class="form-label">Role</label>
                        <select class="form-control" id="role_id" name="role_id">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">
                                    {{ $role->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                            @foreach(App\Enums\UserStatus::values() as $status)
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
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Edit User</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

               <form id="editUserForm">
                     @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" >
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="text" class="form-control" id="edit_password" name="password" >
                    </div>
                    <div class="mb-3">
                        <label for="password_c" class="form-label">Password Confirmation</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                    <div class="mb-3">
                        <label for="role_id" class="form-label">Role</label>
                        <select class="form-control" id="edit_role_id" name="role_id">
                            @foreach($users as $user)
                                <option value="{{ $user->role->id }}">
                                    {{ $user->role->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="edit_status" name="status">
                            <option value="{{ App\Enums\UserStatus::Active->value }}">Active</option>
                            <option value="{{ App\Enums\UserStatus::Inactive->value }}">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update User</button>
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


        function fetchUsers(search = '') {


            $.ajax({
                url: "{{ route('admin.user.data') }}",
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
                                    No Users found.
                                </td>
                            </tr>
                        `;
                    } else {
                        $.each(response, function(index, user) {
                            let created_at = user.created_at ? dayjs(user.created_at).format('YYYY-MM-DD HH:mm:ss') : 'N/A';
                            let updated_at = user.updated_at ? dayjs(user.updated_at).format('YYYY-MM-DD HH:mm:ss') : 'N/A';

                            tableBody += `
                                <tr>
                                    <td>${user.id}</td>
                                    <td>${user.name}</td>
                                    <td>${user.email}</td>
                                    <td>
                                        ${user.role.display_name}
                                    </td>
                                    <td class="${
                                        user.status === 'active' ? 'text-success' : 'text-danger'}">
                                        ${user.status === 'active' ? 'Active' : 'Inactive'}
                                    </td>
                                    <td>${created_at}</td>
                                    <td>${updated_at}</td>
                                    <td>
                                        <button class="btn btn-sm edit-btn btn-warning" data-id="${user.id}">Edit</button>
                                        <button class="btn btn-sm delete-btn btn-danger" data-id="${user.id}">Delete</button>
                                    </td>
                                </tr>
                            `;
                        });
                    }

                    // Update the DOM after the if/else logic determines tableBody content
                    $('#user-table tbody').html(tableBody);

                    // Re-bind event handlers
                    $('.edit-btn').on('click', handleEdit);
                    $('.delete-btn').on('click', handleDelete);

                    // Pagination visibility is decided here, in sync with the
                    // response that actually rendered, instead of before the
                    // request was even sent.
                    if (search === '') {
                        $("#pagination-wrapper").show();
                    } else {
                        $("#pagination-wrapper").hide();
                    }
                },
                error: function(xhr, status, error) {
                    // "abort" happens on purpose when we cancel a stale request.
                    // It's not a real error, so don't alert or log it as one.
                    if (status === 'abort') {
                        return;
                    }
                    alert(xhr.responseText);
                    console.error('Error fetching users:', error);
                    console.error('Error fetching users:', xhr.responseText);
                }
            });
        }

        // search
        let timer;
        $("#search").on("keyup", function () {

            clearTimeout(timer);

            let value = $(this).val().trim();

            timer = setTimeout(function () {
                fetchUsers(value);
            }, 300);

        });

        // edit
        function handleEdit(){

            const userId = $(this).data('id');

            const url = "{{ route('admin.user.edit', ['user' => ':id']) }}"
            .replace(':id', userId);

            $.ajax({
                method: 'GET',
                url: url,
                success:function(response){

                $("#edit_name").val(response.name);
                $("#edit_email").val(response.email);
                $("#edit_role_id").val(response.role.id);
                $("#edit_status").val(response.status);


                $("#editUserForm")
                    .attr("data-id", userId);

                $("#editUserModal").modal("show");
            },
                error: function(xhr, status, error) {
                    console.error('Error fetching user data:', error);
                    console.error('Error fetching user data:', xhr.responseText);
                    }
                });
        }

        //delete
        function handleDelete(){

            let id = $(this).data("id");

            const url = "{{ route('admin.user.destroy', ['user' => ':id']) }}"
                .replace(':id', id);

            if(!confirm("Are you sure you want to delete this user?")){
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

                    fetchUsers();
                },

                error:function(xhr){

                    console.log(xhr.responseText);

                }

            });
        }

        $('#addUserForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('admin.user.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // Close the modal
                    $('#addUserModal').modal('hide');
                    // Reset the form
                    $('#addUserForm')[0].reset();
                    // Refresh the user list

                    $('.flash-message').text(response.success).fadeIn().delay(3000).fadeOut();

                    fetchUsers();
                },
                error: function(xhr, status, error) {
                    let errors = xhr.responseJSON.errors;
                    let erroeMessage = '';
                    $.each(errors, function(key, value) {
                        erroeMessage += value[0] + '\n';
                    });
                    alert('Error adding user:\n' + erroeMessage);
                    console.error('Error adding user:', error);
                    console.error('Error adding user:', xhr.responseText);
                }
            });
        });

        $("#editUserForm").submit(function(e){

            e.preventDefault();

            let userId = $(this).attr("data-id");

            const url = "{{ route('admin.user.update', ['user' => ':id']) }}"
                .replace(':id', userId);

            $.ajax({

                url: url,

                method: "POST",

                data: $(this).serialize(),

                success:function(response){

                    $("#editUserModal").modal("hide");

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

                        fetchUsers();

                },
                error: function(xhr, status, error) {
                    let errors = xhr.responseJSON.errors;
                    let erroeMessage = '';
                    $.each(errors, function(key, value) {
                        erroeMessage += value[0] + '\n';
                    });
                    alert('Error adding user:\n' + erroeMessage);
                    console.error('Error adding user:', error);
                    console.error('Error adding user:', xhr.responseText);
                }

            });
        });

});
</script>

@endsection
