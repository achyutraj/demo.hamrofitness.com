@extends('layouts.gym-merchant.gymbasic')
@section('CSS')
    <style>
        h4, h5 {
            font-weight: 600;
        }

        .danger {
            color: red;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{route('gym-admin.dashboard.index')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Training Plan</span>
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
                                <i class="fa fa-heart-o font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Training Plan</span>
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
                                <div class="asset-tab">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a data-toggle="tab" href="#home">Default Training Plan</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#menu1">User Training Plan</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="home" class="tab-pane fade in active">
                                            @include('gym-admin.training_plan.default')
                                        </div>
                                        <div id="menu1" class="tab-pane fade">
                                            @include('gym-admin.training_plan.user')
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
   
@endsection
@section('footer')
    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
    {!! HTML::script('admin/global/plugins/bootbox/bootbox.min.js') !!}

    <script>
        $(function(){
            setTimeout(function() {
                $('.alert-message').slideUp();
            }, 3000);
        });
        $(document).ready(function() {
            $('#paymentTable').DataTable();
        } );
    </script>
    <script>
        $('#myTab a[href="#defaultTraining"]').tab('show') // Select tab by name
        $('#myTab li:first-child a').tab('show') // Select first tab
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            setTimeout(function () {
                location.reload();
            },100);

        }
       
        /* default training plan */
        $("select.level").change(function(){
            let selectedLevel = $(this).children("option:selected").val();
            @foreach($levelActivity as $activity)
                level = <?php echo json_encode($activity->level); ?>;
                if (level === selectedLevel){
                    table = `

                        <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Activity</th>
                                <th>Days</th>
                                <th>Sets</th>
                                <th>Repetition (Times)</th>
                                <th>Weights (KG)</th>
                                <th>Rest Time (Minutes)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $arr = json_decode($activity['activity'],true);
                                $activities = explode(',',$arr[0]);
                                $i = count($activities);
                            @endphp
                                @for($j=0;$j<$i;$j++)
                                <tr>
                                    <td><input type="text" readonly class="form-control" value="{{$activities[$j]}}" class="form-control" name="activity[]"></td>
                                    <td>
                                        <select class="select2 bs-select" data-live-search="true" data-size="8" multiple="multiple" name="days[{{$j}}][]" required>
                                            <option value="sunday">Sunday</option>
                                            <option value="monday">Monday</option>
                                            <option value="tuesday">Tuesday</option>
                                            <option value="wednesday">Wednesday</option>
                                            <option value="thursday">Thursday</option>
                                            <option value="friday">Friday</option>
                                            <option value="saturday">Saturday</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control" name="sets[]" required></td>
                                    <td><input type="number" class="form-control" name="repetition[]" required></td>
                                    <td><input type="number" class="form-control" name="weights[]"></td>
                                    <td><input type="number" class="form-control" name="restTime[]" required></td>
                                </tr>
                                @endfor
                        </tbody>
                        </table>
                        `;


                    $('#levelActivityDataForm').empty();
                    $('#levelActivityDataForm').append(table);
                    $('select.select2').select2({
                        placeholder: "Please Select",
                    }).focus(function () {
                        $(this).select2('focus');
                    });
            }
            @endforeach
        });
        $("select.levelEdit").change(function() {
            let selectedLevel = $(this).children("option:selected").val();
            @foreach($defaultTrainingPlan as $defaultTraining)
                level = <?php echo json_encode($defaultTraining->level); ?>;
                if (level === selectedLevel) {
                    editTable =`
                        <form action="{{route('gym-admin.updateDefaultTrainingPlan',$defaultTraining->id)}}" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" name="level" value="{{$defaultTraining->level}}">
                            <div class="modal-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Activity</th>
                                            <th>Days</th>
                                            <th>Sets</th>
                                            <th>Repetition (Times)</th>
                                            <th>Weights (KG)</th>
                                            <th>Rest Time (Minutes)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $arr['days'] = json_decode($defaultTraining->days,true);
                                        $arr['activities'] = json_decode($defaultTraining->activity,true);
                                        $arr['sets'] = json_decode($defaultTraining->sets,true);
                                        $arr['repetition'] = json_decode($defaultTraining->repetition,true);
                                        $arr['weights'] = json_decode($defaultTraining->weights,true);
                                        $arr['restTime'] = json_decode($defaultTraining->restTime,true);
                                        $l = count($arr['activities']);
                                        $j = count($arr['days']);
                                    @endphp
                                    @for($k=0;$k<$j;$k++)
                                    @php
                                        $count = count($arr['days'][$k]);
                                    @endphp
                                        <tr>
                                            <td><input type="text" class="form-control" readonly value="{{$arr['activities'][$k]}}" class="form-control" name="activity[]"></td>
                                            <td>
                                                <select class="select2 bs-select" data-live-search="true" data-size="8" multiple="multiple" name="days[{{$k}}][]" required>
                                                    <option value="sunday" @for($i=0;$i<$count;$i++)@if($arr['days'][$k][$i] == 'sunday') selected = 'selected' @endif @endfor>Sunday</option>
                                                    <option value="monday" @for($i=0;$i<$count;$i++)@if($arr['days'][$k][$i] == 'monday') selected = 'selected' @endif @endfor>Monday</option>
                                                    <option value="tuesday" @for($i=0;$i<$count;$i++)@if($arr['days'][$k][$i] == 'tuesday') selected = 'selected' @endif @endfor>Tuesday</option>
                                                    <option value="wednesday" @for($i=0;$i<$count;$i++)@if($arr['days'][$k][$i] == 'wednesday') selected = 'selected' @endif @endfor>Wednesday</option>
                                                    <option value="thursday" @for($i=0;$i<$count;$i++)@if($arr['days'][$k][$i] == 'thursday') selected = 'selected' @endif @endfor>Thursday</option>
                                                    <option value="friday" @for($i=0;$i<$count;$i++)@if($arr['days'][$k][$i] == 'friday') selected = 'selected' @endif @endfor>Friday</option>
                                                    <option value="saturday" @for($i=0;$i<$count;$i++)@if($arr['days'][$k][$i] == 'saturday') selected = 'selected' @endif @endfor>Saturday</option>
                                                </select>
                                            </td>
                                            <td><input type="number" value="{{$arr['sets'][$k]}}" class="form-control" name="sets[]" required></td>
                                            <td><input type="number" value="{{$arr['repetition'][$k]}}" class="form-control" name="repetition[]" required></td>
                                            <td><input type="number" value="{{$arr['weights'][$k]}}" class="form-control" name="weights[]"></td>
                                            <td><input type="number" value="{{$arr['restTime'][$k]}}" class="form-control" name="restTime[]" required></td>
                                        </tr>
                                    @endfor

                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>`;
                    $('#defaultTrainingPlanEditBox').empty();
                    $('#defaultTrainingPlanEditBox').append(editTable);
                    $('select.select2').select2({
                        placeholder: "Please Select",
                    }).focus(function () {
                        $(this).select2('focus');
                    });
                }
            @endforeach

        });

        $("select.clientEdit").change(function() {
            let selectedLevel = $(this).children("option:selected").val();
            @foreach($TrainingPlan as $defaultTraining)
                var trainingId = <?php echo json_encode($defaultTraining->id); ?>;
                level = <?php echo json_encode($defaultTraining->level); ?>;
            if (level === selectedLevel) {
            editTable =`
                <table class="table">
                    <thead>
                        <tr>
                            <th>Activity</th>
                            <th>Days</th>
                            <th style="width: 12%;">Sets</th>
                            <th>Repetition (Times)</th>
                            <th>Weights (KG)</th>
                            <th>Rest Time (Minutes)</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                <tbody>
                @php
                    $arr['days'] = json_decode($defaultTraining->days,true);
                    $arr['activities'] = json_decode($defaultTraining->activity,true);
                    $arr['sets'] = json_decode($defaultTraining->sets,true);
                    $arr['repetition'] = json_decode($defaultTraining->repetition,true);
                    $arr['weights'] = json_decode($defaultTraining->weights,true);
                    $arr['restTime'] = json_decode($defaultTraining->restTime,true);
                    $arr['startDate'] = json_decode($defaultTraining->startDate,true);
                    $arr['endDate'] = json_decode($defaultTraining->endDate,true);
                    $k = count($arr['activities']);
                @endphp
                @for($j=0;$j<$k;$j++)
                    @php $count = count($arr['days'][$j]);@endphp
                    <tr>
                        <td><input type="text" readonly class="form-control" value="{{$arr['activities'][$j]}}" class="form-control" name="activity[]"></td>

                        <td>
                            <select class="select2 bs-select" data-live-search="true" data-size="8" multiple="multiple" name="days[{{$j}}][]" required>
                                <option value="sunday" @for($i=0;$i<$count;$i++)@if($arr['days'][$j][$i] == 'sunday') selected = 'selected' @endif @endfor>Sunday</option>
                                <option value="monday" @for($i=0;$i<$count;$i++)@if($arr['days'][$j][$i] == 'monday') selected = 'selected' @endif @endfor>Monday</option>
                                <option value="tuesday" @for($i=0;$i<$count;$i++)@if($arr['days'][$j][$i] == 'tuesday') selected = 'selected' @endif @endfor>Tuesday</option>
                                <option value="wednesday" @for($i=0;$i<$count;$i++)@if($arr['days'][$j][$i] == 'wednesday') selected = 'selected' @endif @endfor>Wednesday</option>
                                <option value="thursday" @for($i=0;$i<$count;$i++)@if($arr['days'][$j][$i] == 'thursday') selected = 'selected' @endif @endfor>Thursday</option>
                                <option value="friday" @for($i=0;$i<$count;$i++)@if($arr['days'][$j][$i] == 'friday') selected = 'selected' @endif @endfor>Friday</option>
                                <option value="saturday" @for($i=0;$i<$count;$i++)@if($arr['days'][$j][$i] == 'saturday') selected = 'selected' @endif @endfor>Saturday</option>
                            </select>
                        </td>
                        <td><input type="number" class="form-control" value="{{$arr['sets'][$j]}}" name="sets[]" required></td>
                        <td><input type="number" class="form-control" name="repetition[]" value="{{$arr['repetition'][$j]}}" required></td>
                        <td><input type="number" class="form-control" name="weights[]" value="{{$arr['weights'][$j]}}"></td>
                        <td><input type="number" class="form-control" name="restTime[]" value="{{$arr['restTime'][$j]}}" required></td>
                        <td><input type="date" class="form-control" name="startDate[]" data-date-format="yyyy-mm-dd" value="{{ date('Y-m-d',strtotime($arr['startDate'][$j]))}}" required></td>
                        <td><input type="date" class="form-control" name="endDate[]" data-date-format="yyyy-mm-dd" value="{{ date('Y-m-d',strtotime($arr['endDate'][$j]))}}" required></td>
                    </tr>
                    @endfor
                </tbody>
            </table>`;
                $('#clientTrainingPlanEditBox-'+trainingId).empty();
                $('#clientTrainingPlanEditBox-'+trainingId).append(editTable);
                $('select.select2').select2({
                        placeholder: "Please Select",
                    }).focus(function () {
                        $(this).select2('focus');
                    });
            }
            @endforeach

        })
        $("select.clientLevel").change(function(){
            let selectedLevel = $(this).children("option:selected").val();
            @foreach($levelActivity as $activity)
                level = <?php echo json_encode($activity->level); ?>;
            if (level === selectedLevel){
                table = `
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Days</th>
                                <th style="width: 12%;">Sets</th>
                                <th>Repetition (Times)</th>
                                <th>Weights (KG)</th>
                                <th>Rest Time (Minutes)</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $arr = json_decode($activity['activity'],true);
                            $activities = explode(',',$arr[0]);
                            $i = count($activities);
                        @endphp
                        @for($j=0;$j<$i;$j++)
                            <td colspan="7"><label>Activity</label><input type="text" readonly class="form-control" value="{{$activities[$j]}}" class="form-control" name="activity[]"></td>
                            <tr>
                                <td>
                                    <select class="select2 bs-select" data-live-search="true" data-size="8" multiple="multiple" name="days[{{$j}}][]" required>
                                        <option value="sunday">Sunday</option>
                                        <option value="monday">Monday</option>
                                        <option value="tuesday">Tuesday</option>
                                        <option value="wednesday">Wednesday</option>
                                        <option value="thursday">Thursday</option>
                                        <option value="friday">Friday</option>
                                        <option value="saturday">Saturday</option>
                                    </select>
                                </td>
                                <td><input type="number" class="form-control" name="sets[]" required></td>
                                <td><input type="number" class="form-control" name="repetition[]" required></td>
                                <td><input type="number" class="form-control" name="weights[]"></td>
                                <td><input type="number" class="form-control" name="restTime[]" required></td>
                                <td><input type="date" style="width: 90%;" class="form-control" name="startDate[]" required></td>
                                <td><input type="date" style="width: 90%;" class="form-control" name="endDate[]" required></td>
                            </tr>
                        @endfor
                    </tbody>
                </table>`;

                $('#levelActivityDataFormClient').empty();
                $('#levelActivityDataFormClient').append(table);
                $('select.select2').select2({
                    placeholder: "Please Select",
                }).focus(function () {
                    $(this).select2('focus');
                });
            }
            @endforeach
        });


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var UIBootbox = function () {
            var defaultData = function () {
                $('.default-remove').on('click', function () {
                    var url = $(this).data('default-url');
                    bootbox.confirm({
                        message: "Do you want to delete this default plan?",
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
            var clientData = function () {
                $('.user-remove').on('click', function () {
                    var url = $(this).data('user-url');
                    bootbox.confirm({
                        message: "Do you want to delete this user training plan?",
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
                    clientData()
                    defaultData()
                }
            }
        }();
        jQuery(document).ready(function () {
            UIBootbox.init()
        });
    </script>
@endsection
