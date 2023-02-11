@extends('core::layouts.app')
@section('title', __('Event Template'))
@push('head')
    <link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/responsive-datatables/css/responsive.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"/>
    <style>
        table.dataTable td {
            text-overflow: clip;
            overflow: inherit;
        }
        #template_table .dropdown-toggle {
            border-radius: 50%;
            height: 30px;
            width: 30px;
            padding: 0px;
            border-color: #3b7ddd;
        }
    </style>
@endpush
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <div class="d-flex flex-column mb-2 iframe-done-container">
            <div class="d-flex justify-content-start">
                <h1 class="h3 text-gray-800">@lang('Event Templates')</h1>
            </div>
        </div>
        <div class="ml-auto d-sm-flex">
            <form method="get" action="" class="navbar-search mr-4">
                <div class="input-group">
                    <input type="text" name="query" value="{{ \Request::get('query', '') }}"
                           class="form-control bg-light border-0 small" placeholder="@lang('Search')"
                           aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if($templates->count() > 0)
        <div class="row">
            <div class="col-sm-12">
                <div class="display: block; width: 100%">
                <table id="template_table" class="table table-striped table-bordered dt-responsive nowrap desktop text-center" style="width: 100%">
                    <thead class="thead-dark">
                    <tr>
                        <th>@lang('Name')</th>
                        <th>@lang('Event Name')</th>
                        <th>@lang('Created Time')</th>
                        <th style="width: 4%">@lang('Actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($templates as $template)
                        <tr>
                            <td>{{ $template->name }}</td>
                            <td>{{ $template->event->name }}</td>
                            <td>{{ $template->created_at}}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item edit_template_btn" href="javascript:void(0);" data-id="{{ $template->id }}">
                                            <i class="fa fa-edit"></i> Edit Template
                                        </a>
                                        <a class="dropdown-item delete_template_btn" data-id="{{ $template->id }}" href="javascript:void(0)">
                                            <i class="fa fa-trash"></i> Delete Template
                                        </a>
                                    </div>
                                </div>
                                <!-- Modal -->
                                <div class="modal edit_template_modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <form action="{{ route('templates.update', ['id' => $template->id]) }}" method="POST">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Edit Template</h4>
                                                </div>
                                                <div class="modal-body">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group mb-0 text-left">
                                                        <label>Template Name:</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $template->name }}"/>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
                                                    <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Close
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
                <div class="mt-2 d-flex justify-content-center">
                    {{ $templates->appends( Request::all() )->links() }}
                </div>
                <form class="template_delete_form" action="" method="POST">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    @endif
    @if($templates->count() == 0)
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center">
                    <div class="error mx-auto mb-3"><i class="fas fa-calendar-day"></i></div>
                    <p class="lead text-gray-800">@lang('Not Found')</p>
                    <p class="text-gray-500">@lang("You don't have any event template.")</p>
                </div>
            </div>
        </div>
    @endif
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
        $(document).ready(function() {
            $("#template_table").DataTable({
                "responsive": true,
                "searching": false,
                "lengthChange": false,
                "paging": false,
                "info": false
            });

            $("#template_table").on("click", ".edit_template_btn", function() {
                $(this).closest("td").find(".edit_template_modal").modal("show");
            });
            $("#template_table").on("click", ".delete_template_btn", function() {
                var id = $(this).data("id");
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: '<i class="fa fa-times"></i> Cancel',
                    confirmButtonText: '<i class="fa fa-trash"></i> Delete'
                }).then(function(res) {
                    if (res.value) {
                        $(".template_delete_form").attr("action", BASE_URL + "/templates/" + id);
                        $(".template_delete_form").submit();
                    }
                });
            });
            $(".clipboard-btn").click(function() {
                var $temp = $("<input id='html-content' type='text'>");
                $("body").append($temp);
                var content = $(".language-html").text();
                $("#html-content").val(content).select();
                var input = document.getElementById("html-content");
                input.focus();
                input.select();
                document.execCommand("copy");
                $("#html-content").remove();
                toastr.success("Copied sucessfully.", "Success !");
            });
            $("input[name='is_show_about_us_form']").change(function() {
                var checked = $(this).prop("checked");
                $.ajax({
                    type: "POST",
                    url: BASE_URL + "/events/setting/show-form",
                    data: {
                        _token: _token,
                        _method: "PUT",
                        is_show_about_us_form: checked ? 1 : 0
                    }
                }).then(function(res) {
                    toastr.success("Set the option successfully.", "Success !");
                });
            });
            $("input[name='is_show_contact_us_form']").change(function() {
                var checked = $(this).prop("checked");
                $.ajax({
                    type: "POST",
                    url: BASE_URL + "/events/setting/show-form",
                    data: {
                        _token: _token,
                        _method: "PUT",
                        is_show_contact_us_form: checked ? 1 : 0
                    }
                }).then(function(res) {
                    toastr.success("Set the option successfully.", "Success !");
                });
            });
        });
    </script>
@endpush