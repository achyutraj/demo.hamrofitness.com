@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/Responsive-2.0.2/css/responsive.bootstrap.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/Responsive-2.0.2/css/responsive.dataTables.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
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
                <span>Progress Tracker</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="m-heading-1 border-green m-bordered">
                        <h3>Progress Tracker</h3>
                        <p>In this section, you can track fitness progress of clients having more than one body measurement history. </p>
                        <ul>
                            <li>From Date Must be Lesser or Previous Date</li>
                            <li>To Date Must be Greater Or Recent Date</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="portlet light portlet-fit">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-target font-red"></i><span class="caption-subject font-red bold uppercase">Select Client</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-5">
                            {!! Form::open(['id'=>'createTargetReport','class'=>'ajax-form']) !!}
                            <div class="form-body">
                                <div class="form-group form-md-line-input ">
                                    <select class="bs-select form-control targetData" data-live-search="true"
                                            data-size="8" name="client" id="client">
                                        <option value="null" selected> Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{$client->id}}">{{$client->fullName}}</option>
                                    @endforeach
                                    </select>
                                    <label for="title">Select Client</label>
                                    <span class="help-block"></span>
                                </div>
                                <div id="select_date_div">

                                </div>
                                <div class="form-actions" style="margin-top: 70px">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn dark mt-ladda-btn ladda-button"
                                                    data-style="zoom-in" id="save-form">
                                                <span class="ladda-label"><i class="icon-arrow-up"></i> Submit</span>
                                            </button>
                                            <button type="reset" class="btn default">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="targetDataTable">
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
@stop

@section('footer')
    {!! HTML::script('admin/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/DataTables-1.10.11/media/js/jquery.dataTables.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/DataTables-1.10.11/media/js/dataTables.bootstrap.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/Responsive-2.0.2/js/dataTables.responsive.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/Responsive-2.0.2/js/responsive.bootstrap.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! HTML::script('admin/pages/scripts/components-bootstrap-select.min.js') !!}
    <script>
        $('#client').on('change',function(){
           var clientId = $(this).val();
           var url = "{{ route('gym-admin.measurements.getClientDate',[':id']) }}";
           url = url.replace(':id', clientId);
            $.easyAjax({
                url: url,
                type: 'GET',
                data: {clientID: clientId},
                success: function (response) {
                    $('#select_date_div').html(response.data);
                }
            })
        });

        $('#save-form').click(function () {
            var client = $('#client').val();
            var fromDate = $('#from_date').val();
            var toDate = $('#to_date').val();
            if (client == 'null') {
                $.showToastr('Please Select a Client', 'error');
            }
            if(fromDate == null || toDate == null){
                $.showToastr('Please Select Date', 'error');
            }
            if(fromDate > toDate){
                $.showToastr('From Date Must be less or previous than To Date', 'error');
            }else if(fromDate == toDate){
                $.showToastr('Enter different Date', 'error');
            } else {
                $.easyAjax({
                    url: "{{route('gym-admin.measurements.clientProgressReport')}}",
                    container: '#createTargetReport',
                    type: "POST",
                    data: $('#createTargetReport').serialize(),
                    success: function (res) {
                        if (res.status == 'success') {
                            $('#targetDataTable').html(res.data);
                        }
                    }
                });
            }

        });
    </script>
@stop
