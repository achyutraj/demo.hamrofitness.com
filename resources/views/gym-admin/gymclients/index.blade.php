@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/datatables.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css") }}">
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
                <span>Clients</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    @if(session()->has('message'))
                        <div class="alert alert-message alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                    @if(session()->has('danger'))
                            <div class="alert alert-danger alert-success">
                                {{ session()->get('danger') }}
                            </div>
                    @endif
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="icon-users font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Customers</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a id="sample_editable_1_new" href="{{route('gym-admin.client.create')}}" class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover order-column" style="width: 100%"
                                   id="gym_clients">
                                <thead>
                                <tr>
                                    <th class="max-desktop"> Name</th>
                                    <th class="desktop"> Referred By</th>
                                    <th class="desktop"> Mobile</th>
                                    <th class="desktop"> Joined</th>
                                    <th class="desktop"> Subscription</th>
                                    <th class="desktop"> Locker</th>
                                    <th class="desktop"> Username</th>
                                    <th class="desktop"> Actions</th>
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
    {{--Modal Start--}}

    <div class="modal fade bs-modal-md in" id="gymClientsModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

    {{--End Modal--}}
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

        var clientTable = $('#gym_clients');

        var table = clientTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('gym-admin.client.ajax_create')}}",
            columns: [
                {data: 'first_name', name: 'first_name'},
                {data: 'referred_client_id', name: 'referred_client_id'},
                {data: 'mobile', name: 'mobile'},
                {data: 'joining_date', name: 'joining_date'},
                {data: 'membership', name: 'membership'},
                {data: 'locker', name: 'locker'},
                {data: 'username', name: 'username', visible: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            pageLength: 25,
            lengthMenu: [
                [25, 50, 75 , 100, -1],
                ['25', '50','75' ,'100', 'All']
            ],
        });

        function deleteModal(id) {
            var url_modal = "{{route('gym-admin.remove.modal',[':id'])}}";
            var url = url_modal.replace(':id', id);
            $('#modelHeading').html('Remove Client');
            $.ajaxModal("#gymClientsModal", url);
        }

        $(function () {
            setTimeout(function () {
                $('.alert-message').slideUp();
            }, 3000);
        });
    </script>
@stop
