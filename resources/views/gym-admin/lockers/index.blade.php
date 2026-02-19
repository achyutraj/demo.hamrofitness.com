@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/datatables.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css") }}">
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
                <span>Locker Management</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">

                    <div class="portlet light portlet-fit">
                        <div class="portlet-title col-xs-12">
                            <div class="caption col-sm-10 col-xs-12">
                                <i class="icon-key font-red"></i><span class="caption-subject font-red bold uppercase">Lockers</span>
                            </div>

                            <div class="actions col-sm-2 col-xs-12">
                                <a href="{{ route('gym-admin.lockers.create') }}" class="btn dark"> Add <span class="hidden-xs">Locker</span>
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table style="width: 100%" class="table table-striped table-bordered table-hover order-column" id="lockers_table">
                                <thead>
                                <tr>
                                    <th class="max-desktop"> Locker Category </th>
                                    <th class="max-desktop"> Locker Number </th>
                                    <th class="desktop"> Status </th>
                                    <th class="desktop"> Details </th>
                                    <th class="desktop"> Action </th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>

    <div class="modal fade bs-modal-md in" id="lockerModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@stop

@section('footer')
    <script src="{{ asset("admin/global/scripts/datatable.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/table-datatables-managed.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/datatables.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/ladda/spin.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/ladda/ladda.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/ui-buttons.min.js") }}"></script>

    <script>
        var lockerTable = $('#lockers_table');

        var table = lockerTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('gym-admin.lockers.ajax-create')}}",
            columns: [
                {data: 'locker_category_id', name: 'locker_category_id'},
                {data: 'locker_num', name: 'locker_num'},
                {data: 'status', name: 'status'},
                {data: 'details', name: 'details'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#lockers_table').on('click', '.delete-button', function () {
            var uid = $(this).data('locker-id');
            var url_modal = "{{route('gym-admin.lockers.modal',[':uuid'])}}";
            var url = url_modal.replace(':uuid',uid);
            $('#modelHeading').html('Remove Locker');
            $.ajaxModal("#lockerModal", url);
        });

        $('#lockerModal').on('click', '#remove', function(){
            var lockerId = $(this).data('locker-id');
            var url = "{{route('gym-admin.lockers.destroy',[':id'])}}";
            url = url.replace(':id',lockerId);
            $.easyAjax({
                url: url,
                container:'.modal-body',
                data: { '_token': '{{ csrf_token() }}' },
                type: "DELETE",
                success: function (response) {
                    if(response.status == 'success'){
                        $('#lockerModal').modal('hide');
                        table._fnDraw();
                    }
                }
            });
        });
    </script>
@stop

