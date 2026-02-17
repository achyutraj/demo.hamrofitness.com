<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Default Traing Report</title>
    <style>
        table, tr {
            border: 1px solid #0a0a0a;
        }

        table, th, td {
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <h3 style="text-align:left;">Default Training Plan</h3>
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
            @foreach($defaultTrainingPlan as $defaultTraining)
                @php
                    $arr['days'] = json_decode($defaultTraining->days,true);
                    $arr['activities'] = json_decode($defaultTraining->activity,true);
                    $arr['sets'] = json_decode($defaultTraining->sets,true);
                    $arr['repetition'] = json_decode($defaultTraining->repetition,true);
                    $arr['weights'] = json_decode($defaultTraining->weights,true);
                    $arr['restTime'] = json_decode($defaultTraining->restTime,true);
                    $j = count($arr['activities']);
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
                            {{$arr['days'][$k][$i]}}<br>
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
</body>
</html>
