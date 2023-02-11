@extends('core::layouts.app')
@section('title', __('Notifications'))
@push('head')
<link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}" />
<link rel="stylesheet" href="{{ asset('vendor/responsive-datatables/css/responsive.bootstrap4.min.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" />
<style>
    .dataTables_length select {
        min-width: 65px !important;
    }
</style>
@endpush
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">@lang('Notifications')</h1>
</div>

<div class="row">
    <div class="col-lg-3 col-md-5">
        @include('core::partials.admin-sidebar')
    </div>
    <div class=" col-lg-9 col-md-7">
        <div class="card">
            <div class="card-body">
                <button class="btn btn-primary mb-3 add_notification_btn"><i class="fa fa-plus"></i> New Notification</button>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="notification_table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Content</th>
                                <th width="100">Created At</th>
                                <th width="90">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($notifications))
                                @foreach($notifications as $notification)
                                <tr>
                                    <td>{{ $notification->title }}</td>
                                    <td>{{ $notification->content }}</td>
                                    <td>{{ $notification->created_at }}</td>
                                    <td>
                                        @if ($notification->status)
                                            <button class="btn btn-dark btn-sm change_status_btn" title="disable" data-id="{{ $notification->id }}" data-status="{{ $notification->status }}"><i class="fa fa-thumbs-down"></i></button>
                                        @else
                                            <button class="btn btn-primary btn-sm change_status_btn" title="enable" data-id="{{ $notification->id }}" data-status="{{ $notification->status }}"><i class="fa fa-thumbs-up"></i></button>
                                        @endif
                                        <button class="btn btn-sm btn-success edit_notification_btn" data-id="{{ $notification->id }}"><i class="fa fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger remove_notification_btn" data-id="{{ $notification->id }}"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="new_notification_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('settings.notifications.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">New Notification</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Title:</label>
                                <input type="text" class="form-control" name="title" required/>
                            </div>
                            <div class="form-group">
                                <label>Content:</label>
                                <textarea class="form-control" name="content" rows="5" required></textarea>
                            </div>
                            <label><input type="checkbox" name="is_account_setup_payment" value="1"/> Account Setup Payment</label>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary"><i class="fa fa-save"></i> Create</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="edit_notification_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('settings.notifications.store') }}" method="POST">
                        @csrf
                        @method("PUT")
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Notification</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Title:</label>
                                <input type="text" class="form-control" name="title" required/>
                            </div>
                            <div class="form-group">
                                <label>Content:</label>
                                <textarea class="form-control" name="content" rows="5" required></textarea>
                            </div>
                            <label><input type="checkbox" name="is_account_setup_payment" value="1"/> Account Setup Payment</label>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <form action="" method="POST" class="remove_notification_form">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-switch/bootstrap4-toggle.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/jquery.dataTables.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/responsive-datatables/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('vendor/responsive-datatables/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/loading-overlay/dist/loadingoverlay.min.js') }}"></script>
<script>
    var BASE_URL = "{{ url('/') }}";
    var _token = "{{ csrf_token() }}";
    $(document).ready(function () {
        $("#notification_table").DataTable();
        $(".add_notification_btn").click(function() {
            document.querySelector("#new_notification_modal form").reset();
            $("#new_notification_modal").modal("show");
        });
        $("#notification_table").on("click", ".change_status_btn", function() {
            var _this = this;
            var id = $(this).data("id");
            var status = $(this).data("status") ? 0 : 1;
            $.ajax({
                type: "GET",
                url: `${BASE_URL}/settings/notifications/${id}/${status}`
            }).then(function(result) {
                if (result.status) {
                    console.log(result);
                    toastr.success(result.msg, "Success");
                    $(_this).data("status", status ? true : false);
                    if (status) {
                        $(_this).data("title", "disable")
                            .removeClass("btn-primary")
                            .addClass("btn-dark")
                            .html(`<i class="fa fa-thumbs-down"></i>`);
                    } else {
                        $(_this).data("title", "enable")
                            .removeClass("btn-dark")
                            .addClass("btn-primary")
                            .html(`<i class="fa fa-thumbs-up"></i>`);
                    }
                } else {
                    toastr.error(result.msg, "Error");
                }
            });
        });

        $("#notification_table").on("click", ".edit_notification_btn", function() {
            var id = $(this).data("id");
            $.ajax({
                url: `${BASE_URL}/settings/notifications/${id}`,
                type: "GET"
            }).then(function(notification) {
                $("#edit_notification_modal form").attr("action", BASE_URL + "/settings/notifications/" + id);
                $("#edit_notification_modal input[name='title']").val(notification.title);
                $("#edit_notification_modal textarea[name='content']").val(notification.content);
                if (notification.is_account_setup_payment) {
                    $("#edit_notification_modal input[name='is_account_setup_payment']").prop("checked", true);
                } else {
                    $("#edit_notification_modal input[name='is_account_setup_payment']").prop("checked", false);
                }
                $("#edit_notification_modal").modal("show");
            });
        });

        $("#notification_table").on("click", ".remove_notification_btn", function() {
            var id = $(this).data("id");
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this again!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: '<i class="fa fa-times"></i> Cancel',
                confirmButtonText: '<i class="fa fa-trash"></i> Delete'
            }).then(function(res) {
                if (res.value) {
                    $(".remove_notification_form").attr("action", BASE_URL + "/settings/notifications/" + id);
                    $(".remove_notification_form").submit();
                }
            });
        });
    });
</script>
@endpush