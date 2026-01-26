@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
@stop

@section('content')
    <div class="container-fluid"  >
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ route('gym-admin.dashboard.index') }}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ route('gym-admin.lockers.index') }}">Lockers</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{route('gym-admin.reservations.index')}}">Locker Reservation</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Pending Reservation</span>
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
                                <i class="fa {{ $gymSettings->currency->symbol }} font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Pending Reservation</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a class="btn btn-danger add-pending-btn-gap" href="{{ route('gym-admin.reservations.deleted') }}">Deleted
                                        Reservation ({{ $deletedCount }})</a>
                                    <a id="addTarget" href="{{route('gym-admin.reservations.index')}}" class="btn sbold dark"> Reservation
                                        <i class="fa fa-list"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="table-toolbar">
                            </div>
                            <table  class="table table-100 table-striped table-bordered table-hover order-column responsive" id="pending_table">
                                <thead>
                                <tr>
                                    <th class="all"> Client   </th>
                                    <th class="min-tablet"> Locker </th>
                                    <th class="min-tablet">  Amount </th>
                                    <th class="min-tablet"> Remaining Amount </th>
                                    <th class="min-tablet"> Start Date </th>
                                    <th class="min-tablet"> Expires On </th>
                                    <th class="min-tablet"> Action </th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>

    {{--Model--}}

@stop

@section('footer')
    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}
    <script>
        var table = $('#pending_table');
        table.dataTable({
            "responsive": true,
            "serverSide": true,
            "processing": true,
            "ajax": "{{ route('gym-admin.reservations.ajax-pending-data') }}",
            "aoColumns": [
                {'data': 'client_id', 'name': 'client_id'},
                {'data': 'locker_id', 'name': 'locker_id'},
                {'data': 'amount_to_be_paid', 'name': 'amount_to_be_paid'},
                {'data': 'remaining', 'name': 'remaining'},
                {'data': 'start_date', 'name': 'start_date'},
                {'data': 'end_date', 'name': 'end_date'},
                {'data': 'action', 'name': 'action'}
            ],
            "lengthMenu": [
                [25, 50, 75 , 100, -1],
                ['25', '50','75' ,'100', 'All']
            ],
            "pageLength": 25,
        });

        table.on('click','.remove-reservation',function(){
            var id = $(this).data('id');
            bootbox.confirm({
                message: "Do you want to delete this reservation?",
                buttons: {
                    confirm: {
                        label: "Yes",
                        className: "btn-primary"
                    }
                },
                callback: function(result){
                    if(result){
                        var url = "{{route('gym-admin.reservations.destroy',':id')}}";
                        url = url.replace(':id',id);

                        $.easyAjax({
                            url: url,
                            type: "DELETE",
                            success: function() {
                                table.fnDraw();
                            }
                        });
                    }
                }
            })
        });

    </script>

@stop
