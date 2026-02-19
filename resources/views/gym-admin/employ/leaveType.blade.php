@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/datatables.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css") }}">
@stop

@section('content')
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
             <li>
                <a href="{{ route('gym-admin.dashboard.index') }}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Employ Leave Type</span>
            </li>
        </ul>
        <div class="page-content-inner">
            <div class="row">
                @if(session()->has('message'))
                    <div class="alert alert-message alert-success">
                        {{session()->get('message')}}
                    </div>
                @endif
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="fa fa-users font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Employ Leave Types</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a id="sample_editable_1_new" data-toggle="modal" data-target="#addLeave" class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover table-100" id="manage-branches">
                                <thead>
                                    <tr>
                                        <th class="desktop">Leave Type</th>
                                        <th class="desktop">Days</th>
                                        <th class="desktop">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaveType as $leave)
                                        <tr>
                                            <td>{{$leave->name}}</td>
                                            <td>{{$leave->days}}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i> <span class="hidden-xs">Action</span>
                                                        <i class="fa fa-angle-down"></i>
                                                    </button>
                                                    <ul class="dropdown-menu pull-right" role="menu">
                                                        <li>
                                                            <a data-toggle="modal" data-target="#editLeaveType{{$leave->id}}"> <i class="fa fa-edit"></i> Edit</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="remove" data-type-id="{{$leave->id}}" 
                                                                data-url="{{ route('gym-admin.employ.delete.leaveType',$leave->id)}}">
                                                            <i class="fa fa-trash"></i> Delete</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="modal fade bs-modal-md in" id="editLeaveType{{$leave->id}}" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-md" id="modal-data-application">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4>Edit Leave Type</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                                                            </div>
                                                            <form action="{{route('gym-admin.employ.edit.leaveType',$leave->id)}}" method="post" enctype="multipart/form-data">
                                                                {{csrf_field()}}
                                                                <div class="modal-body">

                                                                    <div class="form-group form-md-line-input">
                                                                        <label><h4>Leave Type</h4></label>
                                                                        <input type="text" class="form-control" placeholder="Leave Type" value="{{$leave->name}}"
                                                                               name="leaveType" required>
                                                                        <div class="form-control-focus"></div>
                                                                        <span class="help-block">Enter Leave Type</span>
                                                                    </div>
                                                                    <div class="form-group form-md-line-input">
                                                                        <label><h4>Leave Days</h4></label>
                                                                        <input type="number" min="0" class="form-control" value="{{$leave->days}}" placeholder="Leave Days"
                                                                               name="days" required>
                                                                        <div class="form-control-focus"></div>
                                                                        <span class="help-block">Enter Leave Days</span>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn primary">Update</button>
                                                                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade bs-modal-md in" id="addLeave" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" id="modal-data-application">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Create Leave Type</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                    </div>
                    <form action="{{route('gym-admin.employ.create.leaveType')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="branch_id">
                        <div class="modal-body">

                            <div class="form-group form-md-line-input">
                                <label><h4>Leave Type</h4></label>
                                <input type="text" class="form-control" placeholder="Leave Type"
                                       name="leaveType" required>
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Leave Type</span>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label><h4>Leave Days</h4></label>
                                <input type="number" min="0" class="form-control" placeholder="Leave Days"
                                       name="days" required>
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Leave Days</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Create</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('footer')
    <script src="{{ asset("admin/global/scripts/datatable.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/table-datatables-managed.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/datatables.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootbox/bootbox.min.js") }}"></script>

    <script>
        var table = $('#manage-branches');
        table.dataTable({
            responsive: true,
        });
        $(function () {
            setTimeout(function () {
                $('.alert-message').slideUp();
            }, 3000);
        });
    </script>
    <script>
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var UIBootbox = function () {
            var branchData = function () {
                $(".remove").click(function () {
                    var branchUrl = $(this).data('url');
                    bootbox.confirm({
                        message: "Do you want to delete this leave type?",
                        buttons: {
                            confirm: {
                                label: "Yes",
                                className: "btn-primary"
                            }
                        },
                        callback: function(result){
                            if(result){
                                $.easyAjax({
                                    url: branchUrl,
                                    type: 'POST',
                                    data: {
                                        '_method': 'delete' , '_token': '{{ csrf_token() }}'
                                    },
                                    success: function(){
                                        location.reload();
                                    }
                                });
                            }
                            else {
                                console.log('cancel');
                            }
                        }
                    })

                })
            };
            return {
                init: function () {
                    branchData()
                }
            }
        }();
        jQuery(document).ready(function () {
            UIBootbox.init()
        });

    
    </script>
@stop
