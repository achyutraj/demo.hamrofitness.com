<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Training Report</title>
    <style>
        table, tr {
            border: 1px solid #0a0a0a;
        }

        table, th, td {
            text-align: center;
            padding: 5px;
        }
    </style>
</head>
<body>
    @foreach($clientTrainingPlan as $defaultTraining)
        @php
            $arr['days'] = json_decode($defaultTraining->days,true);
            $arr['activities'] = json_decode($defaultTraining->activity,true);
            $arr['sets'] = json_decode($defaultTraining->sets,true);
            $arr['repetition'] = json_decode($defaultTraining->repetition,true);
            $arr['weights'] = json_decode($defaultTraining->weights,true);
            $arr['restTime'] = json_decode($defaultTraining->restTime,true);
            $arr['startDate'] = json_decode($defaultTraining->startDate,true);
            $arr['endDate'] = json_decode($defaultTraining->endDate,true);
            $j = count($arr['activities']);
        @endphp
        <h3 style="text-align:left;">Training Plan for {{$defaultTraining->first_name. ' ' .$defaultTraining->middle_name . ' ' . $defaultTraining->last_name}}</h3>
        <table>
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Level</th>
                    <th>Days</th>
                    <th>Sets</th>
                    <th>Repetitions</th>
                    <th>Weights</th>
                    <th>Rest Time</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
            </thead>
            <tbody>
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
                        <td>{{$arr['startDate'][$k]}}</td>
                        <td>{{$arr['endDate'][$k]}}</td>
                    </tr>
                @endfor
            </tbody>
        </table>
         @endforeach
</body>
</html>
