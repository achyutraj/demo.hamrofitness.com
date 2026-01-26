@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
@stop

@section('content')
    <div class="container-fluid">
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{route('gym-admin.dashboard.index')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Employ Leaves</span>
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
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="fa fa-users font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Employ Leaves</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a id="sample_editable_1_new" data-toggle="modal" data-target="#addLeave"
                                       class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover table-100" id="manage-branches">
                                <thead>
                                <tr>
                                    <th class="max-desktop">Employee</th>
                                    <th class="desktop">Leave Type</th>
                                    <th class="desktop">Days</th>
                                    <th class="desktop">Start Date</th>
                                    <th class="desktop">End Date</th>
                                    <th class="desktop">Remaining</th>
                                    <th class="desktop">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($employLeaves as $leave)
                                    <tr>
                                        <td>{{$leave->employee->fullName ?? ''}}</td>
                                        <td>{{$leave->leaveType}}</td>
                                        <td>{{$leave->days}}</td>
                                        <td>{{ $leave->startDate ? date('M d, Y',strtotime($leave->startDate)) : '' }}</td>
                                        <td>{{ $leave->endDate ? date('M d, Y',strtotime($leave->endDate)) : '' }}</td>
                                        <td>{{$leave->remaining_days}}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn blue btn-xs dropdown-toggle" type="button"
                                                        data-toggle="dropdown"><i class="fa fa-gears"></i> <span
                                                            class="hidden-xs">Action</span>
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                                <ul class="dropdown-menu pull-right" role="menu">
                                                    <li>
                                                        <a data-toggle="modal"
                                                           data-target="#editLeave{{$leave->id}}{{$loop->index}}"> <i
                                                                    class="fa fa-edit"></i> Edit</a>
                                                    </li>
                                                    <li>
                                                        <a data-url="{{route('gym-admin.employ.deleteLeave',$leave->id)}}"
                                                           data-index="{{ isset($leave->is_legacy) && $leave->is_legacy ? $leave->legacy_index : '' }}"
                                                           class="remove-user"> <i class="fa fa-trash"></i> Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            {{-- Employ Leave edit modal --}}
                                             @include('gym-admin.employ.leaves.edit')
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

        {{-- Employ Leave create modal --}}
        @include('gym-admin.employ.leaves.create')

    </div>
@stop

@section('footer')
    {!! HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! HTML::script('admin/pages/scripts/components-bootstrap-select.min.js') !!}
    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
    {!! HTML::script('admin/global/plugins/bootbox/bootbox.min.js') !!}

    <script>
        var table = $('#manage-branches');
        table.dataTable({
            responsive: true,
            pageLength: 25,
            lengthMenu: [
                [25, 50, 75 , 100, -1],
                ['25', '50','75' ,'100', 'All']
            ],
        });

        $(function () {
            setTimeout(function () {
                $('.alert-message').slideUp();
            }, 3000);
            $('.date').datepicker({
                rtl: App.isRTL(),
                autoclose: true,
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.remove-user', function () {
            var url = $(this).data('url');
            var idx = $(this).data('index');
            bootbox.confirm({
                message: "Do you want to delete this employee leave?",
                buttons: { confirm: { label: "Yes", className: "btn-primary" } },
                callback: function (result) {
                    if (result) {
                        $.easyAjax({
                            url: url,
                            type: 'POST',
                            data: { '_method': 'delete', 'index': idx, '_token': '{{ csrf_token() }}' },
                            success: function () {
                                location.reload();
                            }
                        });
                    }
                }
            })
        });
    </script>
@stop
