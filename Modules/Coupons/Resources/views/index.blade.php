@extends('core::layouts.app')
@section('title', __('Coupon'))
@push('head')
@endpush
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h4 class="h3 mb-0 text-gray-800">Coupons</h4>
    <button class="btn btn-primary add_new_coupon"><i class="fa fa-plus"></i> Add new</button>
</div>
<div class="row">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="coupons_table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Coupon Code</th>
                    <th>Discount Amount</th>
                    <th>Expire Date</th>
                    <th>Created At</th>
                    <th width="60"></th>
                </tr>
            </thead>
            <tbody>
                @if (count($coupons))
                    @foreach ($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->name }}</td>
                        <td>{{ $coupon->code }}</td>
                        <td>${{ $coupon->discount_amount }}</td>
                        <td>{{ $coupon->is_unlimited ? "Unlimited" : $coupon->expire_date }}</td>
                        <td>{{ $coupon->created_at }}</td>
                        <td>
                            <button class="btn btn-success btn-sm edit_coupon_btn" data-id="{{ $coupon->id }}"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm remove_coupon_btn" data-id="{{ $coupon->id }}"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
<div id="new_coupon_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('coupons.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">New Coupon</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Coupon Name:</label>
                        <input type="text" class="form-control" name="name" required/>
                    </div>
                    <div class="form-group">
                        <label>Coupon Code:</label>
                        <input type="text" class="form-control" name="code" required/>
                    </div>
                    <div class="form-group">
                        <label>Discount Amount:</label>
                        <input type="number" class="form-control" name="discount_amount" required/>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" value="1" name="is_unlimited"/> Unlimited coupon?
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Expire Date:</label>
                        <input type="date" class="form-control" name="expire_date" required/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="edit_coupon_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                @method("PUT")
                <div class="modal-header">
                    <h4 class="modal-title">Edit Coupon</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Coupon Name:</label>
                        <input type="text" class="form-control" name="name" required/>
                    </div>
                    <div class="form-group">
                        <label>Coupon Code:</label>
                        <input type="text" class="form-control" name="code" required/>
                    </div>
                    <div class="form-group">
                        <label>Discount Amount:</label>
                        <input type="number" class="form-control" name="discount_amount" required/>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" value="1" name="is_unlimited"/> Unlimited coupon?
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Expire Date:</label>
                        <input type="date" class="form-control" name="expire_date" required/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<form class="delete_coupon_form" action="" method="POST">
    @csrf
    @method('DELETE')
</form>

@stop


@push('scripts')
<script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/jquery.dataTables.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/responsive-datatables/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('vendor/responsive-datatables/js/responsive.bootstrap4.min.js') }}"></script>
<script>
    var BASE_URL = "{{ url('/') }}";
    var _token = "{{ csrf_token() }}";
    $(document).ready(function () {
        $("#coupons_table").DataTable();
        $(".add_new_coupon").click(function() {
            document.querySelector("#new_coupon_modal form").reset();
            $("#new_coupon_modal").modal("show");
        });
        $("#new_coupon_modal input[name='is_unlimited']").change(function() {
            if ($(this).prop("checked")) {
                $("#new_coupon_modal input[name='expire_date']").attr("disabled", true);
            } else {
                $("#new_coupon_modal input[name='expire_date']").attr("disabled", false);
            }
        });

        $("#coupons_table").on("click", ".edit_coupon_btn", function() {
            var id = $(this).data("id");
            $.ajax(`${BASE_URL}/coupons/${id}`).then(function(res) {
                $("#edit_coupon_modal form").attr("action", `${BASE_URL}/coupons/${id}`);
                $("#edit_coupon_modal input[name='name']").val(res.name);
                $("#edit_coupon_modal input[name='code']").val(res.code);
                $("#edit_coupon_modal input[name='discount_amount']").val(res.discount_amount);
                if (res.is_unlimited) {
                    $("#edit_coupon_modal input[name='is_unlimited']").prop("checked", true);
                    $("#edit_coupon_modal input[name='expire_date']").prop("disabled", true);
                } else {
                    $("#edit_coupon_modal input[name='is_unlimited']").prop("checked", false);
                    $("#edit_coupon_modal input[name='expire_date']").val(res.expire_date);
                }
            });
            $("#edit_coupon_modal").modal("show");
        });

        $("#edit_coupon_modal input[name='is_unlimited']").change(function() {
            if ($(this).prop("checked")) {
                $("#edit_coupon_modal input[name='expire_date']").attr("disabled", true);
            } else {
                $("#edit_coupon_modal input[name='expire_date']").attr("disabled", false);
            }
        });

        $("#coupons_table").on("click", ".remove_coupon_btn", function() {
            var id = $(this).data("id");
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert it again!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: '<i class="fa fa-times"></i> Cancel',
                confirmButtonText: '<i class="fa fa-trash"></i> Delete'
            }).then(function(res) {
                if (res.value) {
                    $(".delete_coupon_form").attr("action", `${BASE_URL}/coupons/${id}`).submit();
                }
            })
        });
    });
</script>
@endpush