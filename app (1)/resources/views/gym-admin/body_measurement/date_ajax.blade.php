@if(isset($data))
<div class="form-group form-md-line-input ">
    <select  class="form-control" name="from_date" id="from_date">
        <option selected>Please Select From Date</option>
        @forelse($data as $date)
            <option value="{{$date->entry_date->format('Y-m-d')}}">{{ $date->entry_date->toFormattedDateString() }}</option>
        @empty
            <option value="">No Entry Date by this client</option>
        @endforelse
    </select>
    <label for="title">From Date </label>
    <span class="help-block"></span>
</div>
<div class="form-group form-md-line-input ">
    <select  class="form-control" name="to_date" id="to_date">
        <option selected>Please Select To Date</option>
        @forelse($data as $date)
            <option value="{{$date->entry_date->format('Y-m-d')}}">{{ $date->entry_date->toFormattedDateString() }} </option>
        @empty
            <option value="">No Entry Date by this client</option>
        @endforelse
    </select>
    <label for="title">To Date</label>
    <span class="help-block"></span>
</div>
@endif

@if(isset($show) && $show)
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="row">
                    <div class="col-md-6 col-sm-12" style="padding:10px;">
                        <div class="caption font-dark">
                            <i class="icon-target font-red"></i>
                            <span class="caption-subject font-red bold uppercase"> Progress Tracker Details
                                        @if($show) of {{ $progress->first()->client->fullName ?? '' }} @endif</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table-100 table table-striped table-bordered table-hover order-column responsive"
                       id="targets_table">
                    <thead>
                    <tr>
                        <th class="all"> Measurement</th>
                        <th class="min-tablet"> Recent Data  @if($show) of {{ $progress->first()->entry_date->toFormattedDateString() ?? '' }} @endif</th>
                        @if(isset($progress) && count($progress) == 2)
                            <th class=""> Previous Data  @if($show) of {{ $progress->last()->entry_date->toFormattedDateString() ?? '' }} @endif</th>
                        @endif
                        <th class=""> Change (%)</th>
                        <th class=""> Progress</th>
                    </tr>
                    </thead>
                        <tbody>
                        <tr>
                            <td>Height </td>
                            @foreach($progress as $p)
                                <td>{{ $p->height_feet ?? 0 }} ft {{ $p->height_inches ?? 0 }} in</td>
                            @endforeach
                            <td>{{ $measurements['height'] }}</td>
                            <td>
                                {!! progressStatus($measurements['height']) !!}
                            </td>
                        </tr>
                        <tr>
                            <td>Weight (KG)</td>
                            @foreach($progress as $p)
                                <td>{{ $p->weight ?? 0 }}</td>
                            @endforeach
                            <td>{{ $measurements['weight'] }}</td>
                            <td>
                                {!! progressStatus($measurements['weight']) !!}
                            </td>
                        </tr>
                        <tr>
                            <td>Fat</td>
                            @foreach($progress as $p)
                                <td>{{ $p->fat ?? 0 }}</td>
                            @endforeach
                            <td>{{ $measurements['fat'] }}</td>
                            <td>
                                {!! progressStatus($measurements['fat']) !!}
                            </td>
                        </tr>
                        <tr>
                            <td>Fore Arms</td>
                            @foreach($progress as $p)
                                <td>{{ $p->fore_arms ?? 0 }}</td>
                            @endforeach
                            <td>{{ $measurements['fore_arms'] }}</td>
                            <td>
                                {!! progressStatus($measurements['fore_arms']) !!}
                            </td>
                        </tr>
                        <tr>
                            <td>Neck</td>
                            @foreach($progress as $p)
                                <td>{{ $p->neck ?? 0}}</td>
                            @endforeach
                            <td>{{ $measurements['neck'] }}</td>
                            <td>
                                {!! progressStatus($measurements['neck']) !!}
                            </td>
                        </tr>
                        <tr>
                            <td>Shoulder</td>
                            @foreach($progress as $p)
                                <td>{{ $p->shoulder ?? 0 }}</td>
                            @endforeach
                            <td>{{ $measurements['shoulder'] }}</td>
                            <td>
                                {!! progressStatus($measurements['shoulder']) !!}
                            </td>
                        </tr>
                        <tr>
                            <td>Chest</td>
                            @foreach($progress as $p)
                                <td>{{ $p->chest ?? 0 }}</td>
                            @endforeach
                            <td>{{ $measurements['chest'] }}</td>
                            <td>
                                {!! progressStatus($measurements['chest']) !!}
                            </td>
                        </tr>
                        <tr>
                            <td>Waist</td>
                            @foreach($progress as $p)
                                <td>{{ $p->waist ?? 0 }}</td>
                            @endforeach
                            <td>{{ $measurements['waist'] }}</td>
                            <td>
                                {!! progressStatus($measurements['waist']) !!}
                            </td>
                        </tr>
                        <tr>
                            <td>Hip</td>
                            @foreach($progress as $p)
                                <td>{{ $p->hip ?? 0 }}</td>
                            @endforeach
                            <td>{{ $measurements['hip'] }}</td>
                            <td>
                                {!! progressStatus($measurements['hip']) !!}
                            </td>
                        </tr>
                        <tr>
                            <td>Thigh</td>
                            @foreach($progress as $p)
                                <td>{{ $p->thigh ?? 0 }}</td>
                            @endforeach
                            <td>{{ $measurements['thigh'] }}</td>
                            <td>
                                {!! progressStatus($measurements['thigh']) !!}
                            </td>
                        </tr>
                        <tr>
                            <td>Calves</td>
                            @foreach($progress as $p)
                                <td>{{ $p->calves ?? 0 }}</td>
                            @endforeach
                            <td>{{ $measurements['calves'] }}</td>
                            <td>
                                {!! progressStatus($measurements['calves']) !!}
                            </td>
                        </tr>
                        <tr>
                            <td>Arms</td>
                            @foreach($progress as $p)
                                <td>{{ $p->arms ?? 0 }}</td>
                            @endforeach
                            <td>{{ $measurements['arms'] }}</td>
                            <td>
                                {!! progressStatus($measurements['arms']) !!}
                            </td>
                        </tr>
                        </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
