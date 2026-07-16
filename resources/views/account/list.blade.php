@extends('layouts.app')

@section('style')
<style>
.account-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef, 0 2px 6px rgba(0,0,0,0.15);
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.account-avatar:hover {
    transform: scale(1.15);
    box-shadow: 0 0 0 2px #0d6efd, 0 4px 10px rgba(0,0,0,0.25);
}

.account-avatar-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #6c757d, #495057);
    color: #fff;
    font-weight: 600;
    font-size: 1.1rem;
    cursor: default;
}
</style>
@endsection

@section('content')

<main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">{{ __('account.page_title') }}</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">{{ __('common.home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ __('account.page_title') }}
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
                                    <h3 class="card-title">{{ __('account.account_information') }}</h3>
                                </div>

                                <div class="card-body">
                                    <table id="user-table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('account.name') }}</th>
                                                <th>{{ __('account.email') }}</th>
                                                <th>{{ __('account.role') }}</th>
                                                <th>{{ __('account.account_image') }}</th>
                                                <th>{{ __('account.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                <tr>
                                                    <td>{{ $account->name  }}</td>
                                                    <td>{{ $account->email }}</td>
                                                    <td>{{ $account->role->display_name }}</td>
                                                   <td class="text-center align-middle">
                                                        @if($account->account_image)
                                                            <img src="{{ asset('storage/' . $account->account_image) }}"
                                                                alt="{{ $account->name }}"
                                                                class="account-avatar"
                                                                onclick="showImagePreview(this.src)">
                                                        @else
                                                            <div class="account-avatar account-avatar-placeholder">
                                                                {{ strtoupper(substr($account->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <button class="btn btn-sm edit-btn btn-warning" data-id="{{ $account->id }}">{{ __('common.edit_account') }}</button>
                                                    </td>
                                                </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</main>

{{-- Edit Modal --}}
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>{{ __('common.edit_account') }}</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

               <form id="editUserForm" enctype="multipart/form-data">
                     @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('account.password') }}</label>
                        <input type="text" class="form-control" id="edit_password" name="password" >
                    </div>
                    <div class="mb-3">
                        <label for="password_c" class="form-label">{{ __('account.password_confirmation') }}</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                    <div class="mb-3">
                        <label for="account_image" class="form-label">{{ __('account.account_image') }}</label>
                        <input type="file" class="form-control" id="edit_account_image" name="account_image" >
                        <div class="mt-2">
                            <img id="current_account_image"
                                src=""
                                alt="{{ __('account.current_account_image') }}"
                                class="account-avatar"
                                style="display:none;">
                            <span id="no_account_image" class="text-muted small" style="display:none;">
                                {{ __('account.no_image_uploaded') }}
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('common.update_account') }}</button>
                </form>


            </div>

        </div>
    </div>
</div>

 <!-- Flash message content will be inserted here -->
<div class="flash-message alert alert-success" id="flash" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 9999; background-color: #d4edda; color: #155724; padding: 10px 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
</div>

<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="bg-transparent border-0 modal-content">
            <img id="previewImage" src="" class="rounded shadow img-fluid" style="max-height: 80vh; margin: auto;">
        </div>
    </div>
</div>

<script>
function showImagePreview(src) {
    document.getElementById('previewImage').src = src;
    new bootstrap.Modal(document.getElementById('imagePreviewModal')).show();
}
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs/dayjs.min.js"></script>
<script type="text/javascript">

    $(document).ready(function() {
        $('.edit-btn').on('click', handleEdit);

        // edit
        function handleEdit(){

            const userId = $(this).data('id');

            let account = @json($account);

            let url = "{{ route('admin.account.edit', ['account' => ':id']) }}"
            .replace(':id', userId);


            if(account.role.name == 'admin'){

                url = "{{ route('admin.account.edit', ['account' => ':id']) }}"
            .replace(':id', userId);

            }else {

            url = "{{ route('cashier.account.edit', ['account' => ':id']) }}"
            .replace(':id', userId);

            }

            $.ajax({
                method: 'GET',
                url: url,
                success: function(response) {

                    if (response.account_image) {
                        $("#current_account_image")
                            .attr("src", "/storage/" + response.account_image)
                            .show();
                        $("#no_account_image").hide();
                    } else {
                        $("#current_account_image").hide();
                        $("#no_account_image").show();
                    }

                    // clear any previously selected file
                    $("#edit_account_image").val('');

                    $("#editUserForm").attr("data-id", userId);
                    $("#editUserModal").modal("show");
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching user data:', error);
                    console.error('Error fetching user data:', xhr.responseText);
                }
            });
        }

        $("#editUserForm").submit(function(e){

            e.preventDefault();

            let account = @json($account);

            let userId = $(this).attr("data-id");

            let url = "{{ route('admin.account.update', ['account' => ':id']) }}"
            .replace(':id', userId);

            if(account.role.name == 'admin'){

                url = "{{ route('admin.account.update', ['account' => ':id']) }}"
            .replace(':id', userId);

            }else {

            url = "{{ route('cashier.account.update', ['account' => ':id']) }}"
            .replace(':id', userId);

            }

            let formData = new FormData(this);

            $.ajax({

                url: url,

                method: "PUT",

                data: formData,
                processData: false,       // don't let jQuery try to serialize FormData
                contentType: false,

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


                    location.reload();

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
