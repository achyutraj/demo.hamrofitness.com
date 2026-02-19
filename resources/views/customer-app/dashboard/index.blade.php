@extends('layouts.customer-app.basic')

@section('title')
    HamroFitness | Customer Dashboard
@endsection

@section('CSS')
    <link rel="stylesheet" href="{{ asset("fitsigma_customer/bower_components/morrisjs/morris.css") }}">
    <link rel="stylesheet" href="{{ asset("fitsigma_customer/bower_components/datatables/jquery.dataTables.min.css") }}">

@endsection

@section('content')
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Dashboard</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li>Main Menu</li>
                <li class="active">Dashboard</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="white-box analytics-info">
                <h3 class="box-title">Total Subscription</h3>
                <ul class="list-inline two-part">
                    <li class="text-right w-100"><span class="counter text-purple">{{ $totalSubscriptions }}</span></li>
                </ul>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="white-box analytics-info">
                <h3 class="box-title">Total Amount Paid</h3>
                <ul class="list-inline two-part">
                    <li class="text-right w-100">{{$gymSettings->currency->acronym}} <span class="counter text-success">{{ $totalAmountPaid }}</span></li>
                </ul>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="white-box analytics-info">
                <h3 class="box-title">Total Due Amount</h3>
                <ul class="list-inline two-part">
                    <li class="text-right w-100">{{$gymSettings->currency->acronym}} <span class="counter text-success">{{ $totalDueAmount }}</span></li>
                </ul>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="white-box analytics-info">
                <h3 class="box-title">Total Reservation</h3>
                <ul class="list-inline two-part">
                    <li class="text-right w-100"><span class="counter text-purple">{{ $totalReservations }}</span></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="white-box">
                <div class="box-title">
                    <div class="caption ">
                        <span class="caption-subject font-dark bold uppercase">Membership Due Payments</span>
                        <span class="caption-helper"></span>
                    </div>
                </div>
                <div class="box-body flip-scroll table-responsive">

                    <table class="table table-bordered table-striped table-condensed flip-content">
                        <thead class="flip-content">
                        <tr class="uppercase">
                            <th> # </th>
                            <th> Membership </th>
                            <th> Due Amount </th>
                            <th> Due Date </th>
                        </tr>
                        </thead>
                        <tbody>

                        @forelse($duePayments as $key=>$payment)
                            <tr>
                                <td> {{ $key+1 }} </td>
                                <td>{{ ucwords($payment->membership) }} </td>
                                <td> {{$gymSettings->currency->acronym}} {{ $payment->amount_to_be_paid - $payment->paid }} </td>
                                <td>
                                    @if($payment->due_date != null)
                                    {{ \Carbon\Carbon::createFromFormat('Y-m-d', $payment->due_date)->toFormattedDateString() }}
                                    @else
                                    {{ __('No Due Date') }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No due payments.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="white-box">
                <div class="box-title">
                    <div class="caption ">
                        <span class="caption-subject font-dark bold uppercase">Subscriptions Expiring in next {{$expireSubscriptionDays}} days</span>
                        <span class="caption-helper"></span>
                    </div>
                </div>
                <div class="box-body flip-scroll table-responsive">

                    <table class="table table-bordered table-striped table-condensed flip-content">
                        <thead class="flip-content">
                        <tr class="uppercase">
                            <th> # </th>
                            <th> Subscriptions </th>
                            <th> Join Date </th>
                            <th> Expiring on </th>
                            <th> Remaining Days </th>
                        </tr>
                        </thead>
                        <tbody>

                        @forelse($expiringSubscriptions as $key=>$expSubs)
                                <?php
                                $expires_on =  $expSubs->expires_on->format('Y-m-d');
                                $start_date =  $expSubs->start_date->format('Y-m-d');
                                $result = \Carbon\Carbon::createFromFormat('Y-m-d', $expires_on)->diffForHumans($start_date);
                                ?>
                            <tr>
                                <td> {{ $key+1 }} </td>
                                <td>{{ ucwords($expSubs->membership) }} </td>
                                <td>{{ $expSubs->start_date->format('d M, Y') }}</td>
                                <td>{{ $expSubs->expires_on->format('d M, Y') }}</td>
                                <td>{{ $result }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No subscription expiring.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="white-box">
                <div class="box-title">
                    <div class="caption ">
                        <span class="caption-subject font-dark bold uppercase">Locker Due Payments</span>
                        <span class="caption-helper"></span>
                    </div>
                </div>
                <div class="box-body flip-scroll table-responsive">

                    <table class="table table-bordered table-striped table-condensed flip-content">
                        <thead class="flip-content">
                        <tr class="uppercase">
                            <th> # </th>
                            <th> Locker </th>
                            <th> Due Amount </th>
                            <th> Due Date </th>
                        </tr>
                        </thead>
                        <tbody>

                        @forelse($dueReservationPayments as $key=>$payment)
                            <tr>
                                <td> {{ $key+1 }} </td>
                                <td>{{ ucwords($payment->locker->locker_num) }} </td>
                                <td> {{$gymSettings->currency->acronym}} {{ $payment->amount_to_be_paid - $payment->paid_amount }} </td>
                                <td>
                                    @if($payment->next_payment_date != null)
                                        {{ $payment->next_payment_date->toFormattedDateString() }}
                                    @else
                                        {{ __('No Due Date') }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No due payments.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="white-box">
                <div class="box-title">
                    <div class="caption ">
                        <span class="caption-subject font-dark bold uppercase">Reservation Expiring in next {{$expireSubscriptionDays}} days</span>
                        <span class="caption-helper"></span>
                    </div>
                </div>
                <div class="box-body flip-scroll table-responsive">

                    <table class="table table-bordered table-striped table-condensed flip-content">
                        <thead class="flip-content">
                        <tr class="uppercase">
                            <th> # </th>
                            <th> Locker </th>
                            <th> Join Date </th>
                            <th> Expiring on </th>
                            <th> Remaining Days </th>
                        </tr>
                        </thead>
                        <tbody>

                        @forelse($expiringReservations as $key=>$expire)
                                <?php
                                $expires_on =  $expire->end_date->format('Y-m-d');
                                $start_date =  $expire->start_date->format('Y-m-d');
                                $result = \Carbon\Carbon::createFromFormat('Y-m-d', $expires_on)->diffForHumans($start_date);
                                ?>
                            <tr>
                                <td> {{ $key+1 }} </td>
                                <td>{{ ucwords($expire->locker->locker_num) }} </td>
                                <td>{{ $expire->start_date->toFormattedDateString() }}</td>
                                <td>{{ $expire->end_date->toFormattedDateString() }}</td>
                                <td>{{ $result }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No reservation expiring.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <!-- Diet Plan  -->
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-xs-12">
            <div class="white-box">
                <div class="box-title">
                    <div class="caption ">
                        <span class="caption-subject font-dark bold uppercase">Default Diet Plan</span>
                        <span class="caption-helper"></span>
                    </div>
                </div>
                <div class="box-body flip-scroll table-responsive">
                    @foreach($default_diet_plan as $defaultDiet)
                        @php
                            $arr['days'] = unserialize($defaultDiet->days);
                            $arr['breakfast'] = json_decode($defaultDiet->breakfast,true);
                            $arr['lunch'] = json_decode($defaultDiet->lunch,true);
                            $arr['dinner'] = json_decode($defaultDiet->dinner,true);
                            $arr['meal_4'] = json_decode($defaultDiet->meal_4,true);
                            $arr['meal_5'] = json_decode($defaultDiet->meal_5,true);
                        @endphp
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Days</th>
                                    <th>Meal1</th>
                                    <th>Meal2</th>
                                    <th>Meal3</th>
                                    <th>Meal4</th>
                                    <th>Meal5</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!$defaultDiet->client_id)
                                    @for($i = 0; $i<7; $i++)
                                        <tr>
                                            <td>{{$arr['days'][$i]}}</td>
                                            <td>{{$arr['breakfast'][$i]}}</td>
                                            <td>{{$arr['lunch'][$i]}}</td>
                                            <td>{{$arr['dinner'][$i]}}</td>
                                            <td>{{$arr['meal_4'][$i]}}</td>
                                            <td>{{$arr['meal_5'][$i]}}</td>
                                        </tr>
                                    @endfor
                                @endif
                            </tbody>
                        </table>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 col-xs-12">
            <div class="white-box">
                <div class="box-title">
                    <div class="caption ">
                        <span class="caption-subject font-dark bold uppercase">Your Diet Plan</span>
                        <span class="caption-helper"></span>
                    </div>
                </div>
                <div class="box-body flip-scroll table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Days</th>
                                <th>Meal1</th>
                                <th>Meal2</th>
                                <th>Meal3</th>
                                <th>Meal4</th>
                                <th>Meal5</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($client_diet_plan as $defaultDiet)
                                @php
                                    $arr['days'] = unserialize($defaultDiet->days);
                                    $arr['breakfast'] = json_decode($defaultDiet->breakfast,true);
                                    $arr['lunch'] = json_decode($defaultDiet->lunch,true);
                                    $arr['dinner'] = json_decode($defaultDiet->dinner,true);
                                    $arr['meal_4'] = json_decode($defaultDiet->meal_4,true);
                                    $arr['meal_5'] = json_decode($defaultDiet->meal_5,true);
                                @endphp
                                @for($i = 0; $i<7; $i++)
                                    <tr>
                                        <td>{{$arr['days'][$i]}}</td>
                                        <td>{{$arr['breakfast'][$i]}}</td>
                                        <td>{{$arr['lunch'][$i]}}</td>
                                        <td>{{$arr['dinner'][$i]}}</td>
                                        <td>{{$arr['meal_4'][$i]}}</td>
                                        <td>{{$arr['meal_5'][$i]}}</td>
                                    </tr>
                                @endfor
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- training Plan -->
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-xs-12">
            <div class="white-box">
                <div class="box-title">
                    <div class="caption ">
                        <span class="caption-subject font-dark bold uppercase">Default Training Plan</span>
                        <span class="caption-helper"></span>
                    </div>
                </div>
                <div class="box-body flip-scroll table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Activity</th>
                                <th>Level</th>
                                <th>Days</th>
                                <th>Sets</th>
                                <th>Repetitions</th>
                                <th>Weights</th>
                                <th>Rest Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($default_training_plan as $defaultTraining)
                                @php
                                    $arr['days'] = json_decode($defaultTraining->days,true);
                                    $arr['activities'] = json_decode($defaultTraining->activity,true);
                                    $arr['sets'] = json_decode($defaultTraining->sets,true);
                                    $arr['repetition'] = json_decode($defaultTraining->repetition,true);
                                    $arr['weights'] = json_decode($defaultTraining->weights,true);
                                    $arr['restTime'] = json_decode($defaultTraining->restTime,true);
                                    $j = count($arr['activities']);
                                    $l = count($arr['days']);
                                @endphp
                                @for($k=0;$k<$j;$k++)
                                    <tr>
                                        <td>{{$arr['activities'][$k]}}</td>
                                        <td>{{$defaultTraining->level}}</td>
                                        <td>
                                            @php
                                                $count = count($arr['days'][$k]);
                                            @endphp
                                            @for($i=0;$i<$count;$i++)
                                                {{$arr['days'][$k][$i]}},
                                            @endfor
                                        </td>
                                        <td>{{$arr['sets'][$k]}}</td>
                                        <td>{{$arr['repetition'][$k]}}</td>
                                        <td>{{$arr['weights'][$k]}}</td>
                                        <td>{{$arr['restTime'][$k]}}</td>
                                    </tr>
                                @endfor
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- class schedule -->
        <div class="col-lg-6 col-sm-12 col-xs-12">
            <div class="white-box">
                <div class="box-title">
                    <div class="caption ">
                        <span class="caption-subject font-dark bold uppercase">Default Class Schedule</span>
                        <span class="caption-helper"></span>
                    </div>
                </div>
                <div class="box-body flip-scroll table-responsive">
                    <table class="table table-striped" id="classScheduleTable">
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <th>Trainer</th>
                                <th>Days</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($class_schedule as $schedule)
                                @php
                                    $arr = json_decode($schedule->days);
                                    $sTime = new dateTime($schedule->startTime);
                                    $eTime = new dateTime($schedule->endTime);
                                    $j = count($arr);
                                @endphp
                                <tr>
                                    <td>{{$schedule->classes->class_name ?? ''}}</td>
                                    <td>{{$schedule->trainers->name ?? ''}}</td>
                                    <td>@for($i=0;$i<$j;$i++){{$arr[$i]}}{{ '<br>' }}  @endfor</td>
                                    <td>{{$sTime->format('h:i a')}}</td>
                                    <td>{{$eTime->format('h:i a')}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- personal training plan -->
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xs-12">
            <div class="white-box">
                <h3 class="box-title">My Training Plan</h3>
                <div class="box-body flip-scroll table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Activity</th>
                                <th>Level</th>
                                <th>Days</th>
                                <th>Sets</th>
                                <th>Repetition</th>
                                <th>Weights</th>
                                <th>Rest Time</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($client_training_plan as $clientTraining)
                            @if($clientTraining->client_id)
                                @php
                                    $arr['days'] = json_decode($clientTraining->days,true);
                                    $arr['activities'] = json_decode($clientTraining->activity,true);
                                    $arr['sets'] = json_decode($clientTraining->sets,true);
                                    $arr['repetition'] = json_decode($clientTraining->repetition,true);
                                    $arr['weights'] = json_decode($clientTraining->weights,true);
                                    $arr['restTime'] = json_decode($clientTraining->restTime,true);
                                    $arr['startDate'] = json_decode($clientTraining->startDate,true);
                                    $arr['endDate'] = json_decode($clientTraining->endDate,true);

                                    $j = count($arr['activities']);
                                @endphp
                                @for($i=0;$i<$j;$i++)
                                    <tr>
                                        <td>{{$arr['activities'][$i]}}</td>
                                        <td>{{$clientTraining->level}}</td>
                                        <td>
                                            @php
                                                $count = count($arr['days'][$i]);
                                            @endphp
                                            @for($k=0;$k<$count;$k++)
                                                {{$arr['days'][$i][$k]}},
                                            @endfor
                                        </td>
                                        <td>{{$arr['sets'][$i]}}</td>
                                        <td>{{$arr['repetition'][$i]}}</td>
                                        <td>{{$arr['weights'][$i]}} KGs</td>
                                        <td>{{$arr['restTime'][$i]}} minutes</td>
                                        <td>{{$arr['startDate'][$i]}}</td>
                                        <td>{{$arr['endDate'][$i]}}</td>
                                    </tr>
                                @endfor
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-12 col-xs-12">
            <div class="white-box">
                <h3 class="box-title">My Class Schedule</h3>
                <div class="box-body flip-scroll table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Class Name</th>
                            <th>Trainer</th>
                            <th>Days</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($client_class_schedule as $schedule)
                            @php
                                $arr = json_decode($schedule->days);
                                $sTime = new dateTime($schedule->startTime);
                                $eTime = new dateTime($schedule->endTime);
                                $j = count($arr);
                            @endphp
                            <tr>
                                <td>{{$schedule->classes->class_name ?? ''}}</td>
                                <td>{{$schedule->trainers->name ?? ''}}</td>
                                <td>@for($i=0;$i<$j;$i++){{$arr[$i]}}{{ '<br>' }}  @endfor</td>
                                <td>{{$sTime->format('h:i a')}}</td>
                                <td>{{$eTime->format('h:i a')}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @if($paymentCharts->count() > 0)
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xs-12">
            <div class="white-box">
                <h3 class="box-title">Payments Chart</h3>
                <div id="morris-bar-chart"></div>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('JS')
    <script src="{{ asset("fitsigma_customer/bower_components/raphael/raphael-min.js") }}"></script>
    <script src="{{ asset("fitsigma_customer/bower_components/morrisjs/morris.js") }}"></script>
    <script src="{{ asset("fitsigma_customer/bower_components/datatables/jquery.dataTables.min.js") }}"></script>

    <script>
        var months = [];
        months['1'] = 'Jan';
        months['2'] = 'Feb';
        months['3'] = 'Mar';
        months['4'] = 'Apr';
        months['5'] = 'May';
        months['6'] = 'Jun';
        months['7'] = 'Jul';
        months['8'] = 'Aug';
        months['9'] = 'Sep';
        months['10'] = 'Oct';
        months['11'] = 'Nov';
        months['12'] = 'Dec';
        Morris.Bar({
            element: 'morris-bar-chart',
            data: [
                @foreach($paymentCharts as $chart)
                {
                    "Month": months['{{$chart->M}}'],
                    "Income": '{{ $chart->S}}'
                },
                @endforeach
            ],
            xkey: 'Month',
            ykeys: ['Income'],
            labels: ['Income'],
            barColors:['#b8edf0', '#b4c1d7'],
            hideHover: 'auto',
            gridLineColor: '#eef0f2',
            resize: true
        });
    </script>
@endsection
