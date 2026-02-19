@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/datatables.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">

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
                <span>Party</span>
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
                               <span class="caption-subject font-red bold uppercase">Parties</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a id="sample_editable_1_new" href="{{route('gym-admin.suppliers.create')}}" class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover table-100"
                                   id="gym_clients">
                                <thead>
                                <tr>
                                    <th class="desktop"> Name</th>
                                    <th class="desktop"> Email</th>
                                    <th class="desktop"> Mobile</th>
                                    <th class="desktop"> Address</th>
                                    <th class="desktop"> Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($suppliers as $s)
                                        <tr>
                                            <td>{{$s->name}}</td>
                                            <td>{{$s->email}}</td>
                                            <td>{{$s->phone}}</td>
                                            <td>{{$s->address}}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i> <span class="hidden-xs">Action</span>
                                                        <i class="fa fa-angle-down"></i>
                                                    </button>
                                                    <ul class="dropdown-menu pull-right" role="menu">
                                                        <li>
                                                            <a href="{{ route('gym-admin.suppliers.edit',$s->id) }}">
                                                                <i class="fa fa-pencil"></i> Edit </a>
                                                        </li>
                                                        <li>
                                                            <a data-supplier-id="{{ $s->id }}" class="delete-button" href="javascript:;">
                                                                <i class="fa fa-trash-o"></i> Delete </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
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
        $(document).ready(function () {
            $('#gym_clients').DataTable({
                pageLength: 25,
                lengthMenu: [
                    [25, 50, 75 , 100, -1],
                    ['25', '50','75' ,'100', 'All']
                ],
            });
        });
        var UIBootbox = function () {
            var o = function () {
                $(".delete-button").click(function () {
                    var memID = $(this).data('supplier-id');

                    bootbox.confirm({
                        message: "Do you want to delete this supplier?",
                        buttons: {
                            confirm: {
                                label: "Yes",
                                className: "btn-primary"
                            }
                        },
                        callback: function(result){
                            if(result){

                                var url = "{{route('gym-admin.suppliers.destroy',':id')}}";
                                url = url.replace(':id',memID);

                                $.easyAjax({
                                    url: url,
                                    type: "DELETE",
                                    data: {memID: memID,_token: '{{ csrf_token() }}'},
                                    success: function(){
                                        $('#supplier-'+memID).fadeOut();
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

