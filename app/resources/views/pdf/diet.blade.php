<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Diet Plan</title>
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
    <div class="col-md-3"></div>
    <div class="col-md-6">
        @foreach($diets as $defaultDiet)
            @php
                $arr['days'] = unserialize($defaultDiet->days);
                $arr['breakfast'] = json_decode($defaultDiet->breakfast,true);
                $arr['lunch'] = json_decode($defaultDiet->lunch,true);
                $arr['dinner'] = json_decode($defaultDiet->dinner,true);
                $arr['meal_4'] = json_decode($defaultDiet->meal_4,true);
                $arr['meal_5'] = json_decode($defaultDiet->meal_5,true);
            @endphp
            @if($defaultDiet->client_id == null)
                <h3 style="text-align: left;">Default Diet Plan</h3>
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
                    </tbody>
                </table>
            @else
                <h3 style="text-align: center;">Diet Plan for {{$defaultDiet->first_name.' '.$defaultDiet->middle_name. ' '.$defaultDiet->last_name }}</h3>
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
                    </tbody>
                </table>
            @endif
        @endforeach
    </div>
    <div class="col-md-3"></div>

</body>
</html>
