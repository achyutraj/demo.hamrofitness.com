@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/datatables.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css") }}">
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
                <span>Employ Leaves</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->

        <div class="page-content-inner">
            <div class="row">
                @if(session()->has('message'))
                    <div class="alert alert-message alert-success">
                        {{session()->get('message')}}
                    </div>
                @endif
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="fa fa-users font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Employ Leaves</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a id="sample_editable_1_new" data-toggle="modal" data-target="#addLeave"
                                       class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover table-100"
                                   id="manage-branches">
                                <thead>
                                <tr>
                                    <th class="max-desktop">Employee</th>
                                    <th class="desktop">Leave Type</th>
                                    <th class="desktop">Days</th>
                                    <th class="desktop">Start Date</th>
                                    <th class="desktop">End Date</th>
                                    <th class="desktop">Remaining</th>
                                    <th class="desktop">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($leaveBranch as $leave)
                                    @php
                                        $arr['days'] = json_decode($leave->days);
                                        $arr['leaveType'] = json_decode($leave->leaveType);
                                        $arr['startDate'] = json_decode($leave->startDate);
                                        $arr['endDate'] = json_decode($leave->endDate);
                                        $count = count($arr['days']);
                                        for ($i=0;$i<$count;$i++){
                                            $count1 = count($arr['days'][$i]);
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{$leave->first_name .' ' . $leave->middle_name .' ' . $leave->last_name}}</td>
                                        <td>
                                            @for($i=0;$i<$count;$i++)
                                                @for($j=0;$j<$count1;$j++)
                                                    {{$arr['leaveType'][$i][$j]}}<br>
                                                @endfor
                                            @endfor
                                        </td>
                                        <td>
                                            @for($i=0;$i<$count;$i++)
                                                @for($j=0;$j<$count1;$j++)
                                                    {{$arr['days'][$i][$j]}}<br>
                                                @endfor
                                            @endfor
                                        </td>
                                        <td>
                                            @for($i=0;$i<$count;$i++)
                                                @for($j=0;$j<$count1;$j++)
                                                    {{ date('M d, Y',strtotime($arr['startDate'][$i][$j])) }}<br>
                                                @endfor
                                            @endfor
                                        </td>
                                        <td>
                                            @for($i=0;$i<$count;$i++)
                                                @for($j=0;$j<$count1;$j++)
                                                    {{ date('M d, Y',strtotime($arr['endDate'][$i][$j])) }}<br>
                                                @endfor
                                            @endfor
                                        </td>
                                        <td>
                                            @for($i=0;$i<$count;$i++)
                                                @for($j=0;$j<$count1;$j++)
                                                    @foreach ($leaveType as $leaves)
                                                        @if ($leaves->name == $arr['leaveType'][$i][$j])
                                                            {{$remainingDays = $leaves->days - $arr['days'][$i][$j]}}
                                                            <br>
                                                        @endif
                                                    @endforeach
                                                @endfor
                                            @endfor
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn blue btn-xs dropdown-toggle" type="button"
                                                        data-toggle="dropdown"><i class="fa fa-gears"></i> <span
                                                            class="hidden-xs">Action</span>
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                                <ul class="dropdown-menu pull-right" role="menu">
                                                    <li>
                                                        <a data-toggle="modal"
                                                           data-target="#editLeave{{$leave->id}}"> <i
                                                                    class="fa fa-edit"></i> Edit</a>
                                                    </li>
                                                    <li>
                                                        <a data-url="{{route('gym-admin.employ.deleteLeave',$leave->id)}}"
                                                           class="remove-user"> <i class="fa fa-trash"></i> Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="modal fade bs-modal-lg in" id="editLeave{{$leave->id}}"
                                                 role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" id="modal-data-application">
                                                    <div class="modal-content">
                                                        <form action="{{route('gym-admin.employ.editLeave',$leave->id)}}"
                                                              method="post" enctype="multipart/form-data">
                                                            {{csrf_field()}}
                                                            <input type="hidden" name="employ_id"
                                                                   value="{{$leave->employ_id}}">
                                                            <div class="modal-header">
                                                                <h4>Edit Leave
                                                                    For {{$leave->first_name .' ' . $leave->middle_name .' ' . $leave->last_name}}</h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true"></button>
                                                                <span class="caption-subject font-red-sunglo bold uppercase"
                                                                      id="modelHeading"></span>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group form-md-line-input">
                                                                    <div class="input-icon right">
                                                                        <input type="number" readonly
                                                                               value="{{$leave->leaveDays}}"
                                                                               class="form-control"
                                                                               placeholder="Total Leave Days for Employee"
                                                                               name="leaveDays">
                                                                        <div class="form-control-focus"></div>
                                                                        <span class="help-block">Enter Total Leave Days for the Employ</span>
                                                                    </div>
                                                                </div>
                                                                <table class="table table-striped">
                                                                    <thead>
                                                                    <tr>
                                                                        <th style="width: 15%;">Leave Type</th>
                                                                        <th>Leave Days Taken</th>
                                                                        <th>Leave From</th>
                                                                        <th>Leave To</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <td>
                                                                        @for($i=0;$i<$count;$i++)
                                                                            @for($j=0;$j<$count1;$j++)
                                                                                <input type="text" class="form-control"
                                                                                       placeholder="Leave Type"
                                                                                       value="{{$arr['leaveType'][$i][$j]}}"
                                                                                       name="leaveType[]"><br>
                                                                            @endfor
                                                                        @endfor
                                                                    </td>
                                                                    <td>
                                                                        @for($i=0;$i<$count;$i++)
                                                                            @for($j=0;$j<$count1;$j++)
                                                                                <input type="number"
                                                                                       class="form-control"
                                                                                       placeholder="Days"
                                                                                       value="{{$arr['days'][$i][$j]}}"
                                                                                       name="days[]"><br>
                                                                            @endfor
                                                                        @endfor
                                                                    </td>
                                                                    <td>
                                                                        @for($i=0;$i<$count;$i++)
                                                                            @for($j=0;$j<$count1;$j++)
                                                                                <input name="startDate[]" data-date-format="yyyy-mm-dd"
                                                                                       class="form-control date"
                                                                                       value="{{$arr['startDate'][$i][$j]}}"
                                                                                       type="text"><br>
                                                                            @endfor
                                                                        @endfor
                                                                    </td>
                                                                    <td>
                                                                        @for($i=0;$i<$count;$i++)
                                                                            @for($j=0;$j<$count1;$j++)
                                                                                <input name="endDate[]" data-date-format="yyyy-mm-dd"
                                                                                       class="form-control date"
                                                                                       value="{{$arr['endDate'][$i][$j]}}"
                                                                                       type="text"><br>
                                                                            @endfor
                                                                        @endfor
                                                                    </td>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Update
                                                                </button>
                                                                <button type="button" class="btn btn-danger"
                                                                        data-dismiss="modal">Close
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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

        <div class="modal fade bs-modal-md in" id="addLeave" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" id="modal-data-application">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Create Leave</h4>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true"></button>
                        <span class="caption-subject font-red-sunglo bold uppercase"
                              id="modelHeading"></span>
                    </div>
                    <form action="{{route('gym-admin.employ.createLeave')}}"
                          method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="branch_id" value="{{$user->id}}">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-md-line-input">
                                        <label for="employName"><h4>Select Employ</h4>
                                        </label>
                                        <select onchange="getEmployId()" id="employName"
                                                class="bs-select" class="form-control"
                                                name="employ_id"
                                                style="width:100%;min-height:25px !important;"
                                                required>
                                            <option value="">Select Employ</option>
                                            @foreach($employees as $employ)
                                                <option value="{{$employ->id}}">{{$employ->first_name .' '. $employ->middle_name .' '. $employ->last_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-md-line-input">
                                        <label for="employName"><h4>Select Leave</h4>
                                        </label>
                                        <select class="bs-select" class="form-control"
                                                id="employName" name="leaveType[]"
                                                style="width:100%;min-height:25px !important;"
                                                required>
                                            <option value="">Select Leave Type</option>
                                            @foreach($leaveType as $employ)
                                                <option value="{{$employ->name}}">{{$employ->name . ' ' .($employ->days) . ' ' .'days'}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input">
                                <div class="input-icon right">
                                    <label for="Leave Days">Total Leave Days an Employ
                                        Can take</label>
                                    <input type="number" readonly value="{{$total}}"
                                           class="form-control"
                                           placeholder="Total Leave Days for Employee"
                                           name="leaveDays">
                                    <div class="form-control-focus"></div>
                                    <span class="help-block">Enter Total Leave Days for the Employ</span>
                                </div>
                            </div>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Leave Days Taken</th>
                                    <th>Leave From</th>
                                    <th>Leave To</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input type="number" class="form-control"
                                               placeholder="Leave Taken" name="days[]">
                                    </td>
                                    <td><input name="startDate[]" type="text"
                                               class="form-control date"
                                               data-date-format="yyyy-mm-dd"
                                               placeholder="Leave Starts from"></td>
                                    <td><input name="endDate[]" type="text"
                                               class="form-control date"
                                               data-date-format="yyyy-mm-dd"
                                               placeholder="Leave Starts To"></td>
                                </tr>
                                <div id="hiddenData"></div>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Create</button>
                            <button type="button" class="btn btn-danger"
                                    data-dismiss="modal">Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@stop

@section('footer')
    <script src="{{ asset("admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js") }}"></script>
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") }}">
    <script src="{{ asset("admin/global/plugins/ladda/spin.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/ladda/ladda.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/ui-buttons.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/components-bootstrap-select.min.js") }}"></script>
    <script src="{{ asset("admin/global/scripts/datatable.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/table-datatables-managed.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/datatables.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootbox/bootbox.min.js") }}"></script>

    <script>
        // Declare a global object to store view data.
        var viewData;

        viewData = {};

        $(function () {
            // Update the viewData object with the current field keys and values.
            function updateViewData(key, value) {
                viewData[key] = value;
            }

            // Register all bindable elements
            function detectBindableElements() {
                var bindableEls;

                bindableEls = $('[data-bind]');

                // Add event handlers to update viewData and trigger callback event.
                bindableEls.on('change', function () {
                    var $this;

                    $this = $(this);

                    updateViewData($this.data('bind'), $this.val());

                    $(document).trigger('updateDisplay');
                });

                // Add a reference to each bindable element in viewData.
                bindableEls.each(function () {
                    updateViewData($(this));
                });
            }

            // Trigger this event to manually update the list of bindable elements, useful when dynamically loading form fields.
            $(document).on('updateBindableElements', detectBindableElements);

            detectBindableElements();
        });

        $(function () {
            // An example of how the viewData can be used by other functions.
            function updateDisplay() {
                var updateEls;

                updateEls = $('[data-update]');

                updateEls.each(function () {
                    $(this).html(viewData[$(this).data('update')]);
                });
            }


            // Run updateDisplay on the callback.
            $(document).on('updateDisplay', updateDisplay);
        });
        var table = $('#manage-branches');
        table.dataTable({
            responsive: true,
        });

        $("*[bind]").on('change keyup', function (e) {
            var to_bind = $(this).attr('bind');
            $("*[bind='" + to_bind + "']").html($(this).val());
            $("*[bind='" + to_bind + "']").val($(this).val());
        });
        $("span[bind]").bind("DOMSubtreeModified", function () {
            var to_bind = $(this).attr('bind');
            $("*[bind='" + to_bind + "']").html($(this).html());
            $("*[bind='" + to_bind + "']").val($(this).html());
        });

        function getEmployId() {
            employId = $('#employName').val();
            @foreach($leaveBranch as $leave)
                leaveEmployId =  <?php echo $leave->employ_id; ?> ;
            if (leaveEmployId == employId) {
                var html = `@php
                    $arr['days'] = json_decode($leave->days);
                    $arr['leaveType'] = json_decode($leave->leaveType);
                    $arr['startDate'] = json_decode($leave->startDate);
                    $arr['endDate'] = json_decode($leave->endDate);
                    $count = count($arr['days']);
                    for ($i=0;$i<$count;$i++){
                        $count1 = count($arr['days'][$i]);
                    }
                @endphp
                @for($i=0;$i<$count;$i++)
                @for($j=0;$j<$count1;$j++)
                <input type="hidden" value="{{$arr['leaveType'][$i][$j]}}" name="leaveType[]">
                                    <input type="hidden" value="{{$arr['days'][$i][$j]}}" name="days[]">
                                    <input name="startDate[]" value="{{$arr['startDate'][$i][$j]}}" type="hidden">
                                    <input name="endDate[]" value="{{$arr['endDate'][$i][$j]}}" type="hidden">
                                @endfor
                @endfor`;
                $('#hiddenData').empty();
                $('#hiddenData').append(html);

            }
            @endforeach

        }

        $(function () {
            setTimeout(function () {
                $('.alert-message').slideUp();
            }, 3000);
            $('.date').datepicker({
                rtl: App.isRTL(),
                autoclose: true,
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var UIBootbox = function () {
            var branchData = function () {
                $('.remove-user').on('click', function () {
                    var url = $(this).data('url');
                    bootbox.confirm({
                        message: "Do you want to delete this employee leave?",
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
                    branchData()
                }
            }
        }();
        jQuery(document).ready(function () {
            UIBootbox.init()
        });
    </script>
@stop
