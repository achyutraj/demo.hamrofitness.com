@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
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
                <span> Promotional Database </span>
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
                                <i class="fa fa-database font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Promotional Database</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a id="addTarget" href="{{route('gym-admin.promotion-db.create')}}" class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-100 table-striped table-bordered table-hover responsive" id="targets_table">
                                <thead>
                                <tr>
                                    <th class="all"> Name </th>
                                    <th class="min-tablet"> Email </th>
                                    <th class="min-tablet"> Mobile </th>
                                    <th class="min-tablet"> Age </th>
                                    <th class="min-tablet"> Gender </th>
                                    <th class="min-tablet"> Actions </th>
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
        function load_dataTable(){
            var table = $('#targets_table');

            // begin first table
            table.DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('gym-admin.promotion-db.ajax-create') }}",
                bDestroy:true,
                columns: [
                    { data: 'name', name: 'name'  },
                    { data: 'email', name: 'email'  },
                    { data: 'mobile', name: 'mobile'  },
                    { data: 'age', name: 'age'  },
                    { data: 'gender', name: 'gender'  },
                    { data: 'action', name: 'action'  },
                ]
            });
        }
    </script>

    <script>
        $('#targets_table').on('click','.remove-target',function(){
            var id = $(this).data('id');
            bootbox.confirm({
                message: "Do you want to delete this Promotional?",
                buttons: {
                    confirm: {
                        label: "Yes",
                        className: "btn-primary"
                    }
                },
                callback: function(result){
                    if(result){
                        var url = "{{route('gym-admin.promotion-db.destroy',':id')}}";
                        url = url.replace(':id',id);
                        $.easyAjax({
                            url: url,
                            type: "DELETE",
                            data: {id: id,_token: '{{ csrf_token() }}'},
                            success: function(){
                                load_dataTable();
                            }
                        });
                    }
                    else {
                        console.log('cancel');
                    }
                }
            })
        });
    </script>
    <script>
        $(document).ready(function(){
            load_dataTable();
        });
    </script>
@stop
