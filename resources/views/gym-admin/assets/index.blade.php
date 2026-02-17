@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/datepicker.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
    
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
                <span>Asset Management</span>
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
                                <i class="fa fa-dropbox font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Asset Management</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a id="sample_editable_1_new" data-target="#addAssets" data-toggle="modal" class="btn sbold dark"> 
                                        Add New<i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="portlet-body">
                            <div class="table-toolbar">
                                @if(session()->has('message'))
                                    <div class="alert alert-success">
                                        {{ session()->get('message') }}
                                    </div>
                                @endif
                                @if(session()->has('danger'))
                                    <div class="alert alert-danger">
                                        {{ session()->get('danger') }}
                                    </div>
                                @endif
                                {{-- asset management list with tab options--}}
                                <div class="asset-tab">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a data-toggle="tab" href="#assetList">Assets List</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#assetUsage">Assets Usage</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#assetService">Assets Servicing</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        {{-- asset list --}}
                                        <div id="assetList" class="tab-pane fade in active">
                                            @include('gym-admin.assets.asset')
                                        </div>
                                        <div id="assetUsage" class="tab-pane fade">
                                            @include('gym-admin.assets.employee')
                                        </div>
                                        <div id="assetService" class="tab-pane fade">
                                            @include('gym-admin.assets.service')
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- new asset modal form --}}
    <div class="modal fade bs-modal-md in" id="addAssets" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Add Assets</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <form action="{{route('gym-admin.asset-management.store')}}" method="post"
                        enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group form-md-line-input col-md-6">
                                <label><h4>Asset Tag *</h4></label>
                                <input type="text" class="form-control" placeholder="Asset Tag" name="tag"
                                    required>
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Asset Tag</span>
                            </div>
                            <div class="form-group form-md-line-input col-md-6">
                                <label><h4>Asset Name *</h4></label>
                                <input type="text" class="form-control" placeholder="Asset Name" name="name"
                                    required>
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Asset Name</span>
                            </div>
                            <div class="form-group form-md-line-input col-md-6">
                                <label><h4>Asset Brand Name *</h4></label>
                                <input type="text" class="form-control" placeholder="Brand Name"
                                    name="brand_name" required>
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Brand Name</span>
                            </div>
                            <div class="form-group form-md-line-input col-md-6">
                                <label><h4>Asset Supplier *</h4></label>
                                <select name="supplier" class="form-control" required>
                                    @foreach($suppliers as $s)
                                        <option value="{{$s->id}}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Asset Supplier</span>
                            </div>
                            <div class="form-group form-md-line-input col-md-6">
                                <label><h4>Asset Model *</h4></label>
                                <input type="text" class="form-control" placeholder="Asset Model"
                                    name="asset_model" required>
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Asset Model</span>
                            </div>
                            <div class="form-group form-md-line-input col-md-6">
                                <label><h4>Asset Quantity *</h4></label>
                                <input type="number" min="0" class="form-control" placeholder="Asset Quantity"
                                    name="quantity" required>
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Asset Quantity</span>
                            </div>
                            <div class="form-group form-md-line-input col-md-6">
                                <label><h4>Asset Purchase Date *</h4></label>
                                <input type="text" class="form-control date-picker" placeholder="Purchase Date"
                                    data-provide="datepicker" data-date-autoclose="true" data-date-today-highlight="true"
                                    name="purchase_date" required>
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Asset Purchase Date</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn blue mt-ladda-btn ladda-button">Create</button>
                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    {!! HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
    <script>
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'yyyy-mm-dd'
        });

        var table = $('#manage-branches');
        var table1 = $('#asset-usage');
        var table2 = $('#asset-service');
        
        table.dataTable();
        table1.dataTable();
        table2.dataTable();
        
        $(window).ready(function () {
            setTimeout(function () {
                $(".alert-success").remove();
            }, 3000);
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var UIBootbox = function () {
            var assetData = function () {
                $('.asset-remove').on('click', function () {
                    var url = $(this).data('asset-url');
                    bootbox.confirm({
                        message: "Do you want to delete this asset?",
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
            var employData = function () {
                $('.employ-remove').on('click', function () {
                    var url = $(this).data('employee-url');
                    bootbox.confirm({
                        message: "Do you want to delete this employee assign?",
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
            var serviceData = function () {
                $('.service-remove').on('click', function () {
                    var url = $(this).data('service-url');
                    bootbox.confirm({
                        message: "Do you want to delete this servicing?",
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
                    assetData()
                    employData()
                    serviceData()
                }
            }
        }();
        jQuery(document).ready(function () {
            UIBootbox.init()
        });
    </script>
@endsection
