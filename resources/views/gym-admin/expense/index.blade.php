@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
    <style>

        .table-checkable tr > td:first-child {
            vertical-align: middle;
            text-align: left;
            padding-left: 5%;
        }
    </style>
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
                <span>Expense</span>
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
                                <i class="fa fa-money font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Expenses</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a id="sample_editable_1_new" href="{{route('gym-admin.expense.create')}}" class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover order-column" id="expense">
                                <thead>
                                <tr>
                                    <th class="desktop"> Item  Category</th>
                                    <th class="desktop"> Item Name</th>
                                    <th class="desktop"> Supplier </th>
                                    <th class="desktop"> Date </th>
                                    <th class="desktop"> Price</th>
                                    <th class="desktop"> Status</th>
                                    <th class="desktop"> Remarks</th>
                                    <th class="desktop"> Actions </th>
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

    <div class="modal fade bs-modal-md in" id="gymExpenseModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}

    <script>
        var expenseTable = $('#expense');
        var table = expenseTable.dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('gym-admin.expense.get-expense')}}",
            columns: [
                {data: 'category_id', name: 'category_id'},
                {data: 'item_name', name: 'item_name'},
                {data: 'supplier_id', name: 'supplier_id'},
                {data: 'purchase_date', name: 'purchase_date'},
                {data: 'price', name: 'price'},
                {data: 'payment_status', name: 'payment_status'},
                {data: 'remarks', name: 'remarks'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            pageLength: 25,
            lengthMenu: [
                [25, 50, 75 , 100, -1],
                ['25', '50','75' ,'100', 'All']
            ],
        });

        $('#expense').on('click', '.delete-button', function () {
            var uid = $(this).data('expense-id');
            var url_modal = "{{route('gym-admin.remove-expense-modal',[':id'])}}";
            var url = url_modal.replace(':id',uid);
            $('#modelHeading').html('Remove Expense');
            $.ajaxModal("#gymExpenseModal", url);
        });

    </script>
@stop
