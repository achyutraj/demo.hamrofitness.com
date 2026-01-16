@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}

@stop

@section('content')
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{route('gym-admin.dashboard.index')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Level & Activities</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="fa fa-level-up font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Level & Activities</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a class="btn sbold dark" data-toggle="modal" data-target="#addActivity">Add New <i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-bordered table-hover order-column table-100" id="reserved_table">
                                <thead>
                                <tr>
                                    <th>Level</th>
                                    <th>Activities</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($levelActivity as $activity)
                                        @php
                                            $arr['activity'] = json_decode($activity->activity,true);
                                        @endphp
                                        <tr>
                                            <td>{{$activity->level}}</td>
                                            <td>{{$arr['activity'][0]}}</td>
                                            <td>
                                                <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit-{{ $activity->id }}" style="font-size: 12px;">Edit <i class="fa fa-edit"></i></a>
                                                <a class="btn btn-sm btn-danger remove-level" href="javascript:;"
                                                    data-level-url="{{route('gym-admin.activity.destroy',$activity->id)}}">Delete<i class="fa fa-trash"></i></a>
                                                
                                                <div class="modal" id="edit-{{ $activity->id }}" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4>Edit Activity</h4>
                                                            </div>
                                                            <form action="{{ route('gym-admin.activity.update',$activity->id) }}" method="post">
                                                                {{csrf_field()}}
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <input type="text" placeholder="Level" value="{{$activity->level}}" class="form-control" name="level">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <textarea placeholder="Activities" class="form-control" name="activity[]">{{$arr['activity'][0]}}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
    <div class="modal" id="addActivity" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Add Activity for Level</h4>
                </div>
                <form action="{{ route('gym-admin.activity.create') }}" method="post">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" placeholder="Level" class="form-control" name="level" required>
                        </div>
                        <div class="form-group">
                            <textarea placeholder="Activities(separated with commas)" class="form-control" name="activity[]" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Create</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('footer')

    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
    {!! HTML::script('admin/global/plugins/bootbox/bootbox.min.js') !!}
    <script>
        var clientTable = $('#reserved_table');

        var table = clientTable.dataTable();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var UIBootbox = function () {
            var branchData = function () {
                $('.remove-level').on('click', function () {
                    var url = $(this).data('level-url');
                    bootbox.confirm({
                        message: "Do you want to delete this level?",
                        buttons: {
                            confirm: {
                                label: "Yes",
                                className: "btn-primary"
                            }
                        },
                        callback: function (result) {
                            if (result) {
                                $.easyAjax({
                                    url: url,
                                    type: 'POST',
                                    data: {
                                        '_method': 'delete' , '_token': '{{ csrf_token() }}'
                                    },
                                    success: function () {
                                        location.reload();
                                    }
                                });
                            }
                            else {
                                console.log('cancel');
                            }
                        }
                    })
                });
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
