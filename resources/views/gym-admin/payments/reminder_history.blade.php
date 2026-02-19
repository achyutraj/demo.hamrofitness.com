@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/datatables.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css") }}">
@stop

@section('content')
    <div class="container-fluid"      >
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{route('gym-admin.dashboard.index')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Reminder History</span>
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
                                <i class=" fa fa-list font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Payments Reminder History</span>
                            </div>

                            <div class="actions col-sm-2 col-xs-12">

                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover table-100" id="payments-reminder">
                                <thead>
                                <tr>
                                    <th class="desktop"> Date </th>
                                    <th class="max-desktop">Customers </th>
                                    <th class="desktop"> Message</th>
                                    <th class="desktop"> Mobile</th>
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



    <div class="modal fade bs-modal-md in" id="reminderModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    {{--Model End--}}
@stop

@section('footer')


    <script src="{{ asset("admin/global/scripts/datatable.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/table-datatables-managed.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/datatables.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootstrap-maxlength/bootstrap-maxlength.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/components-bootstrap-maxlength.min.js") }}"></script>

    <script>
        jQuery(document).ready(function() {

            load_dataTable();
        });
        function load_dataTable()
        {
            var table = $('#payments-reminder');
            // begin first table
            table.dataTable({
                responsive: true,
                ajax: "{{ route('gym-admin.client-purchase.ajax-reminder-history') }}",
                bDestroy:true,
                columns: [
                    {data: 'created_at', name: 'created_at'},
                    {data: 'reminder_text', name: 'reminder_text'},
                    {data: 'client_id', name: 'client_id'},
                    {data: 'mobile', name: 'mobile'},
                ]
            });
        }

        $('#payments-reminder').on('click','.show-reminder', function () {
            var id = $(this).data('id');
            var show_url = "{{route('gym-admin.client-purchase.show-model',['#id'])}}";
            var url = show_url.replace('#id', id);
            $('#modelHeading').html('Reminder');
            $.ajaxModal("#reminderModal", url);
        });

    </script>

@stop
