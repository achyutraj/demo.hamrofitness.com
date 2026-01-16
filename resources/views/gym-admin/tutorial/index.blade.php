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
                <span>Gym Tutorial</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    @if(session()->has('success'))
                        <div class="alert alert-message alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    <div class="portlet light portlet-fit">
                        <div class="portlet-title col-xs-12">
                            <div class="caption col-sm-10 col-xs-12">
                                <i class="fa fa-bicycle font-red"></i><span class="caption-subject font-red bold uppercase">Tutorials</span>
                            </div>

                            <div class="actions col-sm-2 col-xs-12">
                                <a href="{{ route('gym-admin.tutorials.create') }}" class="btn dark"> Add <span class="hidden-xs">Tutorial</span>
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table style="width: 100%" class="table table-striped table-bordered table-hover order-column" id="tutorials_table">
                                <thead>
                                <tr>
                                    <th class="max-desktop"> Title </th>
                                    <th class="max-desktop"> Type </th>
                                    <th class="desktop"> Status </th>
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
    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}

    <script>
        var lockerTable = $('#tutorials_table');

        var table = lockerTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('gym-admin.tutorials.ajax-create')}}",
            columns: [
                {data: 'title', name: 'title'},
                {data: 'type', name: 'type'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#tutorials_table').on('click', '.delete-button', function () {
            var uid = $(this).data('tutorial-id');
            var url_modal = "{{route('gym-admin.tutorials.modal',[':uuid'])}}";
            var url = url_modal.replace(':uuid',uid);
            $('#modelHeading').html('Remove Tutorial');
            $.ajaxModal("#lockerModal", url);
        });

        $('#lockerModal').on('click', '#remove', function(){
            var lockerId = $(this).data('tutorial-id');
            var url = "{{route('gym-admin.tutorials.destroy',[':id'])}}";
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

