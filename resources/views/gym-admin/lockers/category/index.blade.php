@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    <style>
        .edit{
            padding: 4px 10px 4px 0px !important;
        }
    </style>
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
                <span>Locker Category</span>
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
                                <i class="icon-present font-red"></i><span class="caption-subject font-red bold uppercase">Locker Category</span>
                            </div>

                            <div class="col-sm-2 col-xs-12">

                                <a href="{{ route('gym-admin.locker-category.create') }}" class="btn dark"> Add <span class="hidden-xs">Category</span>
                                    <i class="fa fa-plus"></i>
                                </a>

                            </div>

                        </div>
                        <div class="portlet-body">

                            <div class="row">

                                @foreach($locker_categories as $key=>$pkg)
                                    <div id="pkg-{{ $pkg->id }}" class="col-md-6 col-xs-12">
                                    <!-- BEGIN Portlet PORTLET-->
                                    <div class="portlet solid grey-cararra">
                                        <div class="portlet-title ">
                                            <div class="caption col-md-6 col-xs-12">
                                                <i class="fa fa-gift"></i>{{ ucwords($pkg->title) }}
                                            </div>
                                            <div class="actions col-md-6 col-xs-12">
                                                <div class="btn-group">
                                                    <span class="btn btn-success btn-sm btn-circle edit" title="Total Available Lockers">
                                                        {{ $pkg->countStatus($pkg->detail_id,$pkg->id,'available') }}
                                                    </span>
                                                </div>
                                                <div class="btn-group">
                                                    <a class="btn btn-sm btn-danger btn-circle edit" title="Total Reserved Lockers">
                                                        {{ $pkg->countStatus($pkg->detail_id,$pkg->id,'reserved') }}
                                                    </a>
                                                </div>
                                                <div class="btn-group">
                                                    <span class="btn btn-sm btn-info btn-circle edit" title="Total Switch Lockers">
                                                        {{ $pkg->countStatus($pkg->detail_id,$pkg->id,'switch') }}
                                                    </span>
                                                </div>
                                                <div class="btn-group">
                                                    <span class="btn btn-sm btn-warning btn-circle edit" title="Total Maintenance Lockers">
                                                        {{ $pkg->countStatus($pkg->detail_id,$pkg->id,'maintenance') }}
                                                    </span>
                                                </div>
                                                <div class="btn-group">
                                                    <span class="btn btn-sm red btn-circle edit" title="Total Destroy Lockers">
                                                        {{ $pkg->countStatus($pkg->detail_id,$pkg->id,'destroy') }}
                                                    </span>
                                                </div>
                                                <div class="btn-group">
                                                    <span class="btn btn-sm blue btn-circle edit" title="Total Repaired Lockers">
                                                        {{ $pkg->countStatus($pkg->detail_id,$pkg->id,'repaired') }}
                                                    </span>
                                                </div>

                                                <div class="btn-group">
                                                    <a class="btn blue-hoki btn-sm btn-circle" href="javascript:;" data-toggle="dropdown">
                                                        <i class="fa fa-gear"></i> <span class="hidden-xs">Action</span>
                                                        <i class="fa fa-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu pull-right">
                                                        <li>
                                                            <a href="{{ route('gym-admin.locker-category.edit',$pkg->uuid) }}">
                                                                <i class="fa fa-pencil"></i> Edit </a>
                                                        </li>
                                                        <li>
                                                            <a data-category-id="{{ $pkg->uuid }}" class="delete-button" href="javascript:;">
                                                                <i class="fa fa-trash-o"></i> Delete </a>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </div>
                                            <div class="caption col-md-12 col-xs-12">
                                                @if($pkg->price > 0)
                                                <div class="col-md-6">
                                                    <h5><strong>1 Month Price </strong>: {{ $gymSettings->currency->acronym }} {{ number_format($pkg->price) }}</h5>
                                                </div>
                                                @endif
                                                @if($pkg->three_month_price > 0)
                                                <div class="col-md-6">
                                                    <h5><strong>3 Month Price </strong>: {{ $gymSettings->currency->acronym }} {{ number_format($pkg->three_month_price) }}</h5>
                                                </div>
                                                @endif
                                                @if($pkg->six_month_price > 0)
                                                <div class="col-md-6">
                                                    <h5><strong>6 Month Price </strong>: {{ $gymSettings->currency->acronym }} {{ number_format($pkg->six_month_price) }}</h5>
                                                </div>
                                                @endif
                                                @if($pkg->one_year_price > 0)
                                                <div class="col-md-6">
                                                    <h5><strong>1 Year Price </strong>: {{ $gymSettings->currency->acronym }} {{ number_format($pkg->one_year_price) }}</h5>
                                                </div>
                                                @endif
                                                <div class="col-md-6"><h5>Total Lockers: {{ $pkg->lockers_count }}</h5></div>
                                            </div>
                                        </div>
                                        @if(!is_null($pkg->details))
                                        <div class="portlet-body">
                                            <div class="scroller" style="height:75px">
                                                {!! $pkg->details !!}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <!-- END GRID PORTLET-->
                                </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
@stop

@section('footer')
    {!! HTML::script('admin/global/plugins/bootbox/bootbox.min.js') !!}
    <script>
        var UIBootbox = function () {
            var o = function () {
                $(".delete-button").click(function () {
                    var memID = $(this).data('category-id');

                    bootbox.confirm({
                        message: "Do you want to delete this locker category?",
                        buttons: {
                            confirm: {
                                label: "Yes",
                                className: "btn-primary"
                            }
                        },
                        callback: function(result){
                            if(result){
                                var url = "{{route('gym-admin.locker-category.destroy',':id')}}";
                                url = url.replace(':id',memID);

                                $.easyAjax({
                                    url: url,
                                    type: "DELETE",
                                    data: {memID: memID,_token: '{{ csrf_token() }}'},
                                    success: function(){
                                        $('#pkg-'+memID).fadeOut();
                                    }
                                });
                            }
                            else {
                                console.log('cancel');
                            }
                        }
                    })

                })
            };
            return {
                init: function () {
                    o()
                }
            }
        }();
        jQuery(document).ready(function () {
            UIBootbox.init()
        });
    </script>
@stop

