@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/datatables.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css") }}">
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
                <span>Enquiry</span>
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
                                <i class="icon-earphones-alt font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Enquiries</span>
                                <p class="text-danger">Note: <a target="_blank" href="{{ env('APP_URL').'/enquiry-form/'.$common_details->slug }}"> Enquiry Form Link</a> OR
                                    <a href="{{ route('gym-admin.enquiry.downloadQrCode')}}"> QRCode
                                </a>.</p>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a id="sample_editable_1_new" href="{{route('gym-admin.enquiry.create')}}" class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table style="width: 100%" class="table table-striped table-bordered table-hover order-column" id="gym_enquiry">
                                <thead>
                                <tr>
                                    <th class="max-desktop"> Name </th>
                                    <th class="desktop"> Mobile </th>
                                    <th class="desktop"> Email </th>
                                    <th class="desktop"> Occupation </th>
                                    <th class="desktop"> Last Follow up </th>
                                    <th class="desktop"> Next Follow up </th>
                                    <th class="desktop"> Follow Up </th>
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
        var enquiryTable = $('#gym_enquiry');

        var table = enquiryTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('gym-admin.enquiry.create.ajax')}}",
            columns: [
                {data: 'customer_name', name: 'customer_name'},
                {data: 'mobile', name: 'mobile'},
                {data: 'email', name: 'email'},
                {data: 'occupation', name: 'occupation'},
                {data: 'previous_follow_up', name: 'previous_follow_up'},
                {data: 'next_follow_up', name: 'next_follow_up'},
                {data: 'view_follow_up', name: 'view_follow_up',orderable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            pageLength: 25,
            lengthMenu: [
                [25, 50, 75 , 100, -1],
                ['25', '50','75' ,'100', 'All']
            ],
        });

        function deleteModal(id){
            var url_modal = "{{route('gym-admin.enquiry.modal',[':id'])}}";
            var url = url_modal.replace(':id',id);
            $('#modelHeading').html('Remove Enquiry');
            $.ajaxModal("#gymEnquiryModal", url);
        }

        enquiryTable.on('click', '.new-follow-up', function () {
            var enquiryId = $(this).data('enquiry-id');
            var url_modal = "{{route('gym-admin.enquiry.follow-modal',[':id'])}}";
            var url = url_modal.replace(':id',enquiryId);
            $('#modelHeading').html('Follow Up');
            $.ajaxModal("#gymEnquiryModal", url);
        });

        enquiryTable.on('click', '.view-follow-up', function () {
            var enquiryId = $(this).data('enquiry-id');
            var url_modal = "{{route('gym-admin.enquiry.view-follow-modal',[':id'])}}";
            var url = url_modal.replace(':id',enquiryId);
            $('#modelHeading').html('Follow Up');
            $.ajaxModal("#gymEnquiryModal", url);
        });

        $('#gymEnquiryModal').on('click', '#add-follow-up', function(){
            $.easyAjax({
                url: "{{route('gym-admin.enquiry.saveFollowUp')}}",
                container:'#followUpForm',
                type: "POST",
                data:$('#followUpForm').serialize(),
                success: function (response) {
                    if(response.status == 'success'){
                        $('#gymEnquiryModal').modal('hide');
                        table._fnDraw();
                    }
                }
            })
        });

        $('#gymEnquiryModal').on('click', '#removeEnquiry', function(){
            var enquiryId = $(this).data('enquiry-id');
            var url = "{{route('gym-admin.enquiry.destroy',[':id'])}}";
            url = url.replace(':id',enquiryId);
            $.easyAjax({
                url: url,
                container:'.modal-body',
                data: { '_token': '{{ csrf_token() }}' },
                type: "DELETE",
                success: function (response) {
                    if(response.status == 'success'){
                        $('#gymEnquiryModal').modal('hide');
                        table._fnDraw();
                    }
                }
            });
        });
        $(function(){
            setTimeout(function() {
                $('.alert-message').slideUp();
            }, 3000);
        });
    </script>
@stop
