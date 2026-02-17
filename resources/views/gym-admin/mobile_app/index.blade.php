@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}

@stop

@section('content')
    <div class="container-fluid"      >
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{'gym-admin.dashboard.index'}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Mobile App</span>
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
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="icon-phone font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Mobile App</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a id="sample_editable_1_new" href="{{route('gym-admin.mobile-app.create')}}" class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table style="width: 100%" class="table table-striped table-bordered table-hover table-checkable order-column" id="mobile_app">
                                <thead>
                                <tr>
                                    <th class="desktop"> Logo </th>
                                    <th class="max-desktop"> Branch Name </th>
                                    <th class="desktop"> Address </th>
                                    <th class="desktop"> Email </th>
                                    <th class="desktop"> About </th>
                                    <th class="desktop"> Services </th>
                                    <th class="desktop"> Price Plan </th>
                                    <th class="desktop"> Action </th>
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

    <div class="modal fade bs-modal-md in" id="gymEnquiryModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    {{--End Modal--}}
@stop

@section('footer')
    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}

    <script>
        var appTable = $('#mobile_app');

        var table = appTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('gym-admin.mobile-app.create.ajax')}}",
            columns: [
                {data: 'logo', name: 'logo'},
                {data: 'detail_id', name: 'detail_id'},
                {data: 'address', name: 'address'},
                {data: 'contact_mail', name: 'contact_mail'},
                {data: 'about', name: 'about'},
                {data: 'services', name: 'services'},
                {data: 'price_plan', name: 'price_plan'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        function deleteModal(id){
            var url_modal = "{{route('gym-admin.enquiry.modal',[':id'])}}";
            var url = url_modal.replace(':id',id);
            $('#modelHeading').html('Remove Enquiry');
            $.ajaxModal("#gymEnquiryModal", url);
        }

        $(function(){
            setTimeout(function() {
                $('.alert-message').slideUp();
            }, 3000);
        });
    </script>
@stop