@endif

@if(isset($calculate_show) && $calculate_show)
    <div class="easy-pie-chart">
        <a class="title" href="javascript:;" id="graphTitle"></a>
        <div class="number transactions" id="users_percent" data-percent="0">
            <span id="spanData"></span> @if($type == 'calorie') cal @else % @endif
        </div>
    </div>
    <div class="m-heading-1 m-heading-2 border-green m-bordered">
        <h3>Result</h3>
        <ul>
            @if($type == 'bmi')
            <li><p><strong>Your BMI </strong> - <span> {{ $result['percent'] }} kg/m2 </span></p></li>
            <li><p><strong>Status </strong>  <span> <badge class="label label-{{$result['status_color']}}">({{ucfirst($result['status'])}})</badge></span></p></li>
            @elseif($type == 'fat')
                <li><p><strong>Your Body Fat </strong> - <span> {{ $result['percent'] }} %</span></p></li>
                <li><p><strong>Body Fat Category </strong> - <span> {{ $result['status'] }}</span></p></li>
            @elseif($type == 'calorie')
                <li><p><strong>Maintain weight </strong> - <span> {{ $result['percent'] }} Calories/day</span></p></li>
                <li><p><strong>Exercise </strong> - <span> 15-30 minutes of elevated heart rate activity</span></p></li>
                <li><p><strong>Intense exercise</strong> - <span> 45-120 minutes of elevated heart rate activity</span></p></li>
                <li><p><strong>Very intense exercise</strong> - <span> 2+ hours of elevated heart rate activity</span></p></li>
            @endif
        </ul>
    </div>

    <script>
        $('#users_percent').attr('data-percent', {{ $result['percent'] }});
        $('#spanData').html({{ $result['percent'] }});
        $('#graphTitle').html('Result ' + ' &nbsp;<i class="icon-arrow-right"></i>');
        $('#easyStats').css('display', 'block');

        $('.easy-pie-chart .number.transactions').easyPieChart({
            animate: 3000,
            size: 150,
            lineWidth: 10,
            barColor: '{{$result['color']}}'
        });
    </script>
@endif
