@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
    <style>

    </style>
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
                <span>Class Schedule</span>
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
                                <i class="icon-users font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Class Schedule</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a class="btn sbold dark" href="{{ route('gym-admin.class-schedule.create') }}">Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover table-100" id="gym_class">
                                <thead>
                                <tr>
                                    <th class="max-desktop"> Class Name</th>
                                    <th class="desktop"> Trainer</th>
                                    <th class="desktop"> Days</th>
                                    <th class="desktop"> Start Time</th>
                                    <th class="desktop"> End Time</th>
                                    <th class="desktop"> Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($class as $schedule)
                                    @php
                                        $arr = json_decode($schedule->days);
                                        $sTime = new dateTime($schedule->startTime);
                                        $eTime = new dateTime($schedule->endTime);
                                        $j = count($arr);
                                    @endphp
                                    <tr>

                                        <td class="text-center">{{$schedule->classes->class_name ?? ''}}</td>
                                        <td class="text-center">{{$schedule->trainers->name ?? ''}}</td>
                                        <td>@for($i=0;$i<$j;$i++){{ucfirst($arr[$i])}}{!! '<br>' !!}  @endfor</td>
                                        <td>{{$sTime->format('h:i a')}}</td>
                                        <td>{{$eTime->format('h:i a')}}</td>
                                        <td>
                                            @if($schedule->has_client == 1)
                                            <a class="btn btn-info" data-toggle="modal" data-target="#view-modal-{{$schedule->uuid}}" >View Client <i class="fa fa-eye"></i></a>
                                            {{--view clientname modal--}}
                                            <div class="modal" tabindex="-1" id="view-modal-{{$schedule->uuid}}" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 style="font-weight: 600;" class="modal-title"> Assign Client Name List</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="table table-stripped table-bordered table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Client Name</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($schedule->clients as $key => $client)
                                                                        <tr>
                                                                            <td>{{ $key+1 }}</td>
                                                                            <td>{{ $client->fullName }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            @endif
                                            <a class="btn btn-primary" href="{{ route('gym-admin.class-schedule.edit',$schedule->uuid) }}">Edit <i class="fa fa-edit"></i></a>

                                            <a data-schedule-id="{{$schedule->uuid}}" class="btn btn-danger remove-schedule">Delete <i class="fa fa-trash"></i></a>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
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

        $('select.select2').select2({
            placeholder: "Please Select",
        }).focus(function () {
            $(this).select2('focus');
        });

        var table = $('#gym_class');
        table.dataTable({
            "responsive": true,
            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            "language": {
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "emptyTable": "No data available in table",
                "info": "Showing _START_ to _END_ of _TOTAL_ records",
                "infoEmpty": "No records found",
                "infoFiltered": "(filtered1 from _MAX_ total records)",
                "lengthMenu": "Show _MENU_",
                "search": "Search:",
                "processing": "<i class='fa fa-spinner faa-spin animated'></i> Processing",
                "zeroRecords": "No matching records found",
                "paginate": {
                    "previous": "Prev",
                    "next": "Next",
                    "last": "Last",
                    "first": "First"
                }
            },
            "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

            "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.

            "columnDefs": [{
                "targets": 0,
                "orderable": false,
                "searchable": false
            }],

            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 5,
            "pagingType": "bootstrap_full_number",
            "columnDefs": [{  // set default column settings
                'orderable': false,
                'targets': [0]
            }, {
                "searchable": false,
                "targets": [0]
            }],
            "order": [
                [1, "asc"]
            ] // set first column as a default sort by asc
        });
        $(function(){
            setTimeout(function() {
                $('.alert-message').slideUp();
            }, 3000);
        });

        $('#gym_class').on('click', '.remove-schedule', function () {
            var id = $(this).data('schedule-id');
            bootbox.confirm({
                message: "Do you want to delete this class schedule?",
                buttons: {
                    confirm: {
                        label: "Yes",
                        className: "btn-primary"
                    }
                },
                callback: function (result) {
                    if (result) {
                        var url = "{{route('gym-admin.class-schedule.destroy',':id')}}";
                        url = url.replace(':id', id);
                        $.easyAjax({
                            url: url,
                            type: "DELETE",
                            data: {id: id, _token: '{{ csrf_token() }}'},
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
    </script>
@stop
